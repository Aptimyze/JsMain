<?php
class misFields
{
	static $ftaRegular=array("EOI_DATE","PHOTO_DATE","PAID_DATE","CALLED_DATE");
}
class csvFields
{
	static $csvName		=array("ftaRegular"=>"FTA_Revamp_2_Calling_Data","PHONE_DIALER"=>"PHONE_DIALER_DATA","salesRegularNoida"=>"bulk_csv_crm_data_noida","salesRegularDelhi"=>"bulk_csv_crm_data_delhi","salesRegularMumbai"=>"bulk_csv_crm_data_mumbai","salesRegularPune"=>"bulk_csv_crm_data_pune","salesRegularNri"=>"bulk_csv_crm_data_nri1","sugarcrmLtf"=>"bulk_csv_crm_data","MOBILE_APP_REGISTRATIONS"=>"MOBILE_APP_REGISTRATIONS","failedPaymentInDialer"=>"failedPaymentInDialer","upsellProcessInDialer"=>"upsellDataInDialer","DAILY_GHARPAY"=>"gharPayCsvData","QA_ONLINE"=>"qaOnlineCsvData","renewalProcessInDialer"=>"renewalDataInDialer","VDImpactReport"=>"VDImpactReport");
	static $csvLeadId	=array("ftaRegular"=>"FTA_Revamp_2");

	static $csvPhoneFieldsArr      =array("MOBILE1","MOBILE2","LANDLINE","MOBILE1_COPY","MOBILE2_COPY","LANDLINE_COPY","PHONE_NO1","PHONE_NO2","PHONE_NO3","PHONE_NO4");
	static $csvDateFieldsArr       =array("ENTRY_DATE","LAST_LOGIN_DATE","DOB");
	static $csvRemoveFieldsArr     =array("ID","CSV_ENTRY_DATE","ENTRY_DT","IS_DNC","CSV_TYPE","SOURCE","TYPE");

	static $csvMotherTongueArr     =array("1"=>"MarathiAndKonkani", "2"=>"Tamil", "3"=>"Telugu", "4"=>"Malayalam", "5"=>"Kannad", "6"=>"HindiAndOthers");
	static $csvDNCvalues	       =array("1"=>"Y", "0"=>"N", "DNC"=>"Y", "NDNC"=>"N");	

        static $csvFileHeader          =array("DAILY_GHARPAY"=>"Prefix|First Name|Last Name|Contact Number|Address|Landmark|Email|Pincode|City|Order ID|Order Amount|Delivery Date|Invoice URL|Product ID|Product Description|Quantity|Unit Price|Comments|","QA_ONLINE"=>"PROFILEID|SCORE|EMAIL|MOBILE|LANDLINE|","VDImpactReport"=>"Discount_Offered_Up_To|Number_of_People_Offered_Discount|Number_of_People_Paid|Average_Ticket_Size|");
}
class crmParams
{
	// Sales Regular Process
	public static $renewalSouthCommunity		=array(12,20,34);
	public static $southIndianCommunity    		=array(3,25,16,31,17,35,2,18,1,37);
        public static $salesRegularConsiderCommunity	=array(4,5,9,20,21,22,23,24,12,29,32,34,19);
	public static $salesRegularIgnoreCommunity 	=array(3,16,17,31,4,5,9,20,21,22,23,24,12,29,32,34);
	public static $salesRegularCommunityNewOutbound	=array(27,10,33);
	public static $salesRegularDelhiCity 		=array('DE00','HA03','HA02','UP12','UP25','UP47');
	public static $salesRegularMumbaiCity 		=array('MH04','MH12','MH13','MH14');
	public static $salesRegularPuneCity		=array('MH01','MH02','MH03','MH05','MH06','MH07','MH08','MH09','MH10','MH11','MH15','MH16','MH17','MH18','MH19','MH20','MH21','MH22','MH23','MH24','MH25','MH26','MH27');
	public static $salesRegularNriCountry 		=array(1,2,3,60,138,4,139,140,5,6,8,141,9,10,142,143,12,144,145,13,15,16,146,17,19,148,149,21,22,23,150,151,153,24,26,154,27,28,156,29,30,31,33,157,158,34,35,36,160,161,162,163,164,37,39,40,165,167,69,42,43,44,45,168,169,170,172,173,174,175,46,47,176,49,50,53,54,55,177,56,57,58,215,61,62,63,64,65,66,180,181,67,182,68,216,184,185,186,71,72,73,187,74,76,188,189,190,191,77,192,79,81,193,83,195,84,86,87,88,89,90,91,93,94,218,96,197,97,198,199,200,201,202,99,100,204,101,102,104,219,106,107,109,111,206,207,112,113,114,117,118,208,209,120,121,122,210,123,124,125,126,128,127,129,130,132,133,134,135);	
	public static $premiumIncome			=array(13,14,17,18,19,20,21,22,23,24,25,26,27);
	public static $salesRegularCampaign		=array("1"=>"noida","2"=>"mumbai","3"=>"pune","4"=>"nri","5"=>"delhi","6"=>"noidaAuto");
	public static $salescampaignNames		=array('noida'=>'JS_NCRNEW','mumbai'=>'JS_MAHNEW','noidaAuto'=>'JS_NCRNEW_Auto');
	public static $inDialerCampaign			=array("noida","mumbai","pune","delhi");
	public static $inDialerCampaignNewArr           =array("noidaAuto");
	public static $nonAutoCampaign			=array('mumbai','pune','nri');
	public static $salesRegularValueRange		=array('SCORE1'=>'70','SCORE2'=>'90','SCORE3'=>'100','DISCOUNT1'=>'50','DISCOUNT2'=>'70');
        public static $salesRegularCampaignTables	=array("noida"=>"incentive_SALES_CSV_DATA_NOIDA","mumbai"=>"incentive_SALES_CSV_DATA_MUMBAI","pune"=>"incentive_SALES_CSV_DATA_PUNE","nri"=>"incentive_SALES_CSV_DATA_NRI","delhi"=>"incentive_SALES_CSV_DATA_DELHI","noidaAuto"=>"incentive_SALES_CSV_DATA_NOIDA_NEW");

