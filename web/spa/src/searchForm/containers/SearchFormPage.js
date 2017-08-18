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
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            loggeInStatus: false,
            showPromo: false
        };
        if(getCookie("AUTHCHECKSUM")) {
            this.state.loggeInStatus = true;
        }
    }

    componentDidMount() {
        document.getElementById("SearchFormPage").style.height = window.innerHeight+"px";
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
        if(prevprops.location) {
            if(prevprops.location.search.indexOf("ham=1") != -1 && window.location.search.indexOf("ham=1") == -1) {
                this.refs.Hamchild.getWrappedInstance().hideHam();
            }
        }
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
            genderView = <div id="search_gender">
                <div className="pad3 brdr1 txtc">
                    <div className="brdr12 fullwid">
                        <div id="searchform_genderF" onClick={(e) => this.changeTab(e)} className="defaultTab selectedTab">
                            Bride
                        </div>
                        <div id="searchform_genderM" onClick={(e) => this.changeTab(e)} className="defaultTab">
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
        let photoView = <div id="search_photo">
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
                    {photoView}
                    <div id="search_submit" className="bg7 white fullwid dispbl txtc lh50 pinkRipple">Search</div>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
       searchData: state.SearchFormReducer.searchData
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        getSearchData: () => {
            let call_url = '/api/v1/search/searchFormData?json={"searchForm":"2013-12-25 00:00:00"}';
            commonApiCall(call_url,{},'GET_SEARCH_DATA','POST',dispatch);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(SearchFormPage)
