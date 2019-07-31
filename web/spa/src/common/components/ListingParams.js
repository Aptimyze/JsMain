import * as CONSTANTS from '../../common/constants/apiConstants';
import {getParameterByName} from '../../common/components/UrlDecoder';
import {getCookie,setCookie} from '../../common/components/CookieHelper';
import { getListingIdFromParams } from "../../common/components/commonFunctions";
import {getSearchParameters} from "../../register/helpers/dataPreprocessor";



var ListingParams = (
	function () {
		var getListingAPI = function (props) 
		{
			var listingParam;
			let tempListingId = getListingIdFromParams(props);
			if ( tempListingId == 'justjoined')
			{
				tempListingId = 'justJoinedMatches';
			}

			if (window.location || props) {
				if (window.location.pathname === "/search/criteoProfile" ||
					props.location.pathname === "/search/criteoProfile") {
					tempListingId = 'criteoProfile';
				}
			}

			let tempMySavedSearchId = getParameterByName(window.location.href,"mySaveSearchId");
			//this check is used for when QuickSearchBand listing is used.
			if(tempMySavedSearchId)
			{
				listingParam = CONSTANTS.SEARCH_PAGE_LISTING_API;
				listingParam +="?mySaveSearchId="+tempMySavedSearchId;		
				return listingParam;	
			}
			if(tempListingId == "QuickSearchBand")
			{
				listingParam = CONSTANTS.SEARCH_PAGE_LISTING_API;
				if(typeof props.history.searchFormData !== 'undefined') //if the user comes directly from search
				{
					listingParam += "?QuickSearchBand=1";
					//delete props.history.searchFormData;
					return listingParam;	
				}
				else //if the user refreshes the page
				{	
					let searchid = getCookie("JSSearchId");					
					if ( searchid && listingParam.indexOf('searchId') == -1 && props.location.search.indexOf("sort_logic") == -1)
					{
						listingParam += "?searchId="+searchid;
					}
					else if(props.location.search.indexOf("sort_logic")!=-1)
					{					
						listingParam += appendSortLogic(listingParam,props.location.search);
						listingParam += appendSearchId(listingParam);
					}
					else{
						listingParam += "?QuickSearchBand=1";
						return listingParam;	
					}
									
				}
			}
			
			else if ( props.match.params[0] == 'inbox' || tempListingId == 'shortlisted' || tempListingId == 'visitors')
			{
				let listingId = tempListingId;
				if ( listingId == 'shortlisted')
					listingId = 8;
				if ( listingId == 'visitors' )
				{
					//console.log("matchedOrAll",getParameterByName(window.location.href,"matchedOrAll"));
					//console.log("window.location.href",window.location.href);
					// let url = getParameterByName(window.location.href,"matchedOrAll")
					listingId = 5+"&matchedOrAll="+getParameterByName(window.location.href,"matchedOrAll");
				}
			    listingParam = CONSTANTS.INBOX_LISTING_API+listingId;
			}
			else if ( props.match.params[0] == 'inbox' || tempListingId == 'MobSimilarProfiles' )
			{
				let param1 = "fromAccept";
				let params = getParameterByName(window.location.href,param1);
				params = params ? param1+"=1" :"";
				listingParam = CONSTANTS.SIMILAR_PROFILE_API + "?profilechecksum=" + getParameterByName(window.location.href,"profilechecksum")+"&" +params;
			}
			else //this case is for other listings where sort Icon is to be shown
			{				
	  		  	listingParam = CONSTANTS.SEARCH_LISTING_API+tempListingId;
	  		  	if(props.location.search.indexOf("sort_logic")!=-1)
	  		  	{
	  		  		listingParam += appendSortLogic(listingParam,props.location.search);
	  		  	}
	  		  	if(window.location.href.indexOf("searchId")!=-1)
	  		  	{
	  		  		listingParam += appendSearchId(listingParam);
	  		  	}

			}

			switch(tempListingId)
			{

				case 'partnermatches':
					//console.log("PARTNER MATCHESprops.history",props.history)
					listingParam += '&results_orAnd_cluster=';
				//results_orAnd_cluster=onlyResults
					break;
				case 'twowaymatch':
				case 'reverseDpp':
					listingParam += '&results_orAnd_cluster=';
				//results_orAnd_cluster=onlyResults
					break;
				case 'kundlialerts':
					listingParam += '&kundlialerts=1&results_orAnd_cluster=onlyResults';
				//results_orAnd_cluster=onlyResults
					break;
				case 'verifiedMatches':
					listingParam += '&results_orAnd_cluster=';
				//results_orAnd_cluster=onlyResults
					break;
				case '7':
					listingParam += '&results_orAnd_cluster=onlyResults';
				//results_orAnd_cluster=onlyResults
					break;
				// case '5': //searchid matchedOrAll=A profilevisitors
				// 	listingParam += '&matchedOrAll=A';
				// 	break;
				case '8': //shortlisted.
				case '16': //phonebook.
				case '4': //message.

				case 'matchalerts':
					listingParam += '&results_orAnd_cluster=';
					break;
				default:
					break;
			}
			if(tempListingId === "criteoProfile"){
				let pSum = getSearchParameters();
				pSum = pSum ? pSum.profilechecksum : '';
				listingParam = `${CONSTANTS.CRITEO_LISTING_API}&profilechecksum=${pSum}`
			}
			return listingParam;
		}

		//this function is used to append sort logic into listing param
		var appendSortLogic = function (listingParam,locationSearch)
		{
			//console.log("APPEND SORT LOGIC");
			if(listingParam.indexOf("?") == -1)
			{
				return("?"+locationSearch.substr(1).split("&")[0]);
			}
			else
			{
				return("&"+locationSearch.substr(1).split("&")[0]);	
			}			
		}
		//this function is used to append search id with listing param. commented since it may not be required.
		var appendSearchId = function (listingParam)
		{
			//console.log("APPEND SEARCH ID");
			if(listingParam.indexOf("?") == -1)
			{
				return ("?searchId="+getParameterByName(window.location.href,"searchId"));
			}
			else
			{
				return ("&searchId="+getParameterByName(window.location.href,"searchId"));
			}
		}
		return {
			getListingAPI:getListingAPI
		}
	}
)();
export default ListingParams;