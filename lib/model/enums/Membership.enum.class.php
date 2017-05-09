<?php
class memUserType
{
    const LOGGED_OUT = "1";
    const FREE = "2";
    const EXPIRED_BEYOND_LIMIT = "3";
    const EXPIRED_WITHIN_LIMIT = "4";
    const PAID_BEYOND_RENEW = "5";
    const PAID_WITHIN_RENEW = "6";
    const ONLY_VAS = "7";
    const UPGRADE_ELIGIBLE = "8";
}
class userDiscounts
{
    const RENEWAL = "15";
    const FESTIVE = "10";
}
class userCurrency
{
    const RUPEE = "1";
    const DOLLAR = "2";
}
class billingVariables
{
    const TAX_RATE = "15";
    const SWACHH_TAX_RATE = "0.5";
    const KRISHI_KALYAN_TAX_RATE = 0.5;
    const NET_OFF_TAX_RATE = "0.130435";
    //const NET_OFF_TAX_RATE = "0.12664";
    const SERVICE_TAX_CONTENT = "(Inclusive of Swachh Bharat Cess and Krishi Kalyan Cess)";
}

class memDiscountTypes 
{
    public static $discountArr = array('1' => 'Renewal Discount',
        2 => 'General Discount',
        3 => 'Complementary Discount',
        4 => 'Referral Discount',
        5 => 'Variable Discount',
        6 => 'Festive Discount',
        7 => 'Renewal + Festive Discount',
        8 => 'Voucher Code Discount',
        9 => 'Variable + Festive Discount',
        10 => 'Backend Discount Link',
        11 => 'Cash Discount',
        12 => 'No Discount',
        14 => 'Coupon Code Discount',
        15 => 'Main Membership Upgrade Discount'
    );
}

class VariableParams
{
	public static $membershipMailerArr =array(
		'1785'=> 'REGISTRATION_BASED',
		'1784'=> 'REGISTRATION_BASED',
		'1786'=> 'REGISTRATION_BASED',
		'1804' => 'VD',
		'1797' => 'JS_EXCLUSIVE_FEEDBACK',
		'1795' => 'MEMBERSHIP_PROMOTIONAL',
		'1835' => 'NEW_MEMBERSHIP_PAYMENT',
		'1836' => 'MEM_EXPIRY_CONTACTS_VIEWED'
	);
        
    //config for membership upgrade
    public static $memUpgradeConfig = array(
                                        "deactivationCurlTimeout"=>120000,
                                        "allowedUpgradeMembershipAllowed"=>array("MAIN"),
                                        "mainMemUpgradeLimit"=>7,
                                        "upgradeMainMemAdditionalPercent"=>0.05,
                                        "channelsAllowed"=>array("desktop","mobile_website","Android_app","JSAA_mobile_website"),
                                        "excludeMainMembershipUpgrade"=>array("X","ESP")
                                        );

    public static $lightningDealOfferConfig = array(
                                        "lastLoggedInOffset" => 30,
                                        "lastLightningDiscountViewedOffset" => 30,
                                        "pool2FilterPercent" => 10,
                                        );
    
	public static $discountLimitText =array("flatCap"=>"Flat","flatSmall"=>"flat","uptoCap"=>"Upto","uptoSmall"=>"upto");
    public static $mainMembershipsArr = array(
        "P",
        "C",
        "NCP",
        "D",
        "ESP",
        "X"
    );
    public static $mainMembershipNamesArr = array(
        'P' => 'eRishta',
        'C' => 'eValue',
        'ESP' => 'eSathi',
        'NCP' => 'eAdvantage',
        'D' => 'e-Classifieds',
        'X' => 'JS Exclusive',
        'PL' => 'eRishta',
        'CL' => 'eValue',
        'ESPL' => 'eSathi',
        'NCPL' => 'eAdvantage'
    );
    
    //public static $mainMembershipsArr=array("P","C","ESP","X");
    public static $eSathiAddOns = array(
        "A",
        "R",
        "T",
        "I"
    );
    public static $eValuePlusAddOns = array(
        "R",
        "T"
    );
    
