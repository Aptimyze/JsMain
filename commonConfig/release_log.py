#!/usr/bin/python
import sys, subprocess, getopt, os
import re

gitLogCmd       = ['git', 'log', '--pretty=oneline', '--no-color']
gitAuthorCmd    = ['git', 'show', '-s', '--format=(%an) %aD', '--no-color']
gitCommitMsgCmd = ['git', 'log', '-1', '--pretty=%B', '--no-color']
gitBranchNamesCmd = ['git', 'branch', '-a', '--contains'];

branchAOnly   = False
branchBOnly   = False
parseMerge = False
reversedOrder = True
workingDirLocation = '.'
cherryPickLine = '\(cherry picked from commit '
commitBranchLogA = []
commitBranchLogB = []
detailedLog = False
ignoreArray = ['[CIRelease','Merge bran','[QASanityR']
commitHookStartDelimiter = '['
commitHookEndDelimiter = ']'

# just a basic commit object
class gitCommit:
    def __init__(self, commitID, commitSubject):
        self.commitID      = commitID
        self.commitSubject = commitSubject
        self.cherryPickID  = ""

    def getCommitID(self):
        return self.commitID

    def getCommitSubject(self):
        return self.commitSubject

    def addCherryPickID(self, ID):
        self.cherryPickID = ID

    def getCherryPickID(self):
        return self.cherryPickID


