<?
function convert_files_name($files,$type)
{
	include("profile/commonfile.php");
	if($type=="js")
		$arr_type=$JAVASCRIPT;
	else
		$arr_type=$CSS;
	if(gettype($files)=="string")
		$js_css[0]=$files;
	else
		$js_css=$files;
	
	for($i=0;$i<count($js_css);$i++)
	{
		//$pattern='/^(\\)+[.]+(\.js)$/';
		$filename=$js_css[$i];
		$explr=explode("/",$filename);
		$cnt=count($explr)-1;
		$match=$explr[$cnt];
		$subject = $explr[$cnt];

		//Removing the version from file.
		$pattern = '/_\d*\d\.(js|css)/';
	 	$explr[$cnt]=preg_replace($pattern,'.${1}',$explr[$cnt]);
		$key=array_search($match,$arr_type);
		if($key)
			$explr[$cnt]=$key.".".$type;

		$res_file="";
		for($k=0;$k<count($explr);$k++)
		{
			if($k==0)
				$res_file=$explr[$k];
			else
				$res_file=$res_file."/".$explr[$k];
		}	
		$js_css[$i]=$res_file;
	}
	if(gettype($files)=="string")
		return $js_css[0];
	else
		return $js_css;
}

?>
