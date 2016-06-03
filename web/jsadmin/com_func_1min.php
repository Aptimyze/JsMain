<?php
/*********************************************************************************************
* FILE NAME             : com_func_1min.php
* DESCRIPTION           : to store common functions get_old_value,getAge,populate_religion,create_dd for 1 min registration page
* CREATION DATE         : 6 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

function get_old_value($value,$tablename) 
{
        if($tablename=="newjs.EDUCATION_LEVEL_NEW")
        {
                $sql="SELECT SQL_CACHE OLD_VALUE from $tablename WHERE VALUE='$value'";
                $result=mysql_query_decide($sql) or die(mysql_error_js()."$sql");//or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow=mysql_fetch_array($result);
                $old=$myrow['OLD_VALUE'];
        }         
	elseif($tablename=="newjs.OCCUPATION")
        {
                $sql="SELECT SQL_CACHE VALUE from $tablename WHERE VALUE='$value'";
                $result=mysql_query_decide($sql) or die(mysql_error_js()."$sql");//or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $myrow=mysql_fetch_array($result);
                $old=$myrow['VALUE'];
        }
        return $old;
}

function getAge($newDob)
{
        $today=date("Y-m-d");
        $datearray=explode("-",$newDob);
        $todayArray=explode("-",$today);
                                                                                                 
        $years=($todayArray[0]-$datearray[0]);                                                                                                  
        if(intval($todayArray[1]) < intval($datearray[1]))
                $years--;
        elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
                $years--;                                                                                                  
        return $years;
}

function populate_religion_1min($sel_val=0)
{
        $sql="SELECT SQL_CACHE VALUE,LABEL from newjs.RELIGION ORDER BY SORTBY";
        $result=mysql_query_decide($sql);
        $j=0;
        $i=0;
        while($myrow=mysql_fetch_row($result))
        {
                $strtemp = '';
                $religion_value[]="$myrow[0]";
                $religion_label[]=$myrow[1];
                $strtemp .= $religion_value[$j]."|X|";
                                                                                                 
                $sql="SELECT SQL_CACHE VALUE,LABEL from newjs.CASTE where PARENT='$myrow[0]' order by SORTBY";
                $result1= mysql_query_decide($sql);
                                                                                                 
                 while($myrow1=mysql_fetch_row($result1))
                {
                        $caste_value[]="$myrow1[0]";
                        $caste_label[]="$myrow1[1]";
                        $strtemp .= $caste_value[$i]."$".$caste_label[$i]."#";
                        $i++;
                }
                $strtemp = substr($strtemp,0,(strlen($strtemp)-1));
                $j++;
$str[] = $strtemp;
        }
                                                                                                 
        for($x=0;$x<count($str);$x++)
        {
                $str_temp = explode('|X|',$str[$x]);
                $str_val = $str_temp[0];
                                                                                                 
                if($sel_val == $str_val)
                        $newstr.="<option value=\"" . $str[$x] . "\" selected>" . $religion_label[$x] . "</option>\n";
                else
                        $newstr.="<option value=\"" . $str[$x] . "\">" . $religion_label[$x] . "</option>\n";
        }
        return $newstr;
}


function create_dd_1min($selected,$cname,$minormax=0,$labelselect="")
{
                                                                                                 
        if(is_array($selected))
        {
                $s_arr = $selected;
                //$selected = array();
        }
        elseif($selected!="")
        {
                $s_arr=explode(",",$selected);
        }
        else
                $s_arr=array();
                                                                                                 
        $muli ="[]";
                                                                                                 
        if ($cname == "top_country")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.TOP_COUNTRY order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
}
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
                                                                                                 
        if ($cname == "Religion")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.RELIGION order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "Caste")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.CASTE order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret="";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "Mtongue")
        {
                if($labelselect=="small")
                        $sql = "select SQL_CACHE VALUE, SMALL_LABEL from newjs.MTONGUE order by SORTBY";
                else
                        $sql = "select SQL_CACHE VALUE, LABEL from newjs.MTONGUE order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                if($labelselect=="small")
                                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[SMALL_LABEL]</option>\n";
                                else
                                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                if($labelselect=="small")
                                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[SMALL_LABEL]</option>\n";
                                else
                                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
}
                }
        }
                                                                                                 
        if ($cname == "Family_Back")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.FAMILY_BACK order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql) ;
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                                                                                                 
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
                $ret .= "";
        }
if ($cname == "Country_Residence")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.COUNTRY order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "Height")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.HEIGHT order by SORTBY";
                if($minormax ==1)
                        $sql .= " desc";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "Occupation")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.OCCUPATION order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "Income")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.INCOME where VISIBLE <> 'N' order by SORTBY";
                //$sql = "select SQL_CACHE VALUE, LABEL from INCOME order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
                                                                                                 
if ($cname == "Country_Birth")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.COUNTRY order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "City_India")
    {
        $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
        $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
        $ret = "";
        while($myrow = mysql_fetch_array($res))
        {
                if(in_array($myrow["VALUE"],$s_arr))
                {
                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                }
                else
                {
                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                }
        }
    }
if ($cname == "City_USA")
    {
        $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
        $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
        $ret = "";
        while($myrow = mysql_fetch_array($res))
        {
            if(in_array($myrow["VALUE"],$s_arr))
            {
                    $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
            }
            else
            {
                    $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
            }
        }
    }
if ($cname == "Education_Level")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.EDUCATION_LEVEL order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
 if ($cname == "Education_Level_New")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.EDUCATION_LEVEL_NEW order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "Occupation_New")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.OCCUPATION order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
if ($cname == "Income_Lacs")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.INCOME_NEW WHERE TYPE='L' order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
 if ($cname == "Income_Thousand")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.INCOME_NEW WHERE TYPE='T'order by SORTBY";
                $res = mysql_query_decide($sql) or die(mysql_error_js().$sql);//or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
                                                                                                 
        return $ret;
}


?>
