$(document).ready(function(){

    $('.chkactnot').click(function(){
        var status = "";
        var getID = $(this).attr('data-attr');
        var b = getID.split('-');	
        if(b[0]=="unchk")
        {


            if($('#'+b[1]).prop('checked') != false)
            {
                $('#'+b[1]).prop( "checked", false );
                $('.box').toggleClass('move notshwd');
                $('.outerbox').toggleClass('outchange');
                status = "N";
                console.log("Unchecked");
            }
        }
        else
        {
            if($('#'+b[1]).prop('checked') != true)
            {
                $('#'+b[1]).prop( "checked", true );

                $('.box').toggleClass('move notshwd');
                $('.outerbox').toggleClass('outchange');
                status = "Y";
                console.log("Checked");
            }
        }
        url = "/api/v1/notification/updateNotificationSetting"
        $.ajax({
            type: 'POST',
            url: url,
            data:{
                status: status,
            },
            success: function(data) {
                console.log(data);
            }
        });

    });
if(status != "Y"){
        $('#notifi').prop( "checked", false );
    }
});

