function initiateChat()
{
      converse.initialize({
            bosh_service_url: 'http://localhost:7070/http-bind/', // Please use this connection manager only for testing purposes
            keepalive: true,
            message_carbons: true,
            play_sounds: true,
            roster_groups: true,
            hide_offline_users: true,
            debug:true,
            //prebind:true,
            jid:"a1@localhost",
            //sid:sid,
            //rid:1,
            authentication:'prebind',
            show_controlbox_by_default: true,
            prebind_url: 'http://localhost/api/v1/notification/getNotification?jid=a1@localhost',  
      });
}