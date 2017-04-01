<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
    <head>
        <script language="javascript">
            function validate_matri_serv()
            {
                if(document.paymentcontact_crm.SERVICE.value=="M")
                {
                    var docF=document.paymentcontact_crm;
                    cboxes = docF['addon_services[]'];
                    for(var i=0;i<cboxes.length;i++)
                    {
                        cboxes[i].disabled=true;
                    }
                }
                else
                {
                    var docF=document.paymentcontact_crm;
                    cboxes = docF['addon_services[]'];
                    for(var i=0;i<cboxes.length;i++)
                    {
                        cboxes[i].disabled=false;
                    }
                }
            }
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>JeevanSathi</title>
        <link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
        <link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">
        </head>
            <table width="760" border="0" cellspacing="0" cellpadding="2" align="CENTER">
            <tr> 
            <td><img src="/profile/images/logo_1.gif" width="192" height="65"></td>
            </tr>
            <tr> 
            <td class=bigwhite bgcolor="6BB97B"> 
            <div align="center"><span class="class1"><a href="../../index.php">Home</a> | 
            <a href="../../mainmenu.php?checksum=~$CHECKSUM`">My JeevanSathi</a> | <a href="../../whyjeevansathi.php?checksum=~$CHECKSUM`">Why JeevanSathi</a> 
            | <a href="../../advance_search.php?FLAG=search&checksum=~$CHECKSUM`">Search JeevanSathi</a> | <a href="/membership/jspc">Memberships</a> 
            | <a href="#">Success Stories</a> | <a href="http://Jeevansathi.tolshop.com/" target=_blank">JeevanSathi Shop</a></span>
            </div>
            </td>
            </tr>
            </table>
            <br>
            <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                <table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
                    <tr width=100% border=1>
                        <td width="25%" class="formhead" height="23">
                            <font>
                                <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome :~$name`
                                </b>
                            </font>
                        </td>
                        <td width="25%" class="formhead" align="center">
                        </td>
                        <td width="25%" class="formhead" align="center">
                            <a href="../../crm/mainpage.php?cid=~$cid`">Click here to go to main page</a>
                        </td>
                        <td width="25%" class="formhead" align="center" height="23">
                            <a href="logout.php?cid=~$cid`">Logout</a>
                        </td>
                        <td width="3%" class="formhead" height="23">&nbsp;
                        </td>
                    </tr>
                </table>
                <br>
                ~if $MSG`
                    <table width=80% align="center" cellspacing=2 cellpadding=1 border=0>
                        <tr align="CENTER">
                            <td class="formhead" colspan="8" height="23">
                                <b>
                                    <font size="3" color="blue">~$MSG`
                                    </font>
                                </b>
                            </td>
                        </tr>
                    </table>
                ~else`
                <form method="post" action="~$SITE_URL`/operations.php/crmInterface/documentCollection" name= "document_collection_crm"> 
                    <input type=hidden name=cid value="~$cid`">
                    <input type=hidden name=username value="~$username`">
                    <input type=hidden name=name value="~$name`">
                    <input type=hidden name=pid value="~$pid`">
                    <table width=80% align="center" cellspacing=2 cellpadding=1 border=0>
                        <tr align="CENTER">
                            <td class="formhead" colspan="8" height="23">
                                <b>
                                    <font size="3" color="blue">Send document collection receipt
                                    </font>
                                </b>
                            </td>
                        </tr>
                        <tr align="CENTER">
                            <td class="label" colspan="8" height="23">
                                <font size="2" color="red">~$msg`
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td class=label align="left" width=30%>Username:
                            </td>
                            <td class=fieldsnew width=70%>
                                <class="label">~$username`
                            </td>
                        </tr>
                        ~if $check_document eq "Y"`
                        <tr>
                            <td class=label align="left" width=30%><font color="red">Tick at least one</font></td>
                            <td class=fieldsnew width=70%></td>
                        </tr>
                        ~/if`
                        <tr>
                            <td class=label align="left" width=30%>
                                <input type="checkbox" name="docCollect[]" value="Photographs of user, family and residence/office space">
                            </td>
                            <td class=fieldsnew width=70%>
                                Photographs of user, family and residence/office space
                            </td>
                        </tr>
                        <tr>
                            <td class=label align="left" width=30%>
                                <input type="checkbox" name="docCollect[]" value="Proof of Identity">
                            </td>
                            <td class=fieldsnew width=70%>
                                Proof of Identity
                            </td>
                        </tr>
                        <tr>
                            <td class=label align="left" width=30%>
                                <input type="checkbox" name="docCollect[]" value="Proof of Address">
                            </td>
                            <td class=fieldsnew width=70%>
                                Proof of Address
                            </td>
                        </tr>
                        <tr>
                            <td class=label align="left" width=30%>
                                <input type="checkbox" name="docCollect[]" value="Proof of Educational Qualifications">
                            </td>
                            <td class=fieldsnew width=70%>
                                Proof of Educational Qualifications
                            </td>
                        </tr>
                        <tr>
                            <td class=label align="left" width=30%>
                                <input type="checkbox" name="docCollect[]" value="Proof of Employment and Income Details">
                            </td>
                            <td class=fieldsnew width=70%>
                                Proof of Employment and Income Details
                            </td>
                        </tr>
                        <tr>
                            <td class=label align="left" width=30%>
                                <input type="checkbox" name="docCollect[]" value="Proof of Marital Status">
                            </td>
                            <td class=fieldsnew width=70%>
                                Proof of Marital Status, if applicable
                            </td>
                        </tr>
                        <tr>
                            <td class=label width="30%" height="2">&nbsp;</td>
                            <td colspan="2" height="2" class=fieldsnew> &nbsp;
                                <input type="submit" name="submit" value="submit">
                            </td>
                        </tr>
                    </table>
                </form>
                ~/if`
                <br><br>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr> 
                <td class=mediumblack height="2"> 
                <div align="center"><span class="class3"><a href="https://www.naukri.com"><br>
                Naukri.com</a> |<a href="~$SITE_URL`/profile/faqs.php?checksum=~$CHECKSUM`"> FAQs </a>| <a href="~$SITE_URL`/profile/feedback.php?checksum=~$CHECKSUM`">Feedback</a> | <a href="#">Disclaimer</a> | <a href="~$SITE_URL`/profile/site_map.php?checksum=~$CHECKSUM`">Site Map</a> | <a href="~$SITE_URL`/profile/contact.php?checksum=~$CHECKSUM`">Contact Us</a></span><br>
                <br>
                Copyright &copy; 2004, JeevanSathi Internet Services. 
                </div>
                </td>
                </tr>
                </table>
            </body>
</html>

