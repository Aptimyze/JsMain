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
import {removeProfileLocalStorage,getProfileKeyLocalStorage} from "../../common/components/CacheHelper";
import * as CONSTANTS from '../../common/constants/apiConstants';
import axios from "axios";


class ProfilePage extends React.Component {

    constructor(props)
    {
        super();
        jsb9Fun.recordBundleReceived(this,new Date().getTime());
        jsb9Fun.setJsb9Key(this,'JSNEWMOBPROFILEPAGEURL');
        let profilechecksum = getParameterByName(window.location.href,"profilechecksum");
        let responseTracking = getParameterByName(window.location.href,"responseTracking");
        let stype = getParameterByName(window.location.href,"stype");
        let ownView = false;
        if(getParameterByName(window.location.href,"preview") == 1) {
            ownView = true;
        }

        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            showPromo: false,
            tabArray: ["About","Family","Dpp"],
            displayTab:["About","Family","Looking for"],
            GenderInfo: {"Him" :"Him", "Her":"Her","He":"He","She":"She","His":"His"},
            dataLoaded: false,
            showHistory: false,
            profilechecksum: profilechecksum || "",
            gender: '',
            defaultPicData: "",
            defaultThumbNail: "",
            responseTracking:responseTracking,
            stype:stype,
            disablePhotoLink: false,
            callApi: false,
            listingName: "",
            ownView:ownView,
            ucbrowser:false,
            nextProfileFetched:false

        };
        if(localStorage.getItem('GENDER') == "F") {
            this.state.gender =  "F";
        }
        if(props.fetchedProfilechecksum != false) {
            this.state.callApi = true;
        }
        if (navigator.userAgent.indexOf(' UCBrowser/') >= 0) {
          this.state.ucbrowser = true;
        }
    }

    componentDidUpdate(prevprops) {
      //  console.log('componentDidUpdate');
       jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);


    }
    componentDidMount()
    {
        window.scrollTo(0,0);
        let urlString;
        if(this.state.profilechecksum != "") {
            urlString = "?profilechecksum="+this.state.profilechecksum+"&responseTracking="+this.state.responseTracking+"&stype="+this.state.stype;
        } else if(getParameterByName(window.location.href,"username") != null) {
            urlString = "?username="+getParameterByName(window.location.href,"username")+"&responseTracking="+this.state.responseTracking+"&stype="+this.state.stype;
        }
        else
        {
           
            let contact_id = getParameterByName(window.location.href,"contact_id");
            let actual_offset = getParameterByName(window.location.href,"actual_offset");
            let total_rec = getParameterByName(window.location.href,"total_rec");
            let searchid = getParameterByName(window.location.href,"searchid");
            let stype = getParameterByName(window.location.href,"stype");
            let tupleId = getParameterByName(window.location.href,"tupleId");
            let NAVIGATOR = getParameterByName(window.location.href,"NAVIGATOR");
            let toShowECP = getParameterByName(window.location.href,"toShowECP");
            let similarOf = getParameterByName(window.location.href,"similarOf");
            let fromViewSimilar = getParameterByName(window.location.href,"fromViewSimilar");

            urlString = "?actual_offset=" + parseInt(actual_offset)+ "&total_rec=" + total_rec;

            if(stype != undefined && stype != "undefined"){
                urlString += "&stype=" + stype;
            }
            if(searchid != 1 && searchid != null)
            {
                urlString += "&searchid=" + searchid;
            }
            if(contact_id != undefined && contact_id != "undefined") {
                urlString += "&contact_id=" + contact_id;
            }
            if(tupleId != undefined && tupleId != "undefined"){
                urlString += "&tupleId=" + tupleId;
            }
            if(NAVIGATOR != undefined && NAVIGATOR != "undefined"){
                urlString += "&NAVIGATOR=" + NAVIGATOR;
            }
            if(toShowECP != undefined && toShowECP != "undefined"){
                urlString += "&toShowECP=" + toShowECP;
            }
            if(similarOf != undefined && similarOf != "undefined"){
                urlString += "&similarOf=" + similarOf;
            }
            if(typeof fromViewSimilar != "undefined"){
                urlString += "&fromViewSimilar=" + fromViewSimilar;
            }
        }
        this.props.showProfile(this, urlString);
        if ( getCookie("AUTHCHECKSUM") )
        {
            axios.get("/api/v1/profile/detail"+urlString+"&ul=1");
        }

        let _this = this;
        document.getElementById("ProfilePage").style.height = window.innerHeight+"px";
        document.getElementById("photoParent").style.height = window.innerWidth +"px";
        var backHeight = window.innerHeight - document.getElementById("tabHeader").clientHeight - document.getElementById("photoParent").clientHeight -26;

        if(document.getElementById("animated-background")) {
            document.getElementById("animated-background").style.height = backHeight + "px";
        }
        if(this.state.gender == "M" & this.state.ownView == false) {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto"
            })
        } else if(this.state.gender == "F" & this.state.ownView == false) {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto"
            })
        } else if(this.state.gender == "F" & this.state.ownView == true) {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto"
            })
        } else {
            this.setState({
               defaultPicData : "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto"
            })
        }
        let startX, endX;
        let stype = getParameterByName(window.location.href,"stype");
        document.getElementById("ProfilePage").addEventListener('touchstart', function(e) {
            startX = e.changedTouches[0].clientX;
            endX = 0;
        });
        document.getElementById("ProfilePage").addEventListener('touchmove', function(e) {
            endX = e.changedTouches[0].clientX;
        });
        document.getElementById("ProfilePage").addEventListener('touchend', function(e) {
          // console.log('swipe in');
          // console.log(document.getElementById("comHistoryOverlay"));
          if( (document.getElementById("comHistoryOverlay")!=null) || (document.getElementById("WriteMsgComponent")!=null) || (document.getElementById("overlayove_threedot")!=null)||(document.getElementById("reportAbuseContainer")!=null) || (document.getElementById("reportAbuseContainer")!=null)  ||  (document.getElementById("ReportInvalid")!=null || (document.getElementById("viewContactLayer")!=null) || _this.state.nextProfileFetched == false) )
          {
            return;
          }
          else if(stype == "KM" || stype =="WC") //swipe to be disabled for Kundli Listing
          {
            return;
          }
          else {
            if (endX != 0 && startX - endX > 100 && _this.state.nextUrl != "") {
              _this.swipeNextProfile('next');
              //console.log("s1");
            } else if (endX != 0 && endX - startX > 100 && _this.state.prevUrl != "") {
              _this.swipeNextProfile('prev');
            }
          }

        });


    }

    nextPrevPostDecline(){
      if(this.state.nextUrl != "")
        this.swipeNextProfile('next');
      else if(this.state.prevUrl != "")
        this.swipeNextProfile('prev');
    }
