require('../style/common.css')
require('../style/appPromo.css')
require('../style/errorBar.css')
require('../style/fonts.css')
require('../style/mediaQuery.css')
import React from 'react';
import asyncComponent from './asyncComponent';
import {getRoutePath,getSearchListingUrl,getInboxListingUrl} from './UrlDecoder';
import {getParameterByName} from './UrlDecoder';
import {connect} from 'react-redux';
import {siginFromCookie} from "./../../login/actions/LoginActions";
import {getCookie} from "./CookieHelper";
import {getItem,setItem} from "../../register/services/localStorage";
import {setCookie} from "./CookieHelper";
import {
  BrowserRouter as Router,
  Route,
  browserHistory,
  Redirect,
  Link,
  Switch
} from "react-router-dom";
import EnsureLoggedInContainer from '../containers/EnsureLoggedInContainer';
import MyjsPage from './../../myjs/containers/MyjsPage';
import store from './../../store';

import NewHomePage from './../../homePage/containers/newHome';
import HomePage from './../../homePage/containers/HomePage';
import * as CONSTANTS from '../../common/constants/apiConstants';
import axios from 'axios';
import Strophe from "strophe";
import { LastLocationProvider } from 'react-router-last-location';
import { FINGER_PRINT_CONFIG } from "../constants/CommonConstants";


const LoginPage = asyncComponent(() => import('./../../login/containers/LoginPage')
  .then(module => module.default), { name: 'LoginPage' });
const ProfilePage = asyncComponent(() => import('./../../viewProfile/containers/ProfilePage')
  .then(module => module.default), { name: 'ProfilePage' });
const PhotoAlbumPage = asyncComponent(() => import('./../../photoAlbum/containers/PhotoAlbumPage')
  .then(module => module.default), { name: 'PhotoAlbumPage' });
const PageNotFound = asyncComponent(() => import('./PageNotFound')
  .then(module => module.default), { name: 'PageNotFound' });
const ForgotPassword = asyncComponent(() => import('./../../forgotPassword/containers/forgotPasswordPage')
  .then(module => module.default), { name: 'ForgotPassword' });
const AdvanceSearchForm = asyncComponent(() => import('./../../searchForm/containers/SearchFormPage')
  .then(module => module.default), { name: 'AdvanceSearchForm' });
const ListingPage = asyncComponent(() => import('./../../listing/containers/ListingPage')
  .then(module => module.default), { name: 'ListingPage' });
const SavedSearchPage = asyncComponent(() => import('./../../searchForm/components/savedSearchPage')
  .then(module => module.default), { name: 'SavedSearchPage' });
const SearchByProfileId = asyncComponent(() => import('./../../searchForm/components/SearchByProfileId')
  .then(module => module.default), { name: 'SearchByProfileId' });
const ViewSimilarJSMS = asyncComponent(() => import('./../../viewProfile/components/viewSimilarJSMS')
  .then(module => module.default), { name: 'ViewSimilarJSMS' });
const StaticVerfJSMS = asyncComponent(() => import('./../../static/container/StaticFile')
  .then(module => module.default), { name: 'StaticVerfJSMS' });
const ForgotPasswordV2 = asyncComponent(() => import('./../../forgotPassword/containers/forgotPasswordPageV2')
  .then(module => module.default), { name: 'ForgotPasswordV2' });
const ResetPassword = asyncComponent(() => import('./../../forgotPassword/containers/resetPasswordPage')
  .then(module => module.default), { name: 'ResetPassword' });
const SuccessStoriesPage = asyncComponent(() => import('./../../success_stories/containers/SuccessStoriesPage')
  .then(module => module.default), { name: 'SuccessStoriesPage' });
const StoryTuplePage = asyncComponent(() => import('./../../success_stories/containers/StoryTuplePage')
  .then(module => module.default), { name: 'StoryTuplePage' });
const AssistancePage = asyncComponent(() => import('./../../settings/components/AssistancePage')
  .then(module => module.default), { name: 'AssistancePage' });
const SettingsPage = asyncComponent(() => import('./../../settings/components/SettingsPage')
  .then(module => module.default), { name: 'SettingsPage' });
const AboutUsPage = asyncComponent(() => import('./../../settings/components/AboutUsPage')
  .then(module => module.default), { name: 'AboutUsPage' });
const DesiredPartnerProfile = asyncComponent(() => import('./../../dpp/components/desiredPartnerProfile')
  .then(module => module.default), { name: 'PartnerPreference' });
const AadhaarVerf = asyncComponent(() => import('../containers/AddVerf')
  .then(module => module.default), { name: 'AadhaarVerf' });
const DeleteProfile = asyncComponent(() => import('./../../settings/containers/deleteFlow')
  .then(module => module.default), {name: 'DeleteProfile'});
