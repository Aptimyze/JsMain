/*This file contains global chat configurable variables used in chat javascripts channel wise*/
var chatConfig = chatConfig || {};
chatConfig.Params = {
    categoryNames: {
        "Desired Partner Matches": "dpp",
        "Interest Received": "intrec",
        "Acceptance": "acceptance",
        "Shortlisted Members": "shortlist",
        "Interest Sent": "intsent",
        "Search Results":"mysearch"
    },
    groupIDMapping:{
        'N':"mysearch",
        'A':"acceptance",
        'I':"intsent"
    },
    //subscriptions for group id's
    groupWiseSubscription: {
        "dpp": 'to',
        "intrec": 'to',
        "acceptance": 'both',
        "shortlist": 'to',
        "intsent": 'to'
    },
    //api's url for contact engine actions
    actionUrl: {
        "ACCEPT": "/api/v2/contacts/postAccept",
        "DECLINE": "/api/v2/contacts/postNotInterested",
        "INITIATE": "/api/v2/contacts/postEOI",
        "BLOCK": "/api/v1/common/ignoreprofile",
        "UNBLOCK": "/api/v1/common/ignoreprofile"
    },
    //tracking params for contact engine actions
    trackingParams: {
        "ACCEPT": {
            "responseTracking": chatTrackingVar["rtype"]
        },
        "DECLINE": {
            "responseTracking": chatTrackingVar["rtype"]
        },
        "INITIATE": {
            "stype": chatTrackingVar["stype"]
        },
        "BLOCK": {},
        "UNBLOCK": {}
    },
    categoryTrackingParams: {
        "intrec": {
            "responseTracking": chatTrackingVar["rtype"]
        },
        "dpp": {
            "stype": chatTrackingVar["stype"]
        },
        "intsent": {
            "stype": chatTrackingVar["stype"]
        },
        "shortlist": {
            "stype": chatTrackingVar["stype"]
        },
        "acceptance": {},
        "mysearch":{}
    },
    //api url for getting photo
    photoUrl: "/api/v1/social/getMultiUserPhoto",
    
    //api url for getting self name
    selfNameUr: "/api/v1/chat/selfName",
    //api config for pre acceptance messages
    preAcceptChat: {
        "apiUrl": "/api/v1/chat/sendEOI",
        "extraParams": {
            "stype": chatTrackingVar["stype"],
            "pageSource": "chat",
            "channel": 'pc'
        }
    },
    //api config for chat history
    chatHistoryApi: {
        "apiUrl": "/api/v1/chat/popChat",
        "extraParams": {
            "pageSource": "chat",
            "channel": 'pc'
        }
    },
    pc: {
        updateRosterFromFrontend: true,
        bosh_service_url: 'ws://' + openfireUrl + '/ws/', //connection manager for openfire
        //keepalive: true, //keep logged in session alive
        //roster_groups: true, //show categories in listing
        hide_offline_users: false, //hide offline users from list
        //use_vcards: false, //fetch vcards of users
        //tab id to tab names mapping
        listingTabs: {
            "tab1": {
                "tab_name": "Online Matches",
                "groups": [
                    {
                        "id": "dpp",
                        "group_name": "Desired Partner Matches",
                        "show_group_name": true,
                        "hide_offline_users": false
                    },
                    {
                        "id": "intsent",
                        "group_name": "Interest Sent",
                        "show_group_name": true,
                        "hide_offline_users": false
                    }, {
                        "id": "intrec",
                        "group_name": "Interest Received",
                        "show_group_name": true,
                        "hide_offline_users": false
                    }, {
                        "id": "shortlist",
                        "group_name": "Shortlisted Members",
                        "show_group_name": true,
                        "hide_offline_users": false
                    },{
                        "id":"mysearch",
                        "group_name":"Search Results",
                        "show_group_name":false,
                        "hide_offline_users":false,
                        "nonRosterGroup" : true
                    }
                ]
            },
            "tab2": {
                "tab_name": "Accepted",
                "groups": [{
                    "id": "acceptance",
                    "group_name": "Acceptance",
                    "show_group_name": false,
                    "hide_offline_users": false
                }]
            }
        },
        "buttons": {
            "intrec": [{
                "action": "ACCEPT",
                "label": "Accept Interest",
                "iconid": "090",
                "primary": "true",
                "secondary": null,
                "params": "responseTracking="+chatTrackingVar["rtype"],
                "enable": true,
                "id": "ACCEPT"
            }, {
                "action": "DECLINE",
                "label": "Decline Interest",
                "iconid": "089",
                "primary": "true",
                "secondary": null,
                "params": "responseTracking="+chatTrackingVar["rtype"],
                "enable": true,
                "id": "DECLINE"
            }],
            "dpp": [{
                "action": "INITIATE",
                "label": "Send Interest",
                "iconid": "001",
                "primary": "true",
                "secondary": "true",
                "params": "stype="+chatTrackingVar["stype"],
                "enable": true,
                "id": "INITIATE"
            }],
            "intsent": [{
                "action": "CANCEL",
                "label": "Interest Sent",
                "iconid": "005",
                "primary": "true",
                "secondary": "true",
                "params": "stype="+chatTrackingVar["stype"],
                "enable": true,
                "id": "CANCEL"
            }],
            "shortlist": [{
                "action": "INITIATE",
                "label": "Send Interest",
                "iconid": "001",
                "primary": "true",
                "secondary": "true",
                "params": "stype="+chatTrackingVar["stype"],
                "enable": true,
                "id": "INITIATE"
            }],
            "acceptance": [{
                "action": "WRITE_MESSAGE",
                "label": "Start Conversation",
                "iconid": "086",
                "primary": "true",
                "secondary": "true",
                "enable": true,
                "id": "WRITE_MESSAGE"
            }]
        },
        //no photo url for images
        noPhotoUrl: {
            "listingTuple": {
                "M": "/images/picture/120x120_m.png?noPhoto",
                "F": "/images/picture/120x120_f.png?noPhoto"
            },
            "self120": {
                "M": "/images/picture/120x120_m.png?noPhoto",
                "F": "/images/picture/120x120_f.png?noPhoto",
            }
        },
        //contact status mapping for chat box types
        groupBasedChatBox: {
            "dpp": "pg_interest_pending",
            "intsent": "pog_acceptance_pending",
            "intrec": "pg_acceptance_pending",
            "acceptance": "both_accepted",
            "shortlist": "pg_interest_pending",
            "mysearch":"pg_interest_pending"
        },
        /* communication type and mode config
         * if enableChat is true and useOpenfireForChat is true,then post acceptance msg through openfire,
         * else if enableChat is true and useOpenfireForChat is false,then pre acceptance msg through posteoi api,
         * else if enableChat is false, chat is not possible
         */
        contactStatusMapping: {
            "pg_interest_pending": {
                "key": "pg_interest_pending",
                "enableChat": true,
                "useOpenfireForChat": false,
                "showHistory": true,
                "checkForPaidInitiation":true
            },
            "pog_acceptance_pending": {
                "key": "pog_acceptance_pending",
                "useOpenfireForChat": false,
                "enableChat": true,
                "showHistory": true,
                "checkForPaidInitiation":true
            },
            "pg_acceptance_pending": {
                "key": "pg_acceptance_pending",
                "useOpenfireForChat": false,
                "enableChat": false,
                "showHistory": true,
                "checkForPaidInitiation":true
            },
            "pog_interest_accepted": {
                "key": "pog_interest_accepted",
                "useOpenfireForChat": true,
                "enableChat": true,
                "showHistory": true,
                "checkForPaidInitiation":true
            },
            "pog_interest_declined": {
                "key": "pog_interest_declined",
                "useOpenfireForChat": false,
                "enableChat": false,
                "showHistory": false,
                "checkForPaidInitiation":false
            },
            "pg_interest_declined": {
                "key": "pg_interest_declined",
                "useOpenfireForChat": false,
                "enableChat": false,
                "showHistory": false,
                "checkForPaidInitiation":false
            },
            "none_applicable": {
                "key": "none_applicable",
                "useOpenfireForChat": false,
                "enableChat": false,
                "showHistory": false,
                "checkForPaidInitiation":true
            },
            "both_accepted": {
                "key": "both_accepted",
                "useOpenfireForChat": true,
                "enableChat": true,
                "showHistory": true,
                "checkForPaidInitiation":true
            },
            "pg_interest_accepted": {
                "key": "pg_interest_accepted",
                "useOpenfireForChat": true,
                "enableChat": true,
                "showHistory": true,
                "checkForPaidInitiation":true
            }
        },
        //max count of nodes limit per group
        groupWiseNodesLimit: {
            "dpp": 50,
            "intrec": 50,
            "shortlist": 50,
            "intsent":50,
            "acceptance":400
        },
        storeMsgInLocalStorage:false,
        maxMsgLimit:1000, //upper limit on no of characters in msg that user can type
        moreMsgChunk: 20, //pagination count for msg history
        loginRetryTimeOut: 500,    
        appendRetryLimit: 1000,
        checkForDefaultEoiMsg:false,    //check for default eoi msg in chat history while append
        setLastReadMsgStorage:true,
	    loginSessionTimeout:30, // session will expire after 30 days in case of no activity
        autoChatLogin:true,  //auto-login to chat on site login
        rosterDeleteChatBoxMsg:"You can no longer chat, as the other user has blocked you/declined your interest"
    }
};
chatConfig.Params.pc.rosterGroups = [chatConfig.Params.categoryNames['Desired Partner Matches'], chatConfig.Params.categoryNames['Interest Sent'], chatConfig.Params.categoryNames['Interest Received'], chatConfig.Params.categoryNames['Acceptance'], chatConfig.Params.categoryNames['Shortlisted Members'],chatConfig.Params.categoryNames['Search Results']];
