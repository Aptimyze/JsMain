<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>

<HEAD>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html;CHARSET=iso-8859-1">
<TITLE>TRANSACTION DETAILS</TITLE>
</HEAD>

<BODY bgcolor='#83a1C6'>
<!--%@ page import="java.sql.*"%-->
<%@ page language="java" import="java.sql.Timestamp,com.opus.epg.sfa.java.*,java.sql.*" session="false" isErrorPage="false" %>
<%! 
public String  process(String  toBeProcessed){
		int asciiOfDouble='"';
		int  check=0;
		StringBuffer strBuffer=new StringBuffer(); 
		char []chars=toBeProcessed.toCharArray();
		for(int i=0;i<chars.length;i++){
			//System.out.println("char st i="+i+" value is >>"+chars[i]);
			int asciiValue;
			int  newChar;
			if(i%2 == 0){
				 asciiValue=chars[i];
				//System.out.println("ascii value is character is>>>"+asciiValue);
				 newChar=asciiValue-17;
				if (newChar<0){
					newChar=newChar+127;
				}
			}else{
				asciiValue=chars[i];
				//System.out.println("ascii value is character is>>>"+asciiValue);
				newChar=asciiValue-7;
				if (newChar<0){
					newChar=newChar+127;
				}
			}
			
			//System.out.println("ascii value of>>>>"+asciiOfDouble);
			if(newChar ==asciiOfDouble ){
				check=1;
			}
			char toBeAppend=(char) newChar;
			//strBuffer.append(newChar);
			strBuffer.append(toBeAppend);
		}
		
		if(check==1){
			process(strBuffer.toString());
		}
		
		return strBuffer.toString();
	
		//return toBeProcessed;	
	}

%>
<%
	com.opus.epg.sfa.java.BillToAddress oBTA 	= new com.opus.epg.sfa.java.BillToAddress();
	com.opus.epg.sfa.java.ShipToAddress oSTA 	= new com.opus.epg.sfa.java.ShipToAddress();
	com.opus.epg.sfa.java.Merchant oMerchant 	= new com.opus.epg.sfa.java.Merchant();
	com.opus.epg.sfa.java.MPIData oMPI 		= new com.opus.epg.sfa.java.MPIData();
	com.opus.epg.sfa.java.CardInfo oCI 		= new com.opus.epg.sfa.java.CardInfo();

	com.opus.epg.sfa.java.PostLib oPostLib	= new com.opus.epg.sfa.java.PostLib();
	//com.opus.epg.sfa.java.PGReserveData oPGReserveData	= new com.opus.epg.sfa.java.PGReserveData();

	String orderid =process((String)request.getParameter("orderid"));
	//out.print("orderid is >>>>"+orderid);
	
	String amount="0";
        String currency_code="";
	String mod_type;
	String pm_mod=request.getParameter("pm_mod");
	if(pm_mod.equals("db_card")){
		mod_type="req.Sale";
	}
	else{
		mod_type="req.Sale";
	}

//	String amount = request.getParameter("amount");
	String merchantid =process((String)request.getParameter("merchantid"));
//	String currency_code=request.getParameter("currency_code");
	String return_url = request.getParameter("return");
	String num_currency_code = process((String)request.getParameter("num_currency_code"));
	String purchase_amt = process((String)request.getParameter("purchase_amt"));
	String display_amt = process((String)request.getParameter("display_amt"));

		
                String [] two=orderid.split("-");
	//	out.println("to be taken is >>>>"+two[0]);
		String sql_con_name="jdbc:mysql://payseal.jeevansathi.jsb9.net/billing";
		String sql_con_username="user";
		String sql_con_password="CLDLRTa9";
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
                       // String query2="SELECT * FROM billing.ORDERS WHERE ORDERID ='"+two[0]+"'";
			String query2="SELECT AMOUNT,CURTYPE FROM billing.ORDERS WHERE ID ='"+two[1]+"'";
                        rs=stmt.executeQuery(query2);

                        if (rs.next())
                        {
				amount=Double.toString(rs.getDouble("AMOUNT"));
				//out.println("amount is >>>>"+amount);
				currency_code=(String)rs.getString("CURTYPE");
				//out.println("currency_code is>>>"+currency_code);
				if(currency_code.equals("DOL"))
				{
					currency_code ="USD";
				}
				else
				{
					currency_code ="INR";
				}
				//out.println("currency_code is>>>"+currency_code);
                        }
                        else
                         {
                         }

                        rs.close();
                        con.close();
                }
                catch(Exception e)
                {
			e.printStackTrace();
                }

		
	
	oMerchant.setMerchantDetails(
					merchantid //"00001598"
					,merchantid //"00001598"
					,merchantid //"00001598"
					,""
					,orderid //System.currentTimeMillis()+""
					,orderid
					, return_url //"http://www.jeevansathi.com/jspellhtml2k4/SFAResponse.jsp"
					, "POST"
					,currency_code
					,""
					
					,mod_type
					,amount
					,"GMT+05:30"
					, "Ext1"
					, "true"
					, "Ext3"
					, "Ext4"
					, "Ext5"
					);

	oBTA.setAddressDetails(
			""
			,""
			,""
			,""
			,""
			,""
			,""
			,""
			,""
			, ""
			);

	oSTA.setAddressDetails(
			""
			,""
			,""
			,""
			,""
			,""
			,""
			,""
			);

	oMPI.setMPIRequestDetails(purchase_amt
				,display_amt
				,num_currency_code
				,""
				,""
				,""
				,""
				,""
				,""
				,""
				,""
				,""
				);

	//System.out.println("before PGResponse");
	
	//System.out.println("before calling postssl ");
	
		long lstart = System.currentTimeMillis();	
		PGResponse oPGResponse = oPostLib.postSSL(oBTA,oSTA,oMerchant,oMPI,response);
		long lend = System.currentTimeMillis();

	//	System.out.println("Time taken: "+(lend-lstart) + "<br>");

	//	System.out.println("after PGResponse");

		if(oPGResponse.getRedirectionUrl() != null) {
	//		System.out.println("inside getRedirectionUrl not null");
	//		out.println("inside getRedirectionUrl not null");
			String strRedirectionURL = oPGResponse.getRedirectionUrl();
			response.sendRedirect(strRedirectionURL);
		}
		else {
			System.out.println("inside else part i/e  null");
			System.out.println("Error encountered. Error Code : " +oPGResponse.getRespCode() + " . Message " +  oPGResponse.getRespMessage());
		}
%>
</BODY>
</HTML>