const Registration = asyncComponent(() => import('./../../register/containers/register')
  .then(module => module.default), {name: 'Registration'});

const MarketingRegistration = asyncComponent(() => import('./../../register/containers/marketingRegistration')
  .then(module => module.default), {name: 'MarketingRegistration'});


const AboutMeAndFamilyFlow = asyncComponent(() => import('../../register/containers/aboutMeAndFamilyFlow')
  .then(module => module.default), {name: 'AboutMeAndFamilyFlow'});

const CompleteRegistration = asyncComponent(() => import('./../../register/containers/completeRegistration')
  .then(module => module.default), {name: 'CompleteRegistration'});

const FamilyInfoContainer = asyncComponent(() => import('./../../register/containers/familyInfoContainer')
  .then(module => module.default), {name: 'FamilyInfoContainer'});

const hash = getRoutePath(window.location.href, hash1);
const searchListingUrl = getSearchListingUrl(window.location.href, hash1);
const inboxListingUrl = getInboxListingUrl(window.location.href, hash1);


class App extends React.Component {

  constructor() {
    super();
    this.store = store;
    window.jsMain = {};
    window.jsMain.abc = null;
    this.handleLoad = this.handleLoad.bind(this);
  }

  componentDidMount() {
    // set fingerprint cookie
    try{
      if (typeof Fingerprint2 !== "undefined" && Fingerprint2 !== null) {
          if (document.cookie.indexOf(FINGER_PRINT_CONFIG.COOKIE_NAME) !== -1) {
              let a = getCookie(FINGER_PRINT_CONFIG.COOKIE_NAME);
          } else {
              new Fingerprint2(FINGER_PRINT_CONFIG).get(function(result, components) {
              setCookie(FINGER_PRINT_CONFIG.COOKIE_NAME, result, (30*24), ".jeevansathi.com");
              });
          }}
    }catch(e){
    console.log(e);
    }
    // chat request in case of login only 
    //in app bcz it execute in case of refresh on each component
    if (getCookie('AUTHCHECKSUM')) {
      // connection should disconnect in case of tab change
      // connection should gets established in case of visibility hidden
      document.addEventListener("visibilitychange", () => {
        window.jsMain.visibilityState = document.visibilityState;
        if (window.jsMain.visibilityState == 'hidden') {
          // disconnectSocket();
          if (window.jsMain.abc && window.jsMain.abc.connected) {
            window.jsMain.abc.disconnect();
          }
        } else {
          if (window.jsMain.jsChatFlag == '1') {
            this.chatResponse();
          }
        }
      });
    }

    // on hardreload of page
    if (getCookie('AUTHCHECKSUM') && !window.jsMain.abc) {
      window.addEventListener('load', this.handleLoad);
    } else {
      if (window.jsMain.abc && window.jsMain.abc.connected) {
        window.jsMain.abc.disconnect();
      }
    }

    let userRegSource = localStorage.getItem('userRegSource');
    if(userRegSource == 1 || !userRegSource){
      setItem('userRegSource',2); // 2 is reacat 1 is angular
      setItem('UD',{});
      setItem('UD_display',{});
    }
    this.hideLoaderAfterFewSec()
  }

  // initialize strophe variable
  handleLoad() {
    window.jsMain.abc = new window.Strophe.Connection(`wss://${openfireUrl}/ws/`);
    if ((window.jsMain.stropheResponse && window.jsMain.stropheResponse.userStatus == 'Added') || window.jsMain.stropheResponse == undefined) {
      for (let i = 0; i < 5; i++) {
        setTimeout(() => {
          if ((window.jsMain.stropheResponse && window.jsMain.stropheResponse.userStatus == 'Added') || window.jsMain.stropheResponse == undefined) {
            this.chatResponse();
          }

        }, i * 2000);

      }
    }
  }

  //hit chat api get response and establish connection
  chatResponse() {
    let url = `${authServiceUrl}${CONSTANTS.CHAT_CONNECTION}?authchecksum=${getCookie('AUTHCHECKSUM')}`;
    // let url = `https://auth.jeevansathi.com/${CONSTANTS.CHAT_CONNECTION}?authchecksum=${getCookie('AUTHCHECKSUM')}`;

    axios.post(url)
      .then((response) => {
        window.jsMain.stropheResponse = response.data.data;
        if (response.data.data.userStatus == 'User exists') {
          window.jsMain.abc.connect(`${window.jsMain.stropheResponse.user}@${openfireServerName}`, `${window.jsMain.stropheResponse.hash}`, onConnect);
        }
      })
  }
  componentWillMount() {
    // let AUTHCHECKSUM_FROM_GET = getParameterByName(window.location.href, "AUTHCHECKSUM");
    // if (AUTHCHECKSUM_FROM_GET != null && AUTHCHECKSUM_FROM_GET != '') {
    //   setCookie("AUTHCHECKSUM", AUTHCHECKSUM_FROM_GET);
    // }

    this.props.MyProfile.AUTHCHECKSUM = getCookie('AUTHCHECKSUM');
    this.props.MyProfile.GENDER = localStorage.getItem('GENDER');
    this.props.MyProfile.USERNAME = localStorage.getItem('USERNAME');
    if (window.location.href.indexOf("profile/viewprofile.php") == -1) 
    {
      localStorage.removeItem('lastProfilePageLocation');
    }
  }

