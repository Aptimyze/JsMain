require ('../style/ham.css')
import React from "react";
import {Link} from "react-router-dom";
import { getAndroidVersion, getIosVersion} from "../../common/components/commonFunctions";
import {getCookie,setCookie,removeCookie} from '../../common/components/CookieHelper';
import axios from "axios";
import * as CONSTANTS from '../../common/constants/apiConstants'
import * as API_SERVER_CONSTANTS from '../../common/constants/apiServerConstants'
import Loader from "../../common/components/Loader";
import { connect } from "react-redux";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';


class HamMain extends React.Component {

    constructor(props)
    {
        super();
        this.state = {
            showLoader:false,
            bellResponse: props.bellResponse || "notDefined"
        }
        this.resizeHam = this.resizeHam.bind(this);
    }

    translateSite(translateURL)
    {
        if(translateURL.indexOf('hindi')!=-1){
            setCookie("jeevansathi_hindi_site_new","Y",100,".jeevansathi.com");
        } else {
            setCookie("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
        }
    }

    componentWillReceiveProps(nextProps)
    {
        if(this.state.bellResponse == "notDefined") {
            this.setState({
                bellResponse: nextProps.myjsData.apiDataHam.hamburgerDetails
            },this.checkHeight);
        }
    }

    componentDidMount()
    {
        if(!this.props.bellResponse && this.props.page == "others") {
            this.props.getHamData();
        }
        document.getElementById("settingsMinor").style.height = "0px";
        if(this.props.page == "others" && this.state.bellResponse != "notDefined") {
            this.checkHeight();
        } else {
            document.getElementById("mainHamDiv").style.height = (window.innerHeight-84)+"px";
            document.getElementById("scrollElem").style.height = (window.innerHeight-document.getElementById("bottomTab").getBoundingClientRect().height)+"px";
        }
        window.addEventListener("resize", this.resizeHam);
    }

    resizeHam() {
        if(this.state.bellResponse.MEMBERSHIPT_TOP == null || !this.state.bellResponse.MEMBERSHIPT_TOP) {
            document.getElementById("mainHamDiv").style.height = (window.innerHeight-84)+"px";
        } else {
            document.getElementById("mainHamDiv").style.height = (window.innerHeight-100)+"px";
        }
    }
    componentWillUnmount()
    {
        window.removeEventListener('resize', this.resizeHam);
    }

    checkHeight() {

        if(this.state.bellResponse.MEMBERSHIPT_TOP == null || !this.state.bellResponse.MEMBERSHIPT_TOP) {
                document.getElementById("mainHamDiv").style.height = (window.innerHeight-84)+"px";
        } else {
            document.getElementById("mainHamDiv").style.height = (window.innerHeight-100)+"px";
        }
        document.getElementById("scrollElem").style.height = (window.innerHeight-document.getElementById("bottomTab").getBoundingClientRect().height)+"px";
        document.getElementById("myMatchesMinor").style.height = "0px";
        document.getElementById("contactsMinor").style.height = "0px";
    }

    logoutAccount() {
        this.setState({showLoader:true});
        this.hideHam();

        axios.get(API_SERVER_CONSTANTS.API_SERVER+"/static/logoutPage")
        .then(function(response){
            removeCookie("AUTHCHECKSUM");
            localStorage.clear();
            window.location.href="/";
        })
    }

    checkHome(e)
    {
        if(window.location.pathname == "/" || window.location.pathname == "/login/") {
           e.preventDefault();
           this.hideHam();
        }
    }

    scrollAnimate(element, difference,time) {
        if (difference <= 0) return;
        time = difference/time;
        let _this = this;
        setTimeout(function() {
            element.scrollTop = element.scrollTop + 7;
            _this.scrollAnimate(element, difference-7);
        }, time);
    }

    expandListing(e)
    {
        let element = document.getElementById(e);
        let minorElem = element.id.split("Parent")[0] +"Minor";
        if(element.className.indexOf("plusParent") != -1) {
            element.classList.remove("plusParent");
            document.getElementById(minorElem).style.height = "0px";
        } else {
            element.classList.add("plusParent");
            let liElems = document.getElementById(minorElem).getElementsByTagName("li");
            let minorLiHeight = 0;
            for(let i=0;i<liElems.length;i++) {
                minorLiHeight += liElems[i].getBoundingClientRect().height +15;
            }
            minorLiHeight -=20;
            let listingLen = document.getElementById(minorElem).getElementsByTagName("li").length;
            document.getElementById(minorElem).style.height = minorLiHeight + "px";
            let differHeight = document.getElementById(minorElem).getElementsByTagName("li")[listingLen-1].getBoundingClientRect().bottom - document.getElementById("bottomTab").getBoundingClientRect().top + 10;
            let _this = this;
            setTimeout(function(){
               _this.scrollAnimate(document.getElementById('scrollElem'),differHeight,400)
            },200);
        }
    }

    openHam()
    {
        document.getElementById("mainContent").classList.add("scrollhid");
        document.getElementById("hamView").classList.add("z99")
        document.getElementById("hamView").classList.remove("dn")
        document.getElementById("hamView").classList.add("backShow")
        document.getElementById("hamburger").classList.add("hamShow")
    }

    hideHam()
    {
        document.getElementById("hamView").classList.remove("z99")
        document.getElementById("hamView").classList.add("dn")
        document.getElementById("hamView").classList.remove("backShow")
        document.getElementById("hamburger").classList.remove("hamShow")
        document.getElementById("mainContent").classList.remove("scrollhid");
    }

    render()
    {
        let loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="page"></Loader>;
        }
        let startingTuple,editProfileView,savedSearchView,myMatchesView,myContactView,shortlistedView,phoneBookView,profileVisitorView,membershipRegisterView,awaitingResponseCount,accMeCount,justJoinedCount,filteredCount,allAccCount,messageCount,intRecCount,shortlistedCount,savedSearchCount,dailyRecCount,profileVisitorCount,recommendationView,privacySettingView,changePassView,hideProfileView,deleteProfileView,helpView,logoutView,intSentCount;



        if(this.props.page == "others" && this.state.bellResponse != "notDefined")
        {
            let topView='',btnView='';
            console.log("ham");
            console.log(this.state.bellResponse);
            if(this.state.bellResponse.MEMBERSHIPT_TOP!=null)
            {
              console.log("ham in 1");
              topView = <div className="brdrTop pad150">
                          <div className="txtc color9 mb15">{this.state.bellResponse.MEMBERSHIPT_TOP}</div>
                        </div>
            }
            btnView =   <a href="/profile/mem_comparison.php" id="membershipLink" className="hamBtn f17 white bg7 mt15 fullwid lh50">
                      {this.state.bellResponse.MEMBERSHIPT_BOTTOM}
              </a>;
              console.log(topView);
            membershipRegisterView = <div> {topView} {btnView}</div>;
            if(this.state.bellResponse.VISITOR_ALERT != 0) {
                profileVisitorCount = <span className="f12 album_color1 ml15">{this.state.bellResponse.VISITOR_ALERT}</span>;
            }

            profileVisitorView = <li className="mb12">
                <i className="hamSprite profileVisitorIcon"></i>
                <a href="/search/visitors?matchedOrAll=A" id="profileVisitorLink" className="white">
                    Profile Visitors
                </a>
                {profileVisitorCount}
            </li>;

            phoneBookView = <li className="mb12">
                <i className="hamSprite phoneIcon"></i>
                <a href="/inbox/16/1" id="phoneLink" className="white">
                    Phonebook
                </a>
            </li>;

            if(this.state.bellResponse.BOOKMARK != 0) {
                shortlistedCount = <span className="f12 album_color1 ml15">{this.state.bellResponse.BOOKMARK}</span>;
            }

            shortlistedView = <li className="mb12">
                <i className="hamSprite shortlistedIcon"></i>
                <a href="/search/shortlisted" id="shortlistedLink" className="white">
                    Shortlisted
                    {shortlistedCount}
                </a>
            </li>;

            if(this.state.bellResponse.FILTERED != 0) {
                filteredCount = <span className="f15 album_color1 ml15">{this.state.bellResponse.FILTERED}</span>;
            }
            if(this.state.bellResponse.ACCEPTED_MEMBERS != 0) {
                allAccCount = <span className="f12 album_color1 ml15">{this.state.bellResponse.ACCEPTED_MEMBERS}</span>;
            }
            if(this.state.bellResponse.MESSAGE_NEW != 0) {
                messageCount = <span className="f12 album_color1 ml15">{this.state.bellResponse.MESSAGE_NEW}</span>;
            }
            if(this.state.bellResponse.AWAITING_RESPONSE !=0) {
                intRecCount = <span className="f12 album_color1 ml15">{this.state.bellResponse.AWAITING_RESPONSE}</span>;
            }
            if(this.state.bellResponse.NOT_REP !=0){
                intSentCount = <span className="f12 album_color1 ml15">{this.state.bellResponse.NOT_REP}</span>;
            }

            myContactView = <li className="mb12">
                <div id="contactsParent" onClick={(e) => this.expandListing("contactsParent")}>
                    <i className="hamSprite myContactIcon"></i>
                    <div id="myContactLink" className="ml10 white ml15 dispibl">
                        My Contacts
                    </div>
                    <i id="expandContacts" className="hamSprite plusIcon fr"></i>
                </div>
                <ul id="contactsMinor" style={{"margin":"0px","padding":"12px 0px 0px 40px"}} className = "minorList f15">
                    <li className="mb12">
                        <a id="intRecLink" href="/inbox/1/1" className="newS white">
                            Interests Received
                            {intRecCount}
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="intSentLink" href="/inbox/6/1" className="newS white">
                            Interests Sent
                            {intSentCount}
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="filtIntLink" href="/inbox/12/1" className="newS white">
                            Filtered Interest
                            {filteredCount}
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="allAccLink" href="/inbox/2/1" className="newS white">
                            All Acceptances
                            {allAccCount}
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="declinedLink" href="/inbox/11/1" className="newS white">
                            Declined Members
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="blockedLink" href="/inbox/20/1" className="newS white">
                            Blocked/Ignored Members
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="messagesLink" href="/inbox/4/1" className="newS white">
                            Messages
                            {messageCount}
                        </a>
                    </li>
                    <li>
                        <a id="messagesLink" href="/inbox/17/1" className="newS white">
                            Who Viewed My Contacts
                        </a>
                    </li>
                </ul>
            </li>;

            if(this.state.bellResponse.SAVE_SEARCH != 0) {
                savedSearchCount = <span id="savedSearchCount" className="f12 album_color1 ml15">{this.state.bellResponse.SAVE_SEARCH}</span>;
            }

            savedSearchView = <li className="mb12">
                <i className="hamSprite savedSearchIcon"></i>
                <a href="/search/MobSaveSearch" id="savedSearchLink" className="white">
                    Saved Searches
                    {savedSearchCount}
                </a>
            </li>;

            editProfileView = <li className="mb12">
                <i className="hamSprite editProfileIcon"></i>
                <a href="/profile/viewprofile.php?ownview=1" id="editProfileLink" className="white">
                    Edit Profile
                </a>
            </li>;

            if(this.state.bellResponse.MATCHALERT != 0) {
                dailyRecCount = <span className="f12 album_color1 ml15">{this.state.bellResponse.MATCHALERT}</span>
            }
            myMatchesView = <li className="mb12">
                <div id="myMatchesParent" onClick={(e) => this.expandListing("myMatchesParent")}>
                    <i className="hamSprite myMatchesIcon"></i>
                    <div className=" ml15 f17 white ml15 dispibl">
                        My Matches
                    </div>
                    <i id="expandMyMatches" className="hamSprite plusIcon fr"></i>
                </div>
                <ul id="myMatchesMinor" style={{"height":"0px","margin":"0px","padding":"12px 0px 0px 40px"}} className = "minorList f15">
                    <li className="mb12">
                        <a id="dppLink" href="/search/perform?partnermatches=1" className="newS white">
                            Desired Partner Matches
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="mutualMatchesLink" href="/search/perform?twowaymatch=1" className="newS white">
                            Mutual Matches
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="memLookingLink" href="/search/perform?reverseDpp=1" className="newS white">
                            Members Looking For Me
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="kundliLink" href="/search/perform?kundlialerts=1" className="newS white">
                            Kundli Matches
                        </a>
                    </li>
                    <li className="mb12">
                        <a id="verifiedLink" href="/search/verifiedMatches" className="newS white">
                            Matches Verified By Visit
                        </a>
                    </li>
                    <li>
                        <a id="dailyRec" href="/inbox/7/1" className="newS white">
                            Daily Recommendations
                            {dailyRecCount}
                        </a>
                    </li>
                </ul>
            </li>;

            if(this.state.bellResponse.AWAITING_RESPONSE_NEW != 0) {
                if(this.state.bellResponse.AWAITING_RESPONSE_NEW > 99){
                    awaitingResponseCount = <div className="bg7 disptbl white f13 newham_count txtc">
                          <div className="vertmid dispcell">99+</div>
                      </div>;
                }
                else{
                      awaitingResponseCount = <div className="bg7 disptbl white f13 newham_count txtc">
                          <div className="vertmid dispcell">
                          {this.state.bellResponse.AWAITING_RESPONSE_NEW}
                          </div>
                      </div>;
                }
            }
            if(this.state.bellResponse.ACC_ME_NEW != 0) {
                if(this.state.bellResponse.ACC_ME_NEW > 99){
                    accMeCount = <div className="bg7 disptbl white f13 newham_count txtc">
                        <div className="vertmid dispcell">99+</div>
                    </div>;
                }
                else{
                    accMeCount = <div className="bg7 disptbl white f13 newham_count txtc">
                        <div className="vertmid dispcell">
                            {this.state.bellResponse.ACC_ME_NEW}
                        </div>
                    </div>;
                }
            }
            if(this.state.bellResponse.JUST_JOINED_NEW != 0) {
                if(this.state.bellResponse.JUST_JOINED_NEW > 99){
                    justJoinedCount = <div className="bg7 disptbl white f13 newham_count txtc">
                    <div className="vertmid dispcell">99+</div>
                    </div>;
                }
                else{
                    justJoinedCount = <div className="bg7 disptbl white f13 newham_count txtc">
                        <div className="vertmid dispcell">
                            {this.state.bellResponse.JUST_JOINED_NEW}
                        </div>
                    </div>;
                }
            }

            startingTuple = <li>
                <div className="fullwid">
                    <div className="dispibl txtc wid32p">
                        <a id="awaitingResponseLinkTop" href="/inbox/1/1" className="dispbl white f12">
                            <i id="int_rec" className="hamSprite irIcon posrel">
                                <div className="posabs newham_pos1">
                                    {awaitingResponseCount}
                                </div>
                            </i>
                            <div>Interests <br /> Received</div>
                        </a>
                    </div>
                    <div className="dispibl txtc wid32p">
                        <a id="accMemLinkTop" href="/inbox/2/1" className="dispbl white f12">
                            <i id="acc_mem" className="hamSprite allAcIcon posrel">
                                <div className="posabs newham_pos1">
                                    {accMeCount}
                                </div>
                            </i>
                            <div>All<br />Acceptances</div>
                        </a>
                    </div>
                    <div className="dispibl txtc wid32p">
                        <a id="justJoinedLinkTop" href="/search/perform?justJoinedMatches=1" className="dispbl white f12">
                            <i id="just_join" className="hamSprite justJnIcon  posrel">
                                <div className="posabs newham_pos1">
                                    {justJoinedCount}
                                </div>
                            </i>
                            <div>Just Joined <br /> Matches</div>
                        </a>
                    </div>
                </div>
            </li>;
            recommendationView = <li className="mb12">
                <a id="recommendationLink" href="/profile/viewprofile.php?ownview=1#Dpp" className="white">
                    Recommendation
                </a>
            </li>;
            privacySettingView = <li className="mb12">
                <a id="privacySettingLink" href="/static/privacySettings" className="white">
                    Privacy Settings
                </a>
            </li>;
            changePassView = <li className="mb12">
                <a id="changePassLink" href="/static/changePass" className="white">
                    Change Password
                </a>
            </li>;
            hideProfileView = <li className="mb12">
                <a id="hideProfileLink" href="/static/hideOption" className="white">
                    Hide Profile
                </a>
            </li>;
            deleteProfileView = <li className="mb12">
                <a id="deleteProfileLink" href="/static/deleteOption" className="white">
                    Delete Profile
                </a>
            </li>;
            helpView = <li className="mb12">
                <a id="helpLink" href="/help/index" className="white">
                    Help
                </a>
            </li>;
            logoutView = <li className="mb12">
                <div onClick={() => this.logoutAccount()} id="logoutLink" className="white ml30">
                    Logout
                </div>
            </li>;
        }
        else if(this.props.page == "Login") {
            membershipRegisterView = <div className="brdrTop pad150 fontreg">
                                                <div className="dispibl wid49p pad16">
                                                    <Link to={"/"} onClick={(e) => this.checkHome(e)} id="homeLink2" className="hamBtnLoggedOut bg10 lh40 br6 white">
                                                        LOGIN
                                                    </Link>
                                                </div>
                                                <div className="dispibl wid49p pad16">
                                                    <a className="bg7 br6 lh40 white hamBtnLoggedOut" href="/register/page1?source=mobreg4">REGISTER</a>
                                                </div>
                                     </div>;
            editProfileView = <li className="mb12">
                                <i className="hamSprite editProfileIcon"></i>
                                <a href="/browse-matrimony-profiles-by-community-jeevansathi" id="borwseCommLink" className="white">
                                    Browse By Community
                                </a>
                               </li>;
        }
        let urlString = "",appText = "";
        if(getAndroidVersion()) {
            urlString = "https://jeevansathi.com/static/appredirect?type=androidLayer";
            appText = "Download APP (3MB)";
        } else if(getIosVersion()) {
            urlString = "https://jeevansathi.com/static/appredirect?type=iosLayer";
            appText = "Download iOS App ";
        }

        let newHref;
        if(getCookie("AUTHCHECKSUM")) {
            newHref = CONSTANTS.HINDI_SITE+"?AUTHCHECKSUM="+getCookie("AUTHCHECKSUM")+"&newRedirect=1";
        } else {
             newHref = CONSTANTS.HINDI_SITE;
        }

        let listingView =
        <div>
            <ul id="scrollElem" className="fontreg white listingHam listingStyle overAutoHidden">
                <li className="appDownload f13 pb8 fontlig" style={{"padding":"10px 20px 0 20px"}}>
                    <div className="brdrBtm pb10">
                        <div className="wid49p dispibl">
                            <a id="appLink" href={urlString} target="_blank"  className="white fl mar0Imp">{appText}</a>
                        </div>
                        <div className="wid49p dispibl">
                            <div id="hindiLink" onclick="translateSite(\'http://hindi.jeevansathi.com\');" className="white fr mar0Imp">Hindi Version</div>
                        </div>
                    </div>
                </li>

                {startingTuple}
                <li className="mb12">
                    <i className="hamSprite homeIcon"></i>
                    <Link to={"/"} onClick={(e) => this.checkHome(e)} id="homeLink1" className="white">
                        Home
                    </Link>
                </li>
                <li className="mb12">
                    <i className="hamSprite searchIcon"></i>
                    <a id="searchLink" href="/search/topSearchBand?isMobile=Y" className="white">
                        Search
                    </a>
                </li>
                <li className="mb12">
                    <i className="hamSprite searchProfileIcon"></i>
                    <a id="searchProfileIdLink" href="/search/searchByProfileId" className="white">
                        Search by Profile ID
                    </a>
                </li>
                {savedSearchView}
                {editProfileView}
                {myMatchesView}
                {myContactView}
                {shortlistedView}
                {phoneBookView}
                {profileVisitorView}
                <li className="mb12">
                    <div id="settingsParent" onClick={(e) => this.expandListing("settingsParent")}>
                        <i className="hamSprite settingsIcon"></i>
                        <div id="settingsLink" className="mrl10 dispibl white">
                            Settings & Assistance
                        </div>
                        <i id="expandSettings" className="hamSprite plusIcon fr"></i>
                    </div>
                    <ul id="settingsMinor" style={{"margin":"0px","padding":"12px 0px 0px 40px"}} className="minorList f15 settingStyle">
                        {recommendationView}
                        {privacySettingView}
                        {changePassView}
                        {hideProfileView}
                        {deleteProfileView}
                        {helpView}
                        <li className="mb12">
                            <a id="contactUsLink" href="/contactus/index" className="newS white">
                                Contact Us
                            </a>
                        </li>
                        <li className="mb12">
                            <a id="privacyPolicyLink" href="/static/page/privacypolicy" className="newS white">
                                Privacy Policy
                            </a>
                        </li>
                        <li className="mb12">
                            <a id="termsLink" href="/static/page/disclaimer" className="newS white">
                                Terms of use
                            </a>
                        </li>
                        <li className="mb12">
                            <a id="fraudLink" href="/static/page/fraudalert" className="newS white">
                                Fraud Alert
                            </a>
                        </li>
                        {logoutView}
                        <li className="mb12">
                            <a id="switchLink" href="/?desktop=Y" className="newS white">
                                Switch to Desktop Site
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div id="bottomTab" className="mar0Imp posabs btm0 fullwid">
                {membershipRegisterView}
            </div>
            </div>;

        return (
            <div id="hamMain">
                <div id="hamburger" className="white posfix z105 wid90p fullheight">
                        <div id="outerHamDiv">
                        <div id="mainHamDiv" >
                        <div id="newHamlist" className="hamlist hampad1" >
                        {listingView}
                        </div>
                        </div>
                        </div>
                </div>
                {loaderView}
                <div onClick={this.hideHam} id="hamView" className="fullwid darkView fullheight hamView dn"></div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
        myjsData: state.MyjsReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        getHamData: () => {
            commonApiCall(CONSTANTS.MYJS_CALL_URL3,{},'SET_HAM_DATA','POST',dispatch);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps,null,{ withRef: true })(HamMain)