    public static $indianCurrency = "Indian Rupees";
    public static $otherCurrency = "Dollars";
    public static $defVASPopularity = array(
        "A" => "6",
        "T" => "9",
        "M" => "5",
        "B" => "10",
        "R" => "8",
        "I" => "4"
    );
    public static $mainMostpopularSrvc = "P,C,NCP,ESP";
    public static $matriProfilePriceRS = "550";
    public static $matriProfilePriceDOL = "12.99";
    public static $paymentOptions = array(
        "card" => "Credit Card",
        "card2" => "Credit Card",
        "card3" => "Cash Card",
        "card9" => "Debit Card",
        "netBanking" => "Net Banking",
        "paypal" => "PayPal"
    );
    public static $membershipKeyArray = array(
        "desktop_MAIN_MEMBERSHIP_RS",
        "iOS_app_MAIN_MEMBERSHIP_RS",
        "mobile_website_MAIN_MEMBERSHIP_RS",
        "JSAA_mobile_website_MAIN_MEMBERSHIP_RS",
        "Android_app_MAIN_MEMBERSHIP_RS",
        "old_mobile_website_MAIN_MEMBERSHIP_RS",
        "desktop_MAIN_MEMBERSHIP_DOL",
        "iOS_app_MAIN_MEMBERSHIP_DOL",
        "mobile_website_MAIN_MEMBERSHIP_DOL",
        "JSAA_mobile_website_MAIN_MEMBERSHIP_DOL",
        "Android_app_MAIN_MEMBERSHIP_DOL",
        "old_mobile_website_MAIN_MEMBERSHIP_DOL",
        "desktop_ADDON_MEMBERSHIP_RS",
		"iOS_app_ADDON_MEMBERSHIP_RS",
		"mobile_website_ADDON_MEMBERSHIP_RS",
		"JSAA_mobile_website_ADDON_MEMBERSHIP_RS",
		"Android_app_ADDON_MEMBERSHIP_RS",
		"old_mobile_website_ADDON_MEMBERSHIP_RS",
		"desktop_ADDON_MEMBERSHIP_DOL",
		"iOS_app_ADDON_MEMBERSHIP_DOL",
		"mobile_website_ADDON_MEMBERSHIP_DOL",
		"JSAA_mobile_website_ADDON_MEMBERSHIP_DOL",
		"Android_app_ADDON_MEMBERSHIP_DOL",
		"old_mobile_website_ADDON_MEMBERSHIP_DOL",
        "desktop_MAIN_HIDDEN_MEMBERSHIP_RS",
        "iOS_app_MAIN_HIDDEN_MEMBERSHIP_RS",
        "mobile_website_MAIN_HIDDEN_MEMBERSHIP_RS",
        "JSAA_mobile_website_MAIN_HIDDEN_MEMBERSHIP_RS",
        "Android_app_MAIN_HIDDEN_MEMBERSHIP_RS",
        "old_mobile_website_MAIN_HIDDEN_MEMBERSHIP_RS",
        "desktop_MAIN_HIDDEN_MEMBERSHIP_DOL",
        "iOS_app_MAIN_HIDDEN_MEMBERSHIP_DOL",
        "mobile_website_MAIN_HIDDEN_MEMBERSHIP_DOL",
        "JSAA_mobile_website_MAIN_HIDDEN_MEMBERSHIP_DOL",
        "Android_app_MAIN_HIDDEN_MEMBERSHIP_DOL",
        "old_mobile_website_MAIN_HIDDEN_MEMBERSHIP_DOL"
    );
    public static $vasOrder = array(
        'T',
        'R',
        'I',
        'A',
        'B',
        'M',
        'J'
    );