	// Other Sales Campaign Tables for Processes
	public static $salesCampaignTables		=array("failedPaymentInDialer"=>"incentive_SALES_CSV_DATA_FAILED_PAYMENT","upsellProcessInDialer"=>"incentive_SALES_CSV_DATA_UPSELL","renewalProcessInDialer"=>"incentive_SALES_CSV_DATA_RENEWAL","paidCampaignProcess"=>"incentive_SALES_CSV_DATA_PAID_CAMPAIGN","rcbCampaignInDialer"=>"incentive_SALES_CSV_DATA_RCB");
	public static $salesCampaign			=array("failedPaymentInDialer"=>"fp","upsellProcessInDialer"=>"upsell","renewalProcessInDialer"=>"renewal","paidCampaignProcess"=>"paid","rcbCampaignInDialer"=>"rcb","OB_RENEWAL_MAH"=>"renewalMah");
	public static $crmCsvTables                     =array("DAILY_GHARPAY"=>"incentive_GHARPAY_CSV_DATA","QA_ONLINE"=>"incentive_QA_ONLINE_CSV_DATA");
	public static $campaignNames			=array('renewal'=>'JS_RENEWAL','renewalMah'=>'OB_RENEWAL_MAH');

	// Field Sales Process	
	public static $fieldSalesIgnoreCommunity	=array(1,3,16,17,31);
	public static $fieldSalesPincodeMappedCity      =array("DE00","MH04","MH08");
	//public static $fieldSalesBlankPincodeMapping	=array("DE00"=>"110001","MH04"=>"400049","MH08"=>"410510");

	// Sales Registration Process	
	public static $salesRegIgnoreCommunity		="1,3,16,17,31";
	public static $salesRegConsiderCity		=array("UP12","UP25","UP47","UP21","HA03","HA02","UK05","UP06","UP01","DE","DE00","RA07");
	
	public static $monthOrder      			=array("Jan"=>1, "Feb"=>2, "Mar"=>3, "Apr"=>4, "May"=>5, "Jun"=>6, "Jul"=>7, "Aug"=>8, "Sep"=>9, "Oct"=>10, "Nov"=>11, "Dec"=>12);
	public static $monthDays      			=array("Jan"=>31, "Feb"=>28, "Mar"=>31, "Apr"=>30, "May"=>31, "Jun"=>30, "Jul"=>31, "Aug"=>31, "Sep"=>30, "Oct"=>31, "Nov"=>30, "Dec"=>31);
	
	// Sugarcrm LTF Process	
        public static $mother_tongue 			=array(array(20,34),array(31),array(3),array(17),array(16),array(10,19,33,27,7,28,13,14,15,30,12,9,2,18,6,25,5,4,21,22,23,24,29,32));
        
        //Sales process wise tracking
        public static $processNames = array("RCB_TELE"=>"Request Call Back Telesales","INBOUND_TELE" => "Inbound Telesales", "CENTER_SALES" => "Center Sales", "FP_TELE" => "Failed Payment Telesales", "CENTRAL_RENEW_TELE" => "Central Renewal Telesales", "FIELD_SALES" => "Field Sales", "FRANCHISEE_SALES" => "Franchisee Sales", "OUTBOUND_TELE" => "Outbound Telesales", "UNASSISTED_SALES" => "Unassisted Sales");

    // Tracking Flag for various processes
    
    public static $processFlag = array("INBOUND_TELE" => "0", "CENTER_SALES" => "1", "FP_TELE" => "2", "CENTRAL_RENEW_TELE" => "3", "FIELD_SALES" => "4", "FRANCHISEE_SALES" => "5", "OUTBOUND_TELE" => "6", "UNASSISTED_SALES" => "7");
    public static $processFlagReverse = array(0=>"INBOUND_TELE", 1=>"CENTER_SALES", 2=>"FP_TELE", 3=>"CENTRAL_RENEW_TELE", 4=>"FIELD_SALES", 5=>"FRANCHISEE_SALES", 6=>"OUTBOUND_TELE", 7=>"UNASSISTED_SALES");
}
?>
