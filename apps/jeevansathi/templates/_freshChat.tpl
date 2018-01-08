<script>
    ~if $profileid`
    var udObj = '~$userDetails`';
    udObj = udObj.replace(/&quot;/g, '"');
    var userDetails = $.parseJSON(udObj);
    ~/if`

    window.fcSettings = {
        token: "~$token`",
        host: "https://wchat.freshchat.com",
        ~if $profileid`
        ~if !$logout`
        externalId: userDetails['username'],     // user’s id - USERNAME
        firstName:  userDetails['firstName'],    // user’s first name
        lastName:   userDetails['lastName'],     // user's last name
        email:      userDetails['email'],        // user’s email address
        phone:      userDetails['mob'],          // phone number
        restoreId:  userDetails['restoreid'],    // user's restoreid
        ~if $tag`
        tags:       ["Service","Membership"],    // user specific tag
        ~/if`
        ~/if`
        onInit: function() {
            window.fcWidget.on('widget:loaded', function() {
                if(localStorage.getItem("logout")){
                    window.fcWidget.user.clear();
                    window.fcWidget.destroy();
                } else if(!userDetails['restoreid']){
                    ~*console.log("Adding user restoreid");*`
                    ~*window.fcWidget.user.get().then(function(resp) {*`
                    ~*var status = resp && resp.status,*`
                    ~*data = resp && resp.data;*`
                    ~*if (status === 200) {*`
                    ~*if (data.restoreId) {*`
                    ~*console.log(data.restoreId);*`
                    ~*url = "/api/v1/membership/updateRestoreId"*`
                    ~*$.ajax({*`
                    ~*type: 'POST',*`
                    ~*url: url,*`
                    ~*data:{*`
                    ~*profileid: ~$profileid`,*`
                    ~*restoreid: data.restoreId*`
                    ~*},*`
                    ~*success: function(data) {*`
                    ~*console.log("Success");*`
                    ~*}*`
                    ~*});*`
                    ~*}*`
                    ~*}*`
                    ~*});*`
                }
            });
        }
        ~/if`
    };
</script>
<script src="~FreshChat::$widgetUrl`" async></script>