    public static $serviceFeatues = array(
        "Send/Receive Interests",
        "Instantly see Phone/Email",
        "Initiate Messages and Chat",
        "Priority Customer service",
        "Show your Phone/Email to other members",
        "Four additional services"
    );
    public static $serviceMessages = array(
        "P" => array(
            0,
            1,
            2,
            3
        ) ,
        "C" => array(
            0,
            1,
            2,
            3,
            4
        ) ,
        "ESP" => array(
            0,
            1,
            2,
            3,
            4,
            5
        )
    );
    public static $memTabContent = array(
        "1" => "Membership Plans",
        "2" => "Additional Services",
        "3" => "Payment Options"
    );
    public static $vasNamesAndDescription = array(
        "T" => array(
            "name" => "Response <br>Booster",
            "description" => "Busy? Don't worry! Allow Jeevansathi.com to contact people who match your criteria and get 8 times more response.",
            "visibility" => 0,
            "vas_id" => 1
        ) ,
        "A" => array(
            "name" => "Astro <br>Compatibility",
            "description" => "Horoscope match a must? Get detailed kundli matching reports with profiles you like.",
            "visibility" => 0,
            "vas_id" => 2
        ) ,
        "I" => array(
            "name" => "We <br>Talk For You",
            "description" => "Busy? Personalized service where Jeevansathi executive will speak to profiles you like.",
            "visibility" => 0,
            "vas_id" => 3
        ) ,
        "R" => array(
            "name" => "Featured <br>Profile",
            "description" => "Want to grab your partner's attention? Feature in a special section on top of all relevant searches on PC site.",
            "visibility" => 0,
            "vas_id" => 4
        ) ,
        "B" => array(
            "name" => "Profile <br>Highlighting",
            "description" => "Want to stand out? Highlight your profile in different color on the PC site and get 3 times higher response.",
            "visibility" => 0,
            "vas_id" => 5
        ) ,
        "M" => array(
            "name" => "Matri <br>Profile",
            "description" => "Express Yourself! Get our experts to create a comprehensive and well-written profile for you.",
            "visibility" => 0,
            "vas_id" => 6
        )
    );
    public static $newApiVasNamesAndDescription = array(
        "T" => array(
            "name" => "Response Booster",
            "description" => "Jeevansathi contacts people matching your criteria to get you 8 times more responses",
            "visibility" => 0,
            "vas_id" => 1
        ) ,
        "A" => array(
            "name" => "Astro Compatibility",
            "description" => "Get detailed Kundli matching reports with profiles you like",
            "visibility" => 0,
            "vas_id" => 2
        ) ,
        "I" => array(
            "name" => "We Talk For You",
            "description" => "Jeevansathi speaks on your behalf to profiles you like",
            "visibility" => 0,
            "vas_id" => 3
        ) ,
        "R" => array(
            "name" => "Featured Profile",
            "description" => "Feature in a special section on top of all relevant searches",
            "visibility" => 0,
            "vas_id" => 4
        ) ,
        "B" => array(
            "name" => "Profile Highlighting",
            "description" => "Highlight your profile in a different color & get 3 times higher responses",
            "visibility" => 0,
            "vas_id" => 5
        ) ,
        "M" => array(
            "name" => "Matri Profile",
            "description" => "Get our experts to create comprehensive & well-written profile for you",
            "visibility" => 0,
            "vas_id" => 6
        ),
        "J" => array(
            "name" => "Profile Boost",
            "description" => "Get more response through Profile Boost. 1.Get featured on top of search results. 2.Be shown in profile of the day section. 3.Your profile will be sent daily in app notifs. 4.Appear on top of Daily Recommendations",
            "visibility" => 0,
            "vas_id" => 7
        )
    );
    public static $vasPerService = array(
        "P" => array(
            0,
            0,
            0,
            0,
            0,
            0
        ) ,
        "C" => array(
            0,
            0,
            0,
            0,
            0,
            0,
        ) ,
        "ESP" => array(
            1,
            1,
            1,
            1,
            0,
            0
        ) ,
        "NCP" => array(
            1,
            0,
            0,
            1,
            0,
            0
        )
    );
    public static $apiMembershipIcons = array(
        array(
            "icon_id" => "1",
            "icon_name" => "See <br>Contacts",
            "description" => "Instantly view contact details (subject to a limit) of members you like. See UNLIMITED number of contact details of accepted members.",
            "visibility" => 0
        ) ,
        array(
            "icon_id" => "2",
            "icon_name" => "Initiate chat <br>and message",
            "description" => "Send your own personalised message to users while expressing interest. Exchange messages with accepted members.",
            "visibility" => 0
        ) ,
        array(
            "icon_id" => "3",
            "icon_name" => "Let others see <br>your Contacts",
            "description" => "Let even free members see your contact details. (Only members who satisfy your contact filters will be able to see your contact details)",
            "visibility" => 0
        ) ,
        array(
            "icon_id" => "4",
            "icon_name" => "Response <br>Booster",
            "description" => "Busy? Don't worry! Allow Jeevansathi.com to contact people who match your criteria and get 8 times more response.",
            "visibility" => 0
        ) ,
        array(
            "icon_id" => "5",
            "icon_name" => "Astro <br>Compatibility",
            "description" => "Horoscope match a must? Get detailed kundli matching reports with profiles you like.",
            "visibility" => 0
        ) ,
        array(
            "icon_id" => "6",
            "icon_name" => "Featured <br>Profile",
            "description" => "Want to grab your partner's attention? Feature in a special section on top of all relevant searches on PC site.",
            "visibility" => 0
        ) ,
        array(
            "icon_id" => "7",
            "icon_name" => "We Talk <br>For You",
            "description" => "Busy? Personalized service where Jeevansathi executive will speak to profiles you like.",
            "visibility" => 0
        )
    );
    public static $apiMembershipIconsJSExclusive = array(
        array(
            "icon_id" => null,
            "icon_name" => null,
            "description" => "A dedicated matchmaking expert will create a profile for you that gets noticed",
            "visibility" => "1"
        ) ,
        array(
            "icon_id" => null,
            "icon_name" => null,
            "description" => "Understand qualities you are looking for in your desired partner",
            "visibility" => "1"
        ) ,
        array(
            "icon_id" => null,
            "icon_name" => null,
            "description" => "Hand-pick profiles that match your expectations",
            "visibility" => "1"
        ) ,
        array(
            "icon_id" => null,
            "icon_name" => null,
            "description" => "Contact shortlisted profiles & arrange meetings on your behalf",
            "visibility" => "1"
        )
    );
    public static $iconsPerMembership = array(
        "P" => array(
            "1",
            "1",
            "0",
            "0",
            "0",
            "0",
            "0"
        ) ,
        "C" => array(
            "1",
            "1",
            "1",
            "0",
            "0",
            "0",
            "0"
        ) ,
        "ESP" => array(
            "1",
            "1",
            "1",
            "1",
            "1",
            "1",
            "1"
        )
    );
    public static $apiPageOneBenefits = array(
        "View Contact Details",
        "Send Personalized Messages",
        "Get Your Profile Featured & more .."
    );
    public static $apiPageOnePerMembershipBenefits = array(
        'Instantly see Phone/Email of members',
        'Initiate Chat and Send Messages',
        'Priority Customer service',
        'Publish your contacts to other members',
        'Response Booster',
        'Astro Compatibility',
        'We Talk For You',
        'Featured Profile',
        'Personalized service by a matchmaking expert'
    );
    public static $apiPageOnePerMembershipBenefitsVisibility = array(
        "P" => array(
            1,
            1,
            1,
            0,
            0,
            0,
            0,
            0,
            0
        ) ,
        "C" => array(
            1,
            1,
            1,
            1,
            1,
            0,
            0,
            0,
            0
        ) ,
        "ESP" => array(
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            0
        ) ,
        "X" => array(
            1,
            1,
            1,
            0,
            0,
            0,
            0,
            0,
            1
        ) ,
        "NCP" => array(
            1,
            1,
            1,
            1,
            1,
            0,
            0,
            1,
            0
        )
    );
    public static $newApiPageOneBenefits = array(
        "Send Personalized Messages & Chat",
        "View contacts of members you like",
        "Priority Customer service",
        "Make your contacts visible to others",
        "Profile Boost",
        "Response Booster",
        "Featured Profile",
        "Astro Compatibility",
        "We Talk For You"
    );
    public static $newApiPageOneBenefitsVisibility = array(
        "P" => array(
            1,
            1,
            1,
            0,
            0,
            0,
            0,
            0,
            0
        ) ,
        "C" => array(
            1,
            1,
            1,
            1,
            0,
            0,
            0,
            0,
            0
        ) ,
        "ESP" => array(
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            1
        ) ,
        "NCP" => array(
            1,
            1,
            1,
            1,
            1,
            0,
            0,
            0,
            0
        ) ,
        "D" => array(
            1,
            1,
            1,
            1,
            1,
            0,
            0,
            0,
            0
        ) ,
        "FREE" => array(
            1,
            1,
            0,
            1,
            1,
            0,
            0,
            0,
            0
        )

    );

