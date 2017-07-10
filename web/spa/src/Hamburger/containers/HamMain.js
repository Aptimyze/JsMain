require ('../style/ham.css')
import React from "react";
import {Link} from "react-router-dom";
import { getAndroidVersion, getIosVersion} from "../../common/components/commonFunctions";


export default class HamMain extends React.Component {

    constructor(props) {
        super();
    }

    componentDidMount() {
      
    }
    openHam() {
        document.getElementById("hamView").classList.add("z99")
        document.getElementById("hamView").classList.add("backShow")
        document.getElementById("hamburger").classList.add("hamShow")
    }
    hideHam() {
        document.getElementById("hamView").classList.remove("z99")
        document.getElementById("hamView").classList.remove("backShow")
        document.getElementById("hamburger").classList.remove("hamShow")
    }

    render() {

        var appLinkView,urlString,appText;
        if(getAndroidVersion()) {
            urlString = "https://jeevansathi.com/static/appredirect?type=androidLayer";
            appText = "Download  App | 3MB only";
        } else if(getIosVersion()) {
            urlString = "https://jeevansathi.com/static/appredirect?type=iosLayer";
            appText = "Download iOS App";
        }

        var CommBrowseView;
        if(this.props.page == "Login") {
            CommBrowseView = <a href="/browse-matrimony-profiles-by-community-jeevansathi" className="white">
                Browse by Community
            </a>
        }

        var listingView = <div id="liting">
            <ul className="fontlig white">
                <li>
                    <div id="appDownloadLink">
                        <a href = {urlString}  className="white">
                            {appText}    
                        </a>
                    </div>
                </li>
                <li>
                    <Link to={"/"} id="homeLink" className="f17">
                        Home
                    </Link>
                </li>
                <li>
                    <a id="searchLink" href="/search/topSearchBand?isMobile=Y" className="white">
                        Search
                    </a>
                </li>
                <li>
                    <a id="searchProfileId" href="/search/searchByProfileId" className="white">
                        Search by Profile ID
                    </a>
                </li>
                <li>
                    {CommBrowseView}
                </li>
                <li>
                    <a href="/static/settings" className="white">
                        Settings
                    </a>
                </li>
            </ul> 
        </div>;           

        return (
            <div id="hamMain">
                <div id="hamburger" className="white posfix z105 wid80p fl fullheight">
                    {listingView} 
                </div>
                <div onClick={this.hideHam} id="hamView" className="fullwid darkView fullheight hamView"></div>
            </div>
        );
    }
}
