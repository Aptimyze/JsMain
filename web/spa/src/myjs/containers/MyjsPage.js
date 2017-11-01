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
	if(this.props.mountFun)this.props.mountFun();
	this.props.restApiFun();
}
	render(){


	 if(!this.props.fetched)
		{
			return (<div className="nodatafetch"></div>)
		}

		switch (this.props.blockname) {
			case "int_exp":
						if( !this.props.data.responseStatusCode ||  (this.props.data===null)  || (this.props.data.profiles===null))
						{
							  return (<div className="noData Intexp"></div>);
						}
						return(<InterestExp int_exp_list={this.props.data}/>);
						break;
			case "prf_visit":
						if(!this.props.data.responseStatusCode ||  this.props.data.profiles===null)
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
			jsb9Fun.setJsb9Key(this,'JSNEWMOBMYJSURL');
			this.state=
			{
				myjsApi: false,
				hamApi: false,
				showPromo: false,
				deviceHeight: window.innerHeight,
				didUpdateCall: false,
				loaderStyle: {display:'none'}
			}

  	}

  	componentDidMount()
  	{
			this.callEventListner();
  		if(this.props.myjsData.timeStamp==-1 || ( (new Date().getTime() - this.props.myjsData.timeStamp) > this.props.myjsData.apiData.cache_interval) ){ // caching conditions go here in place of true
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
		//	this.hideLoader('hide');
		}

	}


	componentDidUpdate(){
		jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
	}

	componentWillReceiveProps(nextProps)
	{
		if(nextProps.myjsData.hamFetched && nextProps.myjsData.fetched){
		}
		else{
			if(!this.props.myjsData.fetched || !this.props.myjsData.hamFetched){
				this.firstApiHits(this);
			}
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
		if(current > (this.state.deviceHeight +70))
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
		window.removeEventListener('scroll',this.scrollFun,false);
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
			this.scrollFun = (event) => {
				this.restApiHits(true)
			};
   		window.addEventListener('scroll', this.scrollFun,false );
  	}

  	firstApiHits(obj){
  		if(!this.state.myjsApi){
		    this.props.hitApi_MYJS(obj);
		    this.setState({
		    	myjsApi: true
		    });
		}
  		else if(!this.state.hamApi){
		    this.props.hitApi_Ham(this);
		    this.setState({
		    	hamApi: true
		    });
		}
  	}

  	restApiHits(fromScroll)
		{
			if(fromScroll)
			{
				this.scrolledOnce = 1;
				window.removeEventListener('scroll',this.scrollFun,false);
			}
			try
			{
				if(!this.scrolledOnce && (this.isScreenFull())  )
				{
					 return;
				}
				// else{
				// 	if(event.type == "scroll"){
				// 		this.setState({
				// 			is_already_scrolled:true
				// 		})
				// 	}
				// }
				if(!this.ieMounted)
				{
					if(!this.ieApi)
					{
						this.ieApi = true;
					this.props.hitApi_IE(this);
					}
				}
				else if(!this.irMounted)
				{
					if(!this.irApi)
					{
						this.irApi = true;
						this.props.hitApi_IR({containerObj:this});
					}
				}
				else if(!this.modMounted)
				{
					if(!this.modApi)
					{
						this.modApi = true;
						this.props.hitApi_MOD(this);
					}
				}
				else if(!this.vaMounted)
				{
					if(!this.vaApi)
					{
						this.vaApi = true;
						this.props.hitApi_VA(this);

					}
				}
				else if(!this.drMounted)
				{
					if(!this.drApi)
					{
						this.drApi = true;
						this.props.hitApi_DR(this);
					}
				}
			}


			catch(e)
			{
				console.log("excpection coming from restapi"+e);
			}

		}
		hideLoader(param)
		{

      let ele=document.getElementById("JBrowserGap");
			if(param=='hide')
			{
				this.setState({loaderStyle:{display:'none'}});
			}
			else if(param=="show")
			{
				this.setState({loaderStyle:{display:'block'}});
			}

		}

		hitIRforPagination(){
			if(this.props.myjsData.apiDataIR.nextpossible!='true' || this.props.myjsData.apiDataIR.paginationHit)return;
			this.props.myjsData.apiDataIR.paginationHit = true;
			var nextPage = parseInt(this.props.myjsData.apiDataIR.page_index);
			nextPage++;
			this.props.hitApi_IR({nextPage:nextPage});
		}
  	render() {
		var promoView;
        if(this.state.showPromo == true)
        {
            promoView = <AppPromo parentComp="others" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }

  		// if(!this.props.myjsData.fetched){
	   //       return (<div><Loader show="page"></Loader></div>)
	   //  }
	    if(this.props.myjsData.apiData.calObject && !this.props.myjsData.calShown){
				 return (<CalObject myjsApiHit={this.props.hitApi_MYJS.bind(this)} calData={this.props.myjsData.apiData.calObject} myjsObj={this.props.setCALShown} />);
	    }

	    let MyjsHeadHTMLView, EditBarView, membershipmessageView, AcceptCountView, LoaderView;
  		if(this.props.myjsData.fetched && this.props.myjsData.hamFetched){

			MyjsHeadHTMLView = <MyjsHeadHTML location={this.props.location} history={this.props.history} bellResponse={this.props.myjsData.apiDataHam.hamburgerDetails} fetched={this.props.myjsData.hamFetched}/>

			EditBarView = <EditBar cssProps={this.state.cssProps}  profileInfo ={this.props.myjsData.apiData.my_profile} fetched={this.props.myjsData.fetched}/>

			if(this.props.myjsData.apiData.membership_message!=null){
			 	membershipmessageView = <MyjsOcbLayer Ocb_data={this.props.myjsData.apiData.membership_message} timeDiff={new Date().getTime() - this.props.timeStamp} ocb_currentT={this.props.myjsData.apiData.currentTime}/>
			}

  			AcceptCountView =  <AcceptCount  hamFetched={this.props.myjsData.hamFetched} acceptance={this.props.myjsData.apiDataHam.hamburgerDetails} justjoined={this.props.myjsData.apiDataHam.hamburgerDetails}/>
	    }
	    else{

  			LoaderView = <div><Loader show="page"></Loader></div>
	    }


			if(this.props.myjsData.ieFetched){
	    	var interestExpView = <CheckDataPresent mountFun={()=>{this.ieMounted=1;}} restApiFun={this.restApiHits.bind(this)} fetched={this.props.myjsData.ieFetched} blockname={"int_exp"} data={this.props.myjsData.apiDataIE} url='/inbox/23/1'/>
	    }

	    if(this.props.myjsData.irFetched ){
	    	var interestRecView = <MyjsSlider mountFun={()=>{this.irMounted=1;}} restApiFun={this.restApiHits.bind(this)} showLoader='1' cssProps={this.state.cssProps} apiNextPage={this.hitIRforPagination.bind(this)} fetched={this.props.myjsData.irFetched} displayProps = {DISPLAY_PROPS} title='Interest Received' history={this.props.history} location={this.props.location} listing ={this.props.myjsData.apiDataIR} listingName = 'interest_received' url='inbox/1/1'/>
	    }

	    if(this.props.myjsData.modFetched){
	    	var matchOfTheDayView = <MyjsSlider mountFun={()=>{this.modMounted=1;}} restApiFun={this.restApiHits.bind(this)} cssProps={this.state.cssProps} fetched={this.props.myjsData.modFetched} displayProps = {DISPLAY_PROPS} title='Match of the Day' listing ={this.props.myjsData.apiDataMOD} location={this.props.location} history={this.props.history} listingName = 'match_of_the_day' url='/inbox/24/1'/>
	    }
	    if(this.props.myjsData.vaFetched ){
	    	var MyjsProfileVisitorView = <CheckDataPresent mountFun={()=>{this.vaMounted=1;}} restApiFun={this.restApiHits.bind(this)} fetched={this.props.myjsData.vaFetched} location={this.props.location} history={this.props.history} blockname={"prf_visit"} data={this.props.myjsData.apiDataVA}/>
	    }
	    if(this.props.myjsData.drFetched)
	    {
				var dailyRecommendationsView = <MyjsSlider mountFun={()=>{this.drMounted=1;this.setState({allHitsDone:true});}} restApiFun={this.restApiHits.bind(this)} cssProps={this.state.cssProps} fetched={this.props.myjsData.drFetched} displayProps = {DISPLAY_PROPS} title='Daily Recommendations' listing ={this.props.myjsData.apiDataDR} location={this.props.location} history={this.props.history} listingName = 'dailymatches' hitFromMyjs='1' url='/inbox/7/1'/>
	    }
			if( this.state.allHitsDone && ( (this.props.myjsData.drFetched) || (this.props.myjsData.vaFetched)|| (this.props.myjsData.irFetched)) )
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
    		if (this.props.myjsData.apiData.showBrowserNotification['showLayer'] == 1)
    		{
    			ShowBrowserNotificationView = <ShowBrowserNotification/>
    		}
    	}

  		return(
  		<div id="MyjsPage" style={{}}>
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
									{LoaderView}
									{interestExpView}
									{interestRecView}
									{matchOfTheDayView}
									{MyjsProfileVisitorView}
									{dailyRecommendationsView}
									{noDatablockView}
									{ShowBrowserNotificationView}
					</div>
					<div id="JBrowserGap" style={this.state.loaderStyle} className={"fullwid txtc "} >
						<img className="pt20" src="https://static.jeevansathi.com/images/jsms/commonImg/loader.gif"/>
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
		 timeStamp : state.MyjsReducer.timeStamp

    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        hitApi_MYJS: (containerObj) => {
		return commonApiCall(CONSTANTS.MYJS_CALL_URL,{},'SET_MYJS_DATA','POST',dispatch,true,containerObj).then(()=>jsb9Fun.recordServerResponse(containerObj,new Date().getTime()));
		},
        jsb9TrackRedirection : (time,url) => {
			jsb9Fun.recordRedirection(dispatch,time,url)
		},
     	hitApi_DR: (containerObj) => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL1+'?&searchBasedParam=matchalerts&caching=1&JSMS_MYJS=1&myjs=1&listingName=dailymatches&hitFromMyjs=1',{},'SET_DR_DATA','POST',dispatch).then(()=> {
            	containerObj.hideLoader('hide');
							window.removeEventListener('scroll',containerObj.scrollFun,false);
						});
		},
     	hitApi_MOD: (containerObj) => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2+'?&infoTypeId=24&pageNo=1&caching=1&JSMS_MYJS=1',{},'SET_MOD_DATA','POST',dispatch).then(()=>{

            });
        },
  	    hitApi_IR: (obj) => {
					let reducerName = '';
					if(typeof obj.nextPage == 'undefined'){ obj.nextPage=1;reducerName = 'SET_IR_DATA';}
					else { reducerName = 'SET_IR_PAGINATION';}
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2+'?&infoTypeId=1&pageNo='+obj.nextPage+'&caching=1&JSMS_MYJS=1',{},reducerName,'POST',dispatch).then(()=>{

            });;
        },
        hitApi_VA: (containerObj) => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2+'?&infoTypeId=5&pageNo=1&matchedOrAll=A&caching=1&JSMS_MYJS=1',{},'SET_VA_DATA','POST',dispatch).then(()=>{

            });;
        },
        hitApi_IE: (containerObj) => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL2+'?&infoTypeId=23&pageNo=1&caching=1&JSMS_MYJS=1',{},'SET_IE_DATA','POST',dispatch).then(()=>{

            });;
        },
        hitApi_Ham: (containerObj) => {
            return commonApiCall(CONSTANTS.MYJS_CALL_URL3,{},'SET_HAM_DATA','POST',dispatch).then(()=>{
            	containerObj.restApiHits(); containerObj.hideLoader("show");
            });
        },
				resetTimeStamp : ()=> dispatch({type: 'RESET_MYJS_TIMESTAMP',payload:{}}),
				setCALShown : ()=> dispatch({type: 'SET_CAL_SHOWN',payload:{}})

    }
}

export default connect(mapStateToProps,mapDispatchToProps)(MyjsPage)
