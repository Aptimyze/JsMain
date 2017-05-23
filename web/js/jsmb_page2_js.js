$(document).ready(function () {
	if(genderVal=='F')
	{
		$("#reg_mstatus option[value='M']").remove();
		$('#have_child_section').css('display','none');
	}
	$("#reg").validate({
		onkeyup : false,
				onfocusout: function(element) {
					$(element).valid(); 
		},
		});
$("#reg_religion").rules("add",{
	required:true,
	messages:
	{
		required:$('#religion_required').html()
	}
});
$("#reg_caste").rules("add",{
    checkCaste : true
});
$('#reg_mstatus').rules("add",{
	  mstatusMarried : true,
	  required: true,
	  messages:
	  {
		  required:$("#mstatus_required").html()
	  }
	});
$('#reg_mstatus').change(function(){
	if(this.value!='N' && this.value!=''){
		
			$('#have_child_section').css('display','block');
			 // dID("has_children").focus();
						 //dID("mtongue_submit_err").style.display="none";
		//	SetSelectDropdown($('#reg_havechild'));
		}else
		{
			$('#have_child_section').css('display','none');
			$("#reg_havechildren").value='';
		}
}
);
$('#reg_havechild').rules("add",{
haveChild : true
});	
$('#reg_height').rules("add",{
    required:true,
    messages:
    {
        required:$('#height_required').html()
    }    
});
$('#reg_religion').change(function(){
	if(this.value=='2' || this.value=='3')
		$("#caste_section").find(".lblStyl").html("Sect :");
	else
		$("#caste_section").find(".lblStyl").html("Caste :");	
	});
});
