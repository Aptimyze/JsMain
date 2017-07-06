var settingsValue  = {"":"visible","Y":"visible","N":"semiVisible","C":"semiVisible"};
var settingsValuePv  = {"":"visible","A":"visible","F":"semiVisible","C":"notVisible"};
var settingsValuePp  = {"":"visible","A":"visible","C":"semiVisible"};

var getT;
$(function(){	
	$('.js-showPr').click(function(){
		getT = $(this).attr('data-title');
		var fieldWithSpace = getT.replace("_", " ");
		$('#layerT').html(fieldWithSpace);	
		$('#layerT').parent().attr('id',getT);	
		$('.'+settingsValue[$(this).attr('value')]).after('<i class="fr privsel"></i>');		
		$('.tapoverlay, .showT').css('display','block');		
	});

	$('.js-showPv').click(function(){
		getT = $(this).attr('data-title');		
		var fieldWithSpace = getT.replace("_", " ");
		$('#layerPv').html(fieldWithSpace);	
		$('#layerPv').parent().attr('id',getT);	
		$('.'+settingsValuePv[$(this).attr('value')]).after('<i class="fr privsel"></i>');		
		$('.tapoverlay, .showpv').css('display','block');		
	});

	$('.js-showPp').click(function(){
		getT = $(this).attr('data-title');		
		var fieldWithSpace = getT.replace("_", " ");
		$('#layerPp').html(fieldWithSpace);	
		$('#layerPp').parent().attr('id',getT);	
		$('.'+settingsValuePp[$(this).attr('value')]).after('<i class="fr privsel"></i>');		
		$('.tapoverlay, .showPp').css('display','block');		
	});
	
	$('.js-saveOption').click(function(){		
		getEleV = $(this).parent().parent().attr('id');
		getEleatt = $(this).attr('value');
		var _this= $(this);
		
			
		$.ajax({
			method: "POST",
			url : "/api/v1/settings/privacySettings?field="+getEleV+"&privacy="+getEleatt,
			data : ({dataType:"json"}),
			async:true,
			timeout:20000,
			success:function(data, textStatus, xhr){
				data = JSON.parse(data);				
				if(data.responseStatusCode == 0)
				{					
					$("div[data-title="+getEleV+"] div span").html(_this.find('.textV').html());
					$("div[data-title="+getEleV+"]").attr('value',getEleatt);	
				}				
				$('.js-saveOption').each(function(){
					$(this).find('.privsel').remove('.privsel');
				});
				$('.tapoverlay, .setndiv').css('display','none');	
			}
		});		
		
	});

});


