/*This file includes functions used for intermediate data transfer for JSPC chat from 
* converse client(converse.js) to chat plugin(chat_js.js)
*/

var pluginId = '#chatOpenPanel',device = 'PC';

/*function initiateChat
* request sent to openfire to initiate chat and maintain session
* @params:none
*/     
function initiateChatConnection() //pass callback function -listing panel show in converse.initialize-ankita
{
    try
    {
        //initialise converse settings and fetch data and then execute callback function
        require(['converse'], function (converse) {
            converse.initialize({
                bosh_service_url: 'ws://localhost:7070/ws/',
                keepalive: true,
                message_carbons: true, //why req?? - ankita
                //play_sounds: true,
                roster_groups: chatConfig.Params[device].roster_groups,
                hide_offline_users: chatConfig.Params[device].hide_offline_users,
                debug:false,
                //prebind:true,
                auto_login:true,
                jid:"a1@localhost",
                password:"123",
                //sid:sid,
                //rid:1,
                authentication:'login',
                show_controlbox_by_default: true, //why req--ankita
                listing_data:{},
                rosterDisplayGroups:chatConfig.Params[device].rosterDisplayGroups,
                //prebind_url: 'http://localhost/api/v1/chat/authenticateChatSession?jid=a1@localhost',  
            }),function(){
                console.log("calling callback");
            }
        });
    }
    catch(e)
    {
        console.log("Exception thrown in initatechat function - "+e);
    }
}

/*function fetchConverseSettings
* get value of converse settings' key
* @params:key
* @return:value
*/
function fetchConverseSettings(key)
{
    var value = converse.settings.get(key);  //if not works then use require like initatechat
    return value;
}

/*function mapListingJsonToHTML
* map json data for listing to html
* @params:jsonData
* @return:listingHTML
*/
function mapListingJsonToHTML(jsonData)  //use partial if possible - ankita
{
    var listingHTML = '<div id="listing_tab1">';
    $.each(jsonData,function( index, val ){
        listingHTML = listingHTML+ '<div id="'+chatConfig.Params[device].rosterDisplayGroups[index]+'"><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">'+index+'</p></div><ul class="chatlist">';
        $.each(val,function( key, details ){
              listingHTML = listingHTML+'<li class="clearfix profileIcon" id="profile_'+details.fullname+'"><img id="pic_'+key.fullname+'" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">'+details.fullname+'</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li>';
        });
        listingHTML = listingHTML+'</ul></div>';
    });
    listingHTML = listingHTML + '</div>';
    return listingHTML;   
}

/*function updateListing
* update listing
* @params:rosterData
*/
function updateListing(rosterData) //use partial if possible - ankita
{
    console.log("roster update");
    console.log(rosterData);
    var newRosterHTML = "",groupid;
    groupid = chatConfig.Params[device].rosterDisplayGroups[rosterData.groups[0]]; //group id mapping
    //handle if no user in group - case ankita
    //if this new user doesn't exist already
    if($("#"+groupid).find("ul.chatlist").find("li#profile_"+rosterData.fullname).length === 0)
    {
        newRosterHTML = '<li class="clearfix profileIcon" id="profile_'+rosterData.fullname+'"> <img id="pic_'+rosterData.fullname+'" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">'+rosterData.fullname+'</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li>';
        //append newRosterHTML
        $("#"+groupid).find("ul.chatlist").append(newRosterHTML);
    }     
}

/*function createListingPanel
* creates listing panel after login and json data generation
* @params:none
*/ 
function createListingPanel()
{
    //get json data for listing
    var listingData = fetchConverseSettings("listing_data");
    $(pluginId).setChatPluginOption("listingJsonData",listingData);
    //map json data to listing html
    $(pluginId).setChatPluginOption("Tab1Data" ,mapListingJsonToHTML($(pluginId).getChatPluginOption("listingJsonData")));
    //show listing panel by appending html
    var ChatPluginObj = $(pluginId).chatplugin();
    ChatPluginObj.addListingBody();
}