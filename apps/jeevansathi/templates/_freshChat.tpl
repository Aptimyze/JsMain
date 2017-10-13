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
        externalId: userDetails['username'],   // user’s id - USERNAME
        firstName:  userDetails['firstName'],    // user’s first name
        lastName:   userDetails['lastName'],     // user's last name
        email:      userDetails['email'],        // user’s email address
        phone:      userDetails['mob'],          // phone number
        tag:        userDetails['tag'],          // channel tag

        ~*onInit: function() {*`
            ~*window.fcWidget.on('widget:loaded', function() {*`
                ~*window.fcWidget.user.get().then(function(resp) {*`
                    ~*var status = resp && resp.status,*`
                        ~*data = resp && resp.data;*`
                    ~*if (status === 200) {*`
                        ~*if (data.restoreId) {*`
                             ~*Update restoreId in your database*`
                            ~*console.log(data.restoreId);*`
                             ~*add updateRestoreId in RequestHandlerConfig and make new function in membershipAction executeUpdateRestoreId*`
                            ~*url = "/api/v1/membership/updateRestoreId"*`
                            ~*$.ajax({*`
                                ~*type: 'POST',*`
                                ~*url: url,*`
                                ~*data:{*`
                                    ~*profileid: ~$profileid`,*`
                                    ~*restoreid: data.restoreid,*`
                                ~*},*`
                                ~*success: function(data) {*`
                                    ~*//console.log(data);*`
                                ~*}*`
                            ~*});*`
                        ~*}*`
                    ~*}*`
                ~*});*`
            ~*});*`
        ~*}*`
    ~/if`
    };
</script>
<script src="https://wchat.freshchat.com/js/widget.js" async></script>
