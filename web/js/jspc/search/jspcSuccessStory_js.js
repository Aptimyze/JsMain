/** 
* This function will send ajax request related to success story data for vsp.
* @param : none
*/
function sendSuccessStoryDataRequest() 
{
    var url = '/successStory/getSuccessStoryData';
    
    $.myObj.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        timeout: 60000,
        beforeSend: function( xhr ) 
        {               
        },

        success: function(response) 
        {
            loadSuccessStoryTuples(response);         
        },
        error: function(xhr) 
        {
            console.log("error"); //LATER
            return "error";
        }
    });
    return false;
}

/** 
* load success story tuples
* @param : response
*/
function loadSuccessStoryTuples(response)
{
    var tupleStructure = $("#successStoryBasicdiv").html(),ssTupleHtml="",contentHtml="",mapObj="";
    var mainContentStructure = $("#successStoryMainStructure").html();
    var tupleNo;
    $.each(response.stories,function( key, val ){
           tupleNo = key +1;
            mapObj = successStoryBasicTupleMapping(val,tupleNo);
            ssTupleHtml+ = $.ReplaceJsVars(tupleStructure,mapObj);
    });
    if(ssTupleHtml)
    {
        mapObj = successStoryContentMapping(ssTupleHtml);
        contentHtml = $.ReplaceJsVars(mainContentStructure,mapObj);
        if(contentHtml)
        {
            $("#successStoryMaindiv").append(contentHtml);
        }
    }
    //align images to container
    alignSuccessStoryImages();
}

/** 
 * Function for mapping each tuple values of success story
 * 
 */

function successStoryBasicTupleMapping(val,tupleNo)
{
    var mapping = {
    'data="{picUrl}"': "src='"+removeNull(val.vspSSPicUrl)+"'",
    '{name1}': removeNull(val.NAME1),
    '{name2}': removeNull(val.NAME2),
    '{vspSuccessImgClass}':"vspSuccessImgCover",
    '{storyTuple}': "story"+tupleNo,
    '{year}':removeNull(val.YEAR),
    '{sid}':removeNull(val.SID)
    };
    return mapping;
}

/** 
 * Function for mapping content of success story
 * @param : successStoryData
 */

function successStoryContentMapping(successStoryData)
{
    var mapping = {
    '{title}': "Success Stories",
    '{successStories}': removeNull(successStoryData)
    };
    return mapping;
}

/** 
 * Function to align success story pics to container
 * @param : none
 */
function alignSuccessStoryImages()
{
    $(".vspSuccessImgCover").addClass("imgLiquidFill imgLiquid");
    $(".imgLiquidFill").imgLiquid();
}
