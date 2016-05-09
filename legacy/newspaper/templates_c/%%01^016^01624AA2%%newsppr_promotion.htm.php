<?php /* Smarty version 2.6.6, created on 2008-09-11 02:35:52
         compiled from newsppr_promotion.htm */ ?>
<html>
<head>
<title>JeevanSathi Matrimonials - Promotion through newspapers</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<?php echo $this->_tpl_vars['HEAD']; ?>

<br>
<body leftmargin="5" topmargin="5">
<form action="newsppr_promotion.php" method="POST">
<table width="560" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class=formhead>
<td width=30% align=center >Welcome : <?php echo $this->_tpl_vars['username']; ?>
</td>
<td height="20" align="right">
<a href=mainpage.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&mode=<?php echo $this->_tpl_vars['mode']; ?>
>Main page</a>&nbsp;&nbsp;
</td>
<td height="20" width=20% align="center">
<a href=logout.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
>Logout</a>
</td>
</tr>
<tr>
<td height="20" colspan="3" class="formhead" align="center"><strong>ADD RECORDS FROM NEWSPAPER</strong></td>
</tr>
<tr>
<td height="20" colspan="3" class="formhead" align="center"><font color="#ff0000"><b><?php echo $this->_tpl_vars['MSG']; ?>
</b></font></td>
</tr>
</table>
<table width="560" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
<td class="label" width=20%><strong>Source</strong></td>
<td width=80% >
<select name="source" class=Textbox><?php echo $this->_tpl_vars['SOURCE']; ?>
</select>
</td>
</tr>
<tr class="fieldsnew">
<td class="label"><strong>Name </strong></td>
<td ><input type=text  name=Name value="<?php echo $this->_tpl_vars['NAME']; ?>
"></td>
</tr>
<tr class="fieldsnew">
<td class="label"><strong>Age</strong></td>
<td >
<select name="age" class="TextBox">
<option value="18" <?php if ($this->_tpl_vars['AGE'] == '18'): ?> selected <?php endif; ?>>18</option>
<option value="19" <?php if ($this->_tpl_vars['AGE'] == '19'): ?> selected <?php endif; ?>>19</option>
<option value="20" <?php if ($this->_tpl_vars['AGE'] == '20'): ?> selected <?php endif; ?>>20</option>
<option value="21" <?php if ($this->_tpl_vars['AGE'] == '21'): ?> selected <?php endif; ?>>21</option>
<option value="22" <?php if ($this->_tpl_vars['AGE'] == '22'): ?> selected <?php endif; ?>>22</option>
<option value="23" <?php if ($this->_tpl_vars['AGE'] == '23'): ?> selected <?php endif; ?>>23</option>
<option value="24" <?php if ($this->_tpl_vars['AGE'] == '24'): ?> selected <?php endif; ?>>24</option>
<option value="25" <?php if ($this->_tpl_vars['AGE'] == '25'): ?> selected <?php endif; ?>>25</option>
<option value="26" <?php if ($this->_tpl_vars['AGE'] == '26'): ?> selected <?php endif; ?>>26</option>
<option value="27" <?php if ($this->_tpl_vars['AGE'] == '27'): ?> selected <?php endif; ?>>27</option>
<option value="28" <?php if ($this->_tpl_vars['AGE'] == '28'): ?> selected <?php endif; ?>>28</option>
<option value="29" <?php if ($this->_tpl_vars['AGE'] == '29'): ?> selected <?php endif; ?>>29</option>
<option value="30" <?php if ($this->_tpl_vars['AGE'] == '30'): ?> selected <?php endif; ?>>30</option>
<option value="31" <?php if ($this->_tpl_vars['AGE'] == '31'): ?> selected <?php endif; ?>>31</option>
<option value="32" <?php if ($this->_tpl_vars['AGE'] == '32'): ?> selected <?php endif; ?>>32</option>
<option value="33" <?php if ($this->_tpl_vars['AGE'] == '33'): ?> selected <?php endif; ?>>33</option>
<option value="34" <?php if ($this->_tpl_vars['AGE'] == '34'): ?> selected <?php endif; ?>>34</option>
<option value="35" <?php if ($this->_tpl_vars['AGE'] == '35'): ?> selected <?php endif; ?>>35</option>
<option value="36" <?php if ($this->_tpl_vars['AGE'] == '36'): ?> selected <?php endif; ?>>36</option>
<option value="37" <?php if ($this->_tpl_vars['AGE'] == '37'): ?> selected <?php endif; ?>>37</option>
<option value="38" <?php if ($this->_tpl_vars['AGE'] == '38'): ?> selected <?php endif; ?>>38</option>
<option value="39" <?php if ($this->_tpl_vars['AGE'] == '39'): ?> selected <?php endif; ?>>39</option>
<option value="40" <?php if ($this->_tpl_vars['AGE'] == '40'): ?> selected <?php endif; ?>>40</option>
<option value="41" <?php if ($this->_tpl_vars['AGE'] == '41'): ?> selected <?php endif; ?>>41</option>
<option value="42" <?php if ($this->_tpl_vars['AGE'] == '42'): ?> selected <?php endif; ?>>42</option>
<option value="43" <?php if ($this->_tpl_vars['AGE'] == '43'): ?> selected <?php endif; ?>>43</option>
<option value="44" <?php if ($this->_tpl_vars['AGE'] == '44'): ?> selected <?php endif; ?>>44</option>
<option value="45" <?php if ($this->_tpl_vars['AGE'] == '45'): ?> selected <?php endif; ?>>45</option>
<option value="46" <?php if ($this->_tpl_vars['AGE'] == '46'): ?> selected <?php endif; ?>>46</option>
<option value="47" <?php if ($this->_tpl_vars['AGE'] == '47'): ?> selected <?php endif; ?>>47</option>
<option value="48" <?php if ($this->_tpl_vars['AGE'] == '48'): ?> selected <?php endif; ?>>48</option>
<option value="49" <?php if ($this->_tpl_vars['AGE'] == '49'): ?> selected <?php endif; ?>>49</option>
<option value="50" <?php if ($this->_tpl_vars['AGE'] == '50'): ?> selected <?php endif; ?>>50</option>
<option value="51" <?php if ($this->_tpl_vars['AGE'] == '51'): ?> selected <?php endif; ?>>51</option>
<option value="52" <?php if ($this->_tpl_vars['AGE'] == '52'): ?> selected <?php endif; ?>>52</option>
<option value="53" <?php if ($this->_tpl_vars['AGE'] == '53'): ?> selected <?php endif; ?>>53</option>
<option value="54" <?php if ($this->_tpl_vars['AGE'] == '54'): ?> selected <?php endif; ?>>54</option>
<option value="55" <?php if ($this->_tpl_vars['AGE'] == '55'): ?> selected <?php endif; ?>>55</option>
<option value="56" <?php if ($this->_tpl_vars['AGE'] == '56'): ?> selected <?php endif; ?>>56</option>
<option value="57" <?php if ($this->_tpl_vars['AGE'] == '57'): ?> selected <?php endif; ?>>57</option>
<option value="58" <?php if ($this->_tpl_vars['AGE'] == '58'): ?> selected <?php endif; ?>>58</option>
<option value="59" <?php if ($this->_tpl_vars['AGE'] == '59'): ?> selected <?php endif; ?>>59</option>
<option value="60" <?php if ($this->_tpl_vars['AGE'] == '60'): ?> selected <?php endif; ?>>60</option>
<option value="61" <?php if ($this->_tpl_vars['AGE'] == '61'): ?> selected <?php endif; ?>>61</option>
<option value="62" <?php if ($this->_tpl_vars['AGE'] == '62'): ?> selected <?php endif; ?>>62</option>
<option value="63" <?php if ($this->_tpl_vars['AGE'] == '63'): ?> selected <?php endif; ?>>63</option>
<option value="64" <?php if ($this->_tpl_vars['AGE'] == '64'): ?> selected <?php endif; ?>>64</option>
<option value="65" <?php if ($this->_tpl_vars['AGE'] == '65'): ?> selected <?php endif; ?>>65</option>
<option value="66" <?php if ($this->_tpl_vars['AGE'] == '66'): ?> selected <?php endif; ?>>66</option>
<option value="67" <?php if ($this->_tpl_vars['AGE'] == '67'): ?> selected <?php endif; ?>>67</option>
<option value="68" <?php if ($this->_tpl_vars['AGE'] == '68'): ?> selected <?php endif; ?>>68</option>
<option value="69" <?php if ($this->_tpl_vars['AGE'] == '69'): ?> selected <?php endif; ?>>69</option>
<option value="70" <?php if ($this->_tpl_vars['AGE'] == '70'): ?> selected <?php endif; ?>>70</option>
</select>	</td>
</tr>
<tr class="fieldsnew">
<td class="label"><strong>Gender </strong></td>
<td >
<select name=gender class=textbox>
<option value="Female" <?php if ($this->_tpl_vars['GENDER'] == 'Female'): ?> selected <?php endif; ?>>Female</option>
<option value="Male" <?php if ($this->_tpl_vars['GENDER'] == 'Male'): ?> selected <?php endif; ?>>Male</option></td>
</tr>
<tr class="fieldsnew">
<td class="label"><strong>Marital Status </strong></td>
<td >
<select name="maritalstatus" class="TextBox">
<option value="N"  <?php if ($this->_tpl_vars['MARITALSTATUS'] == 'N'): ?>  selected <?php endif; ?>>Never Married</option>
<option value="W"  <?php if ($this->_tpl_vars['MARITALSTATUS'] == 'W'): ?>        selected <?php endif; ?>>Widowed</option>
<option value="D"  <?php if ($this->_tpl_vars['MARITALSTATUS'] == 'D'): ?>       selected <?php endif; ?>>Divorced</option>
<option value="S"  <?php if ($this->_tpl_vars['MARITALSTATUS'] == 'S'): ?>      selected <?php endif; ?>>Separated</option>
<option value="O"  <?php if ($this->_tpl_vars['MARITALSTATUS'] == 'O'): ?>          selected <?php endif; ?>>Other</option>
</select>
</td>
</tr>
<tr class="fieldsnew">
<td height="25" class="label"><strong>Caste</strong></td>
<td>
<select name="caste" class="textbox">
<?php echo $this->_tpl_vars['CASTE']; ?>

