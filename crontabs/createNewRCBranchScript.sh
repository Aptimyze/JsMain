#!/bin/bash
# $1 is the branch on the terminal
# $2 is the branch on which push , pull is to be done


branch=$1;
branchToCheckout=$2;

#get to the required directory
cd /var/www/html/branches/$branch;

nextDayDate="$(date --date='1 day' +'%Y-%m-%d')"; #make sure that branches are made in Y-m-d format
todayDate="$(date +'%Y-%m-%d')";
# printf "Current date in dd/mm/yyyy format %s\n" "$nextDayDate";

lastReleasedBranch=$(</var/www/html/branches/branch2/crontabs/lastReleasedBranch.txt);

lastReleasedBranchDate=${lastReleasedBranch:3};

#lastReleaseBranchDateChangedFormat="$(date -d"$lastReleasedBranchDate" "+%Y-%m-%d")";
#taking hardcoded date for now

dateDiff=$(($(($(date -d "$todayDate" "+%s") - $(date -d "$lastReleasedBranchDate" "+%s"))) / 86400));


# #git reset
# git reset --hard;

# #git checkout QASanityReleaseNew
# git checkout $branchToCheckout;

# #git pull 
# git pull origin $branchToCheckout;

# #git pull CIRelease
# git pull origin CIRelease;

# #git push origin QASanityReleaseNew
# git push origin $branchToCheckout;

# create a new branch from QASanityReleaseNew based on whether the liveFlag is "1" or not.
# if the flag is "1" i.e. the previous branch went LIVE, a new branch can be created directly.
# if the flag is NOT "1" i.e. previous branch didnt go LIVE, we need to merge the previous branch to QASanityReleaseNew

if [ "$dateDiff" != "0" ] 
	then	
	git merge --no-ff "RC@$todayDate"; #lastDaYBranch to be merged and not the lastReleasebranch
	git push origin QASanityReleaseNew;
fi

#create next day branch from QASanityReleaseNew
git branch "RC@$nextDayDate" QASanityReleaseNew;
