function autoPopulateFreshdeskDetails(username, email){
    if($("#lc_chat_layout input[id*='name']").length){
        var checkName = $("#lc_chat_layout input[id*='name']").val();
        if(checkName == ''){
            $("#lc_chat_layout input[id*='name']").val(username);
        }
    }
    if($("#lc_chat_layout input[id*='email']").length){
        var checkEmail = $("#lc_chat_layout input[id*='email']").val(); 
        if(checkEmail == ''){
            $("#lc_chat_layout input[id*='email']").val(email); 
        }
    }
}