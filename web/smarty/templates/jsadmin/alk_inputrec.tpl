<html>
<head>
<title>JeevanSathi Matrimonials - Upload Photographs</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`

<body leftmargin="5" topmargin="5">
		<form action="insert_rec.php" method="POST">
		<table width="760" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
                      <td height="20" colspan="3" bgcolor="FDF2DF" class="mediumblack" align="center"><strong>Add Records</strong></td>
                    </tr>
		<tr>
                      <td height="20" colspan="3" class="mediumblack">&nbsp;</td>
                    </tr>
		<tr>
                      <td height="20" colspan="3" class="mediumblack" align="center"><font color="#ff0000"><b>~$MSG`</b></font></td>
                    </tr>
		<tr>
                          <td width="25%" class="mediumblack"><strong>Gender </strong></td>
                          <td width="25%">
				<select name=gender>
				<option value="" ~if $GENDER eq ""` selected ~/if`>Select</option>
				<option value="Female" ~if $GENDER eq "Female"` selected ~/if`>Female</option>
				<option value="Male" ~if $GENDER eq "Male"` selected ~/if`>Male</option></td>
                          <td width="50%"></td>
                        </tr>
                        <tr>
                          <td class="mediumblack"><strong>Age</strong></td>
                          <td>
			<select name="age" class="TextBox">
			<option value="" ~if $AGE eq ""` selected ~/if`>Select</option>
			<option value="18" ~if $AGE eq "18"` selected ~/if`>18</option>
			<option value="19" ~if $AGE eq "19"` selected ~/if`>19</option>
			<option value="20" ~if $AGE eq "20"` selected ~/if`>20</option>
			<option value="21" ~if $AGE eq "21"` selected ~/if`>21</option>
			<option value="22" ~if $AGE eq "22"` selected ~/if`>22</option>
			<option value="23" ~if $AGE eq "23"` selected ~/if`>23</option>
			<option value="24" ~if $AGE eq "24"` selected ~/if`>24</option>
			<option value="25" ~if $AGE eq "25"` selected ~/if`>25</option>
			<option value="26" ~if $AGE eq "26"` selected ~/if`>26</option>
			<option value="27" ~if $AGE eq "27"` selected ~/if`>27</option>
			<option value="28" ~if $AGE eq "28"` selected ~/if`>28</option>
			<option value="29" ~if $AGE eq "29"` selected ~/if`>29</option>
			<option value="30" ~if $AGE eq "30"` selected ~/if`>30</option>
			<option value="31" ~if $AGE eq "31"` selected ~/if`>31</option>
			<option value="32" ~if $AGE eq "32"` selected ~/if`>32</option>
			<option value="33" ~if $AGE eq "33"` selected ~/if`>33</option>
			<option value="34" ~if $AGE eq "34"` selected ~/if`>34</option>
			<option value="35" ~if $AGE eq "35"` selected ~/if`>35</option>
			<option value="36" ~if $AGE eq "36"` selected ~/if`>36</option>
			<option value="37" ~if $AGE eq "37"` selected ~/if`>37</option>
			<option value="38" ~if $AGE eq "38"` selected ~/if`>38</option>
			<option value="39" ~if $AGE eq "39"` selected ~/if`>39</option>
			<option value="40" ~if $AGE eq "40"` selected ~/if`>40</option>
			<option value="41" ~if $AGE eq "41"` selected ~/if`>41</option>
			<option value="42" ~if $AGE eq "42"` selected ~/if`>42</option>
			<option value="43" ~if $AGE eq "43"` selected ~/if`>43</option>
			<option value="44" ~if $AGE eq "44"` selected ~/if`>44</option>
			<option value="45" ~if $AGE eq "45"` selected ~/if`>45</option>
			<option value="46" ~if $AGE eq "46"` selected ~/if`>46</option>
			<option value="47" ~if $AGE eq "47"` selected ~/if`>47</option>
			<option value="48" ~if $AGE eq "48"` selected ~/if`>48</option>
			<option value="49" ~if $AGE eq "49"` selected ~/if`>49</option>
			<option value="50" ~if $AGE eq "50"` selected ~/if`>50</option>
			<option value="51" ~if $AGE eq "51"` selected ~/if`>51</option>
			<option value="52" ~if $AGE eq "52"` selected ~/if`>52</option>
			<option value="53" ~if $AGE eq "53"` selected ~/if`>53</option>
			<option value="54" ~if $AGE eq "54"` selected ~/if`>54</option>
			<option value="55" ~if $AGE eq "55"` selected ~/if`>55</option>
			<option value="56" ~if $AGE eq "56"` selected ~/if`>56</option>
			<option value="57" ~if $AGE eq "57"` selected ~/if`>57</option>
			<option value="58" ~if $AGE eq "58"` selected ~/if`>58</option>
			<option value="59" ~if $AGE eq "59"` selected ~/if`>59</option>
			<option value="60" ~if $AGE eq "60"` selected ~/if`>60</option>
			<option value="61" ~if $AGE eq "61"` selected ~/if`>61</option>
			<option value="62" ~if $AGE eq "62"` selected ~/if`>62</option>
			<option value="63" ~if $AGE eq "63"` selected ~/if`>63</option>
			<option value="64" ~if $AGE eq "64"` selected ~/if`>64</option>
			<option value="65" ~if $AGE eq "65"` selected ~/if`>65</option>
			<option value="66" ~if $AGE eq "66"` selected ~/if`>66</option>
			<option value="67" ~if $AGE eq "67"` selected ~/if`>67</option>
			<option value="68" ~if $AGE eq "68"` selected ~/if`>68</option>
			<option value="69" ~if $AGE eq "69"` selected ~/if`>69</option>
			<option value="70" ~if $AGE eq "70"` selected ~/if`>70</option>
			</select>	</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="25" class="mediumblack"><strong>Caste</strong></td>
                          <td><select name="caste" class="TextBox">
				<option value="" ~if $CASTE eq ""` selected ~/if`>Select</option>
				~section name=index loop=$ROWS`
				<option value="~$ROWS[index].VALUE`" ~if $CASTE eq "$ROWS[index].VALUE"` selected ~/if`>~$ROWS[index].LABEL`</option>
				~/section`
				</select> 
				</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="25" class="mediumblack"><strong>Email</strong></td>
                          <td><input type="text" name="email" value= "~$EMAIL`" class="testbox"></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" height="25" class="mediumblack">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="25" class="mediumblack">&nbsp;</td>
                          <td><input type="submit" name="submit" value="Submit" class="testbox"></td>
                          <td>&nbsp;</td>
                        </tr>
		</table>
		</form>
</body>
</html>
