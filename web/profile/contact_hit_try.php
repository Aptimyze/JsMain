<?
include("connect.inc");
$db=connect_db();
$data=authenticated();
if($data)
{
	if($type)
		contact_hit_limit($data['PROFILEID'],$type);
}
header("Content-Type:image/jpeg");
readfile("$IMG_URL/profile/ser4_images/zero.gif");

?>
