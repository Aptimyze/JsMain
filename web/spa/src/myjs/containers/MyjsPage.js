import React from "react";
import MyjsHeadHTML from "../components/MyjsHeader";
import EditBar from "../components/MyjsEditBar";
import MyjsSlider from "../components/MyjsSliderBar";
import AcceptCount from '../components/MyjsAcceptcount';
import MyjsProfileVisitor from '../components/MyjsProfileVisitor';
import InterestExp from '../components/MyjsInterestExp';
import NodataBlock from '../components/MyjsNodataBlock';
import MyjsOcbLayer from "../components/Myjsocb";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import {DISPLAY_PROPS}  from "../../common/constants/CommonConstants";
import * as CONSTANTS from '../../common/constants/apiConstants';
import { removeCookie } from '../../common/components/CookieHelper';
import { redirectToLogin } from '../../common/components/RedirectRouter';
import GA from "../../common/components/GA";
import Loader from "../../common/components/Loader";
import MetaTagComponents from '../../common/components/MetaTagComponents';
import CalObject from '../../cal/components/CalObject';
import * as jsb9Fun from '../../common/components/Jsb9CommonTracking';
import AppPromo from "../../common/components/AppPromo";
import ShowBrowserNotification from '../components/ShowBrowserNotification';


require ('../style/jsmsMyjs_css.css');



