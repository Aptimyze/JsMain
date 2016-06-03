<?php
//include "connect.php";
$IPS = array(	"0" => "127.0.0.1",
		"1" => "198.65.112.205",
		"2" => "198.65.139.241",
		"3" => "198.64.140.109",
		"4" => "linux11862.dn.net",
		"5" => "linuxcp10082.dn.net",
		"6" => "linuxcp10081.dn.net",
		"7" => "198.64.153.138");

function getSarFile()
{
	global $show,$fields,$times,$file;

	if($fin=@fopen($file,"r"))
	{
		$show = array();
		$fields = array();
		$times = array();
		while($line = fgets($fin))
		{
			$data = explode("\t",$line);
			$field = $data[4];
			$value = $data[5];
/*
	                //as the unix timestamp of linux 9 and linux 7.2 differs by 38400
	                $time = $data[2]-38400;
*/
			$time = $data[2];
			$show[$field][] = $value;

			for($i=0;$i<count($fields);$i++)
				if($fields[$i] == $field)
					break;
			if($i == count($fields))
				$fields[] = $field;

			for($i=0;$i<count($times);$i++)
				if($times[$i] == $time)
					break;

			if($i == count($times))
				$times[] = $time;

		}
	fclose($fin);
	}
	else
		die("Error opening file or No such file.($file)");
}

if(!$sid || !$type || !$graph)
	die;
if($sid != "REPL")
{

	/*$sql = "select `IP` from `IPDETAILS` where `SID`=$sid and `HTTP`='y'";
	$res = mysql_query($sql) or die("DIED : $sql -".mysql_error());
	if(mysql_num_rows($res) == 0)
		die("IP not found");
	$row = mysql_fetch_array($res);
	$ip = $row["IP"];*/

	$file = "http://$IPS[$sid]/";
}
else
	$file = "http://198.65.112.205/";

list($dd,$mm,$yy) = explode("-",$date);
/*$dd=$data[0];
$mm=$data[1];
$yy=$data[2];
*/
if($sid=="REPL")
{
	$type=2;
	$file="http://198.64.140.118/mysql_pulsechck2/$mm$dd";
}
switch($type)
{
	case 1:	$file .= "loadchkr/$mm$dd";		//load graph

		if($fin=@fopen($file,"r"))
		{
			$show = array();
			while($line = fgets($fin))
			{
				$data = explode("            ",$line);
				$time = $data[0];
        			$newdata = explode(" ",$data[1]);
				$load = $newdata[0];
				$load1 = $newdata[1];
				$load2 = $newdata[2];
				$show[] = array($time,$load,$load1,$load2);
			}
		fclose($fin);
		}
		else
			die("Error opening file or No such file.($file)");

		break;
	case 2: if($sid != "REPL")	$file .= "mysql_pulsechck/$mm$dd";	//MySQL pulse graph

		if($fin=@fopen($file,"r"))
		{
			$show = array();
			while($line = fgets($fin))
			{
				$data = explode(" ",$line);
			//      if($last[3] > 0)
				$questions = $data[3] - $last[3];
			//      if($last[4] > 0)
				$slow = $data[4] - $last[4];
				$time = $data[0];

				$show[] = array($time,$questions,$slow);
				$last = $data;
			}
		fclose($fin);

		$show[0][1]=0;
		$show[0][2]=0;
		}
		else
			die("Error opening file or No such file.($file)");
		break;
	case 3:	$file .= "sar/sar_cpu_$dd$mm$yy.txt";
		getSarFile();
		$flds = array("%user","%nice","%system","%idle");
		break;
	case 4:	$file .= "sar/sar_io_$dd$mm$yy.txt";
		getSarFile();
		$flds = array("bread/s","bwrtn/s");
		break;
	case 5:	$file .= "sar/sar_mem_$dd$mm$yy.txt";
		getSarFile();
		$flds = array("%memused","%swpused");
		break;
	case 6:	$file .= "mailq/mailq$mm$dd";		//load graph

		if($fin=@fopen($file,"r"))
		{
			$show = array();$ii=0;
			while($line = fgets($fin))
			{
				unset($data);
				unset($mailq);
				list($data,$mailq) = explode("    ",$line);
				$timestamp = $data;
				$time = date("H:i:s",$timestamp);
				$show[] = array($time,intval($mailq));
				//$show[] = array($time,ereg_replace("\r|\n|\r\n","",$mailq));
			}
		fclose($fin);
		}
		else
			die("Error opening file or No such file.($file)");

		break;
	default:
		die("Invalid Graph Type");
}

