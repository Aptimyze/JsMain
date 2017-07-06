var settingsValue  = {"":"visible","Y":"visible","N":"semiVisible","C":"semiVisible"};
var field;
$(function(){
	console.log("SS");
	$('.js-showPr').click(function(){
		var getT = $(this).attr('data-title');
		$('#layerT').html(getT);	
		$('#layerT').parent().attr('id',getT);	
		$('.'+settingsValue[$(this).attr('value')]).after('<i class="fr privsel">1</i>');		

		$('.tapoverlay, .setndiv').css('display','block');
		$field = $('#layerT').html();
			
	});

	
	
	$('.js-saveOption').click(function(){
		
		getEleV = $(this).parent().parent().attr('id');
		$("div[data-title='Mobile'] div span").html($(this).find('.textV').html());

		$('.js-saveOption').each(function(){
			$(this).find('.privsel').remove('.privsel');
		});





		$('.tapoverlay, .setndiv').css('display','none');		
		$.ajax({
			method: "POST",
			url : "/api/v1/settings/privacySettings?field="+$field+"&privacy="+$(this).attr("value"),
			async:true,
			timeout:20000,
			success:function(response){

			}
		});		
		
	});
});


