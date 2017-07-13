require ('../style/ham.css')
import React from "react";
import {Link} from "react-router-dom";
import { getAndroidVersion, getIosVersion} from "../../common/components/commonFunctions";
import {getCookie,setCookie} from '../../common/components/CookieHelper';


export default class HamMain extends React.Component {

    constructor(props) 
    {
        super();
    }

    translateSite(translateURL)
    {
        let newHref;
        if(getCookie("AUTHCHECKSUM")) {
            newHref = translateURL+"?AUTHCHECKSUM="+getCookie("AUTHCHECKSUM");
        } else {
            newHref = translateURL;
        }
        if(translateURL.indexOf('hindi')!=-1){
            setCookie("jeevansathi_hindi_site_new","Y",100,".jeevansathi.com");
        } else {
            setCookie("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
        }
        window.location.href = newHref;
    }

    componentDidMount() 
    {   
        this.setState({
            minorLiHeight : document.getElementsByClassName("minorList")[0].getElementsByTagName("li")[0].getBoundingClientRect().height
        });
        document.getElementById("settingsMinor").style.height = "0px";
        if(this.props.page == "others") {
            if(this.props.bellResponse.MEMBERSHIPT_TOP == null || !this.props.bellResponse.MEMBERSHIPT_TOP) {
                document.getElementById("listing").style.height = (window.innerHeight-84)+"px";
             } else {
                document.getElementById("listing").style.height = (window.innerHeight-100)+"px";
             }
            document.getElementById("myMatchesMinor").style.height = "0px";
            document.getElementById("contactsMinor").style.height = "0px";  
        } else {
            document.getElementById("listing").style.height = (window.innerHeight-84)+"px";
        }
    }

    checkHome(e) 
    {
        if(window.location.pathname == "/" || window.location.pathname == "/login/") {
           e.preventDefault();
           this.hideHam(); 
        }
    }
    
    scrollAnimate(element, difference) {
        if (difference <= 0) return;
        let _this = this;
        setTimeout(function() {
            element.scrollTop = element.scrollTop + 7;
            _this.scrollAnimate(element, difference-7);
        }, 10);
    }

    expandListing(e) 
    {
        let minorElem = e.target.parentElement.id.split("Parent")[0] +"Minor";
        if(e.target.parentElement.className.indexOf("plusParent") != -1) {
            e.target.parentElement.classList.remove("plusParent"); 
            document.getElementById(minorElem).style.height = "0px";
        } else {
            e.target.parentElement.classList.add("plusParent");
            let listingLen = document.getElementById(minorElem).getElementsByTagName("li").length;
            let exactHeight = this.state.minorLiHeight * listingLen;
            document.getElementById(minorElem).style.height = exactHeight + "px";
            let differHeight = document.getElementById(minorElem).getElementsByTagName("li")[listingLen-1].getBoundingClientRect().bottom - document.getElementById("bottomTab").getBoundingClientRect().top + 10;
            let _this = this;
            setTimeout(function(){
               _this.scrollAnimate(document.getElementById('scrollElem'),differHeight)
            },200); 
        }
    }

    openHam() 
    {
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
    }

    render() 
    {
        let startingTuple,editProfileView,savedSearchView,myMatchesView,myContactView,shortlistedView,phoneBookView,profileVisitorView,membershipRegisterView,awaitingResponseCount,accMeCount,justJoinedCount,filteredCount,allAccCount,messageCount,intRecCount,shortlistedCount,savedSearchCount,dailyRecCount,profileVisitorCount;
        if(this.props.page == "others") {
            membershipRegisterView = <div className="brdrTop pad150">
                    <div className="txtc color9 mb15">{this.props.bellResponse.MEMBERSHIPT_TOP}</div>
                    <a href="/search/visitors?matchedOrAll=A" id="membershipLink" className="hamBtn f17 white bg7 mt15 fullwid lh50">
                            {this.props.bellResponse.MEMBERSHIPT_BOTTOM}
                    </a>
                </div>;
            if(this.props.bellResponse.VISITOR_ALERT != 0) {
                profileVisitorCount = <span className="f12 album_color1 ml15">{this.props.bellResponse.VISITOR_ALERT}</span>; 
            }
            profileVisitorView = <li>
                <div>
                    <i className="hamSprite profileVisitorIcon"></i>
                    <a href="/search/visitors?matchedOrAll=A" id="profileVisitorLink" className="f17 white">
                        Profile Visitors
                    </a>
                    {profileVisitorCount}
                </div>
            </li>;

            phoneBookView = <li>
                <div>
                    <i className="hamSprite phoneIcon"></i>
                    <a href="/inbox/16/1" id="phoneLink" className="f17 white">
                        Phonebook
                    </a>
                </div>
            </li>;
            if(this.props.bellResponse.BOOKMARK != 0) {
                shortlistedCount = <span className="f12 album_color1 ml15">{this.props.bellResponse.BOOKMARK}</span>;  
            }

            shortlistedView = <li>
                <div>
                    <i className="hamSprite shortlistedIcon"></i>
                    <a href="/search/shortlisted" id="shortlistedLink" className="f17 white">
                        Shortlisted
                        {shortlistedCount}
                    </a>
                </div>
            </li>;

            if(this.props.bellResponse.FILTERED != 0) {
                filteredCount = <span className="f15">{this.props.bellResponse.FILTERED}</span>;
            }
            if(this.props.bellResponse.ACCEPTED_MEMBERS != 0) {
                allAccCount = <span className="f12 album_color1 ml15">{this.props.bellResponse.ACCEPTED_MEMBERS}</span>;
            }
            if(this.props.bellResponse.MESSAGE_NEW != 0) {
                messageCount = <span className="f12 album_color1 ml15">{this.props.bellResponse.MESSAGE_NEW}</span>;
            }
            if(this.props.bellResponse.AWAITING_RESPONSE !=0) {
                intRecCount = <span className="f12 album_color1 ml15">{this.props.bellResponse.AWAITING_RESPONSE}</span>;
            }

            myContactView = <li>
                <div id="contactsParent">
                    <i className="hamSprite myContactIcon"></i>
                    <div id="myContactLink" className="f17 ml15 white ml15 dispibl">
                        My Contacts
                    </div>
                    <i id="expandContacts" onClick={(e) => this.expandListing(e)} className="hamSprite plusIcon fr"></i>
                </div>
                <ul id="contactsMinor" className = "minorList f15">
                    <li>
                        <a id="intRecLink" href="/inbox/1/1" className="white">
                            Interests Received
                            {intRecCount}
                        </a>
                    </li>
                    <li>
                        <a id="intSentLink" href="/inbox/6/1" className="white">
                            Interests Sent
                        </a>
                    </li>
                    <li>
                        <a id="filtIntLink" href="/inbox/12/1" className="white">
                            Filtered Interest
                            {filteredCount}
                        </a>
                    </li>
                    <li>
                        <a id="allAccLink" href="/inbox/2/1" className="white">
                            All Acceptances
                            {allAccCount}
                        </a>
                    </li>
                    <li>
                        <a id="declinedLink" href="/inbox/11/1" className="white">
                            Declined Members
                        </a>
                    </li>
                    <li>
                        <a id="blockedLink" href="/inbox/20/1" className="white">
                            Blocked/Ignored Members
                        </a>
                    </li>
                    <li>
                        <a id="messagesLink" href="/inbox/4/1" className="white">
                            Messages
                            {messageCount}
                        </a>
                    </li>
                    <li>
                        <a id="messagesLink" href="/inbox/17/1" className="white">
                            Who Viewed My Contacts
                        </a>
                    </li>
                </ul>
            </li>;

            if(this.props.bellResponse.SAVE_SEARCH != 0) {
                savedSearchCount = <span className="f12 album_color1 ml15">{this.props.bellResponse.SAVE_SEARCH}</span>;
            }

            savedSearchView = <li>
                <div>
                    <i className="hamSprite savedSearchIcon"></i>
                    <a href="/profile/viewprofile.php?ownview=1" id="savedSearchLink" className="f17 white">
                        Saved Searches
                        {savedSearchCount}
                    </a>
                </div>
            </li>;

            editProfileView = <li>
                <div>
                    <i className="hamSprite editProfileIcon"></i>
                    <a href="/profile/viewprofile.php?ownview=1" id="editProfileLink" className="f17 white">
                        Edit Profile
                    </a>
                </div>
            </li>;

            if(this.props.bellResponse.MATCHALERT != 0) {
                dailyRecCount = <span className="f12 album_color1 ml15">{this.props.bellResponse.MATCHALERT}</span>
            }
            myMatchesView = <li>
                <div id="myMatchesParent">
                    <i className="hamSprite myMatchesIcon"></i>
                    <div className=" ml15 f17 white ml15 dispibl">
                        My Matches
                    </div>
                    <i id="expandMyMatches" onClick={(e) => this.expandListing(e)} className="hamSprite plusIcon fr"></i>
                </div>
                <ul id="myMatchesMinor" className = "minorList f15">
                    <li>
                        <a id="dppLink" href="/search/perform?partnermatches=1" className="white">
                            Desired Partner Matches
                        </a>
                    </li>
                    <li>
                        <a id="mutualMatchesLink" href="/search/perform?twowaymatch=1" className="white">
                            Mutual Matches
                        </a>
                    </li>
                    <li>
                        <a id="memLookingLink" href="/search/perform?reverseDpp=1" className="white">
                            Members Looking For Me
                        </a>
                    </li>
                    <li>
                        <a id="kundliLink" href="/search/perform?kundlialerts=1" className="white">
                            Kundli Matches
                        </a>
                    </li>
                    <li>
                        <a id="verifiedLink" href="/search/verifiedMatches" className="white">
                            Matches Verified By Visit
                        </a>
                    </li>
                    <li>
                        <a id="dailyRec" href="/inbox/7/1" className="white">
                            Daily Recommendations 
                        </a>
                        {dailyRecCount}
                    </li>
                </ul>
            </li>;

            if(this.props.bellResponse.AWAITING_RESPONSE_NEW != 0) {
                awaitingResponseCount = <div className="bg7 disptbl white f13 newham_count txtc">
                    <div className="vertmid dispcell">
                        {this.props.bellResponse.AWAITING_RESPONSE_NEW}
                    </div>
                </div>;
            }
            if(this.props.bellResponse.ACC_ME_NEW != 0) {
                accMeCount = <div className="bg7 disptbl white f13 newham_count txtc">
                    <div className="vertmid dispcell">
                        {this.props.bellResponse.ACC_ME_NEW}
                    </div>
                </div>;
            }
            if(this.props.bellResponse.JUST_JOINED_NEW != 0) {
                justJoinedCount = <div className="bg7 disptbl white f13 newham_count txtc">
                    <div className="vertmid dispcell">
                        {this.props.bellResponse.JUST_JOINED_NEW}
                    </div>
                </div>;
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
        }
        else if(this.props.page == "Login") {
            membershipRegisterView = <div className="brdrTop pad150 fontreg">
                <div className = "dispibl wid49p pad16">
                    <Link to={"/"} onClick={(e) => this.checkHome(e)} id="homeLink2" className="hamBtnLoggedOut bg10 lh40 br6 white">
                        LOGIN
                    </Link>  
                </div>
                <div className = "dispibl wid49p pad16">
                    <a className="bg7 br6 lh40 white hamBtnLoggedOut" href="/register/page1?source=mobreg4">
                        REGISTER
                    </a>
                </div>
            </div>
            editProfileView = <li>
                <div>
                    <i className="hamSprite editProfileIcon"></i>
                    <a href="/browse-matrimony-profiles-by-community-jeevansathi" id="borwseCommLink" className="f17 white">
                        Browse By Community
                    </a>
                </div>
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

        let listingView = <div id="listing" className="overflowhidden">
            <ul id="scrollElem" className="fontreg white listingHam fullheight overAuto">
                <li className="brdrBtm f14 pb8 fontlig">
                    <div className="wid49p dispibl">
                        <a id="appLink" href={urlString} target="_blank"  className="white fl mar0Imp">{appText}</a>
                    </div>
                    <div className="wid49p dispibl">
                        <div id="hindiLink" onClick={() => this.translateSite("http://hindi.jeevansathi.com")}  className="white fr mar0Imp">Hindi Version</div>
                    </div>
                </li>
                {startingTuple}
                <li>
                    <i className="hamSprite homeIcon mt10Imp"></i>
                    <Link to={"/"} onClick={(e) => this.checkHome(e)} id="homeLink1" className="f17 white">
                        Home
                    </Link>
                </li>
                <li>
                    <i className="hamSprite searchIcon"></i>
                    <a id="searchLink" href="/search/topSearchBand?isMobile=Y" className="white">
                        Search
                    </a>
                </li>
                <li>
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
                <li>
                    <div id="settingsParent">
                        <i className="hamSprite settingsIcon"></i>
                        <div id="settingsLink" className="ml15 dispibl white">
                            Settings
                        </div>
                        <i id="expandSettings" onClick={(e) => this.expandListing(e)} className="hamSprite plusIcon fr"></i>
                    </div>
                    <ul id="settingsMinor" className = "minorList f15">
                        <li>
                            <a id="changePassLink" href="/static/changePass" className="white">
                                Change Password
                            </a>
                        </li>
                        <li>
                            <a id="hideProfileLink" href="/static/hideOption" className="white">
                                Hide Profile
                            </a>
                        </li>
                        <li>
                            <a id="deleteProfileLink" href="/static/deleteOption" className="white">
                                Delete Profile
                            </a>
                        </li>
                        <li>
                            <a id="helpLink" href="/help/index" className="white">
                                Help
                            </a>
                        </li>
                        <li>
                            <a id="contactUsLink" href="/contactus/index" className="white">
                                Contact Us
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
                <div id="hamburger" className="white posfix z105 wid80p fullheight overflowhidden">
                        {listingView}
                </div>
                <div onClick={this.hideHam} id="hamView" className="fullwid darkView fullheight hamView dn"></div>
            </div>
        );
    }
}
