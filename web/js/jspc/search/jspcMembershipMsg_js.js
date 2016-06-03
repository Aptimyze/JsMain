/** 
* This function will send ajax request related to membership data.
* @param : none
*/
function sendMembershipDataRequest() 
{
    var url = '/membership/getMembershipMessageData';
    
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
            loadMembershipMsgData(response);         
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
function loadMembershipMsgData(response)
{
    var benefitsStructure = $("#membershipBenefitsDiv").html(),benefitsHtml="",mapObj="",contentHtml="";
    var mainContentStructure = $("#membershipMsgStructure").html();
    $.each(response.benefits,function(key,val)
    {
        mapObj = membershipBenefitsDataMapping(val);
        benefitsHtml+ = $.ReplaceJsVars(benefitsStructure,mapObj);
    });
    if(benefitsHtml)
    {
        mapObj = membershipContentMapping(response,benefitsHtml);
        contentHtml = $.ReplaceJsVars(mainContentStructure,mapObj);
        if(contentHtml)
        {
            $("#membershipMsgMainDiv").append(contentHtml);
        }
    }    
}

/** 
 * Function for mapping each benefit of membership section
 * 
 */

function membershipBenefitsDataMapping(val)
{
    var mapping = {
    '{benefit}': removeNull(val)
    };
    return mapping;
}

/** 
 * Function for mapping content of success story
 * @param : data,benefitsHtml
 */

function membershipContentMapping(data,benefitsHtml)
{
    var upgradeButtonshow = "";
    if(data.memIDString != "FREE")
        upgradeButtonshow = "disp-none";
    var mapping = {
    '{title}': "Membership",
    '{heading}': removeNull(data.heading),
    '{subHeading}':removeNull(data.subHeading),
    '{benefitsList}':removeNull(benefitsHtml),
    '{expiryInfo}':data.expiryInfo,
    '{upgradeButtonshow}':upgradeButtonshow
    };
    return mapping;
}