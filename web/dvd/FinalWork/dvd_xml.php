<?
include("titleset.php");

function dvd_xml($profile_data,$s0,$s1,$s2,$s3,$s4,$s5,$s6)
{
	/*print_r($s0);
	print_r($s1);
	print_r($s2);
	print_r($s3);
	print_r($s4);
	print_r($s6);
	die;*/
	global $profileid_costumer;
$dvdauthor="<dvdauthor dest=\"../dvd_content_".$profileid_costumer."/\"><vmgm><menus>
<pgc> <button> jump titleset 1 menu; </button> <button> jump menu 2 ; </button> <button> jump menu 3; </button><button> jump menu 4;</button><button> jump menu 4; </button> <button> jump menu 4;</button><button> jump menu 4; </button> <button> jump menu 4;</button><button> jump menu 4; </button><button> jump menu 4;</button> <vob file=\"Main/".$profileid_costumer."_main.mpg\" pause=\"inf\"/> </pgc> 
<pgc> 
<button> jump titleset 2 menu; </button> 
<button> jump titleset 3 menu ; </button> 
<button> jump titleset 4 menu ; </button> 
<button> jump  menu 1; </button>
<button> jump  menu 1; </button>
<button> jump menu 2; </button>
<button> jump menu 1; </button>
<button> jump menu 1; </button>
<button> jump menu 2; </button>
<button> jump menu 5; </button><vob file=\"Main/3_browse_income.mpg\" pause=\"inf\"/> 
</pgc> 
<pgc> 
<button> jump titleset 5 menu; </button> 
<button> jump titleset 6 menu ; </button>
 <button> jump titleset 7 menu; </button>
 <button>jump  menu 1; </button> 
 <button> jump  menu 1;</button>
 <button> jump menu 3; </button>
 <button> jump menu 1; </button>
 <button> jump menu 1; </button>
 <button> jump menu 3; </button>
 <button>jump menu 6;</button><vob file=\"Main/3_browse_education.mpg\" pause=\"inf\"/> </pgc> 
<pgc><post>jump menu 1;</post><vob file=\"Main/help.mpg\" pause=\"15\"/></pgc>
<pgc><post>jump menu 2;</post><vob file=\"Main/help.mpg\" pause=\"15\"/></pgc>
<pgc><post>jump menu 3;</post><vob file=\"Main/help.mpg\" pause=\"15\"/></pgc>
</menus></vmgm>";
$tts=1;

//include("test.php");
  //  test();
	//$titleset["s6"]=create_titleset($s6);
    for($i=0;$i<=6;$i++)
	{
			$temp="s".$i;
			$titleset[$temp]=create_titleset($$temp,$temp,$i);
			
	}
	
	$dvdauthor.=$titleset['s6'];
	for($i=0;$i<6;$i++)
	{
		$temp="s".$i;
		$dvdauthor.="\n".$titleset[$temp];
	}
	$dvdauthor.="";
	$dvdauthor.="</dvdauthor>";
	
$fp = fopen('dvdauthor_'.$profileid_costumer.'.xml', 'w');
fwrite($fp, $dvdauthor);
//fwrite($fp, '23');
fclose($fp);
}
?>
