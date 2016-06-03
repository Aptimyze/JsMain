#!/bin/bash



for i in *.htm

do

 	sed -i 's/\(<img[^>]*src=\)"~$SITE_URL`/\1"~$IMG_URL`/g' $i

	sed -i 's/\(<img[^>]*src=\)"images\/registration_new/\1"~$IMG_URL`\/profile\/images\/registration_new/g' $i



#	sed -i 's/\(<input[^>]*src=\)"images/\1"~$IMG_URL`\/profile\/images/g' $i



#	sed -i 's/url(images/url(~$IMG_URL`\/profile\/images/g' $i

	sed -i 's/url(~$IMG_URL2`/url(~$IMG_URL`/g' $i



	sed -i 's/src="\/P\/images/src="~$IMG_URL`\/P\/images/g' $i

	sed -i 's/src="\/P\/I/src="~$IMG_URL`\/P\/I/g' $i



	sed -i 's/src="\/profile\/I/src="~$IMG_URL`\/profile\/I/g' $i

	sed -i 's/src="\/profile\/images/src="~$IMG_URL`\/profile\/images/g' $i



#	sed -i 's/src="images/src="~$IMG_URL`\/images/g' $i

#	sed -i 's/url(..\/..\//url(~$IMG_URL`\//g' $i



#	sed -i 's/\(<img[^>]*src=\)"..\/..\//\1"~$IMG_URL`\//g' $i

#	sed -i 's/\(<img[^>]*src=\)"..\//\1"~$IMG_URL`\//g' $i



#	sed -i 's/url(~$IMG_URL`/url(~$IMG_URL`\/profile/g' $i

#	sed -i 's/\(<img[^>]*src=\)"~$IMG_URL`/\1"~$IMG_URL`\/profile/g' $i



#	sed -i 's/\(<SCRIPT[^>]*SRC=\)"~$SITE_URL`/\1"~$MINIFY_URL`/g' $i

# 	sed -i 's/\(<script[^>]*SRC=\)"~$SITE_URL`/\1"~$MINIFY_URL`/g' $i

# 	sed -i 's/\(<script[^>]*src=\)"~$SITE_URL`/\1"~$MINIFY_URL`/g' $i



# 	sed -i 's/\(<LINK[^>]*HREF=\)"~$SITE_URL`/\1"~$MINIFY_URL`/g' $i

#	sed -i 's/\(<link[^>]*href=\)"~$SITE_URL`/\1"~$MINIFY_URL`/g' $i



#	sed -i 's/\(<link[^>]*href=\)"~$SITE_URL`\/profile\/css/\1"~$CSS_URL`/g' $i

#	sed -i 's/\(<link[^>]*href=\)"\/profile\/css\//\1"~$CSS_URL`\//g' $i

#	sed -i 's/\(<link[^>]*href=\)"css\//\1"~$CSS_URL`\//g' $i



	sed -i 's/src="http:\/\/ser4.jeevansathi.com\/profile\/images/src="~$IMG_URL`\/profile\/ser4_images/g' $i

	sed -i 's/url(http:\/\/ser4.jeevansathi.com\/profile\/images/url(~$IMG_URL`\/profile\/ser4_images/g' $i



	sed -i 's/src="http:\/\/ser4.jeevansathi.com\/img_revamp/src="~$IMG_URL`\/img_revamp/g' $i

	sed -i 's/url(http:\/\/ser4.jeevansathi.com\/img_revamp/url(~$IMG_URL`\/img_revamp/g' $i



	sed -i 's/src="http:\/\/ser4.jeevansathi.com\/profile\/imagesnew/src="~$IMG_URL`\/profile\/ser4_imagesnew/g' $i

	sed -i 's/url(http:\/\/ser4.jeevansathi.com\/profile\/imagesnew/url(~$IMG_URL`\/profile\/ser4_imagesnew/g' $i



#	sed -i 's/src="http:\/\/ser4.jeevansathi.com\/images/src="~$IMG_URL`\/ser4_images/g' $i

#	sed -i 's/url(http:\/\/ser4.jeevansathi.com\/images/url(~$IMG_URL`\/ser4_images/g' $i



done 

