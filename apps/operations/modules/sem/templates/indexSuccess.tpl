<style>
#maindiv
{
	background-color:rgb(239,239,211);
	width:760px;
	padding:10px;
	border:2px solid gray;
	margin:0px auto; 
	text-align:centre;
}
#success
{
	background-color:rgb(239,239,211);
	width:760px;
	padding:10px;
	border:2px solid gray;
	margin:0px auto; 
	text-align:centre;
}
.addbutton
{
	background-color:rgb(239,239,255);
	width:181px;
	padding:0px auto;
	margin:0px auto; 
	text-align:centre;
	border:2px solid gray;
	cursor:pointer;
}
.backbutton
{
	background-color:rgb(239,239,255);
	width:35px;
	text-align:left;
	border:2px solid gray;
	cursor:pointer;
	margin-right: 25px;
}
.submitbutton
{
	background-color:rgb(239,239,255);
	width:57px;
	margin-right: 25px;
	text-align:left;
	border:2px solid gray;
	cursor:pointer;
}
.clearbutton
{
	background-color:rgb(239,239,255);
	width:35px;
	text-align:left;
	border:2px solid	 gray;
	cursor:pointer;
}
#NewhtmlEdit
{
	width:688px;
	/*height:310px;*/
	padding:0px auto;
	margin:0px auto; 
	text-align:centre;
}
.clear {clear:both;}
.left {float:left;}
.defaultText { width: 688px; }
.defaultTextActive { color: #a1a1a1; font-style: italic; }
table#PageList th
{
	border:1px solid black;
	border-collapse:collapse;
	text-align:center;
}
table#PageList td
{
	border:1px solid black;
	border-collapse:collapse;
	text-align:center;
}
.h5{height:5px;}
textarea
{
   resize: none;
}
</style>
<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

~include_partial('global/header')`

<div id="maindiv">
	<div class="addbutton"> Add New Registration Page</div>
	
	<div class="listCustomPage" style="height:298px;overflow-y:scroll;width:740px;margin:auto;padding:15px;">
		<table id="PageList"style="width:100%">
			<tr>
				<th> Page ID </th>
				<th> Title </th>
				<th> Url</th>
				<th> Time </th>
				<th> Edit</th>
				<th> Delete</th>
			</tr>

			~foreach from=$arrPages key=szKey item=szPageID`			
				<tr>
					<td>~$szPageID`</td>
					<td>~$arrTitle[$szKey]`</td>
					<td><a href="~sfConfig::get(app_site_url)`/register/customreg/~$szPageID`">Click Here</a></td>
					<td>~$arrTime[$szKey]`</td>
					<td><a href="~sfConfig::get(app_site_url)`/operations.php/sem?cid=~$cid`&act=edit&p=~$szPageID`">Edit</a></td>
					<td><a id="deleteBox" href="~sfConfig::get(app_site_url)`/operations.php/sem?cid=~$cid`&act=delete&p=~$szPageID`" onclick="return deleteConfirm()">Delete</a></td>
				</tr>
			~/foreach`			
		</table>
	</div>
		
	<form method="post" action="~sfConfig::get(app_site_url)`/operations.php/sem?cid=~$cid`&act=new">
	<div id="NewhtmlEdit">
		~$form['title']->render([style=>'width:688px'])`
		~$form['htmlCode']->render()`
		<div id="parseErr_Msg">~$szParserMsg`<br></div>
	</div>
	
	<div class="h5 clear"></div>
	<div style="width:200px; margin:0px auto; text-align:center; ">
		<input class="submitbutton left" type=submit value="Submit" name="submit" id="submit">
		<input class="submitbutton left" type=submit value="Update" name="modify" id="modify">
		<div class="backbutton left">Back</div>
		<div class="clearbutton left">Clear</div>
	
		<div class="h5 clear"></div>
	</div>
	<input type="hidden" name="bSuccess" value="~$bSuccess`" id="bSuccess" />
	<input type="hidden" name="bRefresh" value="~$bRefresh`" id="bRefresh" />
	<input type="hidden" name="iPageID" value="~$iPageID`" id="iPageID" />
	<input type="hidden" name="bEdit" value="~$bEdit`" id="bEdit" />
	<input type="hidden" name="cid" value="~$cid`" id="cid" />
	<input type="hidden" name="bParseError" value="~$bParseError`" id="bParseError" />
	</form>
</div>
<div id="success">
	<div id="message" style="text-align:center;">
		~if $bSuccess eq 1`
			~$Msg` <div id="timer" style="width:100%;text-align:center;"></div>
		~/if`
	</div>
</div>
<!--
Script Tags
-->
<script>
$(document).ready(function(){
	
	initState();
	SuccessState();
	EditState();
	ParseErrorState();

	$(function() {
    setInterval(counter, 1000);
	});
	
	//Refresh Call
	setTimeout(function(){
		if($("#bRefresh").val()){
			//console.log(location);
			var cid = $("#cid").val();
			location.search = "?cid=" + cid;
			}
		},5300);
});
var timeCounter = 5;
function counter()
{
	$("#timer").html("Refresing Page in " + timeCounter + " Seconds.!!");
	timeCounter--;
	if(timeCounter <= 0 )
		$("#timer").html("Refresing Now!!");
}
$("div.addbutton").click(function(){
		$("div.listCustomPage").slideToggle("fast");
		$("div.addbutton").fadeOut(100);	
		
		$("#NewhtmlEdit").slideToggle("slow");
		$("#submit").show();
		$("#modify").hide(1);
		$("div.backbutton").slideToggle("slow");
		$("div.clearbutton").slideToggle("slow");
		$("div.clearbutton").trigger("click");
});
	
$(".defaultText").blur(function()
{
	if ($(this).val() == "")
	{
		$(this).addClass("defaultTextActive");
		$(this).val($(this)[0].title);
	}
});
$("div.backbutton").click(function(){
		
	$("div.addbutton").slideToggle("medium");
	
	$("#NewhtmlEdit").fadeOut(5);
	$("#submit").fadeOut(7);
	$("#modify").fadeOut(8);
	$("div.backbutton").fadeOut(8);
	$("div.clearbutton").fadeOut(8);
	$("div.listCustomPage").slideToggle("slow");
	$("#notes").css('border','1px solid black');
	$("#parseErr_Msg").hide();
});

$(".defaultText").focus(function(){
	if ($(this).val() == $(this)[0].title)
	{
		$(this).removeClass("defaultTextActive");
		$(this).val("");
	}
});
$("div.clearbutton").click(function(){
	$("#notes").val("");
	$("#title").val("");
	$(".defaultText").blur(); 
});

function initState()
{
	$("div.backbutton").fadeOut(1);
	$("#NewhtmlEdit").fadeOut(1);
	$("#submit").fadeOut(1);
	$("#modify").fadeOut(1);
	$("#success").fadeOut(1);
	
	$("div.clearbutton").fadeOut(1);
	$(".defaultText").blur(); 
	
}

function SuccessState()
{
	var value = $("#bSuccess").val();
	if(value == true)
	{
		$("#success").slideToggle();
		$("#maindiv").hide();
		$("#notes").val("");
		$(".defaultText").blur(); 
	}
	else
	{
		$("#maindiv").show();
		$("#success").hide();
	}
}

function EditState()
{
	var value = $("#bEdit").val();
	if(value == true)
	{console.log("In Edit");
		$("#success").hide();
		$("#maindiv").show();
		$(".defaultText").blur(); 
		$("#submit").hide();
		
		$("div.listCustomPage").slideToggle("fast");
		$("div.addbutton").fadeOut(100);	
		
		$("#NewhtmlEdit").slideToggle("slow");
		$("#modify").slideToggle("slow");
		$("div.backbutton").slideToggle("slow");
		$("div.clearbutton").slideToggle("slow");
	}
}

function ParseErrorState()
{
	var value = $("#bParseError").val();
	var valueEdit = $("#bEdit").val();
	if(value == true && valueEdit == false )
	{
		console.log("In Parser Error");
		$("#success").hide();
		$("#maindiv").show();
		$(".defaultText").blur(); 
		$("#submit").slideToggle("slow	");
		
		$("div.listCustomPage").slideToggle("fast");
		$("div.addbutton").fadeOut(100);	
		
		$("#NewhtmlEdit").slideToggle("slow");
		$("#modify").hide(1);
		$("div.backbutton").slideToggle("slow");
		$("div.clearbutton").slideToggle("slow");
		
		$("#notes").css('border','1px solid red');
		$("#parseErr_Msg").show();
	}
	else if(value == true && valueEdit == true)
	{
		$("#notes").css('border','1px solid red');
		$("#parseErr_Msg").show();
	}
	else
	{
		$("#parseErr_Msg").hide();
	}
}
function deleteConfirm()
{
	
	if(confirm("Do you really want to delete this page?"))
	{
		return true;
	}
	else
	{
		return false;
	}
	
};

</script>
~include_partial('global/footer')`
