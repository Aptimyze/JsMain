<script>
    function showDiv(elem){
        if(elem.value == "new"){
            document.getElementById('other').style.display = "block";
        }
        else{
            document.getElementById('other').style.display = "none";
        }
    }
    </script>
~include_partial('global/header')`
<form name="form1" action="/operations.php/crmInterface/helpBackend" method="post">
	<input type="hidden" name="agentName" value="~$agentName`">
	<input type="hidden" name="cid" value="~$cid`">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr class="formhead" align="center">
			<td colspan="2" style="background-color:lightblue"><font size=3>Help Backend Interface</font></td>
		</tr>
		<tr></tr>
	</table>
	~if $successMsg`
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr height="10"></tr>
		<tr align="center">
		    <td class="label">
                <font size=3>~$successMsg`. Click <a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/helpBackend">here</a> to go back</font>
		    </td>
		</tr>
	</table>
	~elseif $errorMsg`
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr height="10"></tr>
        <tr>
            <td class="label" style="background-color:orange">
                <font size=3>~$errorMsg`,Please resubmit form with correct details</font>
            </td>
        </tr>
        <!--
		<tr align="center">
            <div style="display: none">
                <textarea id="question" name="question" style="width:100%;height:100%">~$editQuestion.QUESTION`</textarea>
                <textarea id="editor1" name="editor1" class='fullwid' style="width:100%;height:100px">~$editQuestion.ANSWER`</textarea>
                <input type="hidden" name="category" value="~$editQuestion.CATEGORY`">
                <input type="hidden" name="status" value="~$editQuestion.ACTIVE`">
                <input type="hidden" name="id" value="~$editQuestion.ID`">
            </div>
            <td class="label" style="background-color:orange">
                <font size=3>~$errorMsg`,Please <input type="submit" name="resubmit" value="resubmit"> form with correct details</font>
		    </td>
		</tr>
        -->
	</table>
    ~/if`
	~if $successMsg eq ""`
	<table border="0" align="center" width="70%" cellpadding="4" cellspacing="4" border="0">
		<tr height="10"></tr>
		<tr align="center">
            <td class="label" width=100% colspan="3">Question<font style="color:red">*</font>
			</td>
        </tr>
        <tr>
            <td width="80%" colspan="3" style="width:80%;height:100px">
                <textarea id="question" name="question" style="width:100%;height:100%">~$editQuestion.QUESTION`</textarea>
            </td>
		</tr>
		<tr align="center">
				<td class="label" width=100% colspan="3">Answer<font style="color:red">*</font>
			</td>
        </tr>
        <tr align='center'>
            	<td class="label" width=100% colspan="3"><font style="color:red">Please do not type in '&lt;' and '&gt;'</font>
			</td>
        </tr>
        <tr>
            <td width="80%" colspan="3" style="width:80%;height:100px">
                <textarea id="editor1" name="editor1" class='fullwid' style="width:100%;height:100px">~$editQuestion.ANSWER`</textarea>
            </td>	
		</tr>
        <tr align="center">
            <td class="label" width=33%>Category<font style="color:red">*</font>
			</td>
            <td class="label" width=33%>
                <select name="category" onchange="showDiv(this);">
                    <option value="">Select one of the following</option>
                    ~foreach from = $allCategories key = k item = v name = categoryLoop`
                        <option value="~$v`"~if $editQuestion.CATEGORY eq $v` selected ~/if`>~$v`</option>
                    ~/foreach`
                    <option value="new">New Category...</option>
                </select>
            </td>
            
            <td class="label" width=33% >
                <input type="text" id="other" name="other" style="display: none;background-color: white">
            </td>
        </tr>
        <tr align="center">
            <td class="label" width="33%">Status<font style="color:red">*</font></td>
            <td class="label" width="33%"><input type="radio" name="status" value="Y" ~if $editQuestion.ACTIVE neq 'N'` checked ~/if`>Enabled</td>
            <td class="label" width="33%"><input type="radio" name="status" value="N" ~if $editQuestion.ACTIVE eq 'N'` checked ~/if`>Disabled</td>
        </tr>
		<tr height="10"></tr>
		<tr align="center">
			<td class="label" colspan="3" style="background-color:Moccasin">
            <input type="hidden" name="id" value="~$editQuestion.ID`">
			<input type="submit" name="submit" ~if $editQuestion.ID` value="EDIT" ~else` value="SUBMIT" ~/if` onclick="return validateInputs();">
			</td>
		</tr>
	</table>
</form>

<table border="0" align="center" width="100%" cellpadding="4" cellspacing="4" border="0">
    <tr height="10"></tr>
    ~foreach from = $allQuestions key = k item = v name = questionsLoop`
    <tr align="left">
        <td class="formhead" width="100%" colspan="3" style="font-size: 15px">
            ~$k`
        </td>
    </tr>
    ~foreach from = $v key = kk item = vv name = innerLoop`
    <tr align="left">
    <td class="label" width=100% colspan="1" style="font-size: 14px">Question
    </td>
    <td align="right" class="label" width=100% colspan="1" style="font-size: 14px">~if $vv.ACTIVE eq 'Y'`Enabled~else`Disabled~/if`
    </td>
    <td align="right" class="label" width=100% colspan="1" style="font-size: 14px"><a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/helpBackend?id=~$vv.ID`">Edit</a>
    </td>
    </tr>
    <tr>
        <td width="100%" colspan="3">
            ~$vv.QUESTION|nl2br`
        </td>
    </tr>
    <tr align="left">
            <td class="label" width=100% colspan="3" style="font-size: 14px">Answer
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="3" >
            ~$vv.ANSWER|decodevar`
        </td>	
    </tr>
    ~/foreach`
    <tr><td colspan="3"><hr></td></tr>
    ~/foreach`
</table>

	~/if`
~include_partial('global/footer')`
<!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
    <script>
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
      });
    </script>
    
    