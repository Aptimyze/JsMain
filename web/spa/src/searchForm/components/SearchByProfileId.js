import React from "react";
import * as CONSTANTS from '../../common/constants/apiConstants';
import {getCookie} from '../../common/components/CookieHelper';
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import TopError from "../../common/components/TopError";
import {Link} from "react-router-dom";
import GA from "../../common/components/GA";
import {
  Redirect
} from "react-router-dom";
class SearchByProfileId extends React.Component {
	constructor(props) {
		super();
		this.GAObject = new GA();
		this.state = {
            insertError: false,
            validUsername: false,
            username: '',
            errorMessage: "Please provide a profile ID"
        };
        if(getCookie("AUTHCHECKSUM")) 
            this.currentPageName = 'JSMS_SEARCH_BY_ID';
        else
            this.currentPageName = 'JSMS_SEARCH_BY_ID_LOGOUT';

	}

	componentDidMount()
	{
		let _this = this;
		//this was added to add background color of white on the page after calculating the height.
		let inputDivHeight = (window.innerHeight-document.getElementById("overlayHead").clientHeight-document.getElementById("foot").clientHeight)+"px";
		document.getElementById("inputDiv").style.height = inputDivHeight;
		document.getElementById("inputDiv").classList.add("bg4");
		
        _this.GAObject.trackJsEventGA("jsms","new","1","",this.currentPageName);

	}
	handleSearchByProfileID(e)
	{
		let usernameInputValue = document.getElementById("searchPId").value.trim();
		if ( usernameInputValue )
		{
			this.setState({
				validUsername:true,
				username:usernameInputValue
			});
		}
		else
		{
			this.setState({
				insertError:true
			});
		}
	}

  componentWillUnmount(){
      document.getElementById('searchPId').blur()    
  }


	render()
	{
		let errorView,redirectView;
        if(this.state.insertError == true)
        {
          errorView = <TopError message={this.state.errorMessage}></TopError>;
        }
        if ( this.state.validUsername )
        {
        	this.props.history.push("../profile/viewprofile.php?stype=WO&username="+this.state.username+(localStorage.getItem('USERNAME')==this.state.username ? '&preview=1':""));
          // redirectView = <Redirect to={}/>;
        }
		 return (
		      <div>
		      	{redirectView}
		        <div id="overlayHead" className="bg1">
		      	{errorView}
		          <div className="txtc pad15">
		            <div className="posrel">
		              <div className="fontthin f19 white">Search by Profile ID</div>

		             	 <i id="closeFromSearchByProfileId" className=" posabs mainsp srch_id_cross " onClick={()=>history.back()} style={{right: 0, top: 0}} />
		            </div>
		          </div>
		        </div>
		        <div id="inputDiv">
		          <div id="inputProfileId" className=" " style={{padding: '29%'}}>
		            <input id="searchPId" type="textbox" className="f20 fontthin color11 fullwid" name="searchPid" placeholder="Enter Profile ID" autoFocus/>
		          </div>
		          <div id="foot" className="posfix fullwid bg7 btmo">
		            <div className="scrollhid posrel">
		              <input type="submit" value="Search" onClick={this.handleSearchByProfileID.bind(this)} id="searchByProfileID" className="fullwid dispbl lh50 txtc f16 pinkRipple white"/>
		            </div>
		          </div>
		        </div>
		      </div>
		    );
	}
}
export default SearchByProfileId;
