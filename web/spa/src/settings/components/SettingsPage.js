import React from "react";
import HamMain from "../../Hamburger/containers/HamMain";
import Loader from "../../common/components/Loader";
import {connect} from "react-redux";
import axios from "axios";
import {getCookie, setCookie, removeCookie} from '../../common/components/CookieHelper';
import * as CONSTANTS from '../../common/constants/apiConstants'
import {onLogout} from "../../common/components/CacheHelper";
import {commonApiCall} from "../../common/components/ApiResponseHandler";
import CALCommonCall from "../../cal/components/CommonCALFunctions";
require('../../Hamburger/style/ham.css');
let API_SERVER_CONSTANTS = require('../../common/constants/apiServerConstants');


const USERDEFINEDVALUESKEY = "userDefinedSearchValues";

class SettingsPage extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      showLoader: true,
      ACTIVATED:''
    }

  }

	componentDidUpdate(prevprops) {

	}
  componentDidMount() {
    if(getCookie("AUTHCHECKSUM") != ''){
      commonApiCall(CONSTANTS.SETTINGS_INFO,{},'','GET').then((response) =>
      {
        if(response.responseStatusCode==0)
        {
          this.setState({ACTIVATED: response.hiddenState});
        }

      });
    }

  }
  logoutAccount() {
    this.setState({showLoader: true});
    // this.hideHam();

    let keysToKeep = [USERDEFINEDVALUESKEY]; //specify the values from which the data in local storage needs to be maintained
    axios.get(API_SERVER_CONSTANTS.API_SERVER + "/static/logoutPage")
      .then(function (response) {
        removeCookie("AUTHCHECKSUM");
        onLogout(keysToKeep);
        window.location.href = "/login";
      })
  }

	//----start:header HTML
  headerViewSS() {
    let headHTML;
    headHTML = <div className="fullwid bg1 pad1 z80">
      <div className="rem_pad1 clearfix">
        <div className="posrel">
          <div className="posabs ss_hamPos" id="hamburgerIcon">
            <i onClick={() => this.backToHam()} id="backTohamIcon" className="mainsp posabs set_arrowLeft set_pos3"></i>
          </div>
          <div className="txtc color5  fontthin f19">
            Settings
          </div>
        </div>
      </div>
    </div>;

    return headHTML;
  }
  backToHam() {
    // history.back();
    let locaToRedirect = localStorage.getItem('CURRENTURLHAM');
    if(locaToRedirect){
      window.location = locaToRedirect;
    }
  }

render() {
	 let  ham = '', recommendationView, privacySettingView, changePassView, hideProfileView, deleteProfileView, helpView, logoutView;

	 if(getCookie("AUTHCHECKSUM") != ''){
     recommendationView = <li className="hamPadl15 pr10 pt22 brdr15 f14  fontreg nhm_hor_listHam  hgt64">
       <a id="recommendationLink" href="/profile/viewprofile.php?ownview=1#Dpp" className="white">
         <div className='color3 fl wid92p'>Recommendation Settings</div><i className='rightArrIcon'></i>
       </a>
     </li>;
     privacySettingView = <li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
       <a id="privacySettingLink" href="/static/privacySettings" className="white">
         <div className='color3 fl wid92p'>Privacy Settings</div><i className='rightArrIcon'></i>
       </a>
     </li>;
     changePassView =<li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
       <a id="changePassLink" href="/static/changePass" className="white">
         <div className='color3 fl wid92p'> Change Password</div><i className='rightArrIcon'></i>
       </a>
     </li>;
       if (this.state.ACTIVATED == 'H') {
        hideProfileView = <li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
          <a id="hideProfileLink" href="/static/unHideOption" className="  white">
            <div className='color3 fl wid92p'>Unhide Profile</div><i className='rightArrIcon'></i>
          </a>
        </li>;
      } else {
        hideProfileView = <li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
          <a id="hideProfileLink" href="/static/hideOption" className="white">
            <div className='color3 fl wid92p'> Hide Profile</div><i className='rightArrIcon'></i>
          </a>
        </li>;
      }
     // hideProfileView = <li className="padl15 pr10 pt22 brdr15 f14  fontreg  hgt64">
     //   <a id="hideProfileLink" href="/static/hideOption" className="white">
     //     <div className='color3 fl wid92p'>Hide Profile</div><i className='rightArrIcon'></i>
     //   </a>
     // </li>;
     deleteProfileView = <li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
       <a id="deleteProfileLink" href="/deleteProfile" className="white">
         <div className='color3 fl wid92p'> Delete Profile</div><i className='rightArrIcon'></i>
       </a>
     </li>;
     {/* helpView =  <li className="padl15 pr10 pt22 brdr15 f14  fontreg  hgt64">
        <a id="helpLink" href="/help/index" className="white">
           <div className='color3 fl wid92p'>Help</div><i className='rightArrIcon'></i>
        </a>
      </li>;*/}
     logoutView = <li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
       <div onClick={() => this.logoutAccount()} id="logoutLink" className="white ">
         <div className='color3 fl wid92p'>Logout</div><i className='rightArrIcon'></i>
       </div>
     </li>;

    }

    return (
			<div id="SettingsPage">
        <div id="mainContent" style={{"backgroundColor": "white",'height':'100vh'}}>
          {/*{ham}*/}
          {this.headerViewSS()}
          <ul id="settingsMinor" style={{"margin": "0px", "padding": "0"}} className=" f15 settingStyle fullwid">
              {recommendationView}
              {privacySettingView}
              {changePassView}
              {hideProfileView}
              {deleteProfileView}
              {/* {helpView} */} 
              <li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
                <a id="contactUsLink" href="/contactus/index" className="newS white ">
                   <div className='color3 fl wid92p'>Contact Us</div><i className='rightArrIcon'></i>
                </a>
              </li>              
              <li className='hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64'>
                <a id="cookie" href="/static/page/cookiepolicy" className="newS white">
                  <div className='color3 fl wid92p'>Cookie Policy</div><i className='rightArrIcon'></i></a>
              </li>
              
              {logoutView}              
 <li className="hamPadl15 nhm_hor_listHam pr10 pt22 brdr15 f14  fontreg  hgt64">
                <a id="switchLink" href="/?desktop=Y" className="newS white">
                <div className='color3 fl wid92p'>  Switch to Desktop Site</div><i className='rightArrIcon'></i>
                </a>
              </li>
            </ul>
				</div>
			</div>

		)
 }
}
export default SettingsPage;

