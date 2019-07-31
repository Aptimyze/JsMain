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
import { connect } from "react-redux";
import * as CONSTANTS from '../../common/constants/apiConstants';
let API_SERVER_CONSTANTS = require ('../../common/constants/apiServerConstants');

const SAVEDSEARCHVALUESKEY = "savedSearchValues";
export class savedSearchPage extends React.Component {
	constructor(props) {
		super(props);
        this.GAObject = new GA();
        //console.log("fromSearch",props.fromSearchForm);
		this.state = {
			insertError: false,
			errorMessage: "",
			timeToHide: 3000,
			showLoader: false,
			loggedInStatus: false,
			showPromo: false,
			showMore: false,
			savedSearchCount: 0,
			showSavedSearch: false,
			savedSearchData: [],
			maxSavedSearchLimit: 0,
			tupleData: [],
			showManageLayer:false,
			searchIdList:0,
			heightForScroll:0,
			minHeightStyle:false,
            okButtonText:"OK",
		};
		if(getCookie("AUTHCHECKSUM")) {
			this.state.loggedInStatus = true;
		}
	}

	componentDidMount()
	{
        let _this = this;
    	//if logged in
        if(this.props.fromSearchListing)
        {
            this.setState({
                showManageLayer:true,
                okButtonText:"Select to delete saved searches",
            });
        }
    	if(getCookie("AUTHCHECKSUM")) {

            let savedSearchLocalStorageArray = JSON.parse(localStorage.getItem(SAVEDSEARCHVALUESKEY)); //fetch data from local storage
            if(savedSearchLocalStorageArray)
            {            	//console.log("array exsists");
            	let timeDifference = (new Date().getTime() - savedSearchLocalStorageArray.time)/1000;      //console.log("time:",timeDifference);
            	if(timeDifference > CONSTANTS.SAVED_SEARCH_CACHING_TIME)
            	{
            		localStorage.removeItem(SAVEDSEARCHVALUESKEY);
            		savedSearchLocalStorageArray = null;
            	}
            }

            if(savedSearchLocalStorageArray)
            {
            	this.setStateForSavedSearchData(savedSearchLocalStorageArray.savedSearchData,savedSearchLocalStorageArray.showSavedSearch,savedSearchLocalStorageArray.savedSearchCount,savedSearchLocalStorageArray.maxSavedSearchLimit);
            }
            else
            {
            	let _this = this;
            	let callUrl = CONSTANTS.SAVED_SEARCH_LISTING_API+"&AUTHCHECKSUM="+getCookie("AUTHCHECKSUM");
            	commonApiCall(callUrl,'','','POST').then(function(response) {
            		if(response.saveDetails.details != null) {
            			_this.appendSavedSearch(response.saveDetails.details,response.saveDetails.maxCount,true);
            		}
            	});
            }

        }

        if(this.props.fromSearchForm!="1" && this.props.fromSearchListing!="1")
        {
            _this.GAObject.trackJsEventGA("jsms","new","1");
        }

    }
    //this function is used to set state for savedSearch Data
    setStateForSavedSearchData(searchData,showSavedSearchFlag,savedSearchCount,maxCount)
    {
        if(this.props.fromSearchForm)
        {
            this.props.getCount(savedSearchCount);
        }
    	this.setState({
    		savedSearchData: searchData,
    		showSavedSearch: showSavedSearchFlag,
    		savedSearchCount: savedSearchCount,
    		maxSavedSearchLimit:maxCount
    	});
    }
    //this function saves values in local storage and also sets state
    appendSavedSearch(searchData,maxCount,savedSearchFlag) {
    	let savedSearchDataToStore = {};
    	savedSearchDataToStore["savedSearchData"] = searchData;
    	savedSearchDataToStore["showSavedSearch"] = savedSearchFlag;
    	savedSearchDataToStore["savedSearchCount"] = searchData.length;
    	savedSearchDataToStore["maxSavedSearchLimit"] = maxCount;
    	savedSearchDataToStore["time"] = new Date().getTime();
    	localStorage.setItem(SAVEDSEARCHVALUESKEY,JSON.stringify(savedSearchDataToStore));
    	this.setStateForSavedSearchData(searchData,savedSearchFlag,searchData.length,maxCount);
    }

