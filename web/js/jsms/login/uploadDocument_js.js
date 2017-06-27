var change_color=0;
$(document).ready(function()
{
        if(done == "Y"){
                ShowTopDownError([$(".errmsg").html()]);
                setTimeout(function(){ window.location.href= "/"; }, 2000);
                
        }
        $("#MSTATUS_PROOF").on("change",function(event){
		var MSTATUS_PROOF = $(this)[0];
                var errorMes = ValidateDoc(MSTATUS_PROOF);
                if(errorMes != ""){
                        $("#label_keyMSTATUS_PROOF").html("jpg/pdf only");
                        $("#saveBtn").addClass("opa50");
                        ShowTopDownError([errorMes]);
                }else{
                        $("#label_keyMSTATUS_PROOF").html(MSTATUS_PROOF.files[0].name);
                        $("#saveBtn").removeClass("opa50");
                }
	});
        $("#saveBtn").click(function(){
                var errorMes="";   
		var MSTATUS_PROOF = $("#MSTATUS_PROOF")[0];
                var errorMes = ValidateDoc(MSTATUS_PROOF);
                if(errorMes)
                {
                        $("#label_keyMSTATUS_PROOF").html("jpg/pdf only");
                        $("#saveBtn").addClass("opa50");
                        ShowTopDownError([errorMes]);
                }else{
                        $("#uploadDocForm").submit();
                }
        });
});

function ValidateDoc(thisObj){
        var MSTATUS_PROOF = thisObj;
        var errorMes = "";
        if(typeof MSTATUS_PROOF.files == 'undefined' || typeof MSTATUS_PROOF.files[0] == 'undefined' || MSTATUS_PROOF.files[0] == null){
                errorMes="Invalid file";
                return errorMes;
        }
        var file = MSTATUS_PROOF.files[0];
        if (file && file.name.split(".")[1] == "jpg" || file.name.split(".")[1] == "JPG" || file.name.split(".")[1] == "jpeg" || file.name.split(".")[1] == "JPEG" || file.name.split(".")[1] == "PDF" || file.name.split(".")[1] == "pdf") {
        } else {
                errorMes="Please upload only jpg/pdf file";
                return errorMes;
        }
        if(file.size > 5242880) {
                errorMes="File size should be less than 5MB";
                return errorMes;
        }
        return errorMes;
}