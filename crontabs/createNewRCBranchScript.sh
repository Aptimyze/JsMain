#!/bin/bash
# $1 is the branch on the terminal
# $2 is the branch on which push , pull is to be done


branch=$1;
branchToCheckout=$2;

#get to the required directory
cd /var/www/$branch;

nextDayDate="$(date --date='1 day' +'%Y-%m-%d')"; #make sure that branches are made in Y-m-d format
todayDate="$(date +'%Y-%m-%d')";

printf "Current date in dd/mm/yyyy format %s\n" "$nextDayDate";

#file that contains name of last released branch
lastReleasedBranch=$(</var/www/CI_Files/lastReleasedBranch.txt);

#date of last release
lastReleasedBranchDate=${lastReleasedBranch:3};

#date difference between current date and last release date
dateDiff=$(($(($(date -d "$todayDate" "+%s") - $(date -d "$lastReleasedBranchDate" "+%s"))) / 86400));

#git reset
git reset --hard;

#git checkout QASanityReleaseNew
git checkout $branchToCheckout;

#git pull 
git pull origin $branchToCheckout;

#git pull CIRelease
git pull origin CIRelease;

#git push origin QASanityReleaseNew
git push origin $branchToCheckout;

# create a new branch from QASanityReleaseNew based on whether the datediff is "0" or not.
# if the dateDiff is "0" i.e. the previous branch went LIVE, a new branch can be created directly.
# if the dateDiff is NOT "1" i.e. previous branch didnt go LIVE, we need to merge the previous branch to QASanityReleaseNew before creating the new branch

if [ "$dateDiff" != "0" ] 
	then	
	git pull origin "RC@$todayDate";
	git push origin QASanityReleaseNew;
fi

#create next day branch from QASanityReleaseNew
git checkout -b "RC@$nextDayDate" QASanityReleaseNew;
git pull origin QASanityReleaseNew;
git push origin "RC@$nextDayDate";