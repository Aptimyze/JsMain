require ('../style/ham.css')
import React from "react";
import {Link} from "react-router-dom";
import { getAndroidVersion, getIosVersion} from "../../common/components/commonFunctions";


export default class HamMain extends React.Component {

    constructor(props) {
        super();
    }
    checkHome(e) {
        if(window.location.pathname == "/" || window.location.pathname == "/login/") {
           e.preventDefault();
           this.hideHam(); 
        }
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
        var listingView = <div id="listing">
            <ul className="fontreg white listingHam">
                <li>
                    <i className="hamSprite homeIcon"></i>
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
                <li>
                    <i className="hamSprite settingsIcon"></i>
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