export class CheckDataPresent extends React.Component{

constructor(props){
	super(props);
}
	componentDidMount(){
	this.props.restApiFun();	
}
	render(){


	 if(!this.props.fetched)
		{
			return (<div className="nodatafetch"></div>)
		}

		switch (this.props.blockname) {
			case "int_exp":
						if( (this.props.data===null)  || (this.props.data.profiles===null))
						{
							  return (<div className="noData Intexp"></div>);
						}
						return(<InterestExp int_exp_list={this.props.data}/>);
						break;
			case "prf_visit":
						if(this.props.data.profiles===null)
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
		jsb9Fun.recordBundleReceived(this,new Date().getTime());
		this.state=
		{
			myjsApi: false,
			hamApi: false,
			showPromo: false,
			deviceHeight: window.innerHeight
		}
  	}

  	componentDidMount()
  	{
  		if(this.props.myjsData.timeStamp==-1 || ( (new Date().getTime() - this.props.myjsData.timeStamp) > this.props.myjsData.apiData.cache_interval) ){ // caching conditions go here in place of true
			this.props.resetTimeStamp();
			this.firstApiHits(this);
			this.ieApi = false;
			this.irApi = false;
			this.modApi = false;
			this.vaApi = false;
			this.drApi = false;			
		}
		else {
			if(!this.props.myjsData.fetched || !this.props.myjsData.hamFetched){
				this.firstApiHits(this);
			}
		}
	}


	componentDidUpdate(){
		if(this.props.myjsData.modFetched){
			this.restApiHits(this);
		}
		this.callEventListner();
		jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
	}

	componentWillReceiveProps(nextProps)
	{
		if(nextProps.myjsData.hamFetched && nextProps.myjsData.fetched && !nextProps.myjsData.ieFetched){
			this.restApiHits(this);
		}

		redirectToLogin(this.props.history,nextProps.myjsData.apiData.responseStatusCode);
		this.setState ({
			showLoader : false
		})
		if(nextProps.myjsData.apiData.appPromotion == true && this.state.showPromo == false) {
			this.setState ({
                showPromo : true
            });
		}
	}

	isScreenFull(){
		let current = 0 ;
		if(document.getElementById('perspective')){
			current = document.getElementById('perspective').clientHeight;
		}
		if(current>this.state.deviceHeight)
			return 1;
		else
			return 0;
	}

	removePromoLayer()
    {
        this.setState ({
            showPromo : false
        });
        document.getElementById("mainContent").classList.remove("ham_b100");
    }

	componentWillMount(){
			this.CssFix();
	}

	componentWillUnmount(){
		this.props.jsb9TrackRedirection(new Date().getTime(),this.url);
	}

	CssFix(){
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
   		window.addEventListener('scroll', (event) => {this.restApiHits()});
  	}

  	firstApiHits(){
  		if(!this.state.myjsApi){
		    this.props.hitApi_MYJS();
		    this.setState({
		    	myjsApi: true
		    });
		}
  		if(!this.state.hamApi){
		    this.props.hitApi_Ham();
		    this.setState({
		    	hamApi: true
		    });
		}
  	}

  	restApiHits(){
  		if(this.isScreenFull() && event.type != "scroll" && !this.props.myjsData.modFetched){
  			 return;
  		}
   		if(!this.props.myjsData.ieFetched){
   			if(!this.ieApi){
   				this.ieApi = true;
		 		this.props.hitApi_IE();
			}
		}
  		else if(!this.props.myjsData.irFetched){
  			if(!this.irApi){
  				this.irApi = true;
		    	this.props.hitApi_IR();
		    }
		}
  		else if(!this.props.myjsData.modFetched){
  			if(!this.modApi){
  				this.modApi = true;
		    	this.props.hitApi_MOD();
		    }
		}
		else if(!this.props.myjsData.vaFetched){
			if(!this.vaApi){
				this.vaApi = true;
		    	this.props.hitApi_VA();
		    }
		}
 		else if(!this.props.myjsData.drFetched){
 			if(!this.drApi){
		    	this.drApi = true;
		    	this.props.hitApi_DR();
		    }
		}
  	}

		hitIRforPagination(){
			if(this.props.myjsData.apiDataIR.nextpossible!='true' || this.props.myjsData.apiDataIR.paginationHit)return;
			this.props.myjsData.apiDataIR.paginationHit = true;
			var nextPage = parseInt(this.props.myjsData.apiDataIR.page_index);
			nextPage++;
			this.props.hitApi_IR(nextPage);
		}
  	render() {
		var promoView;
        if(this.state.showPromo == true)
        {
            promoView = <AppPromo parentComp="others" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }

  		if(!this.props.myjsData.fetched){
	         return (<div><Loader show="page"></Loader></div>)
	    }
			if(this.props.myjsData.apiData.calObject && !this.props.myjsData.calShown){
				 return (<CalObject calData={this.props.myjsData.apiData.calObject} myjsObj={this.props.setCALShown} />);
	    }

  		if(this.props.myjsData.fetched)
			{

				var MyjsHeadHTMLView = <MyjsHeadHTML location={this.props.location} history={this.props.history} bellResponse={this.props.myjsData.apiDataHam.hamburgerDetails} fetched={this.props.myjsData.hamFetched}/>

				var EditBarView = <EditBar cssProps={this.state.cssProps}  profileInfo ={this.props.myjsData.apiData.my_profile} fetched={this.props.myjsData.fetched}/>

				if(this.props.myjsData.apiData.membership_message!=null)
				{
				 	var membershipmessageView = <MyjsOcbLayer Ocb_data={this.props.myjsData.apiData.membership_message} ocb_currentT={this.props.myjsData.apiData.currentTime}/>
				}

  			var AcceptCountView =  <AcceptCount fetched={this.props.myjsData.hamFetched} acceptance={this.props.myjsData.apiDataHam.hamburgerDetails} justjoined={this.props.myjsData.apiDataHam.hamburgerDetails}/>
	    }

			if(this.props.myjsData.ieFetched){
	    	var interestExpView = <CheckDataPresent restApiFun={this.restApiHits.bind(this)} fetched={this.props.myjsData.ieFetched} blockname={"int_exp"} data={this.props.myjsData.apiDataIE} url='/inbox/23/1'/>
	    }

	    if(this.props.myjsData.irFetched && this.props.myjsData.apiDataIR.profiles){
	    	var interestRecView = <MyjsSlider restApiFun={this.restApiHits.bind(this)} apiHit={()=>this.props.hitApi_IR()} showLoader='1' cssProps={this.state.cssProps} apiNextPage={this.hitIRforPagination.bind(this)} fetched={this.props.myjsData.irFetched} displayProps = {DISPLAY_PROPS} title='Interest Received' history={this.props.history} location={this.props.location} listing ={this.props.myjsData.apiDataIR} listingName = 'interest_received' url='inbox/1/1'/>
	    }

	    if(this.props.myjsData.modFetched && this.props.myjsData.apiDataMOD.profiles){
	    	var matchOfTheDayView = <MyjsSlider restApiFun={this.restApiHits.bind(this)} cssProps={this.state.cssProps} fetched={this.props.myjsData.modFetched} displayProps = {DISPLAY_PROPS} title='Match of the Day' listing ={this.props.myjsData.apiDataMOD} location={this.props.location} history={this.props.history} listingName = 'match_of_the_day' url='/inbox/24/1'/>
	    }
	    if(this.props.myjsData.vaFetched ){
	    	var MyjsProfileVisitorView = <CheckDataPresent restApiFun={this.restApiHits.bind(this)} fetched={this.props.myjsData.vaFetched} location={this.props.location} history={this.props.history} blockname={"prf_visit"} data={this.props.myjsData.apiDataVA}/>
	    }
	    if(this.props.myjsData.drFetched && this.props.myjsData.apiDataDR.profiles)
	    {
				var dailyRecommendationsView = <MyjsSlider restApiFun={this.restApiHits.bind(this)} cssProps={this.state.cssProps} fetched={this.props.myjsData.drFetched} displayProps = {DISPLAY_PROPS} title='Daily Recommendations' listing ={this.props.myjsData.apiDataDR} location={this.props.location} history={this.props.history} listingName = 'match_alert' url='/inbox/7/1'/>
	    }
			if(   (this.props.myjsData.drFetched)&& (this.props.myjsData.vaFetched)&& (this.props.myjsData.irFetched) )
			{
				var noDatablockView=<NodataBlock restApiFun={this.restApiHits.bind(this)} data={this.props.myjsData}/>
			}

		this.trackJsb9 = 1;
		var style = {
     		height: window.innerHeight + "px"
    	};
    	let ShowBrowserNotificationView = "";

    	if ( this.props.myjsData.apiData.showBrowserNotification )
    	{
    		ShowBrowserNotificationView = <ShowBrowserNotification/>
    	}

  		return(
  		<div id="MyjsPage" style={style}>
  			{promoView}
	  		<div className="fullheight" id="mainContent">
			  	<MetaTagComponents page="MyjsPage"/>
			  	<GA ref="GAchild" />
				<div className="perspective" id="perspective">
					<div className="" id="pcontainer">
									{MyjsHeadHTMLView}
									{EditBarView}
									{membershipmessageView}
									{AcceptCountView}
									{interestExpView}
									{interestRecView}
									{matchOfTheDayView}
									{MyjsProfileVisitorView}
									{dailyRecommendationsView}
									{noDatablockView}
									{ShowBrowserNotificationView}
					</div>
				</div>
			</div>
		</div>
		);
	}

}

const mapStateToProps = (state) => {
    return{
     myjsData: state.MyjsReducer,
	   Jsb9Reducer : state.Jsb9Reducer,

    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        hitApi_MYJS: (containerObj) => {
		return commonApiCall(CONSTANTS.MYJS_CALL_URL,{},'SET_MYJS_DATA','POST',dispatch,true,containerObj);
		},
        jsb9TrackRedirection : (time,url) => {
			jsb9Fun.recordRedirection(dispatch,time,url)
		},
     	hitApi_DR: () => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL1,'&searchBasedParam=matchalerts&caching=1&JSMS_MYJS=1','SET_DR_DATA','POST',dispatch);
		},
     	hitApi_MOD: () => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=24&pageNo=1&caching=1&JSMS_MYJS=1','SET_MOD_DATA','POST',dispatch);
        },
  	    hitApi_IR: (nextPage) => {
					let reducerName = '';
					if(typeof nextPage == 'undefined'){ nextPage=1;reducerName = 'SET_IR_DATA';}
					else { reducerName = 'SET_IR_PAGINATION';}
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=1&pageNo='+nextPage+'&caching=1&JSMS_MYJS=1',reducerName,'POST',dispatch);
        },
        hitApi_VA: () => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=5&pageNo=1&matchedOrAll=A&caching=1&JSMS_MYJS=1','SET_VA_DATA','POST',dispatch);
        },
        hitApi_IE: () => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2,'&infoTypeId=23&pageNo=1&caching=1&JSMS_MYJS=1','SET_IE_DATA','POST',dispatch);
        },
        hitApi_Ham: () => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL3,'&API_APP_VERSION=94','SET_HAM_DATA','POST',dispatch);
        },
				resetTimeStamp : ()=> dispatch({type: 'RESET_MYJS_TIMESTAMP',payload:{}}),
				setCALShown : ()=> dispatch({type: 'SET_CAL_SHOWN',payload:{}})

    }
}

export default connect(mapStateToProps,mapDispatchToProps)(MyjsPage)
