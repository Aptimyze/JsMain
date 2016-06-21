/*This file contains global chat configurable variables used in chat javascripts channel wise*/

var chatConfig = chatConfig || {};

chatConfig.Params = {
				PC:{
					bosh_service_url:'ws://localhost:7070/ws/',  //connection manager for openfire
					keepalive:true, //keep logged in session alive
					roster_groups:true, //show categories in listing
					hide_offline_users:false, //hide offline users from list
					rosterDisplayGroups:{"Desired Partner Matches":"dpp","Interest Received":"eoi_R","Shortlisted Members":"shortlisted","Accepted Members":"accepted_by_me"}
					//categories in listing to be shown with mapping to their div ids
				}
			};