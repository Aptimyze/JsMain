<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>

	<HEAD>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html;CHARSET=iso-8859-1">
			<TITLE>TRANSACTION DETAILS</TITLE>
		</HEAD>

		<BODY bgcolor='#83a1C6'>
			<%@ page language="java" import="java.sql.Timestamp,com.opus.epg.sfa.java.*" session="false" isErrorPage="false" %>

			<%
			com.opus.epg.sfa.java.BillToAddress oBTA 	= new com.opus.epg.sfa.java.BillToAddress();
			com.opus.epg.sfa.java.ShipToAddress oSTA 	= new com.opus.epg.sfa.java.ShipToAddress();
			com.opus.epg.sfa.java.Merchant oMerchant 	= new com.opus.epg.sfa.java.Merchant();
			com.opus.epg.sfa.java.MPIData oMPI 		= new com.opus.epg.sfa.java.MPIData();
			com.opus.epg.sfa.java.CardInfo oCI 		= new com.opus.epg.sfa.java.CardInfo();

			com.opus.epg.sfa.java.PostLib oPostLib	= new com.opus.epg.sfa.java.PostLib();
	//com.opus.epg.sfa.java.PGReserveData oPGReserveData	= new com.opus.epg.sfa.java.PGReserveData();

			String orderid = request.getParameter("orderid");
			String amount = request.getParameter("amount");
			String merchantid = request.getParameter("merchantid");
			String currency_code=request.getParameter("currency_code");
			String return_url = request.getParameter("return");
			String num_currency_code = request.getParameter("num_currency_code");
			String purchase_amt = request.getParameter("purchase_amt");
			String display_amt = request.getParameter("display_amt");

			oMerchant.setMerchantDetails(
					merchantid //"00001598"
					,merchantid //"00001598"
					,merchantid //"00001598"
					,""
					,orderid //System.currentTimeMillis()+""
					,orderid
					, return_url //"http://www.jeevansathi.com/jspellhtml2k4/SFAResponse.jsp"
					, "POST"
					,currency_code //"INR"
					,""
					
					,"req.Preauthorization"
					, amount
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
