require ('../../searchForm/style/searchForm.css')
import React from "react";
import TopError from "../../common/components/TopError"
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import GA from "../../common/components/GA";
import * as jsb9Fun from '../../common/components/Jsb9CommonTracking';
import HamMain from "../../Hamburger/containers/HamMain";
import {getCookie} from '../../common/components/CookieHelper';
import AppPromo from "../../common/components/AppPromo";
import axios from "axios";
import * as CONSTANTS from '../../common/constants/apiConstants';
let API_SERVER_CONSTANTS = require ('../../common/constants/apiServerConstants');
import SearchFormPage from '../../searchForm/containers/SearchFormPage.js';

export default class DesiredPartnerProfile extends React.Component{
	constructor(props) {
       super(props);

       this.state = {
       	dppData : [],
       };
    }
    componentDidMount()
    {
    	/*let _this = this;
            	let callUrl = CONSTANTS.DPP_REG_API+"&AUTHCHECKSUM="+getCookie("AUTHCHECKSUM");
            	commonApiCall(callUrl,'','','POST').then(function(response) {
          			_this.getDppData(response);
            	});*/
    }

    /*getDppData(dataArr)
    {
    	for(let i=0;i<dataArr.length;i++)
    	{
    		if(DPP_FIELDS.includes(dataArr[i].key))
    		{

    		}
    	}
    }*/
    render()
    {
			// console.log("dpp");
			// console.log(this.props);
			// console.log(history);
    	let dppPage = <div><SearchFormPage dppReg="1" history={this.props.history}></SearchFormPage></div>;
    	return(
    		<div>{dppPage}</div>
    		);
    }
}
