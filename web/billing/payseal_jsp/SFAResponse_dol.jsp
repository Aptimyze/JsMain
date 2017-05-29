<!doctype html public "-//w3c/dtd HTML 4.0//en">
<html>
<head>
  <title>SFA - Response from ePG Web Server</title>
</head>
<body>
  <%@ page session="true" import="com.opus.epg.sfa.java.*,java.util.*,java.io.*,java.sql.*" info="SFA - JSP " contentType="text/html" %>
  <%
  System.out.println("In the SFAResponse.jsp"); 

  String astrResponseMethod= request.getMethod(); 
  String strMerchantId= "00001712";
  
  String astrDirectoryPath="/usr/local/apache2/payseal/";
  String astrClearData = null;	
  String respcd =null;
  String respmsg = null;
  String AuthIdCode = null;
  String RRN=null;
  String MerchantTxnId =null;
  String TxnRefNo =null;

  Hashtable oHashtable=null;
  if(astrResponseMethod.equals("POST")||astrResponseMethod.equals("post")){

  String astrResponseData= request.getParameter("DATA");
  astrClearData =validateEncryptedData(astrResponseData,astrDirectoryPath,strMerchantId);
  oHashtable=new Hashtable();

  StringTokenizer oStringTokenizer=new StringTokenizer(astrClearData,"&");
  while(oStringTokenizer.hasMoreElements()){
  String strData = (String)oStringTokenizer.nextElement();
  StringTokenizer oObj1=new StringTokenizer(strData,"=");
  String strKey=(String)oObj1.nextElement();
  String strValue=(String)oObj1.nextElement();
  oHashtable.put(strKey,strValue);
}

respcd=(String) oHashtable.get("RespCode");
respmsg = (String)oHashtable.get("Message");
AuthIdCode =(String) oHashtable.get("AuthIdCode");
RRN = (String)oHashtable.get("RRN");
MerchantTxnId = (String)oHashtable.get("TxnID");
TxnRefNo      = (String)oHashtable.get("ePGTxnID");

}  

%>   
<%
if(respcd.equals("0") || respcd.equals("1") || respcd.equals("2"))
  {
String sql_con_name="jdbc:mysql://payseal.jeevansathi.jsb9.net/billing";
String     sql_con_username="user";
String  sql_con_password="CLDLRTa9";
int autoIncKeyFromFunc = -1;
try
{
  ResultSet rs;
  Connection con;
  Class.forName("com.mysql.jdbc.Driver");

  String fromId="";
  con = DriverManager.getConnection(sql_con_name,sql_con_username,sql_con_password);
  Statement stmt;
  stmt = con.createStatement();
  String query2="insert into billing.PAYSEAL(ORG_STATUS,STATUS,ORDERID,MERCHANTID,TXNREFNO,AUTHCODE,RRN,ENTRY_DT) values('"+respcd+"','"+respcd+"','"+MerchantTxnId+"','"+strMerchantId+"','"+TxnRefNo+"','"+AuthIdCode+"','"+RRN+"',NOW())";
  stmt.executeUpdate(query2,Statement.RETURN_GENERATED_KEYS);

                        //int autoIncKeyFromFunc = -1;
                        rs = stmt.getGeneratedKeys();

                        if (rs.next())
                        {
                        autoIncKeyFromFunc = rs.getInt(1);
                      }
                      else
                      {
                                // throw an exception from here
                              }

                              rs.close();
                              con.close();
                            }
                            catch(Exception e)
                            {
                          }
                          %>
                          <body onLoad="document.form1.submit();">
                            <center>Redirecting!!! Please Wait ..........</center>
                            <form name="form1" action="http://www.jeevansathi.com/P/pg/orderOK_payseal.php" method="post">
                              <input type="hidden" name="id" value="<%=autoIncKeyFromFunc%>">
                              <noscript>
                                <br><br>
                                <center>We cannot redirect you  because <b>Javascript</b> is disabled in your browser.<br>Please click click on <b>Redirect</b> to reach online payment gateway.<br><br>
                                  <input type=submit name=redirect value=Redirect></center>
                                </noscript>
                              </form>
                            </body>
                            <%
                          }
                          %>


                          <%!

                          public String validateEncryptedData(String astrResponseData,String astrDirectoryPath,String strMerchantId) throws Exception {
                          EPGMerchantEncryptionLib    oEncryptionLib = new EPGMerchantEncryptionLib();
                          String astrClearData = null;
                          try {
                          FileInputStream oFileInputStream =  new FileInputStream(new File(astrDirectoryPath + strMerchantId+".key"));
                          BufferedReader oBuffRead = new BufferedReader(new InputStreamReader(oFileInputStream));
                          String strModulus = oBuffRead.readLine();
                          if(strModulus == null) {
                          throw new SFAApplicationException("Invalid credentials. Transaction cannot be processed");
                        }
                        strModulus = decryptMerchantKey(strModulus, strMerchantId);
                        if(strModulus == null) {
                        throw new SFAApplicationException("Invalid credentials. Transaction cannot be processed");
                      }
                      String strExponent = oBuffRead.readLine();
                      if(strExponent == null) {
                      throw new SFAApplicationException("Invalid credentials. Transaction cannot be processed");
                    }
                    strExponent = decryptMerchantKey(strExponent, strMerchantId);
                    if(strExponent == null) {
                    throw new SFAApplicationException("Invalid credentials. Transaction cannot be processed");
                  }
                  astrClearData =oEncryptionLib.decryptDataWithPrivateKeyContents(astrResponseData,strModulus,strExponent);

                }catch(Exception oEx){
                oEx.printStackTrace();
              }
              finally {
              return astrClearData;
            }
          }

          public String decryptMerchantKey(String astrData , String astrMerchantId) throws Exception {
          return(decryptData(astrData, (astrMerchantId+astrMerchantId).substring(0, 16)));
        }


        public String decryptData(String strData , String strKey)throws Exception {
        if(strData==null || strData==""){
        return null;
      }
      if(strKey==null || strKey==""){
      return null;
    }
    EPGCryptLib moEPGCryptLib = new EPGCryptLib();
    String strDecrypt=moEPGCryptLib.Decrypt(strKey, strData);
    return strDecrypt;
  }

  %>

</body>
</html>
