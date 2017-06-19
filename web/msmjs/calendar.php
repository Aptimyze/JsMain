<?php

//require_once('');
$month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$day_of_week = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
?>
<head>
<title>Calendar</title>
<meta http-equiv="Content-Type" content="text/html; charset=" />
<link rel="stylesheet" type="text/css" href="js/calendar.css" />
<script type="text/javascript" src="js/calendar.js"></script>
<script type="text/javascript">
<!--
var month_names = new Array("<?php echo implode('","', $month); ?>");
var day_names = new Array("<?php echo implode('","', $day_of_week); ?>");
//-->
</script>
</head>
<body onload="initCalendar();">
<div id="calendar_data"></div>
<div id="clock_data"></div>
</body>
</html>
