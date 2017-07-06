import React from "react";
import MyjsHeadHTML from "../components/MyjsHeader";
import EditBar from "../components/MyjsEditBar";
import MyjsSlider from "../components/MyjsSliderBar";
import AcceptCount from '../components/MyjsAcceptcount';
import MyjsProfileVisitor from '../components/MyjsProfileVisitor';
import InterestExp from '../components/MyjsInterestExp';
import NodataBlock from '../components/MyjsNodataBlock';
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import {DISPLAY_PROPS}  from "../../common/constants/CommonConstants";
import * as CONSTANTS from '../../common/constants/apiConstants';
import { removeCookie } from '../../common/components/CookieHelper';
import { redirectToLogin } from '../../common/components/RedirectRouter';
import GA from "../../common/components/GA";
import Loader from "../../common/components/Loader";
import MetaTagComponents from '../../common/components/MetaTagComponents';


require ('../style/jsmsMyjs_css.css');



export class CheckDataPresent extends React.Component{
	render(){

	 if(!this.props.fetched)
		{
			return (<div className="nodatafetch"></div>)
		}

		switch (this.props.blockname) {
			case "int_exp":
						console.log('expired list');
						console.log(this.props);
						if( (this.props.data===undefined)  || (this.props.data.tuples===null))
						{
							  return (<div className="noData Intexp"></div>);
						}
						return(<InterestExp int_exp_list={this.props.data}  />);
						break;
			case "prf_visit":
						if(this.props.data.tuples===null)
						{
							return (<div className="noData prfvisit"></div>);
						}
						return(<MyjsProfileVisitor responseMessage={this.props.data}/>);
			default:
					return (<div>nodata</div>);

		}
	}
}

export  class MyjsPage extends React.Component {
	constructor(props) {
  		super();
			this.state=
			{
				irApi: false,
				modApi: false,
				ieApi: false,
				drApi: false,
				vaApi: false
			}
  	}

  	componentDidMount()
  	{
		this.props.hitApi_MYJS();
	}

	componentWillReceiveProps(nextProps)
	{
		this.callEventListner();		
		redirectToLogin(this.props.history,nextProps.myjsData.apiData.responseStatusCode);
		this.setState ({
			showLoader : false
		})
	}

	componentWillMount(){
			this.CssFix();
	}

	CssFix()
	{
			// create our test div element
			var div = document.createElement('div');
			// css transition properties
			var props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
			// test for each property
			for (var i in props) {
					if (div.style[props[i]] !== undefined) {
							var cssPrefix = props[i].replace('Perspective', '');
							this.setState({
								cssProps:{
									cssPrefix : cssPrefix,
									animProp : cssPrefix + 'Transform'
								}
					});
			}
	};
}

  	callEventListner(){
  			window.addEventListener('scroll', (event) => {
			if(!this.state.irApi){
		    	this.props.hitApi_IR();		   
		    	this.setState({
		    		irApi: true
		    	});
		    }
		    if(!this.state.modApi){
		    	this.props.hitApi_MOD();		   
		    	this.setState({
		    		modApi: true
		    	});
		    }
		    if(!this.state.drApi){
		    	this.props.hitApi_DR();		   
		    	this.setState({
		    		drApi: true
		    	});
		    }
		    if(!this.state.vaApi){
		    	this.props.hitApi_VA();		   
		    	this.setState({
		    		vaApi: true
		    	});
		    }
		    if(!this.state.ieApi){
		    	this.props.hitApi_IE();		   
		    	this.setState({
		    		ieApi: true
		    	});
		    }
		    if(!this.state.ieApi){
		    	this.props.hitApi_IE();		   
		    	this.setState({
		    		ieApi: true
		    	});
		    }
		    if(!this.state.hamApi){
		    	this.props.hitAPi_Ham();		   
		    	this.setState({
		    		hamApi: true
		    	});
		    }			   
		});
  	}
  	render() {

  			if(!this.props.myjsData.fetched)
	        {
	          return (<div><Loader show="page"></Loader></div>)
	        }
  		return(
  		<div id="mainContent">
		  	<MetaTagComponents page="MyjsPage"/>
		  		<GA ref="GAchild" />
				  <div className="perspective" id="perspective">
								<div className="" id="pcontainer">
									<MyjsHeadHTML bellResponse={this.props.myjsData.apiData.BELL_COUNT} fetched={this.props.myjsData.fetched}/>
									<EditBar cssProps={this.state.cssProps}  profileInfo ={this.props.myjsData.apiData.my_profile} fetched={this.props.myjsData.fetched}/>
									<AcceptCount fetched={this.props.myjsData.hamFetched} acceptance={this.props.myjsData.apiData.all_acceptance} justjoined={this.props.myjsData.apiData.just_joined_matches}/>																		
									<CheckDataPresent fetched={this.props.myjsData.ieFetched} blockname={"int_exp"} data={this.props.myjsData.apiData.interest_expiring}/>
									<MyjsSlider cssProps={this.state.cssProps} fetched={this.props.myjsData.irFetched} displayProps = {DISPLAY_PROPS} title={this.state.IR} listing ={this.props.myjsData.apiData.interest_received} listingName = 'interest_received' />
									<MyjsSlider cssProps={this.state.cssProps} fetched={this.props.myjsData.modFetched} displayProps = {DISPLAY_PROPS} title={this.state.MOD} listing ={this.props.myjsData.apiData.match_of_the_day} listingName = 'match_of_the_day' />
									<CheckDataPresent fetched={this.props.myjsData.vaFetched} blockname={"prf_visit"} data={this.props.myjsData.apiData.visitors}/>
									<MyjsSlider cssProps={this.state.cssProps} fetched={this.props.myjsData.drFetched} displayProps = {DISPLAY_PROPS} title={this.state.DR} listing ={this.props.myjsData.apiData.match_alert} listingName = 'match_alert' />							</div>
							</div>
			</div>
		);
	}

}

const mapStateToProps = (state) => {
    return{
       myjsData: state.MyjsReducer,
			 listingData :  state.listingReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        hitApi_MYJS: () => {
            dispatch(commonApiCall(CONSTANTS.MYJS_CALL_URL,{},'SET_MYJS_DATA','POST'));
        },
     	hitApi_DR: () => {
            dispatch(commonApiCall(CONSTANTS.MYJS_CALL_URL,'&searchBasedParam=matchalerts&&caching=1&&timestamp=1499147929.097&&myjs=1','SET_DR_DATA','POST'));
        },
     	hitApi_MOD: () => {
            dispatch(commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=24&pageNo=1&&caching=1&timestamp=1499153571.789&&myjs=1','SET_MOD_DATA','POST'));
        },
  		hitApi_IR: () => {
            dispatch(commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=1&pageNo=1&timestamp=1499153669.566&&myjs=1','SET_IR_DATA','POST'));
        },
        hitApi_VA: () => {
            dispatch(commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=5&pageNo=1&matchedOrAll=A&caching=1&timestamp=1499153727.102&&myjs=1','SET_VA_DATA','POST'));
        },
        hitApi_IE: () => {
            dispatch(commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=23&pageNo=1&caching=1&timestamp=1499153828.047&&myjs=1','SET_IE_DATA','POST'));
        },	
        hitApi_Ham: () => {
            dispatch(commonApiCall('/common/hamburgerCounts',{},'SET_IE_DATA','POST'));
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(MyjsPage)