class Branch:
    def __init__(self, branchName):
        self.branchName = branchName
        self.patchIdDict    = {} # for fast search
        self.commitList     = []  # list of git commit ids
        self.commitObjDict  = {}  # list of gitCommit objects
        self.missingDict    = {} # list of missing commitIDs of this branch
        self.commitMissingLog = [] # list of Missing Commit

    def searchCherryPickID(self, commitID):
        commitMsg = subprocess.check_output(gitCommitMsgCmd + [commitID])

        searchRegEx  = re.compile(cherryPickLine)

        for line in commitMsg.splitlines():
            if searchRegEx.search(line):
                cherryPickID = searchRegEx.split(line)[1]

                # remove closing bracket
                cherryPickID = re.sub('\)$', '', cherryPickID)

                return cherryPickID

    def addCommit(self, commitID, commitSubject):
        commitObj = gitCommit(commitID, commitSubject)

        gitShow = subprocess.check_output(['git', 'show', commitID])
        proc = subprocess.Popen(['git', 'patch-id'], stdout=subprocess.PIPE, stdin=subprocess.PIPE)
        patchID = proc.communicate(input=gitShow)[0].split(' ')[0]

        commitObj.addCherryPickID(self.searchCherryPickID(commitID) )
        # print self.branchName + ': Adding: ' + patchID + ' : ' + commitID

        self.commitList.append(commitID)
        self.commitObjDict[commitID] = commitObj
        self.patchIdDict[patchID]    = commitID

    def addLogLine(self, logLine):
        commitID      = logLine[:40]
        commitSubject = logLine[41:]
        self.addCommit(commitID, commitSubject)

    def addGitLog(self, logOutput):
        lines = logOutput.split('\n')
        if lines[-1] == '':
            lines.pop()

        for line in lines:
            self.addLogLine(line)

    def doComparedBranchLog(self, comparedBranchName):
        os.chdir(workingDirLocation)
        cmd = gitLogCmd + [self.branchName]

        if 'logSinceTime' in globals():
            cmd.append('--since="%s"' % logSinceTime)
        elif not 'exactSearch' in globals():
            cmd.append('^' + comparedBranchName)

        # print 'Compared branch log: ' + str(cmd)

        log = subprocess.check_output(cmd);
        
        self.addGitLog(log)

    def createMissingDict(self, comparisonDict):
        os.chdir(workingDirLocation)
        # print "createMissingDict"
        for key in comparisonDict.keys():
            if key not in self.patchIdDict:
                commitID = comparisonDict.get(key)
                self.missingDict[commitID] = commitID

                # print self.branchName + ': missing: ' + key + ' : ' + commitID

    def isCommitInMissingDict(self, commitID):
        if commitID in self.missingDict:
            return True

        return False

    # iterate over missing commits to either reverse-assign cherry-pick-ids or to
    # print missing commits
    def iterateMissingCommits(self, comparisonCommitList, comparisonCommitDict, doPrint):
        os.chdir(workingDirLocation)
        # Note: Print in the order given by the commitList and not
        #       in arbitrary order of the commit dictionary.

        if doPrint:
            missingText = "MISSING FROM %s" % self.branchName
            if 'logSinceTime' in globals():
                missingText += " [Showing only " + logSinceTime + " difference in commits]"
            missingText += " \n"
            print missingText
            jira_ids = []
        for commitID in comparisonCommitList:
            if self.isCommitInMissingDict(commitID):
                cmd          = gitAuthorCmd + [commitID]
                commitAuthor = subprocess.check_output(cmd).rstrip()
                commitObj    = comparisonCommitDict[commitID]
                branchNameCmd = gitBranchNamesCmd + [commitID]
                if doPrint is False:
                    for line in subprocess.check_output(branchNameCmd).rstrip().split('\n'):                    
                        self.commitMissingLog.append(line)
                    
                cherryPickID = commitObj.getCherryPickID()
                if (cherryPickID and (cherryPickID in self.commitObjDict) ):

                    # assign cherry pick id to our branch
                    if not doPrint:
                        cherryObj = self.commitObjDict[cherryPickID]
                        cherryObj.addCherryPickID(commitID)

                    continue

                if doPrint:

                    if 'filterAuthor' in globals() and \
                        not re.search(filterAuthor, commitAuthor):
                            continue # a different owner
                    comment = commitObj.getCommitSubject()
                    commentUptoTenChar = comment[:10]
                    jiraId = commentUptoTenChar
                    print commentUptoTenChar[-1:]
                    if commentUptoTenChar.startswith(commitHookStartDelimiter) and not commentUptoTenChar.endswith(commitHookEndDelimiter):
                        jiraId = re.sub(commentUptoTenChar[-1:]+'$',commitHookEndDelimiter,commentUptoTenChar)
                    
                    if jiraId not in jira_ids and commentUptoTenChar not in ignoreArray:
                        print comment[:15]
                        jira_ids.append(jiraId)
                    #print '  %s %s %s' % \
                    #    (commitID, commitAuthor, commitObj.getCommitSubject() )

        if doPrint:
            print "\n-----------------------------------------------------------------------------------------------------\n\n"

    def printMissingCommits(self, comparisonCommitList, comparisonCommitDict):
        # print 'printMissingCommits'
        global detailedLog
        self.iterateMissingCommits(comparisonCommitList, comparisonCommitDict, detailedLog)

    def reverseAssignCherryPickIDs(self, comparisonCommitList, comparisonCommitDict):
        # print 'reverseAssignCherryPickIDs'
        self.iterateMissingCommits(comparisonCommitList, comparisonCommitDict, False)


    def getPatchIdDict(self):
        return self.patchIdDict

    def getCommitList(self):
        return self.commitList

    def getCommitObjDict(self):
        return self.commitObjDict

    def printCommitLog(self):
        missingText = "MISSING FROM %s" % self.branchName
        if 'logSinceTime' in globals():
            missingText += " [Showing only " + logSinceTime + " difference in commits]"
        missingText += " \n"
        print missingText
        branchesMissing = "\n".join(set(self.commitMissingLog))
        allBranches = []
        for line in branchesMissing.split('\n'):
            allBranches.append("  " + line.replace('remotes/origin/', '').replace('*','').strip().replace('QASanityReleaseNew', ''))
        print "\n".join(set(allBranches))
        print "\n-----------------------------------------------------------------------------------------------------\n\n"

def usage():
        print '''
        Usage:

          -h
                Print this help message.
          -a <branch-name> 
                The name of branch a.
          -b <branch-name>
                The name of branch b.
          -l <full_path_location>
                Working Directory, if nothing passed, current working directory is used
          -f
                Only print commits created by this user.
          -e
                Exact search with *all* commits. Usually we list commits with
                'git log branchA ^branchB', which might not be correct with
                merges between branches.
          -d
                Show Detailed Log, use this to see commit wise log.
          -m
                Will try to do a pull request on branch A from branch B, should always be used with (-l) parameter
          -t
                How far back in time to go (passed to git log as --since) i.e. '1 day/month ago'.
        '''