$X=850;
$Y=530;
header ("Content-type: image/png");

$im = @imagecreate ($X, $Y) or die ("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate ($im, 0, 0, 0);

$white = imagecolorallocate ($im, 255, 255, 255);
$black = imagecolorallocate ($im, 0, 0, 0);
$blue = imagecolorallocate ($im, 0, 0, 255);
$red = imagecolorallocate ($im, 255, 0, 0);
$green = imagecolorallocate ($im, 0, 255, 0);
$grey = imagecolorallocate ($im, 128, 128, 128);

$lgrey = imagecolorallocate ($im, 100, 100, 100);
$c3 = imagecolorallocate ($im, 200, 100, 0);
$c2 = imagecolorallocate ($im, 100, 200, 255);
$c1 = imagecolorallocate ($im, 0, 200, 100);

switch($type)
{
	case 1:
		$MAX=0;
		for($i=0;$i<count($show);$i++)
		{
			if($MAX < $show[$i][1]) $MAX = $show[$i][1];
			if($MAX < $show[$i][2]) $MAX = $show[$i][2];
			if($MAX < $show[$i][3]) $MAX = $show[$i][3];
		}
		if($MAX < 10)		$MAX=10;
		else		$MAX=ceil($MAX);
		
		imageline($im,50,0,50,$Y-100,$white);
		imageline($im,50,$Y-100,$X-20,$Y-100,$white);

		imagestringup($im,4,1,($Y-100)/2,"LOAD",$white);
		$YAXIS2 = $Y-100;
		for($i=0;$i< $YAXIS2;$i+=($YAXIS2/20))
		{
			$q = $MAX-(($MAX/$YAXIS2)*$i);
			imagestring($im,1,20,$i,$q,$white);
			imageline($im,51,$i,$X-21,$i,$blue);
		}

		$DY = 5;
		for($i=0;$i<count($show);$i++)
		{
			$XAXIS = 50+($i*8)+1;
			$YAXIS2 = $Y-100;
			$YAXIS1 = $YAXIS2 - (($YAXIS2/$MAX) * $show[$i][1]);
			imageline($im,$XAXIS,$YAXIS1,$XAXIS,$YAXIS2,$white);
			if($YAXIS1 < 0)
			{
				imagestring($im,1,$XAXIS-10,$DY,$show[$i][1],$grey);
				$DY+=10;
			}

			$YAXIS1 = $YAXIS2 - (($YAXIS2/$MAX) * $show[$i][2]);
			imageline($im,$XAXIS+2,$YAXIS1,$XAXIS+2,$YAXIS2,$green);
			if($YAXIS1 < 0)
			{
				imagestring($im,1,$XAXIS-10,$DY,$show[$i][2],$grey);
				$DY+=10;
			}

			$YAXIS1 = $YAXIS2 - (($YAXIS2/$MAX) * $show[$i][3]);
			imageline($im,$XAXIS+4,$YAXIS1,$XAXIS+4,$YAXIS2,$red);
			if($YAXIS1 < 0)
			{
				imagestring($im,1,$XAXIS-10,$DY,$show[$i][3],$grey);
				$DY+=10;
			}

			if($i%2 == 0)
			{
				imagestringup($im,2,$XAXIS-7,$Y-20,$show[$i][0],$white);
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$grey);
			}
			else
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$blue);
		}
		imagestring($im,2,200,$Y-15,"TIME US($file)",$white);
		break;
	case 2:
		$MAXQ = 150000;	//max for pulse
		$MAXS = 80;	//max for slow queries
		$MAXQ=0;
		$MAXS=0;
		for($i=0;$i<count($show);$i++)
		{
			if($MAXQ < $show[$i][1]) $MAXQ = $show[$i][1];
			if($MAXS < $show[$i][2]) $MAXS = $show[$i][2];
		}
		if($MAXQ < 100000)	$MAXQ=100000;
		else			$MAXQ=round($MAXQ,-3);

		if($MAXS < 10)		$MAXS=10;
		else			$MAXS=ceil($MAXS);

		imageline($im,50,0,50,$Y-100,$white);		//boundary lines
		imageline($im,$X-50,0,$X-50,$Y-100,$white);
		imageline($im,50,$Y-100,$X-50,$Y-100,$white);

		imagestringup($im,4,1,($Y-100)/2,"QUESTIONS",$white);		//labelling
		imagestringup($im,4,$X-20,($Y-100)/2,"SLOW QUERIES",$red);
		imagestring($im,4,$X/2-200,$Y-30,"TIME US($file)",$white);

		$YAXIS2 = $Y-100;
		for($i=0;$i< $YAXIS2;$i+=($YAXIS2/20))
		{
			$q = $MAXQ-(($MAXQ/$YAXIS2)*$i);
			$p = $MAXS-(($MAXS/$YAXIS2)*$i);
			imagestring($im,1,20,$i,$q,$white);	//questions label
			imagestring($im,1,$X-40,$i,$p,$red);	//slow query label
			imageline($im,51,$i,$X-51,$i,$blue);	//horizontal line
		}

		$YS=5;
		for($i=0;$i<count($show);$i++)
		{
			$XAXIS = 50+($i*2)+1;
			//$YAXIS2 = 400;
			$YAXIS2 = $Y-100;

		//	if($show[$i][1] > 0)
		//	{

			$YAXIS1 = $YAXIS2 - (($YAXIS2/$MAXQ) * $show[$i][1]);
			imageline($im,$XAXIS,$YAXIS1,$XAXIS,$YAXIS2,$white);
			if($YAXIS1 < 0)
			{
				imagestring($im,1,$XAXIS-10,$YS,$show[$i][1],$green);
				$YS+=10;
			}

		//	}

			if($show[$i][2] != 0)
			{
				$YAXIS1 = $YAXIS2 - (($YAXIS2/$MAXS) * $show[$i][2]);
				imageline($im,$XAXIS+1,$YAXIS1,$XAXIS+1,$YAXIS2,$red);
				if($YAXIS1 < 0)
				{
					imagestring($im,1,$XAXIS-5,$YS,$show[$i][2],$green);
					$YS+=10;
				}
			}

			if(($i%6) == 0)
			{
				imagestringup($im,2,$XAXIS-7,$Y-40,$show[$i][0],$white);
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$green);
			}
			else
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$blue);
		}

		break;
	case 3:		//draw sar graphs
	case 4:
	case 5:	
		$GAP = 5;
		imageline($im,50,0,50,400,$white);
		imageline($im,50,400,$X-90,400,$white);

		imagestring($im,2,200,$Y-15,"TIME US($file)",$white);

		$color = array($white,$red,$green,$grey,$c1,$c2,$c3);

		for($f=0;$f<count($flds);$f++)
		{
			$YAXIS = 100 + ($f*20);
			imageline($im,750,$YAXIS,$X-70,$YAXIS,$color[$f]);
			imagestring($im,2,$X-60,$YAXIS-10,$flds[$f],$white);
		}


		for($i=0;$i< 400;$i+=(400/20))			imageline($im,51,$i,$X-90,$i,$blue);

		for($f=0;$f<count($flds);$f++)
		{

			$field = $flds[$f];
			if($field[0] == "%")	$MAX = 120;
			else			$MAX = 800;

			if($field[0] == "k" && $field[1] == "b")	$MAX = 7000000;

			if($field == "kbbuffers")			$MAX = 80000;

			if($field == "bread/s" || $field == "bwrtn/s")	$MAX = 3750;

			for($i=0;$i< 400;$i+=(400/20))
			{
				$q = $MAX-(($MAX/400)*$i);
				imagestring($im,1,10,$i,$q,$white);
			}

			$oldX = -1;
			for($i=0;$i<count($show[$field]);$i++)
			{
				$XAXIS = 50+($i* $GAP );
				$YAXIS = 400 - ((400/$MAX) * $show[$field][$i]);
				if($oldX != -1)
				imageline($im,$oldX,$oldY,$XAXIS,$YAXIS,$color[$f]);
				$oldX = $XAXIS;
				$oldY = $YAXIS;

				if($f == 0 && $i%6 ==0)
				{	
					$time = date("d/m H:i:s",$times[$i]);
					imagestringup($im,2,$XAXIS-7,500,$time,$white);
					imageline($im,$XAXIS,410,$XAXIS,401,$green);
				}
				elseif($f == 0 )
					imageline($im,$XAXIS,410,$XAXIS,401,$blue);

			}

		}
		break;	
	case 6:
		$MAXQ = 150000;	//max for mailq
		$MAXQ=0;
		for($i=0;$i<count($show);$i++)
		{
			if($MAXQ < $show[$i][1]) $MAXQ = $show[$i][1];
		}
		
		if($MAXQ < 1000)	$MAXQ=1000;
		else			$MAXQ=round($MAXQ,-3);

		imageline($im,50,0,50,$Y-100,$white);		//boundary lines
		imageline($im,$X-50,0,$X-50,$Y-100,$white);
		imageline($im,50,$Y-100,$X-50,$Y-100,$white);

		imagestringup($im,4,1,($Y-100)/2,"MAILQ COUNT, MAXQ = $MAXQ",$white);		//labelling
		imagestring($im,4,$X/2-200,$Y-30,"TIME US($file)",$white);

		$YAXIS2 = $Y-100;
		for($i=0;$i< $YAXIS2;$i+=($YAXIS2/20))
		{
			$q = $MAXQ-(($MAXQ/$YAXIS2)*$i);
			imagestring($im,1,20,$i,$q,$white);	//questions label
			imageline($im,51,$i,$X-51,$i,$blue);	//horizontal line
		}

		$YS=5;
		for($i=0;$i<count($show);$i++)
		{
			$XAXIS = 50+($i*2)+1;
			//$YAXIS2 = 400;
			$YAXIS2 = $Y-100;

		//	if($show[$i][1] > 0)
		//	{

			$YAXIS1 = $YAXIS2 - (($YAXIS2/$MAXQ) * $show[$i][1]);
			imageline($im,$XAXIS,$YAXIS1,$XAXIS,$YAXIS2,$white);
			if($YAXIS1 < 0)
			{
				imagestring($im,1,$XAXIS-10,$YS,$show[$i][1],$green);
				$YS+=10;
			}

		//	}

			if(($i%6) == 0)
			{
				imagestringup($im,2,$XAXIS-7,$Y-40,$show[$i][0],$white);
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$green);
			}
			else
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$blue);
		}

		break;
	case 7:
		$MAX=0;
		for($i=0;$i<count($show);$i++)
		{
			if($MAX < $show[$i][1]) $MAX = $show[$i][1];
		}
		if($MAX < 10)		$MAX=10;
		else		$MAX=ceil($MAX);
		
		imageline($im,50,0,50,$Y-100,$white);
		imageline($im,50,$Y-100,$X-20,$Y-100,$white);

		imagestringup($im,4,1,($Y-100)/2,"MAILQ COUNT",$white);
		$YAXIS2 = $Y-100;
		for($i=0;$i< $YAXIS2;$i+=($YAXIS2/20))
		{
			$q = $MAX-(($MAX/$YAXIS2)*$i);
			imagestring($im,1,20,$i,$q,$white);
			imageline($im,51,$i,$X-21,$i,$blue);
		}

		$DY = 5;
		for($i=0;$i<count($show);$i++)
		{
			$XAXIS = 50+($i*8)+1;
			$YAXIS2 = $Y-100;
			$YAXIS1 = $YAXIS2 - (($YAXIS2/$MAX) * $show[$i][1]);
			imageline($im,$XAXIS,$YAXIS1,$XAXIS,$YAXIS2,$white);
			if($YAXIS1 < 0)
			{
				imagestring($im,1,$XAXIS-10,$DY,$show[$i][1],$grey);
				$DY+=10;
			}

			if($i%2 == 0)
			{
				imagestringup($im,2,$XAXIS-7,$Y-20,$show[$i][0],$white);
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$grey);
			}
			else
				imageline($im,$XAXIS,$Y-99,$XAXIS,$Y-90,$blue);
		}
		imagestring($im,2,200,$Y-15,"TIME US($file)",$white);
		break;
}

imagepng ($im);
imagedestroy ($im);
?>