swipeNextProfile(nextOrPrev){
      let _this=this;
      document.getElementById("swipePage").classList.add("animateLeft");
      document.getElementById("validProfile").classList.remove("dn");
      _this.setState({
          dataLoaded: false
      });
      _this.resetTab();
      jsb9Fun.flushJSB9Obj(_this);
      _this.props.jsb9TrackRedirection(new Date().getTime(), window.location.href);
      _this.props.history.replace(nextOrPrev=='next' ? _this.state.nextUrl : _this.state.prevUrl);
      jsb9Fun.recordBundleReceived(_this, new Date().getTime());
      let t1,t2;
      t1 = nextOrPrev=='next' ? 'nextProfileVisit' : 'prevProfileVisit';
      t2 = nextOrPrev=='next' ? _this.state.nextDataApi : _this.state.prevDataApi;
      _this.refs.GAchild.trackJsEventGA("Profile Description-jsms",t1,"")
      _this.props.showProfile(_this, t2);

      if ( getCookie("AUTHCHECKSUM") )
      {
        axios.get("/api/v1/profile/detail"+t2+"&ul=1");
      }

}
    setNextPrevLink() {

        if (parseInt(this.state.actual_offset) < parseInt(this.state.total_rec) - 1) {
            let nextUrl = "/profile/viewprofile.php?responseTracking=" + this.state.responseTracking + "&total_rec=" + this.state.total_rec + "&actual_offset=" + (parseInt(this.state.actual_offset) + 1) + "&stype=" +this.state.stype;
            let nextDataApi = "?actual_offset=" + (parseInt(this.state.actual_offset) + 1)+ "&total_rec=" + this.state.total_rec + "&stype=" + this.state.stype;

            if(this.state.searchid != 1 && this.state.searchid != null){
                nextUrl += "&searchid=" + this.state.searchid;
                nextDataApi += "&searchid=" + this.state.searchid;
            }
            if(this.state.contact_id != undefined && this.state.contact_id != "undefined" ) {
                nextUrl += "&contact_id=" + this.state.contact_id;
                nextDataApi += "&contact_id=" + this.state.contact_id;
            }
            if(this.state.tupleId != null){
                nextUrl += "&tupleId=" + this.state.tupleId;
                nextDataApi += "&tupleId=" + this.state.tupleId;
            }
            if(this.state.NAVIGATOR != null){
                nextUrl += "&NAVIGATOR=" + this.state.NAVIGATOR;
                nextDataApi += "&NAVIGATOR=" + this.state.NAVIGATOR;
            }
            if(this.state.toShowECP != null){
                nextUrl += "&toShowECP=" + this.state.toShowECP;
                nextDataApi += "&toShowECP=" + this.state.toShowECP;
            }
            if(this.state.similarOf != null){
                nextUrl += "&similarOf=" + this.state.similarOf;
                nextDataApi += "&similarOf=" + this.state.similarOf;
            }

            this.props.fetchNextPrevData(this, nextDataApi, "saveLocalNext");
            this.setState({
                nextUrl,
                nextDataApi
            });
        } else {
            this.setState({
                nextUrl: "",
                nextDataApi: "",
                nextProfileFetched:true
            });
        }
        if (parseInt(this.state.actual_offset) != 0 && !isNaN(parseInt(this.state.actual_offset))   ) {
            let prevUrl = "/profile/viewprofile.php?responseTracking=" + this.state.responseTracking + "&total_rec=" + this.state.total_rec + "&actual_offset=" + (parseInt(this.state.actual_offset) - 1) + "&stype=" + this.state.stype;
            let prevDataApi = "?actual_offset=" + (parseInt(this.state.actual_offset) - 1) + "&total_rec=" + this.state.total_rec + "&stype=" + this.state.stype;
            if(this.state.searchid != 1 && this.state.searchid != null){
                prevUrl += "&searchid=" + this.state.searchid;
                prevDataApi += "&searchid=" + this.state.searchid;
            }
            if(this.state.contact_id != undefined && this.state.contact_id != "undefined") {
                prevUrl += "&contact_id=" + this.state.contact_id;
                prevDataApi += "&contact_id=" + this.state.contact_id;
            }
            if(this.state.tupleId != null){
                prevUrl += "&tupleId=" + this.state.tupleId;
                prevDataApi += "&tupleId=" + this.state.tupleId;
            }
            if(this.state.NAVIGATOR != null){
                prevUrl += "&NAVIGATOR=" + this.state.NAVIGATOR;
                prevDataApi += "&NAVIGATOR=" + this.state.NAVIGATOR;
            }
            if(this.state.toShowECP != null){
                prevUrl += "&toShowECP=" + this.state.toShowECP;
                prevDataApi += "&toShowECP=" + this.state.toShowECP;
            }
            if(this.state.similarOf != null){
                prevUrl += "&similarOf=" + this.state.similarOf;
                prevDataApi += "&similarOf=" + this.state.similarOf;
            }
            //this.props.fetchNextPrevData(this, prevDataApi, "saveLocalPrev");
            this.setState({
                prevUrl,
                prevDataApi
            });
        } else {
            this.setState({
                prevUrl: "",
                prevDataApi: "",
                nextProfileFetched:true
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
        if(nextProps.fetchedProfilechecksum != this.props.fetchedProfilechecksum || this.state.callApi == true) {

            let profilechecksum = getParameterByName(window.location.href,"profilechecksum");
            let contact_id = getParameterByName(window.location.href,"contact_id");
            let actual_offset = getParameterByName(window.location.href,"offset") || getParameterByName(window.location.href,"actual_offset");
            let total_rec = getParameterByName(window.location.href,"total_rec");
            let searchid = getParameterByName(window.location.href,"searchid");
            let responseTracking = getParameterByName(window.location.href,"responseTracking");
            let stype = getParameterByName(window.location.href,"stype");
            let tupleId = getParameterByName(window.location.href,"tupleId");
            let NAVIGATOR = getParameterByName(window.location.href,"NAVIGATOR");
            let toShowECP = getParameterByName(window.location.href,"toShowECP");
            let similarOf = getParameterByName(window.location.href,"similarOf");


            if(total_rec == "undefined") {
                total_rec = "20";
            }
            if(!profilechecksum) {
                profilechecksum = nextProps.pageInfo.profilechecksum;
            }
            if(contact_id == "nan") {
                contact_id = undefined;
            }
            if(stype != "KM" && stype != "WC" ) //next profile hit should not go in case of Profiles from Kundli Listings
            {
               this.setState({
                profilechecksum: profilechecksum || "",
                contact_id: contact_id,
                actual_offset: actual_offset,
                total_rec:total_rec,
                responseTracking:responseTracking,
                searchid:searchid,
                callApi: false,
                stype: stype,
                tupleId: tupleId,
                NAVIGATOR: NAVIGATOR,
                similarOf: similarOf,
                toShowECP: toShowECP,
                nextProfileFetched:false
                },this.setNextPrevLink);
            }

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
                document.getElementById("tab"+this.state.tabArray[i]).classList.remove("vpro_selectTab");
                document.getElementById(this.state.tabArray[i]+"Tab").classList.add("dn");
            }
            document.getElementById("tab"+elem).classList.add("vpro_selectTab");
            document.getElementById(elem+"Tab").classList.remove("dn");
        }
    }
    resetTab()
    {
      for(let i=0; i<this.state.tabArray.length; i++) {
          document.getElementById("tab"+this.state.tabArray[i]).classList.remove("vpro_selectTab");

      }
      document.getElementById("tabAbout").classList.add("vpro_selectTab");
    }
    initHistory()
    {
        this.setState({
            showHistory:true
        });
        this.props.historyObject.push(()=>this.closeHistoryTab(),"#comm");
    }
    closeHistoryTab()
    {
        this.setState({
            showHistory:false
        });
        return true;
    }
    checkPhotoAlbum(e)
    {
        if(this.state.disablePhotoLink == false) {
            e.preventDefault();
        }
    }

    imageLoaded()
    {
        if ( document.getElementById("showAbout") !== null )
        {
            document.getElementById("showAbout").classList.remove("dn");
        }
        if ( document.getElementById("showPhoto") !== null )
        {
            document.getElementById("showPhoto").classList.remove("dn");
        }
        if ( document.getElementById("tempImage") !== null)
        {
            document.getElementById("tempImage").classList.add("dn");
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

    goBack()
    {

            history.back();

    }

    render()
    {
        let himHer = "him",photoViewTemp,AboutViewTemp;
        let decideHimHer;

        if(this.state.gender == "M" && this.state.ownView == false)
        {
            photoViewTemp = <img id="tempImage" src = "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto" />;

        } else if(this.state.gender == "F" && this.state.ownView == false)
        {
            photoViewTemp = <img id="tempImage" src = "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto" />;

        } else if(this.state.gender == "M" && this.state.ownView == true)
        {
             photoViewTemp = <img id="tempImage" src = "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto" />;
        } else {
            photoViewTemp = <div id="tempImage" className="fullwid bg18" style={{height: window.innerWidth}}></div>;

        }
        let backBtnView;
        if(this.state.ownView == false) {
            backBtnView = <div id="backBtn" onClick={() => this.goBack()} className="posabs ot_pos1">
                <i className="mainsp arow2"></i>
            </div>;
        }
        var swipeView = <div id="swipePage" className="loader simple white loaderimage posRight100p"></div>;
        var historyIcon;
        if(getCookie("AUTHCHECKSUM") && this.props.responseStatusCode != "1" && this.state.ownView == false) {
            historyIcon = <div id="historyIcon" onClick={() => this.initHistory()} className="posabs vpro_pos1">
                <i className="vpro_sprite vpro_comHisIcon cursp"></i>
            </div>;
        } else if(this.state.ownView == true) {
            historyIcon = <div className="posabs vpro_pos1">
                <a href="/profile/viewprofile.php?ownview=1">
                    <i id="closeMyPreview" className="mainsp vpro_cross1"></i>
                </a>
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
        if(this.state.showHistory && this.state.ownView == false)
        {
          let thumbURL;

          if(this.props.AboutInfo.thumbnailPic==null)
          {

            if(this.props.AboutInfo.gender=="Female")
            {
              thumbURL = "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto";
            }
            else
            {
              thumbURL = "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto"
            }
          }
          else
          {
            thumbURL = this.props.AboutInfo.thumbnailPic;
          }


          historyView = <CommHistory
                            closeHistory={()=>this.props.historyObject.pop(true)}
                            profileId={this.props.profileId}
                            username={this.props.AboutInfo.username}
                            profileThumbNailUrl= {thumbURL} >
                          </CommHistory>
        }

        var AboutView,FamilyView,DppView,Header = "View Profile",photoView,metaTagView='',invalidProfileView,contactEngineView,showAlbumView,stockImage;

        if(this.state.dataLoaded)
        {
            if(this.state.ownView == false)
            {
                if(this.props.AboutInfo.gender == "Male")
                {
                  decideHimHer= this.state.GenderInfo.Him;
                }
                else
                {
                  decideHimHer = this.state.GenderInfo.Her;
                }
            }
            else
            {
              if(this.props.AboutInfo.gender == "Male")
              {
                decideHimHer= this.state.GenderInfo.Him;
              }
              else
              {
                decideHimHer = this.state.GenderInfo.Her;
              }
            }


            document.getElementById("swipePage").classList.remove("animateLeft");
            if(this.props.responseStatusCode == "0") {

                let profiledata = {
                    profilechecksum : this.state.profilechecksum,
                    responseTracking: this.state.responseTracking,
                    profileThumbNailUrl: this.props.pageInfo.thumb_url || this.state.defaultPicData,
                    username:this.props.AboutInfo.username
                };
                if(this.state.ownView == false) {
                     contactEngineView = <ContactEngineButton NAVIGATOR={this.props.pageInfo.NAVIGATOR} nextPrevPostDecline={this.nextPrevPostDecline.bind(this)} pageSource='VDP' showError={(inp)=>this.showError(inp)} setScroll={()=>this.setState({profilePageStyle:{overflow:'initial'}})} showLoaderDiv={()=> this.showLoaderDiv()} unsetScroll={()=>this.setState({profilePageStyle:{overflow:'hidden'}})} hideLoaderDiv={()=>this.hideLoaderDiv()} profiledata={profiledata} buttondata={this.props.buttonDetails} pagesrcbtn="pd"/>;
                }

                photoView = <div id="showPhoto" className="dn"><PhotoView defaultPhoto={this.state.defaultPicData} imageLoaded={this.imageLoaded}  verification_status={this.props.AboutInfo.complete_verification_status} profilechecksum={this.state.profilechecksum} picData={this.state.pic} genderPic= {this.props.AboutInfo.gender} /></div>;

                if(this.state.ownView){
                    Header = "Preview";
                }
                else{
                    if(this.props.AboutInfo.name_of_user)
                    {
                        Header = this.props.AboutInfo.name_of_user;
                    }  else
                    {
                         Header = this.props.AboutInfo.username;
                    }
                }

                AboutView = <div id="showAbout"><AboutTab show_gunascore={this.props.show_gunascore} profilechecksum={this.state.profilechecksum} life={this.props.LifestyleInfo} about={this.props.AboutInfo} astroSent={this.props.astroSent} checkUC={this.state.ucbrowser}></AboutTab></div>;

                FamilyView = <FamilyTab username={this.props.AboutInfo.username} family={this.props.FamilyInfo} checkUC={this.state.ucbrowser}></FamilyTab>;

                DppView = <DppTab selfPicUrl={this.props.AboutInfo.selfThumbail} about={this.props.AboutInfo} dpp_Ticks={this.props.dpp_Ticks}  dpp={this.props.DppInfo} checkOwnView={this.state.ownView} checkUC={this.state.ucbrowser}></DppTab>;

                metaTagView = <MetaTagComponents page="ProfilePage" meta_tags={this.props.pageInfo.meta_tags}/>;

                showAlbumView = this.props.pic.pic_count == 0 ?
                  (
                    <div id="showAlbum"><div id="photoParent" style={{height:window.innerWidth +"px"}} className="fullwid scrollhid">
                      {photoView}
                      {photoViewTemp}
                  </div></div>) : getCookie("AUTHCHECKSUM") ?
                (<Link id="showAlbum" onClick={(e) => this.checkPhotoAlbum(e)}  to={"/social/MobilePhotoAlbum?profilechecksum="+this.state.profilechecksum}>
                    <div id="photoParent" style={{height:window.innerWidth +"px"}} className="fullwid scrollhid">
                        {photoView}
                        {photoViewTemp}
                    </div>
                </Link>) :
                      (<Link id="showAlbum" to={"/login?prevUrl="+window.location.href}>
                          <div id="photoParent" style={{height:window.innerWidth +"px"}} className="fullwid scrollhid">
                          {photoView}
                          {photoViewTemp}
                      </div>
                      </Link>)

            } else if(this.props.responseStatusCode == "1") {
                document.getElementById("validProfile").classList.add("dn");
                if(this.state.ownView){
                    Header = "Preview";
                }
                else{
                    if(this.props.AboutInfo.username) {
                        Header = this.props.AboutInfo.username;
                    } else {
                        Header = "Profile not found";
                    }
                }
                if(this.props.AboutInfo.loginRequired)
                {

                    invalidProfileView = <div className="txtc r_vpro_pad1">
                    <div className="f16 fontlig color13 pb20"> To view this profile </div>

                    <a href="/login">
                    <i className="vpro_sprite vpro_login"></i>
                    <div className="f14 color2 fontlig">Login</div>
                    </a>
                    <div className="r_vpro_pad2"></div>

                    <a href="/register/page1?source=mobreg6" >
                    <i className="vpro_sprite vpro_regis"></i>
                    <div className="f1  4 color2 fontlig">Register</div>
                    </a>
                    </div>;
                }
                else
                {
                    if(this.props.AboutInfo.gender == "F")
                        stockImage = <i className="vpro_sprite female_nopro"></i>
                    else
                        stockImage = <i className="vpro_sprite male_nopro"></i>

                    invalidProfileView = <div>
                    <div className="bg4 txtc" id="errorContent">
                    <div className="txtc setmid posfix fullwid" id="noProfileIcon">
                    {stockImage}
                    <div className="f14 fontreg color13 lh30">{this.props.responseMessage}</div>
                    </div>
                    </div>
                    </div>;
                    metaTagView = <MetaTagComponents page="ProfileNotFound" />
                }

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
            showAlbumView = <div id="photoParent" style={{height:window.innerWidth +"px"}} className="fullwid scrollhid"></div>;


            if( document.getElementById("photoParent"))
            {
              setTimeout(function(){
                  var backHeightn = window.innerHeight - document.getElementById("tabHeader").clientHeight - document.getElementById("photoParent").clientHeight -26;
                  if(document.getElementById("animated-background")) {
                      document.getElementById("animated-background").style.height = backHeightn + "px";
                  }
              },100);
            }

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
                                {backBtnView}
                                <div className="fontthin f19 white headerOverflow" id="vpro_headerTitle">
                                    {Header}
                                </div>
                                {historyIcon}
                            </div>
                        </div>
                    </div>
                    {invalidProfileView}
                    <div id="validProfile" className="">
                    {showAlbumView}
                        <div id="tab" className="fullwid tabBckImage posabs mtn39">
                            <div id="tabContent" className="fullwid bg2 vpro_pad5 fontlig posrel">
                                <div id="tabAbout" onClick={() => this.showTab("About")} className="dispibl wid29p f12 vpro_selectTab">
                                  {this.state.displayTab[0]}  {decideHimHer}
                                </div>
                                <div id="tabFamily" onClick={() => this.showTab("Family")} className="dispibl wid40p txtc f12">
                                  {this.state.displayTab[1]}
                                </div>
                                <div id="tabDpp" onClick={() => this.showTab("Dpp")}  className="dispibl wid30p txtr f12">
                                  {this.state.displayTab[2]}
                                </div>
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
       astroSent: state.ProfileReducer.astroSent,
       fetchedProfilechecksum: state.ProfileReducer.fetchedProfilechecksum,
       pageInfo: state.ProfileReducer.pageInfo,
       myjsData: state.MyjsReducer,
       Jsb9Reducer : state.Jsb9Reducer,
       buttonDetails: state.ProfileReducer.buttonDetails,
       contactAction: state.contactEngineReducer,
       historyObject : state.historyReducer.historyObject
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        showProfile: (containerObj,urlString) => {
            containerObj.state.nextProfileFetched = false;
            if ( localStorage.getItem('lastProfilePageLocation') === urlString )
            {
              removeProfileLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,getProfileKeyLocalStorage(urlString));
            }
            else
            {
              localStorage.setItem('lastProfilePageLocation',urlString);
            }
            let call_url = "/api/v1/profile/detail"+urlString;
            commonApiCall(call_url,{},'SHOW_INFO','GET',dispatch,true,containerObj);
        },
        fetchNextPrevData: (containerObj,urlString,saveState) => {
            containerObj.state.nextProfileFetched = false;
            let call_url = "/api/v1/profile/detail"+urlString;
            try
            {
                commonApiCall(call_url,{},'SAVE_INFO','GET',saveState,true,containerObj).then(function (response) {
                    containerObj.state.nextProfileFetched = true;
                });
            }
            catch(e)
            {
                containerObj.state.nextProfileFetched = true;
            }
        },
        jsb9TrackRedirection : (time,url) => {
            jsb9Fun.recordRedirection(dispatch,time,url)
        }
      }
}

export default connect(mapStateToProps,mapDispatchToProps)(ProfilePage)
