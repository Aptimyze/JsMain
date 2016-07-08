/*This file contains global chat configurable variables used in chat javascripts channel wise*/

var chatConfig = chatConfig || {};

chatConfig.Params = {
				PC:{
					bosh_service_url:'ws://localhost:7070/ws/',  //connection manager for openfire
					keepalive:true, //keep logged in session alive
					roster_groups:true, //show categories in listing
					hide_offline_users:false, //hide offline users from list
					use_vcards:true, //fetch vcards of users
					rosterDisplayGroups:{"Desired Partner Matches":"dpp","Interest Received":"eoi_R","Shortlisted Members":"shortlisted","Accepted Members":"accepted_by_me"},
					//categories in listing to be shown with mapping to their div ids---not required in new plugin ankita
					//tab id to tab names mapping
					listingTabs:{
						"tab1":{
							"tab_name":"Online Matches",
							"groups":[
								{
									"id":"dpp",
									"group_name":"Desired Partner Matches",
									"order_id":0
								},
								{
									"id":"eoi_R",
									"group_name":"Interest Received",
									"order_id":1
								},
								{
									"id":"shortlisted",
									"group_name":"Shortlisted Members",
									"order_id":2
								}
							],
							"hide_offline_users":true
						},
						"tab2":{
							"tab_name":"Accepted",
							"groups":[
							],
							"hide_offline_users":false
						}
					},
                    initialRosterLimit:{"nodesCount":3,"timeInterval":120000} //config for initial roster to be sent to plugin to create list initially				
				}
			};