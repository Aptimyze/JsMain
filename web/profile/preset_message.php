<?
global $own_addr;
//Preset EOI message
$email_id_det=$own_addr['EMAIL'];
$phone_no_det=$own_addr['PHONE'];
global $data;
if($data["GENDER"]=='M')
{
	$type_of_post="son's";

}
else
	$type_of_post="daughter's";

if($show_what=="EOI" || $show_what=="ALL")
{
		if($paid)
		{
			if($relation==2)
				$DRA_MES['PRE_1']="We liked your profile on Jeevansathi. Please 'accept' our interest if you want us to contact you further. My $type_of_post id is '$username'.";
			else
				$DRA_MES['PRE_1']="I liked your profile on Jeevansathi. Please 'accept' my interest if you want me to contact you further. My id is '$username'.";

		}
		else
			$DRA_MES['PRE_1']="Jeevansathi member with profile id $username likes your profile. Please 'Accept' to show that you like this profile.";
}
if($show_what=="DECLINE" || $show_what=="ALL")
{
	//Preset decline message.
		if($paid)
		{
			if($relation==2)
				$DRA_MES['PRE_2']="We like your profile and accept your expression of interest. Do call/email us to let us know how you would like to take things forward.";
			else
				$DRA_MES['PRE_2']="I like your profile and accept your expression of interest. Do call/email me to let me know how you would like to take things forward.";
		}
		else
			 $DRA_MES['PRE_2']="Jeevansathi user '$username' has accepted your expression of interest.";
		
		if($paid && $relation==2)
			$DRA_MES['D1']="We are sorry, we do not think our $type_of_post the right match for you. Wish you luck in your search for a Jeevansathi!";
		else
			$DRA_MES['D1']="I am sorry, I do not think I am the right match for you. Wish you luck in your search for a Jeevansathi!";
}

//If user is not login , then preset message to show
if(!$data)
{
        $DRA_MES['PRE_1']=" I liked your profile and found it to be a good match. If you like my profile, please accept my expression of interest and contact me to proceed further";
}

if($add_slash=='yes')
{
	if(is_array($DRA_MES))
		foreach($DRA_MES as $key=>$val)
		{
			$DRA_MES[$key]=preg_replace("/\\r\\n|\\n|\\r/","#n#",addslashes($val));
		}
}
