<html>
	<head>
		Number of Leads Entered Per User:
		<script src="../../jscalendar/lang/calendar-en.js" type="text/javascript" >
		</script>
	</head>
	<body>
		<title>
			SugarCRM: Number of leads
		</title>
		<form method="POST" action="lead_per_user_cron.php">
			From Date:
			<?php $today=date("Y-m-d");
			      $yesterday=date("Y-m-d",JSstrToTime("-1 Day"));
				$dateArr1=explode("-",$yesterday);
				$day1=$dateArr1[2];
				$month1=$dateArr1[1];
				$year1=$dateArr1[0];
				$dateArr2=explode("-",$today);
				$day2=$dateArr2[2];
				$month2=$dateArr2[1];
				$year2=$dateArr2[0];
				?>
			<select style="width:71px;font-size:11px;" name="day" id="day">
			<option selected value="<?=$day1?>"><?=$day1?></option>
				<option value=01 >1 </option>
				<option value=02 >2 </option>
				<option value=03 >3 </option>
				<option value=04 >4 </option>

				<option value=05 >5 </option>
				<option value=06 >6 </option>
				<option value=07 >7 </option>
				<option value=08 >8 </option>
				<option value=09 >9 </option>
				<option value=10 >10 </option>
				<option value=11 >11 </option>
				<option value=12 >12 </option>
				<option value=13 >13 </option>

				<option value=14 >14 </option>
				<option value=15 >15 </option>
				<option value=16 >16 </option>
				<option value=17 >17 </option>
				<option value=18 >18 </option>
				<option value=19 >19 </option>
				<option value=20 >20 </option>
				<option value=21 >21 </option>
				<option value=22 >22 </option>

				<option value=23 >23 </option>
				<option value=24 >24 </option>
				<option value=25 >25 </option>
				<option value=26 >26 </option>
				<option value=27 >27 </option>
				<option value=28 >28 </option>
				<option value=29 >29 </option>
				<option value=30 >30 </option>
				<option value=31 >31 </option>

			</select>
			<select style="width:71px;font-size:11px;" name="month" id="month">
			<option selected value="<?=$month1?>"><?=$month1?></option>
				<option value="01">Jan</option>
				<option value="02">Feb</option>
				<option value="03">Mar</option>
				<option value="04">Apr</option>
				<option value="05">May</option>
				<option value="06">Jun</option>
				<option value="07">Jul</option>

				<option value="08">Aug</option>
				<option value="09">Sep</option>
				<option value="10">Oct</option>
				<option value="11">Nov</option>
				<option value="12">Dec</option>
			</select>
			<select style="width:71px;font-size:11px;" name="year" id="year">
			<option selected value="<?=$year1?>"><?=$year1?></option>
				<option value=2009 >2009</option>
				<option value=2010 >2010</option>
				<option value=2011 >2011</option>
				<option value=2012 >2012</option>
			</select>
		To Date:
			<select style="width:71px;font-size:11px;" name="end_day" id="end_day">
			<option selected value="<?=$day1?>"><?=$day1?></option>
				<option value=01 >1 </option>
				<option value=02 >2 </option>
				<option value=03 >3 </option>
				<option value=04 >4 </option>

				<option value=05 >5 </option>
				<option value=06 >6 </option>
				<option value=07 >7 </option>
				<option value=08 >8 </option>
				<option value=09 >9 </option>
				<option value=10 >10 </option>
				<option value=11 >11 </option>
				<option value=12 >12 </option>
				<option value=13 >13 </option>

				<option value=14 >14 </option>
				<option value=15 >15 </option>
				<option value=16 >16 </option>
				<option value=17 >17 </option>
				<option value=18 >18 </option>
				<option value=19 >19 </option>
				<option value=20 >20 </option>
				<option value=21 >21 </option>
				<option value=22 >22 </option>

				<option value=23 >23 </option>
				<option value=24 >24 </option>
				<option value=25 >25 </option>
				<option value=26 >26 </option>
				<option value=27 >27 </option>
				<option value=28 >28 </option>
				<option value=29 >29 </option>
				<option value=30 >30 </option>
				<option value=31 >31 </option>

			</select>
			<select style="width:71px;font-size:11px;" name="end_month" id="end_month">
			<option selected value="<?=$month1?>"><?=$month1?></option>
				<option value="01">Jan</option>
				<option value="02">Feb</option>
				<option value="03">Mar</option>
				<option value="04">Apr</option>
				<option value="05">May</option>
				<option value="06">Jun</option>
				<option value="07">Jul</option>

				<option value="08">Aug</option>
				<option value="09">Sep</option>
				<option value="10">Oct</option>
				<option value="11">Nov</option>
				<option value="12">Dec</option>
			</select>
			<select style="width:71px;font-size:11px;" name="end_year" id="end_year">
			<option selected value="<?=$year1?>"><?=$year1?></option>
				<option value=2009 >2009</option>
				<option value=2010 >2010</option>
				<option value=2011 >2011</option>
				<option value=2012 >2012</option>
			</select>
<br>
<input type="submit" value="submit" />
</form>
<i><font color="#FF0000">Note that time for From date starts from 00:00:00 and <br>time for To date ends at 23:59:59.</i> 
		</body>
		</html>
