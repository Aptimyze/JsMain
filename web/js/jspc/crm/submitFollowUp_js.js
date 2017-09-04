function validateFollowUp()
{
    var status = $("#followupStatus").val();
    if(status=="F"){      
        var reason = $("#reason").val();
        if(reason==""){
            alert("Choose reason in case of followup");
            return false;
        }
        if(reason=="Others"){
            var reasonText = $("#reasonText").val();
            if(reasonText==""){
               alert("Specify reason in case of others option in followup");
            return false; 
            }
        }
    }
}

function backToFollowUps(){
    window.location = "/operations.php/jsexclusive/followupCaller";
}

$(document).ready(function() {

    $("#followupStatus").change(function(){
        var selectedVal = $(this).find(':selected').val();
        if(selectedVal=="F" || selectedVal=="N"){
            if(selectedVal=="F"){
                $("#reason").removeAttr("disabled");
                $("#reason").removeClass("crm-disabled");
                $("#day").removeAttr("disabled");
                $("#day").removeClass("crm-disabled");
                $("#month").removeAttr("disabled");
                $("#month").removeClass("crm-disabled");
                $("#year").removeAttr("disabled");
                $("#year").removeClass("crm-disabled");
            }
            else{
                $("#reason").attr("disabled","disabled");
                $("#reason").addClass("crm-disabled");
                $("#day").attr("disabled","disabled");
                $("#day").addClass("crm-disabled");
                $("#month").attr("disabled","disabled");
                $("#month").addClass("crm-disabled");
                $("#year").attr("disabled","disabled");
                $("#year").addClass("crm-disabled");
            }
            $("#reasonText").removeAttr("disabled");
            $("#reasonText").removeClass("crm-disabled");
        }
        else{
            $("#reason").attr("disabled","disabled");
            $("#reason").addClass("crm-disabled");
            $("#day").attr("disabled","disabled");
            $("#day").addClass("crm-disabled");
            $("#month").attr("disabled","disabled");
            $("#month").addClass("crm-disabled");
            $("#year").attr("disabled","disabled");
            $("#year").addClass("crm-disabled");
            $("#reasonText").removeAttr("disabled");
            $("#reasonText").removeClass("crm-disabled");
            //$("#reasonText").attr("disabled","disabled");
            //$("#reasonText").addClass("crm-disabled");
        }
    });

    $("#reason").change(function(){
        /*var selectedVal = $(this).find(':selected').val();
        if(selectedVal=="Others"){
            $("#reasonText").removeAttr("disabled");
            $("#reasonText").removeClass("crm-disabled");
        }
        else{
            $("#reasonText").attr("disabled","disabled");
            $("#reasonText").addClass("crm-disabled");
        }*/
    });
});