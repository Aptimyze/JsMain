
/*function initiateChat
* request sent to openfire to initiate chat and maintain session
* @params:none
*/
function initiateChat()
{
      converse.initialize({
            bosh_service_url: 'ws://localhost:7070/ws/', // Please use this connection manager only for testing purposes
            keepalive: true,
            message_carbons: true,
            play_sounds: true,
            roster_groups: true,
            hide_offline_users: false,
            debug:true,
            //prebind:true,
            auto_login:true,
            jid:"a1@localhost",
            password:"123",
            //sid:sid,
            //rid:1,
            authentication:'login',
            show_controlbox_by_default: true,
            //prebind_url: 'http://localhost/api/v1/chat/authenticateChatSession?jid=a1@localhost',  
      });
}

/*function mapListingJsonToHTML
* map json data for listing to html
* @params:jsonData
* @return:listingHTML
*/
function mapListingJsonToHTML(jsonData)
{
      var listingHTML = '<div id="listing_tab1">';
      $.each(jsonData,function( index, val ){

            listingHTML = listingHTML+ '<div id="'+chatAppPc.rosterGroupsIDMapping[index]+'"><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">'+index+'</p></div><ul class="chatlist">';
            $.each(val,function( key, details ){
                  listingHTML = listingHTML+'<li class="clearfix profileIcon" id="profile_'+key+'"><img id="pic_'+key+'" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">'+details.fullname+'</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li>';
            });
            listingHTML = listingHTML+'</ul></div>';
      });
      listingHTML = listingHTML + '</div>';
      return listingHTML;   
}

/*function mapListingJsonToHTML
* map json data for listing to html
* @params:jsonData
* @return:listingHTML
*/
function updateChatPCRoster(rosterData)
{
      try
      {
            //console.log("new roster");
            console.log(rosterData);
            var newRosterHTML = "",groupid;
            groupid = chatAppPc.rosterGroupsIDMapping[rosterData.groups[0]];
            newRosterHTML = '<li class="clearfix profileIcon" id="profile_'+rosterData.id+'"> <img id="pic_'+rosterData.id+'" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">'+rosterData.fullname+'</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li>';
            console.log(groupid);
            if($("#"+groupid).length != 0)
                  $("#"+groupid).find(".chatlist").append(newRosterHTML);
      }
      catch(e)
      {
            console.log(e);
      }
}