    public static $newApiPageOneBenefitsBoost = array("Get featured on top of search results.",
                                                    "Be shown in profile of the day section.",
                                                    "Your profile will be sent daily in app notifs.",
                                                    "Appear on top of Daily Recommendations");

    public static $newApiPageOneBenefitsJSX = array(
        "Connect with our experienced advisor who works on your behalf",
        "Your advisor interacts with you to know your expectations",
        "Then utilizes his expertise to shortlist potential matches for you",
        "Connects with you to find the most suitable matches for you",
        "Introduces you to the chosen matches & arranges meetings",
        "Priority Customer service"
    );
    public static $DOL_CONV_RATE = 60;
    
    public static $apiPageFiveHelpText = array(
        "text1" => "Got any Questions about Paying Online?",
        "text2" => "Call us at 1800-419-6299 (Toll Free)",
        "text3" => "Pay at the nearest Jeevansathi branch",
        "text4" => "Prices may vary, if you choose to pay later"
    );
    
    public static $payUAllowedIps = array(
        '180.179.174.1',
        '180.179.174.2'
    );
    
    public static $apiPageSixParams = array(
        "1" => array(
            "name" => "Name",
            "hint_text" => "Not filled in",
            "id" => "name"
        ) ,
        "2" => array(
            "name" => "Phone Number",
            "hint_text" => "Not filled in",
            "id" => "landline"
        ) ,
        "3" => array(
            "name" => "Mobile Number",
            "hint_text" => "Not filled in",
            "id" => "mobile"
        ) ,
        "4" => array(
            "name" => "City",
            "hint_text" => "Not filled in",
            "id" => "city"
        ) ,
        "5" => array(
            "name" => "Address",
            "hint_text" => "Not filled in",
            "id" => "address"
        ) ,
        "6" => array(
            "name" => "Preferred Date",
            "hint_text" => "Not filled in",
            "id" => "date"
        ) ,
        "7" => array(
            "name" => "Comments",
            "hint_text" => "Not filled in",
            "id" => "comment"
        )
    );

