import React from "react";
import { removeCookie } from '../../common/components/CookieHelper';

export default class LogoutPage extends React.Component{

	constructor(props){
		super(props);
	}

	componentDidMount(){
		removeCookie("AUTHCHECKSUM");
    	this.props.history.push('/login');
	}

	render(){
		return null;
	}
}