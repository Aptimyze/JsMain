<?php
$regularExpressions =
array(
"1"=>array(
	"searchPageProfileId"=>"/profileid%3D\w{1,}/",//search
//	"searchPageProfileId"=>"/profileid=\w{1,}/",//search
	"noOfSearchResults"=>"/\d{0,}\s{0,}\+?\s{0,}profiles found/",//search
	"Age"=>"/Age\s\/\sHeight.{0,}\n{0,}/",//profile view page
	"Date of Birth"=>"/Date\sof\sBirth<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Time of Birth"=>"/Time\sof\sBirth<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"City of Birth"=>"/City\sof\sBirth<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Country of Birth"=>"/City\sof\sBirth<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Marital Status"=>"/Marital\sStatus<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Gender"=>"/Location\sof\s[A-Z|a-z]{0,}/",//profile view page
	"Blood Group"=>"/Blood\sGroup<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Mother Tongue"=>"/Mother\sTongue<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Residency Status"=>"/Residency\sStatus<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Current Residence"=>"/Current\sResidence<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Education"=>"/Education<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"No. of Sisters"=>"/No.\sof\sSisters<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"No. of Brothers"=>"/No.\sof\sBrothers<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Diet"=>"/Diet<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Drink"=>"/Drink<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Smoke"=>"/Smoke<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Sub caste / sect"=>"/Sub\scaste\s\/\ssect<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Height"=>"/\/\sHeight.{0,}\n{0,}/",//profile view page
	"Religion"=>"/Religion<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Caste / Sect"=>"/Caste\s\/\sSect<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page
	"Country of Residence"=>"/Current\sResidence<\/label><\/li>\n{0,}\s{0,}.{0,}/",//profile view page - same as current residence field above
	"se"=>"/se=[A-Z|a-z|0-9]{0,}/",//post to contact detail page //so getting it from profile page
//	"Mobile"=>"/Mobile<\/label>\n{0,}.{0,}\+91-[\d]{10}/",//contact detail
	"Mobile"=>"/Mobile No.<\/label>.91.[\d]{0,}/",//contact detail
//	"Landline"=>"/Landline<\/label>\n{0,}.{0,}\+91-[\d|-]{0,}/",//contact detail
	"Landline"=>"/Landline<\/label>.91.[\d|-]{0,}/",//contact detail
//	"STD"=>"/Landline<\/label>\n{0,}.{0,}\+91-[\d|-]{0,}/",//contact detail
	"STD"=>"/Landline<\/label>.91.[\d|-]{0,}/",//contact detail
	"Gothra"=>"/gotra_gothram_link.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}.{0,}\n{0,}.{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}\n{0,}\n{0,}\n{0,}.{0,}\n{0,}\n{0,}\n{0,}.{0,}\n{0,}.{0,}/",
	"Name"=>"/prof_username.{0,}<\/span>/"
	),
"2"=>array(
	"searchPageProfileId"=>"/\"MId\":\"[A-Z|a-z|0-9]{1,}\"/",
	"noOfSearchResults"=>"/\~\d{0,}\~\{\"profiles\"/",
        "Age"=>"/\d\d\sYears/",

//following 4 values not found in any of search,profile n contact page
        "Date of Birth"=>"/Date\sof\sBirth<\/em>.{0,}<em>[0-9|A-Za-z|-]{0,}/",
        "Time of Birth"=>"/Time\sof\sBirth<\/em>.{0,}<em>[0-9|A-Z|a-z|:]{0,}/",
        "City of Birth"=>"/City\sof\sBirth<\/em>.{0,}<em>[A-Z|a-z|\s]{0,}/",
        "Country of Birth"=>"/Country\sof\sBirth<\/em>.{0,}<em>[A-Z|a-z|\s]{0,}/",

        "Marital status"=>"/Marital\sStatus<\/div>\n{0,}.{0,}/",
        "Blood Group"=>"/Blood\sGroup<\/div>[\n|\t]{0,}.{0,}<\/div>[\n|\t]{0,}.{0,}<\/div>/",
        "Mother Tongue"=>"/Mother\sTongue<\/div>[\n|\t]{0,}.{0,}<\/div>[\n|\t]{0,}.{0,}<\/div>/",
        "Resident Status"=>"/Resident\sStatus<\/div>[\n|\t]{0,}.{0,}<\/div>[\n|\t]{0,}.{0,}<\/div>/",
        "City"=>"/City<\/div>[\n|\t]{0,}.{0,}<\/div>[\n|\t]{0,}.{0,}<\/div>/",
        "Education Category"=>"/Education\sCategory<\/div>\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}<\/div>/", // can b optimized
        "No. of Sister(s)"=>"/Sister\(s\)<\/div>\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}<\/div>/",
        "No. of Brother(s)"=>"/Brother\(s\)<\/div>\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}<\/div>/",
        "Eating Habits"=>"/Eating\sHabits<\/div>\n{0,}.{0,}\n{0,}.{0,}/", 
        "Drinking habits"=>"/Drinking\shabits<\/div>\n{0,}.{0,}\n{0,}.{0,}/", 
        "Smoking habits"=>"/Smoking\shabits<\/div>\n{0,}.{0,}\n{0,}.{0,}/", 
        "Sub Caste"=>"/Caste<\/div>\n{0,}.{0,}<\/div>\n{0,}.{0,}/",
	"Height"=>"/Height<\/div>\n{0,}.{0,}<\/div>\n{0,}.{0,}/",
        "Religion"=>"/Religion<\/div>\n{0,}.{0,}<\/div>\n{0,}.{0,}/",
        "Caste"=>"/Caste<\/div>\n{0,}.{0,}<\/div>\n{0,}.{0,}/",
        "Country"=>"/Country<\/div>\n{0,}.{0,}<\/div>\n{0,}.{0,}/",
	"Name"=>"/Name<\/div>[\n|\t]{0,}.{0,}<\/div>[\n|\t]{0,}.{0,}<\/div>/",
//        "Phone"=>"/Phone<.{0,}\d{10}/",
        "Phone"=>"/Phone<.{0,}\d{7,}/",
	"Gothra"=>"/Gothram.{0,}\n.{0,}<\/div>/"
//	"BM_COMMUNITY"=>"/a\shref.{0,}\..{0,}\matrimony.com/"
        ),
"3"=>array(
	"searchPageProfileId"=>"/targetProfileId.{0,}\/>/",
	"noOfSearchResults"=>"/\"fr\">.{0,}results.{0,}<\/li>/",
	"searchActionId"=>"/search\/.{0,}\/run/",

	"searchActionId2"=>"/jsf_state_64\"\svalue=\".{0,}jsf_viewid/",

	"detailPageUrlParams1"=>"/\/matrimonial\/(groom|bride)\/",
	"detailPageUrlParams2"=>".{0,}\"/",

	"Age"=>"/<h6>.{0,}\n{0,}.{0,}<p>.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}/",//<div\sid=\"cRs_242\"/",
	"Date of Birth"=>"/Date\sOf\sBirth<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
        "Time of Birth"=>"/Time\sOf\sBirth<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
	"City of Birth"=>"/Birth\sPlace<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
	"Country of Birth"=>"/Country\sof\sbirth<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
        "Marital Status"=>"/Marital\sStatus<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
        "Mother Tongue"=>"/Mother\sTongue<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
	"City of Residence"=>"/<h6>.{0,}\n{0,}.{0,}<p>.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}/",//<div\sid=\"cRs_242\"/",
	"Country of Residence"=>"/<h6>.{0,}\n{0,}.{0,}<p>.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}/",//<div\sid=\"cRs_242\"/",
        "Education"=>"/Highest\sQualification<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
        "Eating Habits"=>"/Eating\sHabits<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
        "Drinking"=>"/Drinking<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
        "Smoking"=>"/Smoking<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
	"Height"=>"/<h6>.{0,}\n{0,}.{0,}<p>.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}/",//<div\sid=\"cRs_242\"/",
        "Religion"=>"/Religion<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
        "Caste"=>"/Caste<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
	"Sub Caste"=>"/Sub\sCaste<\/label>\n{0,}.{0,}\n{0,}.{0,}/",
	"Brothers"=>"/Brothers<\/label>\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}/",
	"Sisters"=>"/Sisters<\/label>\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}/",

	"Mobile"=>"/Mobile<\/p>\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}\n{0,}.{0,}/",
	"Email"=>"/Email\sId<\/p>.{0,}\n{0,}.{0,}<\/span>/"
	)
);
?>
