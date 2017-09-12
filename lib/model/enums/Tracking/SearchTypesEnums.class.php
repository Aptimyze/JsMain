<?php
/**
 * @brief This class contains the search types related to search
 * @author Lavesh Rawat
 * @created 2012-08-23
 */
class SearchTypesEnums
{
	const Quick = 'Q' ;
	const Clusters = 'J';
	const Dpp = '1';
	const ReverseDpp = '2';
	const Advance = 'A'; 
	const FeatureProfile = 'W';
	const IOSFeatureProfile = 'FI';
	const JSMSFeatureProfile = 'FM';
	const AAFeatureProfile = 'FA';
	const Online = 'O';
	const SaveSearch = '16';
	const WapSaveSearch = 'W16';
	const iOSSaveSearch = 'I16';
	const AppSaveSearch = 'A16';
	const MobileSearchBand = 'WQ';
        const iOS = 'IQ';
	const WapDpp = 'W1';
        const iOSDpp = 'I1';
	const wapRevDPP = 'WR';  
        const iOSRevDPP = 'IR';
        const AppRevDPP = 'AR';
	const App = 'AQ';
	const AppDpp = 'A1';
	const AppClusters = 'AJ';
	const IosClusters = 'IJ';
	const MyJsMatchAlertSection = '15';
	const AppMyJsMatchAlertSection = 'A15';
	const MyJsVisitorAlertSection = '11';
   	const AppMyJsVisitorAlertSection = 'A11';
	const MyJsHoroscopeUploadSection = '12';
    const MyJsPhotoUploadSection = '13';
	const NewMatchesMailer = "NP";
	const VisitorAlertMailer = "M15";
	const NewMatchesEmail = "F";
	const MatchAlertMailer = "B";
	const TwoWayMatch = 'T';
	const WapTwoWayMatch = 'WT';
	const iOSTwoWayMatch = 'IT';
	const AppTwoWayMatch = 'AT';
        const MobileEOIConfirmationPage = 'WC';
	const JustJoinedMatchesDesktop = 'DU';
	const contactViewAttempt = 'VCD';
	const contactViewAttemptAndroid = 'VCDA';
	const contactViewAttemptIOS = 'VCDI';
	const contactViewAttemptJSMS = 'VCDM';
        const JustJoinedMatches = 'WU';
	const AppJustJoinedMatches = 'AU';
        const iOSJustJoinedMatches = 'IU';
	const MatchAlerts = '25';
	const KundliAlerts = '32';
	const KundliAlertsAndroid = 'KA';
	const KundliAlertsIOS = 'KI';
	const KundliAlertsJSMS = 'KM';
	const WapMatchAlertsCC = 'WM';
        const iOSMatchAlertsCC = 'IM';
        const AppMatchAlertsCC = 'AM';
        const PHOTO_REQUEST_RECEIVED_JSMS = "20";
        //const PHOTO_REQUEST_SENT_JSMS = "20";   
        //const HOROSCOPE_REQUEST_RECEIVED_JSMS = "20";  
        //const HOROSCOPE_REQUEST_SENT_JSMS = "20";

        const SHORTLIST_JSMS = "WS";
        const MATCHING_VISITORS_JSMS = "MWV";
        const VISITORS_JSMS = "WV";
        const VISITORS_MYJS_JSMS = "WMV";
        const MATCHALERT_MYJS_JSMS = "WMM";
         const PHONEBOOK_JSMS = "PCV";
		const FILTERED_INTEREST_MS = "FIMS";
		const CONTACT_VIEWERS_JSMS = "CVS";
		const CONTACTS_VIEWED_ANDROID = "ACV";
		const CONTACT_VIEWERS_ANDROID = "ACVS";
		const JUST_JOINED_MYJS_JSMS="JJM"; 
		//const DPP_MYJS_JSMS="DPM";              not in use now.
                
        const MATCHING_VISITORS_IOS = "MIV";
        const VISITORS_IOS = "IV";
        const SHORTLIST_IOS = "IS";
        const PHOTO_REQUEST_RECEIVED_IOS = "37";
        //const PHOTO_REQUEST_SENT_IOS = "30";			
       //const HOROSCOPE_REQUEST_RECEIVED_IOS = "30";	
        //const HOROSCOPE_REQUEST_SENT_IOS = "30";		
		
