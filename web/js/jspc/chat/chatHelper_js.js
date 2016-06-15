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