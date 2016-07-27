/*This file contains global chat configurable variables used in chat javascripts channel wise*/
var chatConfig = chatConfig || {};
chatConfig.Params = {
    categoryNames: {
        "Desired Partner Matches": "dpp",
        "Interest Received": "intrec",
        "Acceptance": "acceptance",
        "Shortlisted Members": "shortlist",
        "Interest Sent": "intsent"
    },
    actionUrl: {
        "ACCEPT": "/api/v2/contacts/postAccept",
        "DECLINE": "/api/v2/contacts/postNotInterested",
        "INITIATE": "/api/v2/contacts/postEOI",
        "BLOCK":"/api/v1/common/ignoreprofile",
        "UNBLOCK":"/api/v1/common/ignoreprofile"
    },
    trackingParams:{
        "ACCEPT": {
            "responseTracking":8
            },
        "DECLINE": {
            "responseTracking":8
            },
        "INITIATE": {
            "stype":"WV"},
        "BLOCK":{},
        "UNBLOCK":{}
    },
    photoUrl:"/api/v1/social/getMultiUserPhoto",
    PC: {
        bosh_service_url: 'ws://' + openfireUrl + '/ws/', //connection manager for openfire
        keepalive: true, //keep logged in session alive
        roster_groups: true, //show categories in listing
        hide_offline_users: false, //hide offline users from list
        use_vcards: false, //fetch vcards of users
        //rosterDisplayGroups:{"Desired Partner Matches":"dpp","Interest Received":"intrec","shortlist Members":"shortlist","Accepted Members":"acceptance"},
        //categories in listing to be shown with mapping to their div ids---not required in new plugin ankita
        //tab id to tab names mapping
        listingTabs: {
            "tab1": {
                "tab_name": "Online Matches",
                "groups": [{
                    "id": "dpp",
                    "group_name": "Desired Partner Matches",
                    "show_group_name": true,
                    "hide_offline_users": false
                }, {
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
                }]
            },
            "tab2": {
                "tab_name": "Accepted",
                "groups": [{
                    "id": "acceptance",
                    "group_name": "Acceptance",
                    //"order_id":0,
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
                "params": "responseTracking=8",
                "enable": true,
                "id": "ACCEPT"
            }, {
                "action": "DECLINE",
                "label": "Decline Interest",
                "iconid": "089",
                "primary": "true",
                "secondary": null,
                "params": "responseTracking=8",
                "enable": true,
                "id": "DECLINE"
            }],
            "dpp": [{
                "action": "INITIATE",
                "label": "Send Interest",
                "iconid": "001",
                "primary": "true",
                "secondary": "true",
                "params": "stype=WV",
                "enable": true,
                "id": "INITIATE"
            }],
            "intsent": [{
                "action": "CANCEL",
                "label": "Interest Sent",
                "iconid": "005",
                "primary": "true",
                "secondary": "true",
                "params": "stype=WV",
                "enable": true,
                "id": "CANCEL"
            }],
            "shortlist": [{
                "action": "INITIATE",
                "label": "Send Interest",
                "iconid": "001",
                "primary": "true",
                "secondary": "true",
                "params": "stype=WV",
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
        noPhotoUrl:{
            "listingTuple":{
                "M":"/images/picture/120x120_m.png?noPhoto",
                "F":"/images/picture/120x120_f.png?noPhoto"
            }
        },
        //contact status mapping for chat box types
        groupBasedChatBox: {
            "dpp": "pg_interest_pending",
            "intsent": "pog_acceptance_pending",
            "intrec": "pg_acceptance_pending",
            "acceptance": "both_accepted",
            "shortlist": "pg_interest_pending"
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
                "useOpenfireForChat": false
            },
            "pog_acceptance_pending": {
                "key": "pog_acceptance_pending",
                "useOpenfireForChat": false,
                "enableChat": true
            },
            "pg_acceptance_pending": {
                "key": "pg_acceptance_pending",
                "useOpenfireForChat": false,
                "enableChat": false
            },
            "pog_interest_accepted": {
                "key": "pog_interest_accepted",
                "useOpenfireForChat": true,
                "enableChat": true
            },
            "pog_interest_declined": {
                "key": "pog_interest_declined",
                "useOpenfireForChat": false,
                "enableChat": false
            },
            "pg_interest_declined": {
                "key": "pg_interest_declined",
                "useOpenfireForChat": false,
                "enableChat": false
            },
            "none_applicable": {
                "key": "none_applicable",
                "useOpenfireForChat": false,
                "enableChat": false
            },
            "both_accepted": {
                "key": "both_accepted",
                "useOpenfireForChat": true,
                "enableChat": true
            }
        },
        preAcceptChat: {
            "apiUrl": "/api/v1/chat/sendEOI",
            "extraParams": {
                "stype": "WV"
            }
        },
        groupWiseNodesLimit: {
            "dpp": 200,
            "intrec": 100,
            "shortlist": 100,
            "intsent": 100
        },
        //initialRosterLimit:{"nodesCount":3,"timeInterval":30000} //config for initial roster to be sent to plugin to create list initially
    }
};
chatConfig.Params.PC.rosterGroups = [chatConfig.Params.categoryNames['Desired Partner Matches'], chatConfig.Params.categoryNames['Interest Sent'], chatConfig.Params.categoryNames['Interest Received'], chatConfig.Params.categoryNames['Acceptance'], chatConfig.Params.categoryNames['Shortlisted Members']];