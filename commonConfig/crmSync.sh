#!/bin/bash

# if setting up the server for the first time
# copy html_public (entire folder from any of existing servers)
# create 3 new files as follows ::
# 1) /home/developer/tempupload/crmSyncDiff.txt
# 2) /home/developer/tempupload/crmSyncOld.txt
# 3) /home/developer/tempupload/crmSyncNew.txt
# run bash /var/www/html_public/crmSync.sh
# run Deployment

# Get new file list in current release
cd /var/www/html/ && find -type f ! -path "./crmSync.sh" ! -path "./commonConfig/*" ! -path "./.svn/*" ! -path "./cache/*" ! -path "./log/*" ! -path "./web/smarty/templates_c/*" ! -path "./web/robots.txt" -print > /home/developer/tempupload/crmSyncNew.txt
# Diff positive of previous vs new release files list and store in crmSyncDiff.txt
cd /home/developer/tempupload/
diff -u crmSyncOld.txt crmSyncNew.txt | grep -E "^\+" > crmSyncDiff.txt
# Remove 1st line of file since it contains headers of diff
tail -n +2 crmSyncDiff.txt > crmSyncDiff.txt.tmp && mv crmSyncDiff.txt.tmp crmSyncDiff.txt
# Remove +symbol before each file name
sed -i -e 's/+//g' crmSyncDiff.txt
# Get new file list in html_public folder
cd /var/www/html_public/ && find -type f ! -path "./crmSync.sh" ! -path "./commonConfig/*" ! -path "./.svn/*" ! -path "./cache/*" ! -path "./log/*" ! -path "./web/smarty/templates_c/*" ! -path "./web/robots.txt" -print > /home/developer/tempupload/crmSyncPublic.txt
# Append crmSyncDiff.txt contents to crmSyncPublic.txt so new files can be added accordingly
cd /home/developer/tempupload/
cat crmSyncDiff.txt >> crmSyncPublic.txt
# Run the rsync command to complete syncing
rsync --files-from=/home/developer/tempupload/crmSyncPublic.txt /var/www/html/ /var/www/html_public/
# Clear symfony cache in /var/www/html_public
/usr/local/php/bin/php /var/www/html_public/symfony cc --type=minify > /home/developer/tempupload/symfony_cc.txt
# Finally move the crmSyncNew.txt to crmSyncOld.txt so future syncs work perfectly
cd /home/developer/tempupload/
mv crmSyncNew.txt crmSyncOld.txt