/** Setting user **/
git config --global user.email "lavesh.rawat@jeevansathi.com"


/**First Commit**/
git add README.md
git commit -m "first commit"
git push -u origin master


/** adding repository to git after svn export(no .svn files) **/
git status 
git add .
git commit -am "initial setup" 
git push origin master

/** checkout **/
git init
