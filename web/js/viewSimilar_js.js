var NAVIGATOR,DATA,NO_OF_RESULTS;
;var counter,profileData,urls,length1;
var remaining='';
var actionLink,fromViewSimilar;

$(function()
{
	if(NAVIGATOR)
		fromViewSimilar=1;
	else
		fromViewSimilar=2;

	var numRand = Math.floor(Math.random()*101)

	$.ajax(
	{
		url: "/profile/getSimilarProfiles.php",
		data: DATA,
		//timeout: 5000,

		success: function(response)
		{
			var data = response.split("##");
			if(data != 'noResultsFound' && data!='A_E')
			{
				$('#similar_1').show();
				profileData = data[0].split("--");
				urls = data[1].split("--");
				profileURL = data[2].split("--");
				var id1,id2,id3,id4;
				length1 = profileData.length - 1;
				remaining=profileData.length;
				for(var i=0;i<=length1;i++)
				{
					if(i<NO_OF_RESULTS)
					{
						id1='#simi'+i;
						id2='#sim'+i;
						id3='#thumbnail'+i;
						id4='#simProfUrl'+i;
						id5='#photoUrl'+i;
						actionLink=profileURL[i]+'&fromViewSimilar='+fromViewSimilar+'&'+NAVIGATOR+'&stype=30';
						$(id1).show();
						$(id2).html(profileData[i]);
						$(id3).attr('src',urls[i]);
						$(id4).attr('href',actionLink);
						$(id5).attr('href',actionLink);
						counter=i;
					}
					else
						break;
				}
			}
		}
	});
	var id;
	$(".no-grey-box").mouseover(function()
	{
		$(this).addClass("box-grey-shadow");
		$(this).removeClass("no-grey-box");
		id = '#img_'+this.id;
		$(id).attr('src','IMG_URL/images/close.jpg');
		$(id).css( 'cursor', 'pointer' );
		$(id).height(18);
		$(id).width(19);
	});
	$(".no-grey-box").mouseout(function()
	{
		$(this).addClass("no-grey-box");
		$(this).removeClass("box-grey-shadow");
		id = '#img_'+this.id;
		$(id).attr('src','IMG_URL/images/close-grey.jpg');
		$(id).css( 'cursor', 'default' );
		$(id).height(7);
		$(id).width(9);
	});
	$("img[name*='close']").mouseover(function()
	{
		$(id).css( 'cursor', 'pointer' );
		$(id).css( 'text-decoration', 'none' );
	});
	$("img[name*='close']").mouseover(function()
	{
		$(id).css( 'cursor', 'default' );
	});
	$("img[name*='close']").click(function()
	{
		var val=this.id.replace('img_simi','');
		remaining=remaining-1;
		if(counter < length1)
		{ 
			id1='#sim'+val;
			id2='#thumbnail'+val;
			id3='#simProfUrl'+val;
			id4='#photoUrl'+val;
			actionLink=profileURL[counter+1]+'&fromViewSimilar='+fromViewSimilar+'&'+NAVIGATOR+'&stype=30';
			$(id1).html(profileData[counter+1]);
			$(id2).attr('src',urls[counter+1]);
			$(id3).attr('href',actionLink);
			$(id4).attr('href',actionLink);
			counter=counter+1;
		}
		else
		{
			id1='#simi'+val;
			$(id1).remove();
		}
		if(remaining==0)
		{
			$('#similar_1').remove();
			$('#similar_b').remove();
		}
	});
});
