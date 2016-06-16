$(document).ready(function(){
     $("#emailInp, #quesInp").find("input").keydown(function(e) {
                $(this).parent().removeClass("errorDiv");
    });
$("#submitRequest").click(function() {

    validInfo = true;
    $("#errorList").html("");
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test($("#emailInp").find("input").val())) {
        validInfo = false,$("#emailInp").addClass("errorDiv"),$("#errorList").append("Please enter valid email ID.<br>");
    }
    if ($("#emailInp").find("input").val() == "") {
        validInfo = false,$("#emailInp").addClass("errorDiv"),$("#errorList").append("Please enter your email ID.<br>");
    }
    if ($("#quesInp").find("input").val() == "") {
        validInfo = false,$("#quesInp").addClass("errorDiv"),$("#errorList").append("Please enter your question.<br>");
    }
    if (validInfo == true) {
        $("#nextDiv").removeClass("dispnone"), $("#requestForm").addClass("dispnone");
        $("body").animate({
            scrollTop: "-100px"
        }, 100);
        var username = $("#usernameInp").find("input").val(),
            email = $("#emailInp").find("input").val(),
            query = $("#quesInp").find("input").val();
            console.log(username+email+query);
        $.ajax({
            type: "POST",
            url: '/api/v1/help/helpQuery',
            cache: false,
            timeout: 5000, 
            data: {email:email,username:username,query:query},
            success: function(result){
                console.log("S");
            },
            error: function(result){
                console.log("F");
            }
        });
    }
});
});