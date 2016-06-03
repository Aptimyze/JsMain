<?
include('connect_adwords_db.php');

//include("connect.inc");

//$db=connect_misdb();
//$db2=connect_master();

//$db=mysql_connect("localhost:/tmp/mysql.sock","user","CLDLRTa9") or die(mysql_error());
$dollar_customerId=9662096480;

if($site_ajax && !$campaign_ajax)
{
	$sql="SELECT id,name from adwords_$site_ajax.Campaign";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		$campaigns.=$row['name']."^".$row['id']."|";
	}
	$campaigns = substr($campaigns, 0, strlen($campaigns)-1);
	echo $campaigns;
	exit();
}
elseif($site_ajax && $campaign_ajax)
{
	$sql="SELECT id,name from adwords_$site_ajax.AdGroup where belongsToCampaignId=$campaign_ajax";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		$adgroups.=$row['name']."^".$row['id']."|";
	}
	$adgroups = substr($adgroups, 0, strlen($adgroups)-1);
	echo $adgroups;
	exit();
}


//if(authenticated($cid))
if(1)
{

echo    '<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<title>Jeevansathi Matrimonials- My Jeevansathi Account</title>
	<link rel="stylesheet" href="jeevansathi.css" type="text/css">
	<style type="text/css">
	.psts{ float:left;line-height:20px; width:65%}
	.baro{width:200px; border:1px solid #C5C5C5; line-height:20px}
	.barin{ background-color:#99CC00; background-image:url(http://ser4.jeevansathi.com/profile/images/bar_complete.gif)}</style>
	<script type="text/javascript" language="javascript">
    	
	function makeRequest(str)
	{
		var httpRequest;
		if (window.XMLHttpRequest)
		{ 	
			// Mozilla, Safari, ...
			httpRequest = new XMLHttpRequest();
			if (httpRequest.overrideMimeType) 
			{
				httpRequest.overrideMimeType("text/xml");
				// See note below about this line
			}
		} 
		else if (window.ActiveXObject) 
		{	// IE
			try{httpRequest = new ActiveXObject("Msxml2.XMLHTTP");} 
			catch (e){try{httpRequest = new ActiveXObject("Microsoft.XMLHTTP");} catch (e) {}}
		}

		if (!httpRequest)
		{
			alert("Giving up :( Cannot create an XMLHTTP instance");
			return false;
		}

		if(str=="site_ajax" && document.adwords.site.value)
			url="adwords_mis.php?site_ajax="+document.adwords.site.value;
		else if(str=="campaign_ajax" && document.adwords.campaign.value && document.adwords.site.value)
			url="adwords_mis.php?site_ajax="+document.adwords.site.value+"&campaign_ajax="+document.adwords.campaign.value;
		else
			url="";
		
		if(url)
		{	
			httpRequest.onreadystatechange = function() { getdata(httpRequest,str); };
			httpRequest.open("GET", url, true);
			httpRequest.send("");
		}
		else
		{
			docF.campaign.options.length = 0;
			docF.adgroup.options.length = 0;
		}
	}

	function getdata(httpRequest,str)
	{
		if (httpRequest.readyState == 4)
		{
			if (httpRequest.status == 200)
			{
				docF = document.adwords;
				if(str=="site_ajax")
				{	
					docF.campaign.options.length = 0;
					docF.adgroup.options.length = 0;
					var opt = new Option();
					opt.text ="Select...";
					opt.value ="";
					docF.campaign.options[0] = opt
				}
				else
				{	
					docF.adgroup.options.length = 0;
					var opt = new Option();
					opt.text ="Select...";
					opt.value ="";
					docF.adgroup.options[0] = opt
				}
				
				response=httpRequest.responseText;
				
				var update = new Array();
				if(response.indexOf("|" != -1))
				{
					update = response.split("|");
				}
				
				//alert(update.length);
				
				for(i=0;i<update.length;i++)
				{
					var update2 = new Array();
					update2 = update[i].split("^");
					var opt = new Option();
					opt.text = update2[0];
					opt.value = update2[1];
					if(str=="site_ajax")
						docF.campaign.options[i+1] = opt
					else
						docF.adgroup.options[i+1] = opt
				}
			}
			else
			{
				alert("There was a problem with the request.");
			}
		}
	}
	function validate()
	{
		if(document.adwords.site.value!="")
			return true;
		else
		{	
			alert("Please Select a site first");
			return false;
		}
	}
	function hide_back()
	{
		if(navigator.userAgent.indexOf("Firefox")!=-1)
		{	
			//alert("here");
			//hide back in firefox
			document.getElementById("back").style.display="none";
			//alert(document.getElementById("back"));
		}
	}
</script>
<script type="text/javascript" src="calendarDateInput.js"></script>
</head>
<body onload="hide_back()";>';
	

	if($CMDGo)
        {
		//$start_dt=$year."-".$month."-".$day;
		//$end_dt=$year2."-".$month2."-".$day2;
		
		if($groupby)	
			$sql_groupby=$groupby.'(DATE) as '.$groupby.' ,';
               
		if($site && !$campaign && !$adgroup)
			$sql="SELECT campaignid,  customerId, $sql_groupby campaign,budget,campStatus,sum(imps) as imps,sum(clicks) as clicks , ((sum(clicks)/sum(imps))*100) as ctr, (sum(cost)/sum(clicks)) as cpc, (sum(cost)*1000/sum(imps)) as cpm, sum(cost) as cost, (sum(pos)/count(*)) as pos , sum(conv) as conv,(sum(conv)/sum(clicks)*100) as convRate, (sum(cost)/sum(conv)) as costPerConv from adwords_$site.Campaign_Report where Date BETWEEN '$start_dt' AND '$end_dt' group by campaignid";
		
		elseif($site && $campaign && !$adgroup)	
			$sql="SELECT campaignid,  customerId, $sql_groupby adgroupid ,adgroup, agStatus ,sum(imps) as imps,sum(clicks) as clicks , ((sum(clicks)/sum(imps))*100) as ctr, (sum(cost)/sum(clicks)) as cpc, (sum(cost)*1000/sum(imps)) as cpm, sum(cost) as cost, (sum(pos)/count(*)) as pos ,sum(conv) as conv,(sum(conv)/sum(clicks)*100) as convRate, (sum(cost)/sum(conv)) as costPerConv from adwords_$site.AdGroup_Report where campaignid=$campaign and adgroupid!=0 and adgroupid!='' and Date BETWEEN '$start_dt' AND '$end_dt' group by adgroupid";
				
		elseif($site && $campaign && $adgroup)	
		{
			if($keyword_creative=='keyword')
				$sql="SELECT campaignid,  customerId, $sql_groupby adgroupid , keywordid ,siteKwStatus , kwSite ,kwSiteType,kwDestUrl  ,sum(imps) as imps,sum(clicks) as clicks , ((sum(clicks)/sum(imps))*100) as ctr, (sum(cost)/sum(clicks)) as cpc, (sum(cost)*1000/sum(imps)) as cpm, sum(cost) as cost, (sum(pos)/count(*)) as pos ,sum(conv) as conv,(sum(conv)/sum(clicks)*100) as convRate, (sum(cost)/sum(conv)) as costPerConv from adwords_$site.KeywordCriterion_Report where adgroupid=$adgroup and  campaignid=$campaign and keywordid!=0 and keywordid!='' and Date BETWEEN '$start_dt' AND '$end_dt' group by keywordid ";
			else
				$sql="SELECT campaignid,  customerId, $sql_groupby adgroupid , creativeid ,creativeStatus,creativeType ,creativeDestUrl  ,sum(imps) as imps,sum(clicks) as clicks , ((sum(clicks)/sum(imps))*100) as ctr, (sum(cost)/sum(clicks)) as cpc, (sum(cost)*1000/sum(imps)) as cpm, sum(cost) as cost, (sum(pos)/count(*)) as pos ,sum(conv) as conv,(sum(conv)/sum(clicks)*100) as convRate, (sum(cost)/sum(conv)) as costPerConv from adwords_$site.Creative_Report where adgroupid=$adgroup and campaignid=$campaign and creativeid!=0 and creativeid!=3000000 and  creativeid!='' and Date BETWEEN '$start_dt' AND '$end_dt' group by creativeid ";
		}
		
		if($groupby)
			$sql.=' ,'.$groupby;
		if($groupby2)
			$sql.=' ,'.$groupby2;
		$color[$groupby2]="#CCFFFF";	
		if($orderby)
			$sql.=' order by '.$orderby.' '.$orderby_type;
		//echo $sql;

		//die('<br>abcd');
		$res=mysql_query($sql);	
		while($row=mysql_fetch_array($res))
                {
			$customerId_arr[]=$row['customerId'];
			$campaignid_arr[]=$row['campaignid'];
			$campaign_arr[]=$row['campaign'];
			$campStatus_arr[]=$row['campStatus'];
			$budget_arr[]=($row['budget']/1000000);
			$adgroup_arr[]=$row['adgroup'];
			$adgroupid_arr[]=$row['adgroupid'];
			$agStatus_arr[]=$row['agStatus'];
			$keywordid_arr[]=$row['keywordid'];
			$siteKwStatus_arr[]=$row['siteKwStatus'];
			$kwSite_arr[]=$row['kwSite'];
			$kwSiteType_arr[]=$row['kwSiteType'];
			$kwDestUrl_arr[]=$row['kwDestUrl'];
			$creativeid_arr[]=$row['creativeid'];
			$creativeStatus_arr[]=$row['creativeStatus'];
			$creativeType_arr[]=$row['creativeType'];
			$creativeDestUrl_arr[]=$row['creativeDestUrl'];
			$groupby_arr[]=$row[$groupby];
			$imps_arr[]=$row['imps'];
			$clicks_arr[]=$row['clicks'];
			$ctr_arr[]=round($row['ctr'],2);
			$cpc_arr[]=round($row['cpc'],2);
			$cpm_arr[]=round($row['cpm'],2);
			$cost_arr[]=$row['cost'];
			$pos_arr[]=round($row['pos'],2);
			$conv_arr[]=$row['conv'];
			$convRate_arr[]=round($row['convRate'],2);
			$costPerConv_arr[]=round($row['costPerConv'],2);						
		}

		if($campaign)
		{
			$sql_campaign_name="SELECT name from adwords_$site.Campaign where id=$campaign";
			$res_campaign_name=mysql_query($sql_campaign_name);	
			while($row_campaign_name=mysql_fetch_array($res_campaign_name))
			{
				$campaign_name=$row_campaign_name['name'];	
			}

		}
		if($adgroup)
		{
			$sql_adgroup_name="SELECT name from adwords_$site.AdGroup where id=$adgroup";
			$res_adgroup_name=mysql_query($sql_adgroup_name);	
			while($row_adgroup_name=mysql_fetch_array($res_adgroup_name))
			{
				$adgroup_name=$row_adgroup_name['name'];	
			}
		}	
	
               echo
                '<table width=100% border=0 align="center">
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr class="formhead" width="100%"><td align="center">SEM – Automating Monitoring and Maintenance</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                </table>';


		echo    
		'<table width=100% border=0 align="center">
		<tr class="formhead" width="100%"><td align="center" colspan=14>Date Range: From '.$start_dt.'  To '. $end_dt.'</td></tr>
		<tr class="formhead" width="100%"><td align="center" colspan=14>Site:  '.$site.'</td></tr>';
		if($campaign_name)
			echo '<tr class="formhead" width="100%"><td align="center" colspan=14>Campaign:  '.$campaign_name.'</td></tr>';
		if($adgroup_name)
			echo '<tr class="formhead" width="100%"><td align="center" colspan=14>Adgroup:  '.$adgroup_name.'</td></tr>';
		echo '<tr class="formhead" width="100%"><td align="center" colspan=14><a id="back" name="back" href="adwords_mis.php?day='.$day.'&month='.$month.'&year='.$year.'&day2='.$day2.'&month2='.$month2.'&year2='.$year2.'">BACK</a></td></tr>

		</table>';

		echo    
		'<table width="100%"  border="1" cellspacing="3" cellpadding="3" class="mediumblack">
		<tr class="formhead">';
	
			if($groupby && is_array($customerId_arr) )	
			echo	'<td align="center" bgcolor="#CCFFFF">'.$groupby.'</td>	<!--cakr-->';

			if($site && !$campaign && !$adgroup && is_array($campaignid_arr) )	
			{
				$upto=count($campaignid_arr);
				echo 	
				'<td align="center" bgcolor="#CCFFFF">Campaign Id</td>	<!--c-->
				<td align="center">Campaign</td>	<!--c-->
				<td align="center">Current Status</td>	<!--cakr-->
				<td align="center">Current Budget</td>	<!--c-->
				<td align="center" bgcolor="'.$color['clicks'].'">Clicks</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['imps'].'">Impr</td>		<!--cakr-->	
				<td align="center" bgcolor="'.$color['ctr'].'">CTR %</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['cpc'].'">Avg CPC</td>		<!--cak-->
				<td align="center" bgcolor="'.$color['cpm'].'">Avg CPM</td>		<!--c-->	
				<td align="center" bgcolor="'.$color['cost'].'">Cost</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['convRate'].'">Conv Rate %</td>	<!--cakr-->
				<td align="center" bgcolor="'.$color['costPerConv'].'">Cost/Conv</td>	<!--cakr-->
				<td align="center" bgcolor="'.$color['conv'].'">Conversions</td>	<!--c-->';
			}
			elseif($site && $campaign && !$adgroup && is_array($adgroupid_arr) )	
			{
				$adgroupid_str = implode("','",$adgroupid_arr);
				
				$sql_adgroup="SELECT maxCpc,id from adwords_$site.AdGroup where id in ('$adgroupid_str') ";
				$res_adgroup=mysql_query($sql_adgroup);	
				while($row_adgroup=mysql_fetch_array($res_adgroup))
				{
					$maxCpc_arr[$row_adgroup['id']]=$row_adgroup['maxCpc'];	
				}
				
				$upto=count($adgroupid_arr);
				echo 	
				'<td align="center" bgcolor="#CCFFFF">Adgroup Id</td>	<!--a-->
				<td align="center">Adgroup</td>		<!--a-->
				<td align="center">Current Status</td>	<!--cakr-->
				<td align="center">Max CPC</td>		<!--ak-->
				<td align="center" bgcolor="'.$color['clicks'].'">Clicks</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['imps'].'">Impr</td>		<!--cakr-->	
				<td align="center" bgcolor="'.$color['ctr'].'">CTR %</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['cpc'].'">Avg CPC</td>		<!--cak-->
				<td align="center" bgcolor="'.$color['cost'].'">Cost</td>		<!--cakr-->
				<td align="center">Avg Pos</td>		<!--ak-->
				<td align="center" bgcolor="'.$color['convRate'].'">Conv Rate %</td>	<!--cakr-->
				<td align="center" bgcolor="'.$color['costPerConv'].'">Cost/Conv</td>	<!--cakr-->';
			}
			elseif($site && $campaign && $adgroup && $keyword_creative=='keyword' && is_array($keywordid_arr) )	
			{
				
                                $keywordid_str = implode("','",$keywordid_arr);

                                $sql_keyword="SELECT maxCpc,id,text,status from adwords_$site.KeywordCriterion where id in ('$keywordid_str') ";
                                $res_keyword=mysql_query($sql_keyword);
                                while($row_keyword=mysql_fetch_array($res_keyword))
                                {
                                        $maxCpc_arr[$row_keyword['id']]=array("maxCpc" => $row_keyword['maxCpc'],"text"  => $row_keyword['text'], "status" => $row_keyword['status'] )  ;
                                }

				$upto=count($keywordid_arr);
				echo 
				'<td align="center" bgcolor="#CCFFFF">Keyword Id</td>	<!--k-->
				<td align="center">Keyword</td>	<!--k-->
				<td align="center">Current Status</td>	<!--cakr-->
				<td align="center">Max CPC</td>		<!--ak-->
				<td align="center" bgcolor="'.$color['clicks'].'">Clicks</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['imps'].'">Impr</td>		<!--cakr-->	
				<td align="center" bgcolor="'.$color['ctr'].'">CTR %</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['cpc'].'">Avg CPC</td>		<!--cak-->
				<td align="center" bgcolor="'.$color['cost'].'">Cost</td>		<!--cakr-->
				<td align="center">Avg Pos</td>		<!--ak-->
				<td align="center" bgcolor="'.$color['convRate'].'">Conv Rate %</td>	<!--cakr-->
				<td align="center" bgcolor="'.$color['costPerConv'].'">Cost/Conv</td>	<!--cakr-->';
			}
			elseif($site && $campaign && $adgroup && $keyword_creative=='creative' && is_array($creativeid_arr) )	
			{
				foreach( $creativeType_arr as $key => $value)
				{
					if($value=='text')
						$textid_arr[]=$creativeid_arr[$key];
					else
						$imageid_arr[]=$creativeid_arr[$key];
				}
				if(is_array($textid_arr))
					$textid_str = implode("','",$textid_arr);
				if(is_array($imageid_arr))
					$imageid_str = implode("','",$imageid_arr);

				if($textid_str || $imageid_str)
                                {
					if($textid_str)
					{	
						$sql_creative="SELECT id,headline,description1,description2,displayUrl,destinationUrl from adwords_$site.TextAd where id in ('$textid_str') ";
						$res_creative=mysql_query($sql_creative);
						while($row_creative=mysql_fetch_array($res_creative))
						{
							$maxCpc_arr[$row_creative['id']]=array("headline"=>$row_creative['headline'],"description1"=>$row_creative['description1'],"description2"=>$row_creative['description2'],"displayUrl"=>$row_creative['displayUrl'],"destinationUrl" =>$row_creative['destinationUrl'],"name"=>$row_creative['name'],"width"=>$row_creative['width'],"height" =>$row_creative['height'], "thumbnailUrl"=>$row_creative['thumbnailUrl']);
						}
					}
					if($imageid_str)
					{	
						$sql_creative="SELECT id,name,width,height, thumbnailUrl,displayUrl,destinationUrl from adwords_$site.ImageAd where id in ('$imageid_str') ";
						$res_creative=mysql_query($sql_creative);
						while($row_creative=mysql_fetch_array($res_creative))
						{
							$maxCpc_arr[$row_creative['id']]=array("headline"=>$row_creative['headline'],"description1"=>$row_creative['description1'],"description2"=>$row_creative['description2'],"displayUrl"=>$row_creative['displayUrl'],"destinationUrl" =>$row_creative['destinationUrl'],"name"=>$row_creative['name'],"width"=>$row_creative['width'],"height" =>$row_creative['height'], "thumbnailUrl"=>$row_creative['thumbnailUrl']);
						}
					}
				}
				//print_r($maxCpc_arr);
				
				$upto=count($creativeid_arr);
				echo 
				'<td align="center" bgcolor="#CCFFFF">Creative Id</td>	<!--r-->
				<td align="center">Creative</td>	<!--r-->
				<td align="center">Current Status</td>	<!--cakr-->
				<td align="center" bgcolor="'.$color['clicks'].'">Clicks</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['imps'].'">Impr</td>		<!--cakr-->	
				<td align="center" bgcolor="'.$color['ctr'].'">CTR %</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['cost'].'">Cost</td>		<!--cakr-->
				<td align="center" bgcolor="'.$color['convRate'].'">Conv Rate %</td>	<!--cakr-->
				<td align="center" bgcolor="'.$color['costPerConv'].'">Cost/Conv</td>	<!--cakr-->';
			}
		echo '</tr>';
	
		//echo 'upto is '.$upto;
		
		for($i=0;$i<$upto;$i++)
		{
			echo    '<tr class="fieldsnew">';
			
			if($groupby)	
				echo	'<td align="center">'.$groupby_arr[$i].'</td>	<!--cakr-->';
                        
			if($customerId_arr[$i]==$dollar_customerId)
				$currency='$ ';
			else	 	
				$currency='Rs ';
				
				if($site && !$campaign && !$adgroup && is_array($campaignid_arr) )	
				{
					echo 
					'<td align="center">'.$campaignid_arr[$i].'</td>	<!--c-->
					<td align="center">'.$campaign_arr[$i].'</td>	<!--c-->
					<td align="center">'.$campStatus_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$currency.''.$budget_arr[$i].'</td>	<!--c-->
					<td align="center">'.$clicks_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$imps_arr[$i].'</td>		<!--cakr-->	
					<td align="center">'.$ctr_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$currency.' '.$cpc_arr[$i].'</td>		<!--cak-->
					<td align="center">'.$currency.' '.$cpm_arr[$i].'</td>		<!--c-->	
					<td align="center">'.$currency.' '.$cost_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$convRate_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$currency.' '.$costPerConv_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$conv_arr[$i].'</td>	<!--c-->';
				}
				elseif($site && $campaign && !$adgroup && is_array($adgroupid_arr) )	
				{
					echo 	
					'<td align="center">'.$adgroupid_arr[$i].'</td>	<!--a-->
					<td align="center">'.$adgroup_arr[$i].'</td>	<!--a-->
					<td align="center">'.$agStatus_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$currency.' '.$maxCpc_arr[$adgroupid_arr[$i]].'</td>		<!--ak-->
					<td align="center">'.$clicks_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$imps_arr[$i].'</td>		<!--cakr-->	
					<td align="center">'.$ctr_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$currency.' '.$cpc_arr[$i].'</td>		<!--cak-->
					<td align="center">'.$currency.' '.$cost_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$pos_arr[$i].'</td>		<!--ak-->
					<td align="center">'.$convRate_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$currency.' '.$costPerConv_arr[$i].'</td>	<!--cakr-->';
				}
				elseif($site && $campaign && $adgroup && $keyword_creative=='keyword' && is_array($keywordid_arr))	
				{
					echo 
					'<td align="center">'.$keywordid_arr[$i].'</td>	<!--k-->';
				
					if($keywordid_arr[$i]==3000000)	
						echo '<td align="center">Content network</td>	<!--k-->';
					elseif($kwSite_arr[$i])
						echo '<td align="center">'.$kwSite_arr[$i].'</td>	<!--k-->';
					else
						echo '<td align="center">'.$maxCpc_arr[$keywordid_arr[$i]]['text'].'</td>	<!--k-->';
					if($keywordid_arr[$i]==3000000)
						echo '<td align="center">Enabled</td>	<!--cakr-->';
					elseif($siteKwStatus_arr[$i])
						echo '<td align="center">'.$siteKwStatus_arr[$i].'</td>	<!--cakr-->';
					else
						echo '<td align="center">'.$maxCpc_arr[$keywordid_arr[$i]]['status'].'</td>	<!--k-->';

					echo '<td align="center">'.$currency.' '.$maxCpc_arr[$keywordid_arr[$i]]['maxCpc'].'</td> <!--ak-->';
					echo '<td align="center">'.$clicks_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$imps_arr[$i].'</td>		<!--cakr-->	
					<td align="center">'.$ctr_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$currency.' '.$cpc_arr[$i].'</td>		<!--cak-->
					<td align="center">'.$currency.' '.$cost_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$pos_arr[$i].'</td>		<!--ak-->
					<td align="center">'.$convRate_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$currency.' '.$costPerConv_arr[$i].'</td>	<!--cakr-->';
				}
				elseif($site && $campaign && $adgroup && $keyword_creative=='creative' && is_array($creativeid_arr))	
				{
					echo '<td align="center">'.$creativeid_arr[$i].'</td>	<!--cakr-->';
					if($creativeType_arr[$i]=='text')
						echo 
						'<td align="center">'.$maxCpc_arr[$creativeid_arr[$i]]["headline"].' <br> '.$maxCpc_arr[$creativeid_arr[$i]]["description1"].' <br> '.$maxCpc_arr[$creativeid_arr[$i]]["description2"].' <br> '.$maxCpc_arr[$creativeid_arr[$i]]["displayUrl"].'    </td>	<!--r-->';
					else	
						echo 
						'<td align="center">'.$maxCpc_arr[$creativeid_arr[$i]]["name"].' <br> '.$maxCpc_arr[$creativeid_arr[$i]]["width"].' x '.$maxCpc_arr[$creativeid_arr[$i]]["height"].' px <br><img src='.$maxCpc_arr[$creativeid_arr[$i]]["thumbnailUrl"].'>    </td>	<!--r-->';
					
					echo '<td align="center">'.$creativeStatus_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$clicks_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$imps_arr[$i].'</td>		<!--cakr-->	
					<td align="center">'.$ctr_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$currency.' '.$cost_arr[$i].'</td>		<!--cakr-->
					<td align="center">'.$convRate_arr[$i].'</td>	<!--cakr-->
					<td align="center">'.$currency.' '.$costPerConv_arr[$i].'</td>	<!--cakr-->';
				}
			
			echo 	'</tr>';
		}
	}
	else
	{
		echo 
		'<table width=100% border=0 align="center">
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr class="formhead" width="100%"><td align="center">SEM – Automating Monitoring and Maintenance</td></tr>
		<tr class="formhead" width="100%"><td align="center">Report will show data from 29nd Oct 2007 onwards</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		</table>';
		

		$dt_arr=explode("-",Date('Y-m-d'));
		$mmarr_name=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2005;
                }

		$yesterday=$dt_arr[2]-1;
		
		echo'
		<form name="adwords" method="post" action="adwords_mis.php" onsubmit="return validate();">
			

		<input type="hidden" name="cid" value="~$cid`">
		<table border=0 cellspacing=0 cellpadding=0 width="80%">
		
		<tr>
			<td class="label"> Date range </td>
			<td class="fieldsnew">From:&nbsp;&nbsp;&nbsp;&nbsp;
		
			<script> DateInput("start_dt", true, "YYYY-MM-DD","'.$dt_arr[0].'-'.$dt_arr[1].'-01")</script>
				
				<!--select name=day>';
					for($i=0;$i<31;$i++)
						echo '<option value='.$ddarr[$i].'>'.$ddarr[$i].'</option>';
		
		echo'		</select> -
				<select name=month>';
					for($i=0;$i<12;$i++)
					{	
						echo '<option value='.$mmarr[$i];
						if($dt_arr[1]==$mmarr[$i])
							echo ' selected ';
						echo '>'.$mmarr_name[$i].'</option>';
					} 
					

		echo'		</select> -
				<select name=year>';
					for($i=0;$i<10;$i++)
					{	
						echo '<option value='.$yyarr[$i];
						if($dt_arr[0]==$yyarr[$i])
							echo ' selected ';
						echo '>'.$yyarr[$i].'</option>';
					}
		echo'		</select-->
		

			</td>
			
			<td class="fieldsnew">To:&nbsp;&nbsp;&nbsp;&nbsp;
		
			<script> DateInput("end_dt", true, "YYYY-MM-DD","'.$dt_arr[0].'-'.$dt_arr[1].'-'.$yesterday.'")</script>
				
				<!--select name=day2>';
					for($i=0;$i<31;$i++)
					{	
						echo '<option value='.$ddarr[$i];
						if(($dt_arr[2]-1)==$ddarr[$i])
							echo ' selected ';
						echo '>'.$ddarr[$i].'</option>';
					}
		echo'		</select> -
				<select name=month2>';
					for($i=0;$i<12;$i++)
					{	
						echo '<option value='.$mmarr[$i];
						if($dt_arr[1]==$mmarr[$i])
							echo ' selected ';
						echo '>'.$mmarr_name[$i].'</option>';
					}
		echo'		</select> -
				<select name=year2>';
					for($i=0;$i<10;$i++)
					{	
						echo '<option value='.$yyarr[$i];
						if($dt_arr[0]==$yyarr[$i])
							echo ' selected ';
						echo '>'.$yyarr[$i].'</option>';
					}
		echo'		</select-->
			

			</td>
		</tr>';
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
	
		echo '<tr><td class="label"> Site </td>
			
			<td class="fieldsnew">
				
				<select name=site onchange=makeRequest("site_ajax");>
						<option value="">Select...</option>
						<option value="naukri">naukri</option>
						<option value="jeevansathi">jeevansathi</option>
						<option value="99acres">99acres</option>
		
				</select>
			</td></tr>';
		
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><td class="label"> Campaigns </td>
			
			<td class="fieldsnew">
				
				<select name=campaign onchange=makeRequest("campaign_ajax");>
					<option value=""></option>
				</select>
			</td></tr>';
		
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
			
		echo '<tr><td class="label"> Adgroups </td>
			
			<td class="fieldsnew">
				
				<select name=adgroup>
					<option value=""></option>
				</select>
				
				<input type=radio name=keyword_creative value="keyword" checked>Keyword Report
				<input type=radio name=keyword_creative value="creative">Creative Report
			
			</td></tr>';
		
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
			
		echo '<tr><td class="label"> Group By </td>
			
			<td class="fieldsnew">
				
				<select name=groupby>
					<option value="">Select...</option>
					<option value="day">day</option>
					<option value="month">month</option>
					<option value="quarter">quarter</option>
					<option value="year">year</option>
				</select>
			</td></tr>';
		
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
			
		echo '<tr><td class="label"> Group By 2 </td>
			
			<td class="fieldsnew">
				
				<select name=groupby2>
					<option value="">Select...</option>
					<option value="imps"> Impressions</option>
					<option value="clicks"> Clicks</option>
					<option value="ctr"> CTR</option>
					<option value="cpc"> CPC</option>
					<option value="cpm"> CPM</option>
					<option value="cost"> Cost</option>
					<option value="conv"> Conversions</option>
					<option value="convRate"> Conversion Rate</option>
					<option value="costPerConv"> Cost Per Conversion</option>
				</select>
			</td></tr>';
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
			
		echo '<tr><td class="label"> Order By</td>
			
			<td class="fieldsnew">
				
				<select name=orderby>
					<option value="">Select...</option>
					<option value="imps"> Impressions</option>
					<option value="clicks"> Clicks</option>
					<option value="ctr"> CTR</option>
					<option value="cpc"> CPC</option>
					<option value="cpm"> CPM</option>
					<option value="cost"> Cost</option>
					<option value="conv"> Conversions</option>
					<option value="convRate"> Conversion Rate</option>
					<option value="costPerConv"> Cost Per Conversion</option>
				</select>
				<input type=radio name=orderby_type value="desc" checked>Descending
				<input type=radio name=orderby_type value="asc" >Ascending
			</td></tr>';
		
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		

		echo '<tr><td class="label"><input type="submit" name="CMDGo" value="Submit"></td></tr>';
		echo '</table>';	

		echo '</form>';
	}
	
	echo '</body></html>';
}



?>
