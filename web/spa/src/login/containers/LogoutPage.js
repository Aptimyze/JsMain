import React from "react";
import { removeCookie } from '../../common/components/CookieHelper';
import axios from "axios";
import * as API_SERVER_CONSTANTS from '../../common/constants/apiServerConstants'

export default class LogoutPage extends React.Component{

	constructor(props){
		super(props);
	}

	componentDidMount(){
        axios.get(API_SERVER_CONSTANTS.API_SERVER+"/static/logoutPage")
        .then(function(response){
            removeCookie("AUTHCHECKSUM");
            // localStorage.clear();
            window.location.href="/login";
        })
	}

	render(){
		return null;
	}
}