    //remove specified vas services from vas content based on main membership 
    public static $mainMemBasedVasFiltering = array('NCP'=>array('R','T','J'));

    //skip vas page for below main memberships
    public static $skipVasPageMembershipBased = array('X','ESP');
    
    public static $jsExclusiveComboAddon = array('J');
    
    public static $excludeInPrintBill = array('e-Value Pack','JS Boost');
    
    public static $lightningDealDuration = '30'; //in minutes;
}
class discountType
{
    const UPGRADE_DISCOUNT = "UPGRADE";
    const RENEWAL_DISCOUNT = "RENEWAL";
    const SPECIAL_DISCOUNT = "SPECIAL";
    const FESTIVE_DISCOUNT = "FESTIVE";
    const OFFER_DISCOUNT = "OFFER";
}
class mainMem
{
    const ERISHTA = "erishta";
    const EVALUE = "evalue";
    const JSEXCLUSIVE = "jsexclusive";
    const ECLASSIFIED = "eclassified";	
    const EADVANTAGE = "eadvantage";

   // labels Currently used in search api
    const ERISHTA_LABEL = "eRishta";
    const EVALUE_LABEL = "eValue";
    const JSEXCLUSIVE_LABEL = "JS Exclusive";
    const ECLASSIFIED_LABEL = "eClassified";	
    const EADVANTAGE_LABEL = "eAdvantage";
}
class paymentOption
{
    public static $paymentMode = array(
        "CR" => "Credit Card",
        "DR" => "Debit Card",
        "NB" => "Net Banking",
        "CSH" => "Wallet"
    );
    public static $ccCardType = array(
        "card1" => array(
            "ic_id" => "",
            "name" => "American Express"
        ) ,
        "card2" => array(
            "ic_id" => "",
            "name" => "MasterCard"
        ) ,
        "card3" => array(
            "ic_id" => "",
            "name" => "VISA"
        ) 
        // ,
        // "card4" => array(
        //     "ic_id" => "",
        //     "name" => "Diners Club"
        // ) ,
        // "card5" => array(
        //     "ic_id" => "",
        //     "name" => "UCB"
        // ) ,
        // "card6" => array(
        //     "ic_id" => "",
        //     "name" => "RuPay"
        // )
    );
    public static $dbCardType = array(
        // "card1" => array(
        //     "ic_id" => "",
        //     "name" => "American Express"
        // ) ,
        "card2" => array(
            "ic_id" => "",
            "name" => "MasterCard"
        ) ,
        "card3" => array(
            "ic_id" => "",
            "name" => "VISA"
        ) ,
        "card4" => array(
            "ic_id" => "",
            "name" => "Maestro"
        ) ,
        "card6" => array(
            "ic_id" => "",
            "name" => "RuPay"
        )
    );
    