  hideLoaderAfterFewSec() {
      setTimeout(()=>{
          let elm = document.getElementById('tmpRegForm');
          if(elm){
              elm.style.display = "none"
          }
      },10000)

    }


  render() {
    //alert("app");
    var redirectToUrl;
    if (searchListingUrl) {
      redirectToUrl = <Redirect to={searchListingUrl} />;
    }
    else if (inboxListingUrl) {
      redirectToUrl = <Redirect to={inboxListingUrl} />;
    }
    else if (hash) {
      redirectToUrl = <Redirect to={hash} />;
      }
    return (
      <div>
      <Router>
      <LastLocationProvider>
      <div>
      {redirectToUrl}
      <Switch>
        <Route path="/register/newjsms" component={AboutMeAndFamilyFlow}/>
        <Route path="/register/newjsmsreg" component={CompleteRegistration}/>
        <Route path="/register/family" component={FamilyInfoContainer}/>

        <Route path="/static/forgotPasswordV2" component={ForgotPasswordV2} />
                <Route path="/static/resetPassword" component={ResetPassword} />
                <Route path='/login' component={LoginPage} />
                <Route path='/jsmb/login_home.php' component={LoginPage} />
                <Route path='/profile/viewprofile.php' component={ProfilePage} />
                <Route path='/social/MobilePhotoAlbum' component={PhotoAlbumPage} />
                <Route path="/static/forgotPassword" component={ForgotPassword} />
                <Route path="/search/topSearchBand" component={AdvanceSearchForm} />
                <Route path="/search/MobSaveSearch" component={SavedSearchPage} />
                <Route path="/search/searchByProfileId" component={SearchByProfileId} />
                <Route path="/listing" component={ListingPage} />
                <Route path="/search/QuickSearchBand" component={ListingPage} />
                <Route path="/search/criteoProfile" component={ListingPage} />
                <Route path="/static/jsmsVerificationStaticPage" component={StaticVerfJSMS} />
                <Route path="/static/passwordResetMailer" component={LoginPage} />

                <Route path="/success_stories" component={SuccessStoriesPage} />
                <Route path="/assistance" component={AssistancePage} />
                <Route path="/settings" component={SettingsPage} />
                <Route path="/about_us" component={AboutUsPage} />
                <Route path="/story" component={StoryTuplePage} />
                <Route path="/profile/dpp" component={DesiredPartnerProfile} />
                <Route path="/" component={EnsureLoggedInContainer} />
                <Route path='/home'
                       render={(props) => {
                         if (homepageFlowAB!== 'A') {
                           return <HomePage {...props} />
                         } else {
                           return <NewHomePage {...props} />
                         }
                       }}/>
                <Route path="/AadhaarVerf" component={AadhaarVerf} />
                <Route path="/deleteProfile" component={DeleteProfile} />
                <Route component={PageNotFound} />
        <Route path="/register/page1" component={Registration}/>
        <Route path="/registration2" component={MarketingRegistration}/>
                <Route path="/profile/registration_new.php" component={Registration}/>
        <Route path="/register/:any1" component={Registration}/>
        <Route path="/register/:any1/:any2" component={Registration}/>
        {/*<Route path='/:communitypages'*/}
               {/*render={({ match }) => {*/}
                 {/*if (/^(bride|groom)-(.*)-profiles$/gi.test(match.params.communitypages)) {*/}
                   {/*return <ProfilePage />;*/}
                 {/*}*/}
               {/*}}*/}
        {/*/>*/}

      </Switch>
      </div>
      </LastLocationProvider>
      </Router>
      </div>);
  }
}

// provide packets of presence
let onConnect = (status) => {
  if (status == window.Strophe.Status.CONNECTED) {
    // invokePluginLoginHandler("success");
    window.jsMain.abc.send($pres().tree());
  }

}
const mapStateToProps = (state) => {
  return {
    MyProfile: state.LoginReducer.MyProfile,
  }
}

const mapDispatchToProps = (dispatch) => {
  return {
    siginFromCookie: (response) => {
      dispatch(siginFromCookie(response));
    }
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(App);