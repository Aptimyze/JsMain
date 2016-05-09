<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$sql="SELECT * FROM MIS.DATA_MATRIX_2D WHERE DATE='$date' ";
													     
if($gender=='M')
	$sql.=" AND GENDER='M'";
elseif($gender=='F')
	$sql.=" AND GENDER='F'";
													     
$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
													     
while($row=mysql_fetch_array($res))
{
		$total[0]+=$row['T1_F1'];
		$total[1]+=$row['T1_F2'];
		$total[2]+=$row['T1_F3'];
		$total[3]+=$row['T2_F1'];
		$total[4]+=$row['T2_F2'];
		$total[5]+=$row['T2_F3'];
		$total[6]+=$row['T3_F1'];
		$total[7]+=$row['T3_F2'];
		$total[8]+=$row['T3_F3'];
}

$sql="SELECT * FROM MIS.DATA_MATRIX_2D_FREE WHERE DATE='$date' ";
                                                                                                                             
if($gender=='M')
        $sql.=" AND GENDER='M'";
elseif($gender=='F')
        $sql.=" AND GENDER='F'";
                                                                                                                             
$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                                                                                                                             
while($row=mysql_fetch_array($res))
{
                $total_free[0]+=$row['T1_F1'];
                $total_free[1]+=$row['T1_F2'];
                $total_free[2]+=$row['T1_F3'];
                $total_free[3]+=$row['T2_F1'];
                $total_free[4]+=$row['T2_F2'];
                $total_free[5]+=$row['T2_F3'];
                $total_free[6]+=$row['T3_F1'];
                $total_free[7]+=$row['T3_F2'];
                $total_free[8]+=$row['T3_F3'];
}

$total_paid[0]=$total[0]-$total_free[0];
$total_paid[1]=$total[1]-$total_free[1];
$total_paid[2]=$total[2]-$total_free[2];
$total_paid[3]=$total[3]-$total_free[3];
$total_paid[4]=$total[4]-$total_free[4];
$total_paid[5]=$total[5]-$total_free[5];
$total_paid[6]=$total[6]-$total_free[6];
$total_paid[7]=$total[7]-$total_free[7];
$total_paid[8]=$total[8]-$total_free[8];

header ("Content-type: image/png");
$handle = ImageCreate (400, 400) or die ("Cannot Create image");
$bg_color = ImageColorAllocate ($handle, 123, 123, 123);
$txt_color = ImageColorAllocate ($handle, 255, 255, 255);
$txt_color_green = ImageColorAllocate ($handle, 0, 255, 0);
$line_color = ImageColorAllocate ($handle, 255, 255, 255);

ImageLine($handle, 0, 100, 400, 100, $line_color); 

ImageLine($handle, 0, 200, 400, 200, $line_color); 
ImageLine($handle, 0, 300, 400, 300, $line_color); 

ImageLine($handle, 133, 100, 133, 400, $line_color); 
ImageLine($handle, 266, 100, 266,400, $line_color); 

ImageString ($handle, 5, 100, 30, "Gender: $gender", $txt_color);
ImageString ($handle, 5, 100, 60, "Date(yyyy-mm-dd): $date", $txt_color);

//1
ImageString ($handle, 2, 50, 110, "1", $txt_color_green);
ImageString ($handle, 3, 5, 130, "Total: $total[0]", $txt_color);
ImageString ($handle, 3, 5, 150, "Paid: $total_paid[0]", $txt_color);
ImageString ($handle, 3, 5, 170, "Free: $total_free[0]", $txt_color);

//2
ImageString ($handle, 2, 182, 110, "2", $txt_color_green);
ImageString ($handle, 3, 138, 130, "Total: $total[1]", $txt_color);
ImageString ($handle, 3, 138, 150, "Paid: $total_paid[1]", $txt_color);
ImageString ($handle, 3, 138, 170, "Free: $total_free[1]", $txt_color);

//3
ImageString ($handle, 2, 325, 110, "3", $txt_color_green);
ImageString ($handle, 3, 270, 130, "Total: $total[2]", $txt_color);
ImageString ($handle, 3, 270, 150, "Paid: $total_paid[2]", $txt_color);
ImageString ($handle, 3, 270, 170, "Free: $total_free[2]", $txt_color);

//4
ImageString ($handle, 2, 50, 210, "2", $txt_color_green);
ImageString ($handle, 3, 5, 230, "Total: $total[3]", $txt_color);
ImageString ($handle, 3, 5, 250, "Paid: $total_paid[3]", $txt_color);
ImageString ($handle, 3, 5, 270, "Free: $total_free[3]", $txt_color);

//5
ImageString ($handle, 2, 182, 210, "4", $txt_color_green);
ImageString ($handle, 3, 138, 230, "Total: $total[4]", $txt_color);
ImageString ($handle, 3, 138, 250, "Paid: $total_paid[4]", $txt_color);
ImageString ($handle, 3, 138, 270, "Free: $total_free[4]", $txt_color);

//6
ImageString ($handle, 2, 325, 210, "5", $txt_color_green);
ImageString ($handle, 3, 270, 230, "Total: $total[5]", $txt_color);
ImageString ($handle, 3, 270, 250, "Paid: $total_paid[5]", $txt_color);
ImageString ($handle, 3, 270, 270, "Free: $total_free[5]", $txt_color);

//7
ImageString ($handle, 2, 50, 310, "5", $txt_color_green);
ImageString ($handle, 3, 5, 330, "Total: $total[6]", $txt_color);
ImageString ($handle, 3, 5, 350, "Paid: $total_paid[6]", $txt_color);
ImageString ($handle, 3, 5, 370, "Free: $total_free[6]", $txt_color);

//8
ImageString ($handle, 2, 182, 310, "6", $txt_color_green);
ImageString ($handle, 3, 138, 330, "Total: $total[7]", $txt_color);
ImageString ($handle, 3, 138, 350, "Paid: $total_paid[7]", $txt_color);
ImageString ($handle, 3, 138, 370, "Free: $total_free[7]", $txt_color);

//9
ImageString ($handle, 2, 325, 310, "7", $txt_color_green);
ImageString ($handle, 3, 270, 330, "Total: $total[8]", $txt_color);
ImageString ($handle, 3, 270, 350, "Paid: $total_paid[8]", $txt_color);
ImageString ($handle, 3, 270, 370, "Free: $total_free[8]", $txt_color);

//ImageString ($handle, 3, 130, 405, "Freshness decreases-->", $txt_color_green);
ImagePng ($handle);
?>
