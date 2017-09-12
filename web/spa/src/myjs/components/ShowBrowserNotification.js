import React from 'react';
import * as CONSTANTS from '../../common/constants/apiConstants';
import { commonApiCall } from "../../common/components/ApiResponseHandler";



export default class ShowBrowserNotification extends React.Component
{
	notificationLayerActionBrowser(option){
		var url = `&active=${option}`;
    	return commonApiCall(CONSTANTS.BROWSWER_NOTIFICATION,url,'','POST').then((response) =>
		{
			document.getElementById("notifBar").className = " dn";
			if ( option == "Y" )
			{
				window.open('/notification/notify', '_blank' );
			}
		});
    }


	render()
	{
		return(
			<div id="notifBar" className="fullwid posfix hgt180 btm0 boxshadow_new z1000 bg4">
			   <div className="f20 fb fontlig color7 txtc padActi">Activate Notifications</div>
			   <div className="color7 f18 fontlig txtc">Jeevansathi would like to notify you about new matches, interests and acceptances</div>
			   <div className="posabs btm0 dispib bg7 white f19 fontthin txtc padd22 mt15 wid497p" onClick={()=> this.notificationLayerActionBrowser("Y")} >Allow</div>
			   <div className="posabs btm0 dispib bg7 white f19 fontthin txtc padd22 mt15 wid50p" onClick={()=> this.notificationLayerActionBrowser("N")} style={{right:"0px"}}>Not Now</div>
			</div>
		);
	}
}