</tr>
<tr class="fieldsnew">
<td height="25" class="label"><strong>Email</strong></td>
<td ><input type="text" name="email" value= "<?php echo $this->_tpl_vars['EMAIL']; ?>
" class="testbox"></td>
</tr>
<tr class="fieldsnew">
<td height="25" class="label"><strong>Mobile No.</strong></td>
<td ><input type="text" name="mobileno" value= "<?php echo $this->_tpl_vars['MOBILENO']; ?>
" class="testbox"></td>
</tr>
<tr class="fieldsnew">
<td height="25" class="label"><strong>Country</strong></td>
<td ><select name="country" class="TextBox">
<?php echo $this->_tpl_vars['COUNTRY']; ?>

</td>
</tr>
<tr class="fieldsnew">
<td height="25" class="label"><strong>City</strong></td>
<td ><select name="city" class="TextBox">
<?php echo $this->_tpl_vars['CITY']; ?>

</td>
</tr>
<tr class="fieldsnew">
<td height="40" class="label"><strong>Address</strong></td>
<td ><textarea name="address" class="testbox" cols=40 rows=3><?php echo $this->_tpl_vars['ADDRESS']; ?>
</textarea></td>
</tr>
<tr class="fieldsnew">
<td >&nbsp;</td>
<td align=center>
<input type=hidden name=cid  value=<?php echo $this->_tpl_vars['cid']; ?>
>
<input type=hidden name=name  value=<?php echo $this->_tpl_vars['name']; ?>
>
<input type=hidden name=mode  value=<?php echo $this->_tpl_vars['mode']; ?>
>
<input type=hidden name=modcontinue value=<?php echo $this->_tpl_vars['modcontinue']; ?>
>
<input type="submit" name="submit" value="Submit" class="testbox">
</td>
</tr>
</table>
</form>
</body>
</html>
