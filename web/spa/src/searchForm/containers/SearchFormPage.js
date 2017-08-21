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

class SearchFormPage extends React.Component {

    constructor(props) {
        super();
        jsb9Fun.recordBundleReceived(this,new Date().getTime());
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            loggeInStatus: false,
            showPromo: false,
            showMore: false
        };
        if(getCookie("AUTHCHECKSUM")) {
            this.state.loggeInStatus = true;
        }
    }

    componentDidMount() {
        this.props.getSearchData();
    }

    componentWillReceiveProps(nextProps)
    {
        if(nextProps.appPromotion == true) {
            this.setState ({
                showPromo : true
            });
        }
        console.log("data",nextProps.searchData)
       
    }

    componentDidUpdate(prevprops) {
        jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
        if(prevprops.location) {
            if(prevprops.location.search.indexOf("ham=1") != -1 && window.location.search.indexOf("ham=1") == -1) {
                this.refs.Hamchild.getWrappedInstance().hideHam();
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
        
        let ageView = <div id = "search_age">
            <div className="brdr1 pad18">
                <div className="wid45p dispibl">
                    <div className="fullwid" id="search_LAGE">
                        <div className="fl">
                            <div className="color8 f12">Min Age</div>
                            <div className="color8 f17 pt10">
                                <span className="label wid70p">18</span> Years
                            </div>
                        </div>
                        <div className="fr pt8"> 
                            <i className="mainsp arow1"></i>
                        </div>
                    </div>
                </div>
                <div className="wid45p fr mrr5 dispibl">
                    <div className="fullwid" id="search_HAGE">
                        <div className="fl srfrm_wrap">
                            <div className="color8 f12">Max Age</div>
                            <div className="color8 f17 pt10">
                                <span className="label wid70p">70</span> Years
                            </div>
                        </div>
                        <div className="fr pt8"> 
                            <i className="mainsp arow1"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>;
        
        let heightView = <div id = "search_height">
            <div className="brdr1 pad18">
                <div className="wid45p dispibl">
                    <div className="fullwid" id="search_LHEIGHT">
                        <div className="fl">
                            <div className="color8 f12">Min Height</div>
                            <div className="color8 f17 pt10">
                                <span class="label wid70p">4' 0" </span>
                            </div>
                        </div>
                        <div className="fr pt8"> 
                            <i className="mainsp arow1"></i>
                        </div>
                    </div>
                </div>
                <div className="wid45p fr mrr5 dispibl">
                    <div className="fullwid" id="search_HHEIGHT">
                        <div className="fl srfrm_wrap">
                            <div className="color8 f12">Max Height</div>
                            <div className="color8 f17 pt10">
                                <span className="label wid70p">7' </span> Years
                            </div>
                        </div>
                        <div className="fr pt8"> 
                            <i className="mainsp arow1"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>;
        
        let religionView = <div id="search_RELIGION">
            <div className="pad18 brdr1">
                <div className="dispibl srfrm_wrap">
                    <div className="color8 f12">Religion</div>
                    <div className="color8 f17 pt10">
                        <span className="label wid70p">Any Religion</span>
                    </div>
                </div>
                <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
            </div>
        </div>;

        let mtongueView = <div id="search_MTONGUE">
            <div className="pad18 brdr1">
                <div className="dispibl srfrm_wrap">
                    <div className="color8 f12">Mother Tongue</div>
                    <div className="color8 f17 pt10">
                        <span className="label wid70p">Any Mother Tongue</span>
                    </div>
                </div>
                <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
            </div>
        </div>;

        let countryView = <div id="search_COUNTRY">
            <div className="pad18 brdr1">
                <div className="dispibl srfrm_wrap">
                    <div className="color8 f12">Country</div>
                    <div className="color8 f17 pt10">
                        <span className="label wid70p">India</span>
                    </div>
                </div>
                <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
            </div>
        </div>;

        let cityView = <div id="search_CITY">
            <div className="pad18 brdr1">
                <div className="dispibl srfrm_wrap">
                    <div className="color8 f12">State/City</div>
                    <div className="color8 f17 pt10">
                        <span className="label wid70p">Delhi NCR</span>
                    </div>
                </div>
                <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
            </div>
        </div>;

        let incomeView = <div id = "search_INCOME">
            <div className="brdr1 pad18">
                <div className="wid45p dispibl">
                    <div className="fullwid" id="search_LINCOME">
                        <div className="fl">
                            <div className="color8 f12">Min Income</div>
                            <div className="color8 f17 pt10">
                                <span class="label wid70p">Rs. 0</span>
                            </div>
                        </div>
                        <div className="fr pt8"> 
                            <i className="mainsp arow1"></i>
                        </div>
                    </div>
                </div>
                <div className="wid45p fr mrr5 dispibl">
                    <div className="fullwid" id="search_HINCOME">
                        <div className="fl srfrm_wrap">
                            <div className="color8 f12">Max Income</div>
                            <div className="color8 f17 pt10">
                                <span className="label wid70p">and above</span> Years
                            </div>
                        </div>
                        <div className="fr pt8"> 
                            <i className="mainsp arow1"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>;
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
        let educationView = <div id="search_EDUCATION">
            <div className="pad18 brdr1">
                <div className="dispibl srfrm_wrap">
                    <div className="color8 f12">Education</div>
                    <div className="color8 f17 pt10">
                        <span className="label wid70p">Doesn't Matter</span>
                    </div>
                </div>
                <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
            </div>
        </div>;

        let occupationView = <div id="search_OCCUPATION">
            <div className="pad18 brdr1">
                <div className="dispibl srfrm_wrap">
                    <div className="color8 f12">Occupation</div>
                    <div className="color8 f17 pt10">
                        <span className="label wid70p">Doesn't Matter</span>
                    </div>
                </div>
                <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
            </div>
        </div>;

        let manglikView = <div id="search_MANGLIK">
            <div className="pad18 brdr1">
                <div className="dispibl srfrm_wrap">
                    <div className="color8 f12">Manglik</div>
                    <div className="color8 f17 pt10">
                        <span className="label wid70p">Doesn't Matter</span>
                    </div>
                </div>
                <div className="fr wid4p pt8"> <i className="mainsp arow1"></i> </div>
            </div>
        </div>;

        let moreDetailView = <div className="showMoreDiv scrollhid" id="moreDetails">
            {educationView}
            {occupationView}
            {manglikView}
        </div>;

        this.trackJsb9 = 1;
        
        return (
            <div className="bg4" id="SearchFormPage">
                <GA ref="GAchild" />
                {promoView}
                {hamView}
                {errorView}
                {loaderView}
                <div className="fullheight bg4" id="mainContent">
                    {headerView}
                    {genderView}
                    {ageView}
                    {heightView}
                    {religionView}
                    {mtongueView}
                    {countryView}
                    {cityView}
                    {incomeView}
                    {photoView}
                    {moreOptionsView}
                    {moreDetailView}
                    <div id="search_submit" className="bg7 white fullwid dispbl txtc lh50 pinkRipple">Search</div>
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