    //public static $walletType =array("wallet1"=>array("ic_id"=>"","name"=>"ItzCash"),"wallet2"=>array("ic_id"=>"","name"=>"OxiCash"),"wallet3"=>array("ic_id"=>"","name"=>"iCashCard"),"wallet4"=>array("ic_id"=>"","name"=>"Done Card"),"wallet5"=>array("ic_id"=>"","name"=>"PayMate"),"wallet6"=>array("ic_id"=>"","name"=>"MobikWik"));
    public static $walletType = array(
        "wallet6" => array(
            "ic_id" => "",
            "name" => "MobikWik"
        ) ,
        "wallet7" => array(
            "ic_id" => "",
            "name" => "PayTM"
        )
    );
    
    public static $cardTypeMapping = array(
        "card1" => "card",
        "card2" => "card",
        "card3" => "card2",
        "card4" => "card2",
        "card5" => "card2",
        "card6" => "card2",
        "wallet1" => "card3",
        "wallet2" => "card3",
        "wallet3" => "card3",
        "wallet4" => "card3",
        "wallet5" => "card3",
        "wallet6" => "card3",
        "wallet7" => "paytm"
    );
    
    //public static $walletIdMapping =array("wallet1"=>"ITZ_N","wallet2"=>"OXIG_N","wallet3"=>"CCI_N","wallet4"=>"","wallet5"=>"","wallet6"=>"MOBKP_N");
    public static $walletIdMapping = array(
        "wallet1" => "ITZ_N",
        "wallet2" => "OXIG_N",
        "wallet3" => "CCI_N",
        "wallet4" => "",
        "wallet5" => "",
        "wallet6" => "MOBKP_N",
        "wallet7" => "PAYTM"
    );
    
    public static $paymentRedirectPage = array(
        "r1" => "order_payseal.php",
        "ccavenue" => "ccavenue",
        "paypal" => "paypal",
        "r4" => "transecute/chequedrop.php",
        "r5" => "revamp_easy_bill.php",
        "paytm" => "paytm",
        "payu" => "payu"
    );
    
    public static $creditCardIconMapping = array(
        "card1" => "ame_ex",
        "card2" => "masterc",
        "card3" => "rv2_visa",
        "card4" => "rv2_dc",
        "card5" => "rv2_ucb",
        "card6" => "rv2_rupay"
    );
    public static $debitCardIconMapping = array(
        "card1" => "ame_ex",
        "card2" => "masterc",
        "card3" => "rv2_visa",
        "card4" => "rv2_maestro",
        "card5" => "rv2_ucb",
        "card6" => "rv2_rupay"
    );
    
    //public static $walletCardIconMapping =array("wallet1"=>"rv2_itscash","wallet2"=>"rv2_oxicash","wallet3"=>"rv2_icash","wallet4"=>"rv2_dcard","wallet5"=>"rv2_paym","wallet6"=>"rv2_mobiwik");
    public static $walletCardIconMapping = array(
        "wallet1" => "rv2_itscash",
        "wallet2" => "rv2_oxicash",
        "wallet3" => "rv2_icash",
        "wallet4" => "rv2_dcard",
        "wallet5" => "rv2_paym",
        "wallet6" => "rv2_mobiwik",
        "wallet7" => "rv2_paytm"
    );
}

class gatewayConstants
{
    public static $CCAvenueLiveDolMerchantId = "63430";
    public static $CCAvenueLiveDolSalt = "7C1F2325E7B5E8B39C36C2D3BF6D25E3";
    public static $CCAvenueLiveDolURL = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
    public static $CCAvenueLiveDolAccessCode = "AVDD04CD91AO78DDOA";
    
    public static $CCAvenueLiveRsMerchantId = "M_anyana_1395";
    public static $CCAvenueLiveRsSalt = "a5qdxwe59g5af94qphru8hjubw1t9o6u";
    public static $CCAvenueLiveRsURL = "https://www.ccavenue.com/shopzone/cc_details.jsp";
    
    public static $CCAvenueTestDolMerchantId = "63430";
    public static $CCAvenueTestDolSalt = "7C1F2325E7B5E8B39C36C2D3BF6D25E3";
    public static $CCAvenueTestDolURL = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
    public static $CCAvenueTestDolAccessCode = "AVDD04CD91AO78DDOA";
    
