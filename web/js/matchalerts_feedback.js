var validForm, optionSubmitted=[];
	$(document).ready(function(e) {
		$("input").on("click",function(){
			$(this).siblings().each(function(index, element) {
                if($(element).hasClass("errorDiv")) {
					$(element).addClass("dn");
				}
            });
			if($(this).attr("value") == "3" && $(this).is(':checked')) {
				$("#secondInput").removeClass("dn");
			} else if($(this).attr("value") == "3" && !$(this).is(':checked')) {
				$("#secondInput").addClass("dn");
			}
		});
		$("#submitform").submit(function(event){
			validForm = true;
			optionSubmitted[0] = false;
			$("#ques1 input").each(function(index, element) {
                if($(element).is(':checked')) {
					optionSubmitted[0] = true;
				}
            });
			if(optionSubmitted[0] == false) {
				$("#error1").html("Please select atleast one option").removeClass("dn");
				validForm = false;	
			}
			optionSubmitted[1] = false;
			$("#ques2 input").each(function(index, element) {
                if($(element).is(':checked')) {
					optionSubmitted[1] = true;
				}
            });
			if(optionSubmitted[1] == false) {
				$("#error2").html("Please select atleast one option").removeClass("dn");
				validForm = false;	
			} 
			optionSubmitted[2] = false;
			if($("#inpFeild2").val().length != 0){
				optionSubmitted[2] = true;
			} else if(!$("#secondInput").hasClass("dn")){
				$("#error3").html("Please enter some data").removeClass("dn");	
				validForm = false;			
			}
			if(validForm == false) {
			  event.preventDefault();
			}
		});
	});