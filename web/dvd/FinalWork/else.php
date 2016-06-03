<?
/*
$img="template/photo_browse_no.jpg";
//$png="png/3_browse_education_Selected.png";
$mpeg_params=" -n p -f 8 -a 2 -q 1 ";
$jpeg_params=" -I p -f 25 -n 25 ";
$audio_name="silence.mp2";
$jpeg2yuv="jpeg2yuv $jpeg_params -j \"$img\" | mpeg2enc $mpeg_params -o \"tm_dir/no_search.m2v\"";
$mplex="mplex -f 8 \"tm_dir/no_search.m2v\" $audio_name -o \"tm_dir/no_search.mpg\"";
$spumux="spumux help_spumux.xml < \"tm_dir/no_search.mpg\" > \"Main/no_search_res.mpg\"";	
shell_exec($jpeg2yuv);
shell_exec($mplex);shell_exec($spumux);
*/
$img="template/5_help.jpg";
$png="png/help.png";
$mpeg_params=" -n p -f 8 -a 2 -q 1 ";
$jpeg_params=" -I p -f 25 -n 25 ";
$audio_name="silence.mp2";
$jpeg2yuv="jpeg2yuv $jpeg_params -j \"$img\" | mpeg2enc $mpeg_params -o \"tm_dir/help.m2v\"";
$mplex="mplex -f 8 \"tm_dir/help.m2v\" $audio_name -o \"tm_dir/help.mpg\"";
$spumux="spumux help_spumux.xml < \"tm_dir/help.mpg\" > \"Main/help.mpg\"";	
shell_exec($jpeg2yuv);
shell_exec($mplex);
shell_exec($spumux);
/*
		$img="template/1_home_education.jpg";
		$png="png/3_browse_education_Selected.png";
		$mpeg_params=" -n p -f 8 -a 2 -q 1 ";
		$jpeg_params=" -I p -f 25 -n 25 ";
		$audio_name="silence.mp2";
		$jpeg2yuv="jpeg2yuv $jpeg_params -j \"$img\" | mpeg2enc $mpeg_params -o \"tm_dir/edu.m2v\"";
        $mplex="mplex -f 8 \"tm_dir/edu.m2v\" $audio_name -o \"tm_dir/edu.mpg\"";
        $spumux="spumux edu_spumux.xml < \"tm_dir/edu.mpg\" > \"Main/3_browse_education.mpg\"";
        shell_exec($jpeg2yuv);
        shell_exec($mplex);
        shell_exec($spumux);
		$img="template/1_home_income.jpg";
		$png="png/3_browse_income_Selected.png";
        $jpeg2yuv="jpeg2yuv $jpeg_params -j \"$img\" | mpeg2enc $mpeg_params -o \"tm_dir/inc.m2v\"";
        $mplex="mplex -f 8 \"tm_dir/inc.m2v\" $audio_name -o \"tm_dir/inc.mpg\"";
        $spumux="spumux inc_spumux.xml < \"tm_dir/inc.mpg\" > \"Main/3_browse_income.mpg\"";
        shell_exec($jpeg2yuv);
        shell_exec($mplex);
        shell_exec($spumux);
*/
?>
