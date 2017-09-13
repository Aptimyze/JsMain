require ('../style/searchForm.css')
import React from "react";
import { connect } from "react-redux";
import TopError from "../../common/components/TopError"
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import GA from "../../common/components/GA";
import * as jsb9Fun from '../../common/components/Jsb9CommonTracking';
import HamMain from "../../Hamburger/containers/HamMain";
import {getCookie} from '../../common/components/CookieHelper';
import AppPromo from "../../common/components/AppPromo";
import axios from "axios";;
import * as CONSTANTS from '../../common/constants/apiConstants';
import * as API_SERVER_CONSTANTS from '../../common/constants/apiServerConstants'
import DropMain from "../../DropDown/containers/DropMain";


class SearchFormPage extends React.Component {

    constructor(props) {
        super();
        jsb9Fun.recordBundleReceived(this,new Date().getTime());

        let data = [{"name":"age","type":"double","title1":"Min Age","title2":"Max Age","default1":"18 Years","default2":"70 Years"},{"name":"height","type":"double","title1":"Min Height","title2":"Max Height","default1":"4' 0\"","default2":"7'"},{"name":"religion","type":"single","label":"Religion","default":"Any Religion"},{"name":"mtongue","type":"single","label":"Mother Tongue","default":"Any Mother Tongue"},{"name":"location","type":"single","label":"Country","default":"Any Country"},{"name":"location_cities","type":"single","label":"State/City","default":"Any City"},{"name":"income","type":"double","title1":"Min Income","title2":"Max Income","default1":"Rs. 0","default2":"and above"}];

        let moreData = [{"name":"education","label":"Education","default":"Doesn't Matter","type":"single"},{"name":"occupation","label":"Occupation","default":"Doesn't Matter","type":"single"},{"name":"manglik","label":"Manglik","default":"Doesn't Matter","type":"single"}];

        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            loggeInStatus: false,
            showPromo: false,
            showMore: false,
            primaryData: data,
            moreData: moreData,
            savedSearchCount: 0,
            showSavedSearch: false,
            savedSearchData: [],
            maxSavedSearchLimit: 0,
            tupleData: []
        };
        if(getCookie("AUTHCHECKSUM")) {
            this.state.loggeInStatus = true;
        }
    }

    componentDidMount() {
        document.getElementById("SearchFormPage").style.height = window.innerHeight+"px";
        this.props.getSearchData();
        if(getCookie("AUTHCHECKSUM")) {
            let call_url = "/api/v1/search/populateDefaultValues";
            axios({
                method: "POST",
                url: API_SERVER_CONSTANTS.API_SERVER +call_url+"?AUTHCHECKSUM="+getCookie("AUTHCHECKSUM"),
                data: '',
                headers: { 
                  'Accept': 'application/json',
                  'withCredentials':true
                },
            }).then( (response) => {
                this.appendDefaultValues(response.data)
            });

            let call_url2 = "/api/v1/search/saveSearchCall?perform=listing&AUTHCHECKSUM="+ getCookie("AUTHCHECKSUM");
            axios({
                method: "POST",
                url: API_SERVER_CONSTANTS.API_SERVER +call_url2+"?AUTHCHECKSUM="+getCookie("AUTHCHECKSUM"),
                data: '',
                headers: { 
                  'Accept': 'application/json',
                  'withCredentials':true
                },
            }).then( (response2) => {
                if(response2.data.saveDetails.details != null) {
                    this.appendSavedSearch(response2.data.saveDetails.details,response2.data.saveDetails.maxCount);
                }
            });
        }
    }

    appendSavedSearch(searchData,maxCount) {
        this.setState({
            savedSearchData: searchData,
            showSavedSearch: true,
            savedSearchCount: searchData.length,
            maxSavedSearchLimit:maxCount
        });
    }

    appendDefaultValues(defaultData) {
        console.log("default",defaultData);
        console.log("actual",this.state.primaryData)
        var temp = this.state.primaryData;
        //TODO: append default values from api 
    }

    componentWillReceiveProps(nextProps)
    {
        if(nextProps.appPromotion == true) {
            this.setState ({
                showPromo : true
            });
        }
        console.log("data",nextProps.searchData.services.searchForm.data)
        this.setState({
            tupleData: nextProps.searchData.services.searchForm.data
        })
    }

    componentDidUpdate(prevprops) {
        jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
        if(prevprops.location) {
            if(prevprops.location.search.indexOf("ham=1") != -1 && window.location.search.indexOf("ham=1") == -1) {
                this.refs.Hamchild.getWrappedInstance().hideHam();
                if(this.refs.Dropchild) {
                    this.refs.Dropchild.hideHam();
                }
            }
        }
    }

    componentWillUnmount(){
        this.props.jsb9TrackRedirection(new Date().getTime(),this.url);
    }

    showError(inputString) {
        let _this = this;
        this.setState ({
                insertError : true,
                errorMessage : inputString
        })
        setTimeout(function(){
            _this.setState ({
                insertError : false,
                errorMessage : ""
            })
        }, this.state.timeToHide+100);
    }

    showDrop(elem,e) {
        if(window.location.search.indexOf("ham=1") == -1) {
            if(window.location.search.indexOf("?") == -1) {
                this.props.history.push(window.location.pathname+"?ham=1");
            } else {
                this.props.history.push(window.location.pathname+window.location.search+"&ham=1");
            }

        }
        
        let temp;
        if(elem.name.type == "double") {
            if(e.target.className.indexOf("drop1") > -1) {
                this.refs.Dropchild.openHam(elem.name.title1,elem.name.type,this.state.tupleData[elem.name.name]);
            } else if(e.target.className.indexOf("drop2") > -1){
                this.refs.Dropchild.openHam(elem.name.title2,elem.name.type,this.state.tupleData[elem.name.name]);
            }    
        } else {
            this.refs.Dropchild.openHam(elem.name.label,elem.name.type,this.state.tupleData[elem.name.name]);
        }
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

    changeTab(e) {
        if(e.target.nextSibling) {
            e.target.nextSibling.classList.remove("selectedTab");
        } else if(e.target.previousSibling) {
            e.target.previousSibling.classList.remove("selectedTab");
        }
        e.target.classList.add("selectedTab");
    }

    changeMore() {
        if(this.state.showMore == false) {
            document.getElementById("moreDetails").classList.add("openShowMoreDiv");
        } else {
            document.getElementById("moreDetails").classList.remove("openShowMoreDiv");

        }
        this.setState({
            showMore : !this.state.showMore
        });    
    }

    render() {

        let errorView;
        if(this.state.insertError == true)
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }

        let loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="page"></Loader>;
        }

        var promoView;
        if(this.state.showPromo)
        {
            promoView = <AppPromo parentComp="others" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }
        let savedSearchDetailView,savedSearchBottom,maxLimitView;
        if(this.state.showSavedSearch == true) {
            savedSearchBottom = this.state.savedSearchData.map(function(name, index){
                if(index < this.state.maxSavedSearchLimit) {
                    return(
                        <div className="brdr1 savedSearch" id={name.ID}  key={index}>      
                            <div className="pad18">
                                <div className="fl wid94p srfrm_wrap">
                                    <div className="f14 savsrc-colr1">{name.SEARCH_NAME}</div>
                                    <div id="{name.ID}" className="color8 f16 pt10 savsrc-list savedSearchList">{name.dataString}
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
            savedSearchDetailView = <div className="pt22" id="savedSearches">
                <div className="brdr1 pad18">
                    <div className="fullwid clearfix">
                        <div className="fl wid10p">
                            <i className="savsrc-sp savsrc-icon2"></i>
                        </div>

                        <div className="fl savsrc-mrt2 wid90p savsrc-ft1">
                            <div>
                                <div className="fl dispibl color2">Saved Searches ({this.state.savedSearchCount})</div>
                            </div>
                            <div id="manageSavedSearch" className="OpenManagelayer dispibl fr color8 padl20">Manage</div>
                        </div>
                    </div>
                </div>
                {savedSearchBottom}
                {maxLimitView}
            </div>; 
        }

        let savedSearchCountView;
        if(document.getElementById("savedSearchCount")) {
            let count = document.getElementById("savedSearchCount").innerText;
            savedSearchCountView = <div className="posabs savsrc-pos2">
                <div className="txtc color6 f12 roundIcon">{count}</div>
            </div>;    
        }
        
        let savedSearchView,genderView,hamView;
        if(this.state.loggeInStatus == true) {
            savedSearchView = <div className="dispibl fr" id="savedSearchIcon">
                <i className="savsrc-sp savsrc-icon1"></i>
                {savedSearchCountView}
            </div>;
            hamView = <HamMain ref="Hamchild" page="others"></HamMain>;
        } else {
            genderView = <div id="search_GENDER">
                <div className="pad3 brdr1 txtc">
                    <div className="brdr12 fullwid">
                        <div id="search_GENDERF" onClick={(e) => this.changeTab(e)} className="defaultTab selectedTab">
                            Bride
                        </div>
                        <div id="search_GENDERM" onClick={(e) => this.changeTab(e)} className="defaultTab">
                            Groom
                        </div>
                    </div>
                </div>
            </div>;
            hamView = <HamMain ref="Hamchild" page="Login"></HamMain>;
        }

        let headerView = <div className="bg1 padd22">
            <i id="hamburgerIcon" onClick={() => this.showHam()} className="fl dispbl mainsp baricon"></i>
            <div className="white fontthin f19 txtc dispibl wid84p">
                Search Your Match
            </div>
            {savedSearchView}
        </div>;
        
        let photoView = <div id="search_PHOTO">
            <div className="pad3 brdr1 txtc">
                <div className="brdr12 fullwid">
                    <div id="searchform_all" onClick={(e) => this.changeTab(e)} className="defaultTab selectedTab">
                        All Profiles
                    </div>
                    <div id="searchform_photo" onClick={(e) => this.changeTab(e)} className="defaultTab">
                        Profile with Photos
                    </div>
                </div>
            </div>
        </div>;

        

        let mainView = this.state.primaryData.map(function(name, index){
            if(name.type == "double") {
                return (
                    <div id={"search_"+name.name}  key={index}>
                        <div className="brdr1 pad18">
                            <div onClick={(e) => this.showDrop({name},e)} className="wid45p dispibl drop1" id={"search_l"+name.name}>
                                <div className="fullwid drop1">
                                    <div className="fl drop1">
                                        <div className="color8 f12 drop1">{name.title1}</div>
                                        <div className="color8 f17 pt10 drop1">
                                            <span className="label wid70p drop1">{name.default1}</span>
                                        </div>
                                    </div>
                                    <div className="fr pt8 drop1"> 
                                        <i className="drop1 mainsp arow1"></i>
                                    </div>
                                </div>
                            </div>
                            <div onClick={(e) => this.showDrop({name},e)} id={"search_h"+name.name} className="wid45p fr mrr5 dispibl drop2">
                                <div className="fullwid drop2">
                                    <div className="fl srfrm_wrap drop2">
                                        <div className="color8 f12 drop2">{name.title2}</div>
                                        <div className="color8 f17 pt10 drop2">
                                            <span className="label wid70p drop2">{name.default2}</span> 
                                        </div>
                                    </div>
                                    <div className="fr pt8 drop2"> 
                                        <i className="mainsp arow1 drop2"></i> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                );   
            } else {
                return(
                    <div onClick={(e) => this.showDrop({name},e)} id={"search_"+name.name}  key={index}>      
                        <div className="pad18 brdr1">
                            <div className="dispibl srfrm_wrap">
                                <div className="color8 f12">{name.label}</div>
                                <div className="color8 f17 pt10">
                                    <span className="label wid70p">{name.default}</span>
                                </div>
                            </div>
                            <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
                        </div>
                    </div> 
                );
            }
            
          },this)

        let moreOptionsView;
        if(this.state.showMore == false) {
            moreOptionsView = <div onClick={() => this.changeMore()} className="showmorelink pad18 txtc bg6" id="moreoptions">
                <span className="moreoptions color8">More Options +  </span>
                <i className="mainsp arow7 fr"></i>
            </div>;
        } else {
            moreOptionsView = <div  onClick={() => this.changeMore()} className="showlesslink pad18 txtc" id="lessoptions0" rel="0">
                <span className="lessoptions">Less Options - </span>
                <i className="arow8 fr"></i>
            </div>;
        }
        
        
        
        let moreDetailView = this.state.moreData.map(function(name, index){
                return(
                    <div onClick={(e) => this.showDrop({name},e)} id={"search_"+name.name}  key={index}>      
                        <div className="pad18 brdr1">
                            <div className="dispibl srfrm_wrap">
                                <div className="color8 f12">{name.label}</div>
                                <div className="color8 f17 pt10">
                                    <span className="label wid70p">{name.default}</span>
                                </div>
                            </div>
                            <div className="fr wid4p pt8"> 
                                <i className="mainsp arow1"></i> 
                            </div>
                        </div>
                    </div> 
                );
            },this);

        this.trackJsb9 = 1;
        
        return (
            <div id="SearchFormPage">
                <GA ref="GAchild" />
                <DropMain ref="Dropchild" />
                {promoView}
                {hamView}
                {errorView}
                {loaderView}
                <div className="fullheight bg4" id="mainContent">
                    {headerView}
                    {genderView}
                    {mainView}
                    {photoView}
                    {moreOptionsView}
                    <div className="showMoreDiv scrollhid" id="moreDetails">
                        {moreDetailView}
                    </div>
                    <div id="search_submit" className="bg7 white fullwid dispbl txtc lh50 pinkRipple">Search</div>
                    {savedSearchDetailView}
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
       searchData: state.SearchFormReducer.searchData,
       Jsb9Reducer : state.Jsb9Reducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        getSearchData: () => {
            let call_url = '/api/v1/search/searchFormData?json={"searchForm":"2013-12-25 00:00:00"}';
            commonApiCall(call_url,{},'GET_SEARCH_DATA','POST',dispatch);
        },
        jsb9TrackRedirection : (time,url) => {
            jsb9Fun.recordRedirection(dispatch,time,url)
        },
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(SearchFormPage)