try:
    opts, args = getopt.getopt(sys.argv[1:], "ha:b:BAedf:tml:")
except:
    usage()
    sys.exit()

for opt,arg in opts:
    if opt == '-h':
        usage()
        sys.exit();
    if opt == '-a':
        branchAName = arg
    if opt == '-b':
        branchBName = arg
    if opt == '-A':
        branchAOnly = True
    if opt == '-B':
        branchBOnly = True
    if opt == '-e':
        exactSearch = True
    if opt == '-f':
        filterAuthor = arg
    if opt == '-d':
        detailedLog = True
    if opt == '-t':
        for a in args:
            logSinceTime = a
    if opt == '-l':
        workingDirLocation = arg + "/"
    if opt == '-m':
        parseMerge = True

if 'branchAName' not in globals() or 'branchBName' not in globals():
    print 'You must specify two branches with -a and -b'
    sys.exit(1)

if reversedOrder:
    gitLogCmd += ['--reverse']

if workingDirLocation:
    print 'Attempting to move to passed location'
    os.chdir(workingDirLocation)
    print "\nCurrently in location " + os.getcwd() + "\n"
    print "Trying to initialize directory with branch " + branchAName + "\n"
    os.system("git checkout -- . && git checkout " + branchAName + " && git pull origin " + branchAName)
    os.system("git fetch --all")
    print "\n\nBRANCH INITIALIZED : " + branchAName + " @ location " + os.getcwd() + "\n"
    print "\n-----------------------------------------------------------------------------------------------------\n\n"

branchAObj = Branch(branchAName)
branchBObj = Branch(branchBName)

branchAObj.doComparedBranchLog(branchBName)
branchBObj.doComparedBranchLog(branchAName)

branchAObj.createMissingDict(branchBObj.getPatchIdDict() )
branchBObj.createMissingDict(branchAObj.getPatchIdDict() )


branchAObj.reverseAssignCherryPickIDs(branchBObj.getCommitList(), \
    branchBObj.getCommitObjDict()  )

branchBObj.reverseAssignCherryPickIDs(branchAObj.getCommitList(), \
    branchAObj.getCommitObjDict() )

if not branchBOnly:
    branchAObj.printMissingCommits(branchBObj.getCommitList(), \
        branchBObj.getCommitObjDict()  )
    if detailedLog is False:
        branchAObj.printCommitLog()

if not branchAOnly:
    branchBObj.printMissingCommits(branchAObj.getCommitList(), \
        branchAObj.getCommitObjDict() )
    if detailedLog is False:
        branchBObj.printCommitLog()

#print 'DIFF COMPARISON COMPLETED\n'
#print "\n-----------------------------------------------------------------------------------------------------\n\n"

if parseMerge:
    if 'branchAName' not in globals() or 'branchBName' not in globals():
        print "\n\nTwo branches must be supplied with -a branchAName and -b branchBName in arguments for merge to work \n"
        print "\n-----------------------------------------------------------------------------------------------------\n\n"
    else:
        os.chdir(workingDirLocation)
        print "\nMAKING SURE BRANCH : " + branchAName + " IS UPDATED TO LATEST COMMIT\n"
        print "\n-----------------------------------------------------------------------------------------------------\n\n"
        os.system("git checkout -- . && git checkout " + branchAName + " && git pull origin " + branchAName)
        print "\n-----------------------------------------------------------------------------------------------------\n\n"
        print "Trying to do local pull of " + branchBName + " into " + branchAName + " @ location " + workingDirLocation + "\n"
        print "\nPULL REQUEST COMPLETED WITH FOLLOWING OUTPUT\n"
        print "\n-----------------------------------------------------------------------------------------------------\n\n"
        os.system("git pull origin " + branchBName)
        print "\n-----------------------------------------------------------------------------------------------------\n\n"
