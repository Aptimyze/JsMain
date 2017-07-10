require ('../style/ham.css')
import React from "react";
import {Link} from "react-router-dom";
import { getAndroidVersion, getIosVersion} from "../../common/components/commonFunctions";


export default class HamMain extends React.Component {

    constructor(props) {
        super();
        props.bellResponse.MEMBERSHIPT_TOP = "Flat 70% Off till 12 Jul!";
    }
    checkHome(e) {
        if(window.location.pathname == "/" || window.location.pathname == "/login/") {
           e.preventDefault();
           this.hideHam(); 
        }
    }
    openHam() {
        document.getElementById("hamView").classList.add("z99")
        document.getElementById("hamView").classList.remove("dn")
        document.getElementById("hamView").classList.add("backShow")
        document.getElementById("hamburger").classList.add("hamShow")
    }
    hideHam() {
        document.getElementById("hamView").classList.remove("z99")
        document.getElementById("hamView").classList.add("dn")
        document.getElementById("hamView").classList.remove("backShow")
        document.getElementById("hamburger").classList.remove("hamShow")
    }
    render() {
        var startingTuple,editProfileView,savedSearchView,myMatchesView,myContactView,shortlistedView,phoneBookView,profileVisitorView,membershipView;
        if(this.props.page == "others") {

            membershipView = <li className="mar0Imp">
                <div className="brdrTop pad150">
                    <div className="txtc color9 mb15">{this.props.bellResponse.MEMBERSHIPT_TOP}</div>
                    <Link to={"/search/visitors?matchedOrAll=A"} id="profileVisitorLink" className="hamBtn f17 white bg7 mt15 fullwid lh50">
                            {this.props.bellResponse.MEMBERSHIPT_BOTTOM}
                    </Link>
                </div>
            </li>;

            profileVisitorView = <li>
                <div>
                    <i className="hamSprite profileVisitorIcon"></i>
                    <Link to={"/search/visitors?matchedOrAll=A"} id="profileVisitorLink" className="f17 white">
                        Profile Visitors
                    </Link>
                </div>
            </li>;

            phoneBookView = <li>
                <div>
                    <i className="hamSprite phoneIcon"></i>
                    <Link to={"/inbox/16/1"} id="phoneLink" className="f17 white">
                        Phonebook
                    </Link>
                </div>
            </li>;

            shortlistedView = <li>
                <div>
                    <i className="hamSprite shortlistedIcon"></i>
                    <Link to={"/search/shortlisted"} id="shortlistedLink" className="f17 white">
                        Shortlisted
                    </Link>
                </div>
            </li>;

            myContactView = <li>
                <div>
                    <i className="hamSprite myContactIcon"></i>
                    <div id="myContactLink" className="f17 white ml15 dispibl">
                        My Contacts
                    </div>
                    <i className="hamSprite plusIcon fr"></i>
                </div>
            </li>;


            savedSearchView = <li>
                <div>
                    <i className="hamSprite savedSearchIcon"></i>
                    <Link to={"/profile/viewprofile.php?ownview=1"} id="savedSearchLink" className="f17 white">
                        Saved Searches
                    </Link>
                </div>
            </li>;

            editProfileView = <li>
                <div>
                    <i className="hamSprite editProfileIcon"></i>
                    <Link to={"/profile/viewprofile.php?ownview=1"} id="editProfileLink" className="f17 white">
                        Edit Profile
                    </Link>
                </div>
            </li>;

            myMatchesView = <li>
                <div>
                    <i className="hamSprite myMatchesIcon"></i>
                    <div id="myMatchesLink" className="f17 white ml15 dispibl">
                        My Matches
                    </div>
                    <i className="hamSprite plusIcon fr"></i>
                </div>
            </li>;

            startingTuple = <li>
                <div className="fullwid">
                    <div className="dispibl txtc wid32p">
                        <a href="/inbox/1/1" className="dispbl white f12"> 
                            <i id="int_rec" className="hamSprite irIcon posrel">
                                <div className="posabs newham_pos1">
                                    <div className="bg7 disptbl white f13 newham_count txtc">
                                        <div className="vertmid dispcell">1</div>
                                    </div>
                                </div>
                            </i>
                            <div>Interests <br /> Received</div>
                        </a>
                    </div>
                    <div className="dispibl txtc wid32p">
                        <a href="/inbox/2/1" className="dispbl white f12">
                            <i id="acc_mem" className="hamSprite allAcIcon posrel">
                                <div className="posabs newham_pos1">
                                    <div className="bg7 disptbl white f13 newham_count txtc">
                                        <div className="vertmid dispcell">1</div>
                                    </div>
                                </div>
                            </i>
                            <div>All<br />Acceptances</div>
                        </a>
                    </div>
                    <div className="dispibl txtc wid32p">
                        <a href="/search/perform?justJoinedMatches=1" className="dispbl white f12">
                            <i id="just_join" className="hamSprite justJnIcon  posrel">
                                <div className="posabs newham_pos1">
                                    <div className="bg7 disptbl white f13 newham_count txtc">
                                        <div className="vertmid dispcell">8</div>
                                    </div>
                                </div>
                            </i>
                            <div>Just Joined <br /> Matches</div>
                        </a>
                    </div>
                </div>
            </li>;
        }

        var listingView = <div id="listing" className="fullheight overflowhidden">
            <ul className="fontreg white listingHam fullheight overAuto">
                {startingTuple}
                <li>
                    <i className="hamSprite homeIcon mt10Imp"></i>
                    <Link to={"/"} onClick={(e) => this.checkHome(e)} id="homeLink" className="f17 white">
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
                    <a id="searchProfileId" href="/search/searchByProfileId" className="white">
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
                    <i className="hamSprite settingsIcon"></i>
                    <div id="settingsLink" className="dispibl white">
                        Settings
                    </div>
                    <i className="hamSprite plusIcon fr"></i>
                </li>
                {membershipView}
            </ul> 
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
