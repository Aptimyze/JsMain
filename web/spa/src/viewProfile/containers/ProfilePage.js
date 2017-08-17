require ('../style/profile.css')

import React from "react";
import {connect} from "react-redux";
import {Link} from "react-router-dom";

import Loader from "../../common/components/Loader";
import {getParameterByName} from '../../common/components/UrlDecoder';
import AppPromo from "../../common/components/AppPromo";
import TopError from "../../common/components/TopError";
import PhotoView from "../../common/containers/PhotoView";
import AboutTab from"../components/AboutTab";
import FamilyTab from"../components/FamilyTab";
import DppTab from"../components/DppTab";
import CommHistory from "./CommHistory";
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";
import {getCookie} from '../../common/components/CookieHelper';
import GA from "../../common/components/GA";
import * as jsb9Fun from '../../common/components/Jsb9CommonTracking';
import ContactEngineButton from "../../contact_engine/containers/contactEnginePD";
import MetaTagComponents from '../../common/components/MetaTagComponents';

class ProfilePage extends React.Component {

    constructor(props)
    {
        super();
        jsb9Fun.recordBundleReceived(this,new Date().getTime());
        let profilechecksum = getParameterByName(window.location.href,"profilechecksum");
        let responseTracking = getParameterByName(window.location.href,"responseTracking");
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            showPromo: false,
            tabArray: ["About","Family","Dpp"],
            dataLoaded: false,
            showHistory: false,
            profilechecksum: profilechecksum || "",
            gender: "M",
            defaultPicData: "",
            defaultThumbNail: "",
            responseTracking:responseTracking,
            disablePhotoLink: false,
            callApi: false,
            listingName: ""
        };
        if(localStorage.getItem('GENDER') == "F") {
            this.state.gender =  "F";
        }
        if(props.fetchedProfilechecksum != false) {
            this.state.callApi = true;
        }
    }

    componentDidUpdate(prevprops) {
       jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer)
    }
    componentDidMount()
    {
        let urlString;
        if(this.state.profilechecksum != "") {
            urlString = "?profilechecksum="+this.state.profilechecksum+"&responseTracking="+this.state.responseTracking;
        } else {
            let contact_id = getParameterByName(window.location.href,"contact_id");
            let actual_offset = getParameterByName(window.location.href,"actual_offset");
            let total_rec = getParameterByName(window.location.href,"total_rec");
            let searchid = getParameterByName(window.location.href,"searchid");

            urlString = "?actual_offset=" + parseInt(actual_offset)+ "&total_rec=" + total_rec;

            if(searchid != 1 && searchid != null)
            {
                urlString += "&searchid=" + searchid;
            } else if(contact_id != undefined) {
                urlString += "&contact_id=" + contact_id;
            }
        }

        this.props.showProfile(this, urlString);
        let _this = this;
        document.getElementById("ProfilePage").style.height = window.innerHeight+"px";
      //  document.getElementById("photoParent").style.height = window.innerWidth +"px";
        var backHeight = window.innerHeight - document.getElementById("tabHeader").clientHeight - document.getElementById("photoParent").clientHeight -26;
        if(document.getElementById("animated-background")) {
            document.getElementById("animated-background").style.height = backHeight + "px";
        }
        if(this.state.gender == "M") {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto"
            })
        } else {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto"
            })
        }
        let startX, endX;
        document.getElementById("ProfilePage").addEventListener('touchstart', function(e) {
            startX = e.changedTouches[0].clientX;
            endX = 0;
        });
        document.getElementById("ProfilePage").addEventListener('touchmove', function(e) {
            endX = e.changedTouches[0].clientX;
        });
        document.getElementById("ProfilePage").addEventListener('touchend', function(e) {
            if (endX != 0 && startX - endX > 100 && _this.state.nextUrl != "") {
                document.getElementById("swipePage").classList.add("animateLeft");
                document.getElementById("validProfile").classList.remove("dn");
                _this.setState({
                    dataLoaded: false
                });
                jsb9Fun.flushJSB9Obj(_this);
                _this.props.jsb9TrackRedirection(new Date().getTime(), window.location.href);
                _this.props.history.push(_this.state.nextUrl);
                jsb9Fun.recordBundleReceived(_this, new Date().getTime());
                _this.props.showProfile(_this, _this.state.nextDataApi);
            } else if (endX != 0 && endX - startX > 100 && _this.state.prevUrl != "") {
                document.getElementById("swipePage").classList.add("animateLeft");
                document.getElementById("validProfile").classList.remove("dn");
                jsb9Fun.flushJSB9Obj(_this);
                _this.setState({
                    dataLoaded: false
                });
                _this.props.jsb9TrackRedirection(new Date().getTime(), window.location.href);
                _this.props.history.push(_this.state.prevUrl);
                jsb9Fun.recordBundleReceived(_this, new Date().getTime());
                _this.props.showProfile(_this, _this.state.prevDataApi);
            }
        });
    }

    setNextPrevLink() {
        if (parseInt(this.state.actual_offset) < parseInt(this.state.total_rec) - 1) {
            let nextUrl = "/profile/viewprofile.php?responseTracking=" + this.state.responseTracking + "&total_rec=" + this.state.total_rec + "&actual_offset=" + (parseInt(this.state.actual_offset) + 1);
            let nextDataApi = "?actual_offset=" + (parseInt(this.state.actual_offset) + 1)+ "&total_rec=" + this.state.total_rec;

            if(this.state.searchid != 1 && this.state.searchid != null){
                nextUrl += "&searchid=" + this.state.searchid;
                nextDataApi += "&searchid=" + this.state.searchid;
            } else if(this.state.contact_id != undefined) {
                nextUrl += "&contact_id=" + this.state.contact_id;
                nextDataApi += "&contact_id=" + this.state.contact_id;
            }
            this.props.fetchNextPrevData(this, nextDataApi, "saveLocalNext");
            this.setState({
                nextUrl,
                nextDataApi
            });
        } else {
            this.setState({
                nextUrl: "",
                nextDataApi: ""
            });
        }
        if (parseInt(this.state.actual_offset) != 0) {
            let prevUrl = "/profile/viewprofile.php?responseTracking=" + this.state.responseTracking + "&total_rec=" + this.state.total_rec + "&actual_offset=" + (parseInt(this.state.actual_offset) - 1);
            let prevDataApi = "?actual_offset=" + (parseInt(this.state.actual_offset) - 1) + "&total_rec=" + this.state.total_rec;
            if(this.state.searchid != 1 && this.state.searchid != null){
                prevUrl += "&searchid=" + this.state.searchid;
                prevDataApi += "&searchid=" + this.state.searchid;
            } else if(this.state.contact_id != undefined) {
                prevUrl += "&contact_id=" + this.state.contact_id;
                prevDataApi += "&contact_id=" + this.state.contact_id;
            }
            this.props.fetchNextPrevData(this, prevDataApi, "saveLocalPrev");
            this.setState({
                prevUrl,
                prevDataApi
            });
        } else {
            this.setState({
                prevUrl: "",
                prevDataApi: ""
            });
        }


    }
    showLoaderDiv() {
        this.setState({
            showLoader:true
        });
    }
    hideLoaderDiv() {
        this.setState({
            showLoader:false
        });
    }

    componentWillReceiveProps(nextProps)
    {
        if(nextProps.contactAction.acceptDone || nextProps.contactAction.reminderDone || nextProps.contactAction.contactDone){
            this.setState({
                showLoader:false
            });
        }
        else if(nextProps.contactAction.declineDone && nextProps.fetchedProfilechecksum == this.props.fetchedProfilechecksum && this.state.dataLoaded == true){
            this.setState({
                showLoader:false
            });
            document.getElementById("swipePage").classList.add("animateLeft");
            this.setState ({
                dataLoaded : false
            });
            jsb9Fun.flushJSB9Obj(this);
            this.props.jsb9TrackRedirection(new Date().getTime(),window.location.href);
            this.props.history.push(this.state.nextUrl);
            jsb9Fun.recordBundleReceived(this,new Date().getTime());
            this.props.showProfile(this,this.state.nextprofilechecksum,this.state.responseTracking);
        }
        else if(nextProps.fetchedProfilechecksum != this.props.fetchedProfilechecksum || this.state.callApi == true) {

            let profilechecksum = getParameterByName(window.location.href,"profilechecksum");
            let contact_id = getParameterByName(window.location.href,"contact_id");
            let actual_offset = getParameterByName(window.location.href,"actual_offset");
            let total_rec = getParameterByName(window.location.href,"total_rec");
            let searchid = getParameterByName(window.location.href,"searchid");
            let responseTracking = getParameterByName(window.location.href,"responseTracking");

            if(total_rec == "undefined") {
                total_rec = "20";
            }
            if(!profilechecksum) {
                profilechecksum = nextProps.pageInfo.profilechecksum;
            }
            if(contact_id == "nan") {
                contact_id = undefined;
            }

            this.setState({
                profilechecksum: profilechecksum || "",
                contact_id: contact_id,
                actual_offset: actual_offset,
                total_rec:total_rec,
                responseTracking:responseTracking,
                searchid:searchid,
                callApi: false
            },this.setNextPrevLink);
            let picData;
            if(!nextProps.pic) {
                if(this.state.gender == "M") {
                   picData = {url: "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto"};
                } else {
                    picData = {url: "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto"};
                }
            } else {
                picData = nextProps.pic;
            }
            this.setState ({
                dataLoaded : true,
                pic: picData
            });

            if(nextProps.appPromotion == true) {
                this.setState ({
                    showPromo : true
                });
            }
            window.addEventListener('scroll', this.setScrollPos);
            //let _this2 = this;
            if(nextProps.pic) {
                if(nextProps.pic.action == null) {
                    this.setState({disablePhotoLink: true})
                }
            }


            //calling tracking event
            /*setTimeout(function(){
                console.log("mm",_this.refs.GAchild.trackJsEventGA("jsms","new","2"))
            },3000);
            */
        }
        else if(nextProps.location.search != this.props.location.search && this.state.dataLoaded == true)
        {
            if(this.props.history.prevUrl) {
              this.props.history.push(this.props.history.prevUrl);
            }
        }

    }

    componentWillUnmount()
    {
        //this.props.fetchedProfilechecksum = "false";
        window.removeEventListener('scroll', this.setScrollPos);
        this.props.jsb9TrackRedirection(new Date().getTime(),this.url);
    }

    setScrollPos()
    {
        let tabElem = document.getElementById("tab");
        if(tabElem.getBoundingClientRect().top < 0 && !tabElem.classList.contains("posFixTop")) {
            tabElem.classList.add("posFixTop");
        }
        if(document.getElementById("photoParent").getBoundingClientRect().bottom > 30 && tabElem.classList.contains("posFixTop")) {
            tabElem.classList.remove("posFixTop");
        }
    }

    removePromoLayer()
    {
        this.setState ({
            showPromo : false
        });
        document.getElementById("mainContent").classList.remove("ham_b100");
    }

    showTab(elem)
    {
        if(this.state.dataLoaded == true) {
            for(let i=0; i<this.state.tabArray.length; i++) {
                document.getElementById(this.state.tabArray[i]+"Header").classList.remove("vpro_selectTab");
                document.getElementById(this.state.tabArray[i]+"Tab").classList.add("dn");
            }
            document.getElementById(elem+"Header").classList.add("vpro_selectTab");
            document.getElementById(elem+"Tab").classList.remove("dn");
        }
    }
    initHistory()
    {
        this.setState({
            showHistory:true
        });
    }
    closeHistoryTab()
    {
        this.setState({
            showHistory:false
        });
    }
    checkPhotoAlbum(e)
    {
        if(this.state.disablePhotoLink == false) {
            e.preventDefault();
        }

    }

    imageLoaded()
    {
        document.getElementById("showAbout").classList.remove("dn");
        document.getElementById("showPhoto").classList.remove("dn");
    }

    goBack()
    {
        if ( typeof this.props.history.prevUrl == 'undefined' )
        {
            this.props.history.push("/myjs");
        }
        else
        {
            this.props.history.push(this.props.history.prevUrl);
        }
    }

    render()
    {
        var himHer = "him",photoViewTemp,AboutViewTemp;
        if(this.state.gender == "M") {
            himHer = "her";
            photoViewTemp = <img src = "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto" />;

        } else {
            photoViewTemp = <img src = "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto" />;

        }
        var swipeView = <div id="swipePage" className="loader simple white loaderimage posRight100p"></div>;
        var historyIcon;
        if(getCookie("AUTHCHECKSUM") && this.props.responseStatusCode != "1") {
            historyIcon = <div id="historyIcon" onClick={() => this.initHistory()} className="posabs vpro_pos1">
                <i className="vpro_sprite vpro_comHisIcon cursp"></i>
            </div>;
        }
        var errorView;
        if(this.state.insertError)
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }

        var loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="page"></Loader>;
        }

        var promoView;
        if(this.state.showPromo)
        {
            promoView = <AppPromo parentComp="others" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }

        var historyView;
        if(this.state.showHistory) {
            historyView = <CommHistory
                            closeHistory={()=>this.closeHistoryTab()}
                            profileId={this.props.profileId}
                            username={this.props.AboutInfo.username}
                            profileThumbNailUrl={this.props.AboutInfo.thumbnailPic|| this.state.defaultPicData} >
                          </CommHistory>
        }

        var AboutView,FamilyView,DppView,Header = "View Profile",photoView,metaTagView='',invalidProfileView,contactEngineView;

        if(this.state.dataLoaded)
        {
            document.getElementById("swipePage").classList.remove("animateLeft");
            if(this.props.responseStatusCode == "0") {

                let profiledata = {
                    profilechecksum : this.state.profilechecksum,
                    responseTracking: this.state.responseTracking,
                    profileThumbNailUrl: this.props.AboutInfo.thumbnailPic || this.state.defaultPicData,
                    username:this.props.AboutInfo.username
                };

                contactEngineView = <ContactEngineButton setScroll={()=>this.setState({profilePageStyle:{overflowY:'initial'}})} showLoaderDiv={()=> this.showLoaderDiv()} unsetScroll={()=>this.setState({profilePageStyle:{overflowY:'hidden'}})} hideLoaderDiv={()=>this.hideLoaderDiv()} profiledata={profiledata} buttondata={this.props.buttonDetails} pagesrcbtn="pd"/>;

                photoView = <div id="showPhoto" className="dn"><PhotoView defaultPhoto={this.state.defaultPicData} imageLoaded={this.imageLoaded}  verification_status={this.props.AboutInfo.verification_status} profilechecksum={this.state.profilechecksum} picData={this.state.pic}  /></div>;

                if(this.props.AboutInfo.name_of_user)
                {
                    Header = this.props.AboutInfo.name_of_user;
                } else
                {
                     Header = this.props.AboutInfo.username;
                }

                AboutView = <div id="showAbout"><AboutTab show_gunascore={this.props.show_gunascore} profilechecksum={this.state.profilechecksum} life={this.props.LifestyleInfo} about={this.props.AboutInfo}></AboutTab></div>;

                FamilyView = <FamilyTab family={this.props.FamilyInfo}></FamilyTab>;

                DppView = <DppTab about={this.props.AboutInfo} dpp_Ticks={this.props.dpp_Ticks}  dpp={this.props.DppInfo}></DppTab>;

                metaTagView = <MetaTagComponents page="ProfilePage" meta_tags={this.props.pageInfo.meta_tags}/>

            } else if(this.props.responseStatusCode == "1") {
                document.getElementById("validProfile").classList.add("dn");

                invalidProfileView = <div>
                    <div className="bg4 txtc" id="errorContent">
                        <div className="txtc setmid posfix fullwid" id="noProfileIcon">
                            <i className="vpro_sprite female_nopro"></i>
                            <div className="f14 fontreg color13 lh30">{this.props.responseMessage}</div>
                        </div>
                    </div>
                </div>;
                if(this.props.AboutInfo.username) {
                    Header = this.props.AboutInfo.username;
                } else {
                    Header = "Profile not found";
                }
                metaTagView = <MetaTagComponents page="ProfileNotFound" />
            }

        }
        else
        {
            AboutViewTemp = <div id="preLoader" className="timeline-wrapper">
                <div className="timeline-item">
                    <div id="animated-background" className="animated-background">
                        <div className="background-masker div1"></div>
                        <div className="background-masker div2"></div>
                        <div className="background-masker div3"></div>
                        <div className="background-masker div4"></div>
                        <div className="background-masker div5"></div>
                        <div className="background-masker div6"></div>
                        <div className="background-masker div7"></div>
                        <div className="background-masker div8"></div>
                        <div className="background-masker div9"></div>
                        <div className="background-masker div10"></div>
                        <div className="background-masker div11"></div>
                        <div className="background-masker div12"></div>
                        <div className="background-masker div13"></div>
                        <div className="background-masker div14"></div>
                        <div className="background-masker div15"></div>
                        <div className="background-masker div16"></div>
                        <div className="background-masker div17"></div>
                    </div>
                </div>
            </div>;
            setTimeout(function(){
                var backHeight = window.innerHeight - document.getElementById("tabHeader").clientHeight - document.getElementById("photoParent").clientHeight -26;
                if(document.getElementById("animated-background")) {
                    document.getElementById("animated-background").style.height = backHeight + "px";
                }
            },100);
        }
        return (
            <div style={this.state.profilePageStyle} id="ProfilePage">
                <GA ref="GAchild" />
                {metaTagView}
                {promoView}
                {errorView}
                {loaderView}
                {historyView}
                {swipeView}
                <div className="fullheight bg4" id="mainContent">
                    <div id="tabHeader" className="fullwid bg1">
                        <div className="padd22 txtc">
                            <div className="posrel">
                                <div id="backBtn" onClick={() => this.goBack()} className="posabs ot_pos1">
                                    <i className="mainsp arow2"></i>
                                </div>
                                <div className="fontthin f19 white headerOverflow" id="vpro_headerTitle">
                                    {Header}
                                </div>
                                {historyIcon}
                            </div>
                        </div>
                    </div>
                    {invalidProfileView}
                    <div id="validProfile" className="">
                      {this.props.pic ?
                        (<Link id="showAlbum" onClick={(e) => this.checkPhotoAlbum(e)}  to={"/social/MobilePhotoAlbum?profilechecksum="+this.state.profilechecksum}>
                            <div id="photoParent" style={{height:window.innerWidth +"px"}} className="fullwid scrollhid">
                                {photoView}
                                {photoViewTemp}
                            </div>
                        </Link>) : (
                          <div id="showAlbum"><div id="photoParent" style={{height:window.innerWidth +"px"}} className="fullwid scrollhid">
                            {photoView}
                            {photoViewTemp}
                        </div></div>)}
                        <div id="tab" className="fullwid tabBckImage posabs mtn39">
                            <div id="tabContent" className="fullwid bg2 vpro_pad5 fontlig posrel">
                                <div id="AboutHeader" onClick={() => this.showTab("About")} className="dispibl wid29p f12 vpro_selectTab">About  {himHer} </div>
                                <div id="FamilyHeader" onClick={() => this.showTab("Family")} className="dispibl wid40p txtc f12 opa70">Family</div>
                                <div id="DppHeader" onClick={() => this.showTab("Dpp")}  className="dispibl wid30p txtr f12 opa70">Looking for</div>
                                <div className="clr"></div>
                            </div>
                        </div>
                    </div>
                    {AboutView}
                    {AboutViewTemp}
                    {FamilyView}
                    {DppView}
                </div>
                {contactEngineView}
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
       responseStatusCode: state.ProfileReducer.responseStatusCode,
       responseMessage: state.ProfileReducer.responseMessage,
       AboutInfo: state.ProfileReducer.aboutInfo,
       FamilyInfo: state.ProfileReducer.familyInfo,
       DppInfo: state.ProfileReducer.dppInfo,
       appPromotion : state.ProfileReducer.appPromotion,
       pic: state.ProfileReducer.pic,
       LifestyleInfo: state.ProfileReducer.lifestyle,
       dpp_Ticks: state.ProfileReducer.dpp_Ticks,
       profileId: state.ProfileReducer.profileId,
       show_gunascore:state.ProfileReducer.show_gunascore,
       fetchedProfilechecksum: state.ProfileReducer.fetchedProfilechecksum,
       pageInfo: state.ProfileReducer.pageInfo,
       myjsData: state.MyjsReducer,
       Jsb9Reducer : state.Jsb9Reducer,
       buttonDetails: state.ProfileReducer.buttonDetails,
       contactAction: state.contactEngineReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        showProfile: (containerObj,urlString) => {
            let call_url = "/api/v1/profile/detail"+urlString;
            commonApiCall(call_url,{},'SHOW_INFO','GET',dispatch,true,containerObj);
        },
        fetchNextPrevData: (containerObj,urlString,saveState) => {
            let call_url = "/api/v1/profile/detail"+urlString;
            commonApiCall(call_url,{},'SAVE_INFO','GET',saveState,true,containerObj);
        },
        jsb9TrackRedirection : (time,url) => {
            jsb9Fun.recordRedirection(dispatch,time,url)
        }
      }
}

export default connect(mapStateToProps,mapDispatchToProps)(ProfilePage)