        const PHONEBOOK_IOS = "PCI";
	const CONTACT_VIEWERS_IOS = "CVI";
        const VISITORS_MYJS_IOS = "IMV";
        const MATCHALERT_MYJS_IOS = "IMM";
        const MYJS_VISITOR_PC = "11";
        const MYJS_PHOTOREQUEST_PC = "MPP";				
        const MYJS_SHORTLIST_PC = "MSP";				
    //const MYJS_HOROSCOPEREQUEST_PC = "MPP";			
        //const INTRO_CALLS_PC = "28";					
        //const INTRO_CALLS_COMPLETE_PC = "28";			
        const VIEW_SIMILAR_ECP_PC = 'CO';
        const VIEW_SIMILAR_ACCEPT_PC = 'V';
        const PHOTO_REQUEST_SENT_CC_PC = "21";
        const PHOTO_REQUEST_RECEIVED_CC_PC = "20";
        const HOROSCOPE_REQUEST_SENT_CC_PC = "23";
        const HOROSCOPE_REQUEST_RECEIVED_CC_PC = "22";
       // const VISITOR_CC_PC = "WV";
        //const SHORTLIST_CC_PC = "WS";
        const PHONEBOOK_CC_PC = "M26";
        const CONTACTS_VIEWED_BY_CC_PC="M27";
        const ViewSimilarDesktop = "30";
        const MATCHING_VISITORS_JSPC="MV5";
        const MATCHING_VISITORS_ANDROID="MAV";
        const VISITORS_JSPC="5";
        const SHORTLIST_JSPC="7";
        const VIEW_SIMILAR_ANDROID = "ACO";
        const VIEW_SIMILAR_IOS = "ICO";
        const VIEW_SIMILAR_IOS_ON_PD = "ICP";
        const VERIFIED_MATCHES_JSPC="VM";
	const CONTACT_DETAIL_SMS='CDS';
        const VERIFIED_MATCHES_IOS="IVM";
        const VERIFIED_MATCHES_ANDROID="AVM";
        const VERIFIED_MATCHES_JSMS="MVM";
        
        // new match alert mailer Class
	const MatchAlertMailer1 = "BN1";
	const MatchAlertMailer2 = "BN2";
	const MatchAlertMailer3 = "BN3";
        const MatchAlertMailer4 = "BN4";
        const MatchAlertMailer5 = "BN5";
        const MatchAlertMailer7 = "BN7";

        const contactViewerMailer="CVM";

	const PHOTO_REQUEST_ANDROID ='PR';
	const PHOTO_UPLOAD_ANDROID ='PU';
        const PHOTO_REQUEST_IOS ='PRI';
        const PHOTO_UPLOAD_IOS ='PUI';
        const MATCHALERT_ANDROID ='MAA';
	const MATCHALERT_IOS ='MAI';
        const JUST_JOIN_ANDROID ='JJA';
	const JUST_JOIN_IOS ='JJI';

        const APPLY_ONLY_CLUSTER = 'XX';
        const SaveSearchMailer = 'SSM';
        const PC_CHAT_NEW = 'PCN';
        const ANDROID_CHAT_NEW = 'ACN';
        const kundliAlertMailer = "KAM";
        const EXCLUSIVE_SERVICE2_MAILER_STYPE = 'ES2M';
	const AndroidMatchOfDay = "AMD";
	const IOSMatchOfDay = "IMD";
	const MatchOfDay = "MD";
        const LAST_SEARCH_RESULTS = "LSR";
        const JSPC_LAST_SEARCH = "LSPC";
        const LAST_SEARCH_DESIRED_PARTNER_MATCHES = "DPMD";
        const CANCELLED_LISTING_PC = CLPC;
	const CANCELLED_LISTING_MS = CLMS;
	const CANCELLED_LISTING_IOS = CLIOS;
	const CANCELLED_LISTING_APP = CLAA;
    const MATCH_OF_THE_DAY_MYJS_IOS = "IMOD";
    const ANDROID_MATCHOFDAY = "AMOD";
    const PAID_MEMBERS_MAILER = "PMM";
    const PAID_MEMBERS = "PM";
    const ADD_PHOTO_MAILER = "APM";
    const EOI_SIMILAR_PROFILES_MAIL_ACCEPTED = "SPMA";
    const EOI_SIMILAR_PROFILES_MAIL_OTHERS = "SPMO";
    const RECENT_ACTIVITY_IOS = "RAI";
    const RECENT_ACTIVITY_ANDROID = "RAA";
}
?>