    public static $CCAvenueTestRsMerchantId = "M_anyana_1395";
    public static $CCAvenueTestRsSalt = "a5qdxwe59g5af94qphru8hjubw1t9o6u";
    public static $CCAvenueTestRsURL = "https://www.ccavenue.com/shopzone/cc_details.jsp";
    
    public static $CCAvenueSeamlessRedirectURL = "https://www.ccavenue.com/servlet/new_txn.PaymentIntegration";
    
    public static $PayULiveDolMerchantId = "U0TVwL";
    public static $PayULiveDolSalt = "pvDO157G";
    
    public static $PayULiveRsMerchantId = "2JnXhc";
    public static $PayULiveRsSalt = "o3GlbmJG";
    
    public static $PayUTestDolMerchantId = "U0TVwL";
    public static $PayUTestDolSalt = "pvDO157G";
    
    public static $PayUTestRsMerchantId = "gtKFFx";
    public static $PayUTestRsSalt = "eCwWELxi";
    
    public static $PayUTestGatewayURL = 'https://test.payu.in/_payment';
    public static $PayULiveGatewayURL = 'https://secure.payu.in/_payment';
    
    public static $PayUTestPullReqUrl = 'https://test.payu.in/merchant/postservice.php?form=2';
    public static $PayULivePullReqUrl = 'https://info.payu.in/merchant/postservice.php?form=2';
    
    public static $PaysealLiveDolMerchantId = "00001712";
    public static $PaysealLiveRsMerchantId = "00001673";
    
    public static $PaysealTestDolMerchantId = "00001712";
    public static $PaysealTestRsMerchantId = "00001673";
    
    public static $PaypalLiveMerchantId = "paypal@jeevansathi.com";    
    public static $PaypalLiveToken = "fSvi3twDPnzmAzY2DCr3C5IAl7xzOElPvVxwwLx56R8sPX6ru_XwIhjb1p4";
    public static $PaypalLiveURL = "www.paypal.com";
    public static $PaypalTestMerchantId = "paypalTest@jeevansathi.com";
    public static $PaypalTestToken = "SalrMlpieRD60VxsBu6lFIrr2e5Di2DAoSWKbDqjK_rRRKSVgkKknKDzpMO";
    public static $PaypalTestURL = "www.sandbox.paypal.com";
    public static $PaypalGatewayLiveURL = "https://www.paypal.com/cgi-bin/webscr";
    public static $PaypalGatewayTestURL = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    public static $PaypalReturnURL = "/profile/pg/orderOK_paypal.php";
    public static $PaypalCancelReturnURL = "/profile/pg/orderCancel_paypal.php";
    public static $PaypalCurrency = "USD";
    public static $PaypalNoShipping = "1";
    public static $PaypalNoNote = "1";
    public static $PaypalCmd = "_xclick";
    public static $PaypalReturnMethod = "POST";
    
    public static $AppleTestURL = 'https://sandbox.itunes.apple.com/verifyReceipt';
    public static $AppleLiveURL = 'https://buy.itunes.apple.com/verifyReceipt';
    
    public static $PayTmTestIndustryType = 'Retail';
	public static $PayTmTestChannelId = 'WEB';
	public static $PayTmTestWebsite = 'jeevanweb';

	public static $PayTmLiveIndustryType = 'Retail104';
	public static $PayTmLiveChannelId = 'WEB';
	public static $PayTmLiveWebsite = 'jeevanweb';
    
    public static $PayTmTestURL = 'https://pguat.paytm.com/oltp-web/processTransaction';
    public static $PayTmTestRsMerchantId = "jeevan83602134214805";
    public static $PayTmTestRsSalt = '@56e7#njEuVHYmCv';
    
    public static $PayTmLiveURL = 'https://secure.paytm.in/oltp-web/processTransaction';
    public static $PayTmLiveRsMerchantId = "jeevan75053257920857";
    public static $PayTmLiveRsSalt = 'gbgpYlUSGsamIdHC';
    
    
}

class franchiseeCommission
{
    const FRANCHISEE = 40;
}

class SelectGatewayRedirect{
    public static $gatewayOptions = array('default','payu','ccavenue');
}
?>