    showHam() {
    	if(window.location.search.indexOf("ham=1") == -1) {
    		if(window.location.search.indexOf("?") == -1) {
    			this.props.history.push(window.location.pathname+"?ham=1");
    		} else {
    			this.props.history.push(window.location.pathname+window.location.search+"&ham=1");
    		}

    	}
    	this.refs.Hamchild.getWrappedInstance().openHam();
    }
    componentDidUpdate(prevprops) {//console.log("did update");
        jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
        if(this.state.showManageLayer == true && this.state.minHeightStyle == true)
        {//console.log("inside scroll height");
        	 let scrollHeight = window.innerHeight-document.getElementById("saveSearchSubmit").clientHeight-document.getElementById("manageLayerHeader").clientHeight;
        	scrollHeight = scrollHeight+"px";
        	this.setState({
        		minHeightStyle:false,
        		heightForScroll:scrollHeight,
        	});
        }
    }

    //to show manage layer
    showOverlay(e)
    {
    	e.preventDefault();
    	this.setState({
    		showManageLayer: true,
    		minHeightStyle:true,
    	});
    	this.props.historyObject.push(()    =>
          this.closeOverlay()
      ,'#saveLayer');
    }
    //to close manage layer
    closeOverlay() {
    	this.setState({
    		showManageLayer: false
    	});
    	return true;
    }
    //delete the saved search, strike through text and enable undo button
    deleteSavedSearch(searchId)
    {
    	document.getElementById("saved"+searchId).getElementsByClassName("savsrc-vtop")[0].classList.add("undo");
    	document.getElementById("saved"+searchId).getElementsByClassName("deleteButton")[0].classList.add("dispnone");
    	document.getElementById("saved"+searchId).getElementsByClassName("undoButton")[0].classList.remove("dispnone");
    	document.getElementById("saved"+searchId).getElementsByClassName("undo")[0].classList.add("namesav");
    	let tempListId = this.state.searchIdList;
    	tempListId+=","+searchId;
    	this.setState({
    		searchIdList:tempListId,
    	},()=>{
    		this.callBackFunction();
    	});
    }
    //undo strike through, enable delete button
    undoDeleteSavedSearch(searchId)
    {
    	document.getElementById("saved"+searchId).getElementsByClassName("savsrc-vtop")[0].classList.remove("undo","namesav");
    	document.getElementById("saved"+searchId).getElementsByClassName("deleteButton")[0].classList.remove("dispnone");
    	document.getElementById("saved"+searchId).getElementsByClassName("deleteButton")[0].classList.add("dispcell");
    	document.getElementById("saved"+searchId).getElementsByClassName("undoButton")[0].classList.add("dispnone");
    	/*document.getElementById("saved"+searchId).getElementsByClassName("savsrc-vtop")[0].classList.remove("namesav");*/
    	let tempListId = this.state.searchIdList;
    	tempListId = tempListId.replace(","+searchId,"");
    	this.setState({
    		searchIdList:tempListId,
    	},()=>{
    		this.callBackFunction();
    	});
    }
    //to perform actions after state is set
    callBackFunction()
    {
    	if(this.state.searchIdList==0)
    	{
    		let savedSearchSubmitEle = document.getElementById("saveSearchSubmit");
    		document.getElementsByClassName("cross")[0].classList.remove("dispnone");
    		savedSearchSubmitEle.classList.remove("bg7","white");
    		savedSearchSubmitEle.classList.add("bg6","color11");
    		savedSearchSubmitEle.setAttribute("disabled","");
            if(this.props.fromSearchListing)
            {
                this.setState({
                    okButtonText:"Select to delete saved searches",
                });
            }
    	}
    	else
    	{
    		let savedSearchSubmitEle = document.getElementById("saveSearchSubmit");
    		document.getElementsByClassName("cross")[0].classList.add("dispnone");
    		savedSearchSubmitEle.classList.add("bg7", "white");
    		savedSearchSubmitEle.classList.remove("bg6", "color11");
    		savedSearchSubmitEle.removeAttribute("disabled");
            if(this.props.fromSearchListing)
            {
                this.setState({
                    okButtonText:"Delete and Continue to Save Search",
                });
            }
    	}
    }
    //submit saved search changes(deleted saved searches)	on click of OK
    submitSaveSearchData()
    {
    	let savedSearchListArray = JSON.parse("[" + this.state.searchIdList + "]");
    	let tempSavedSearchState = this.state.savedSearchData;
    	for(let i=0;i<savedSearchListArray.length;i++)
    	{
    		for(let j=0;j<tempSavedSearchState.length;j++)
    		{
    			if(savedSearchListArray[i] == tempSavedSearchState[j].ID)
    			{
    				tempSavedSearchState.splice(j,1);
    			}
    		}

    	}
    	//this will update the state and that would allow the back page to render
    	try
    	{
    		let _this = this;
    		commonApiCall(CONSTANTS.SAVED_SEARCH_DELETE_API,{ searchId : this.state.searchIdList},'','POST').then(function (response) {
    			if(response.saveDetails.errorMsg == null)
    			{
    				if(tempSavedSearchState.length>0)
    				{
    					_this.appendSavedSearch(tempSavedSearchState,_this.state.maxSavedSearchLimit,true);
    				}
    				else
    				{
    					_this.appendSavedSearch(tempSavedSearchState,_this.state.maxSavedSearchLimit,false);
    				}

    				_this.setState({
    					searchIdList:0
    				});

    				if(!_this.props.fromSearchListing)
                        _this.props.historyObject.pop(true);
                    else
                        _this.props.callSSLayer();

    			}
    			else
    			{
                		//handle error case
                }
                });
    	}
    	catch(e)
    	{

    	}
    }
    performSearch()
    {
    	let time = new Date().getTime();
      	window.location.href='/search/topSearchBand?isMobile=Y&stime='+time;
    }
    goToSearch(searchId)
    {
        if(!this.props.fromSearchForm)
        {
            this.props.history.push("/search/SavedSearches?mySaveSearchId="+searchId);
        }
        else
            this.props.getSavedSearchResults(searchId);
    }
    toggleMore(e,searchId)
    {
    	e.stopPropagation();
    	document.getElementById(searchId+"_more").classList.remove("dn");
    	document.getElementById(searchId+"moreBtn").classList.add("dn");
    }
    goToSavedSearchView()
    {
          document.getElementById("savedSearches").scrollIntoView();
    }
    render() {
    	let errorView;
    	if(this.state.insertError == true && !this.props.fromSearchForm)
    	{
    		errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
    	}

    	let loaderView;
    	if(this.state.showLoader && !this.props.fromSearchForm)
    	{
    		loaderView = <Loader show="page"></Loader>;
    	}

    	let promoView;
    	if(this.state.showPromo && !this.props.fromSearchForm)
    	{
    		promoView = <AppPromo parentComp="others" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
    	}
    	let hamView;
        if(!this.props.fromSearchForm)
        {
            if(this.state.loggedInStatus == true)
            {
               if(this.props.myjsData.apiDataHam != undefined)
                   hamView = <HamMain bellResponse={this.props.myjsData.apiDataHam.hamburgerDetails} ref="Hamchild" page="others"></HamMain>;
               else
                   hamView = <HamMain ref="Hamchild" page="others"></HamMain>;
            }
            else
            {
                hamView = <HamMain ref="Hamchild" page="Login"></HamMain>;
            }
        }
    	let savedSearchView;
    	if(this.state.savedSearchCount && !this.props.fromSearchForm)
    	{
    		savedSearchView = <div id="manageSavedSearchBtn" onClick={(e) => this.showOverlay(e)} className="dispibl fr dispibl fr posabs white fontthin f16" style={{"right":"15px","top":"18px"}}>Manage
    		</div>;
    	}
    	let headerView;

        if(this.props.fromSearchForm && this.state.savedSearchCount)
        {
            headerView = <div className="pt22" id="savedSearches">
            <div className="brdr1 pad18">
            <div className="fullwid clearfix">
            <div className="fl wid10p">
            <i className="savsrc-sp savsrc-icon2"></i>
            </div>

            <div className="fl savsrc-mrt2 wid90p savsrc-ft1">
            <div>
	            <div className="fl dispibl color2" id="ss_text">
								Saved Searches
								<span id="ss_count">({this.state.savedSearchCount})</span>
							</div>
            </div>
            <div id="manageSavedSearchBtn" className="dispibl fr color8 padl20" onClick={(e) => this.showOverlay(e)} >Manage</div>
            </div>
            </div>
            </div>
            {savedSearchView}
            </div>;
        }
        else if(!this.props.fromSearchForm)
        {
            headerView = <div className="bg1 padd22">
            <i id="hamburgerIcon" onClick={() => this.showHam()} className="fl dispbl mainsp baricon"></i>
            <div className="white fontthin f19 txtc dispibl wid84p" id="ss_text">
            Saved Searches 	<span id="ss_count">{this.state.savedSearchCount}</span>
            {savedSearchView}
            </div>
            </div>;
        }

    	let manageSavedSearchLayer,savedSearchList,manageOverlayHeader,okButton,maxSearchDiv;
        let headerText;
        if(this.props.fromSearchListing)
        {
            headerText = "Save Search Limit Reached";
            maxSearchDiv = <div className="txtc white fontlig f16 pad9 sav-cont">
                            <div>You can only save up to {this.state.maxSavedSearchLimit} searches</div>
                            <div className="opa50 pt10">Remove one of the searches below to save</div>
                            </div>;
        }
        else
            headerText = "Manage Saved Searches";
    	if(this.state.showManageLayer == true)
    	{
    		savedSearchList = this.state.savedSearchData.map(function(name1, index1){
    			if(index1 < this.state.maxSavedSearchLimit){
    				let newDataString;
    				if(name1.dataString.length >100)
    				{
    					newDataString = name1.dataString.substr(0,100)+"...";
    				}
    				else
    				{
    					newDataString=name1.dataString;
    				}
    				return(
    					<div className="pad18" id={"saved"+name1.ID} key={index1}>
    					<div className="disptbl">
    					<div className="dispcell txtl wid85p white opa50 savsrc-vtop">
    					<div>{name1.SEARCH_NAME}</div>
    					<div className="savsrc-list pt15">
    					<ul className="wid94p lh25">
    					{newDataString}
    					</ul></div>
    					</div>
    					<div className="dispcell wid10p vertmid deleteButton" onClick={()=>this.deleteSavedSearch(name1.ID)}><i className="mainsp savsrc-icon4"></i>
    					</div>
    					<div className="dispcell wid10p vertmid dispnone white f16 undoButton" onClick={()=>this.undoDeleteSavedSearch(name1.ID)}>Undo
    					</div>
    					</div>
    					</div>
    					);
    			}
    		},this);

    		manageOverlayHeader = <div id= "manageLayerHeader" className="pad18 txtc sav-head sacsrc-brdr1">
    		<div className="posrel">
    		<div className="white fontthin f19">{headerText}
    		</div>
    		<div className="posabs savsrc-pos4 cross" onClick={()=>this.props.historyObject.pop(true)}>
    		<i id="closeSaveSearchOverlay" className="mainsp savsrc-icon3">
    		</i>
    		</div>
    		</div>
    		</div>;
    		okButton = <div className="btmo border0 okBtn">
    		<button className="savsrc-button fontlig f16 txtc lh50 posfix btmo border0 fullwid pinkRipple color11 bg6 zindex110" id="saveSearchSubmit" disabled="" onClick={()=>this.submitSaveSearchData()}>{this.state.okButtonText}</button>
    		</div>;
    		manageSavedSearchLayer  = <div id="manageOverlay" className="posfix ce_top1 ce_z101 scrollhid fullwid bgColorBlack overflowAuto">
    		{manageOverlayHeader}
            {maxSearchDiv}
    		<div style={{"overflow":"visible","minHeight":this.state.heightForScroll}}>
    		{savedSearchList}
    		</div>
    		<br/>
    		<br/><br/><br/>
    		{okButton}
    		</div>;
    	}
        //this is the main view for saved Search
        let savedSearchDetailView,savedSearchBottom,maxLimitView,moreView;
        if(this.state.showSavedSearch == true && this.state.showManageLayer ==  false) {
        	savedSearchBottom = this.state.savedSearchData.map(function(name, index){
        		let showLessString;
        		let remainingString = "";
        		if(index < this.state.maxSavedSearchLimit) {
        			if(name.dataString.length>100)
        			{
        				showLessString = name.dataString.substr(0,100);
        				remainingString = name.dataString.substr(100,name.dataString.length);
        				moreView =<div className="dispibl">
        				 <span id={name.ID+"_less"} className="">{showLessString}
        				</span>
        				<span id={name.ID+"moreBtn"} className="color2" onClick={(e,SearchId)=>this.toggleMore(e,name.ID)}>...more</span>
        				<span id={name.ID+"_more"} className="dn">{remainingString}
        				</span>
        				</div>;
        			}
        			else
        			{
        				showLessString = name.dataString;
        				moreView = <span id={name.ID+"_less"} className="">{showLessString}
        				</span>;
        			}
        			return(
        				<div className="brdr1 savedSearch" id={name.ID}  key={index} onClick={()=>this.goToSearch(name.ID)}>
        				<div className="pad18">
        				<div className="fl wid94p srfrm_wrap">
        				<div className="f14 savsrc-colr1">{name.SEARCH_NAME}</div>
        				<div id="{name.ID}" className="color8 f16 pt10 savsrc-list savedSearchList">
        					{moreView}
        					{/*<span id={name.ID+"_less"} className="dn">{name.dataString}</span>
        					<span id={name.ID+"_more"}>
        					{showLessString}
        						<span className="color2" onClick={()=>this.toggleMore(name.ID)}>more
        					</span>
        					</span>*/}
        				</div>
        				</div>
        				<div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
        				<div className="clr"></div>
        				</div>
        				</div>
        				);
        		}
        	},this);
        	if(this.state.savedSearchCount >= this.state.maxSavedSearchLimit) {

        		maxLimitView = <div className="brdr1">
        		<div className="pad18 f14 savsrc-colr1 txtc">
        		Saved searches limit reached, tap on 'Manage' to delete
        		</div>
        		</div>;
        	}
        	else if(this.state.savedSearchCount > 0)
        	{
        		maxLimitView = <div className="brdr1">
        		<div className="pad18 f14 savsrc-colr1 txtc">
        		Tap on 'Manage' to delete
        		</div>
        		</div>;
        	}

        	if(this.state.savedSearchCount)
        	{
        		savedSearchDetailView = <div className="" id="savedSearches">
        		{savedSearchBottom}
        		{maxLimitView}
        		</div>;
        	}
        }
        else if (!this.props.fromSearchForm)
        {
        	savedSearchDetailView = <div id="zeroSaved">
        	<div className="svasrc-pada txtc fontreg f16">
        	<div className="color8">Tap on Save icon after performing search
        	</div>
        	<div className="pt10 color1 lh25">Saving searches helps you save time and categorize your matches better
        	</div>
        	</div>
        	<div className="fullwid txtc pad1 ">
        	<div className="posrel">
        	<img src="/images/jsms/searchImg/0search.png" className="border0 classimg3"/>
        	<div className="posabs fullwid"
        	style={{"bottom":"10px"}}>
        	<button className="savsrc-button fontlig f16 white txtc bg7 lh50 fullwid border0 sacsrc-class1" id="performSearch" onClick={()=>this.performSearch()}>
        	Perform A Search Now
        	</button>
        	</div>
        	</div>
        	</div>
        	</div>;
        }
        return (
        	<div className= "fullwid bg4 fontlig" id="saveSearch">
        	{promoView}
        	{hamView}
        	{errorView}
        	{loaderView}
        	<div className=" bg4" id="mainContent">
        	{headerView}
        	{savedSearchDetailView}
        	{manageSavedSearchLayer}
        	</div>
        	</div>
        	);
    }
}
const mapStateToProps = (state) => {
    return{
      myjsData: state.MyjsReducer,
      historyObject : state.historyReducer.historyObject

    }
}
const mapDispatchToProps = (state) => {
    return{

    }
}
export default connect(mapStateToProps,mapDispatchToProps)(savedSearchPage)
