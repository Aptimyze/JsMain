import React from 'react';
import {connect} from "react-redux";
import {withRouter} from "react-router";
import asyncComponent from '../components/asyncComponent';
import {LOGGED_OUT_PAGE, SPA_PAGE} from "../../common/constants/CommonConstants";
import {getParameterByName, stripTrailingSlash} from '../../common/components/UrlDecoder';

import MyjsPage from '../../myjs/containers/MyjsPage';
import StaticFile from '../../static/container/StaticFile';

import HomePage from './../../homePage/containers/HomePage';
import NewHomePage from './../../homePage/containers/newHome';
import {Route, Switch} from "react-router-dom";

import {getCookie} from "../components/CookieHelper";
// const ProfilePage = asyncComponent(() => import('./../../viewProfile/containers/ProfilePage')
//   .then(module => module.default), { name: 'ProfilePage' });
const PageNotFound = asyncComponent(() => import('./../components/PageNotFound')
  .then(module => module.default), {name: 'PageNotFound'});
const LoginPage = asyncComponent(() => import('./../../login/containers/LoginPage')
  .then(module => module.default), {name: 'LoginPage'});
const LogoutPage = asyncComponent(() => import('./../../login/containers/LogoutPage')
  .then(module => module.default), {name: 'LogoutPage'});
const ListingPage = asyncComponent(() => import('./../../listing/containers/ListingPage')
  .then(module => module.default), {name: 'ListingPage'});
const CAL = asyncComponent(() => import('./../../cal/components/CalObject')
  .then(module => module.default), {name: 'CAL'});
const SuccessStoriesPage = asyncComponent(() => import('./../../success_stories/containers/SuccessStoriesPage')
  .then(module => module.default), {name: 'SuccessStoriesPage'});
const AadhaarVerf = asyncComponent(() => import('../containers/AddVerf')
  .then(module => module.default), {name: 'AadhaarVerf'});
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

class EnsureLoggedInContainer extends React.Component {
  componentDidMount() {
    var url = stripTrailingSlash(this.props.location.pathname);

    if (!this.props.MyProfile.AUTHCHECKSUM && !(LOGGED_OUT_PAGE.indexOf(url) !== -1) && (SPA_PAGE.indexOf(url) !== -1)) {
      this.props.history.prevUrl = url;
      this.props.history.push('/login');
    }
  }

  render() {
    var authchecksum_param = getParameterByName(this.props.location.search, 'AUTHCHECKSUM');
    if (this.props.MyProfile.AUTHCHECKSUM) {
      if (this.props.location.state) {
        window.location.href = this.props.location.state;
        return null;
      } else {
        if (this.props.location.pathname == '/')
          this.props.history.push("/myjs");

        return <div>
          <Switch>
            <Route path='/myjs' component={MyjsPage}/>
            <Route path='/profile/mainmenu.php' component={MyjsPage}/>
            <Route path='/P/logout.php' component={LogoutPage}/>
            <Route path='/listing' component={ListingPage}/>
            <Route path='/cal' component={CAL}/>
            <Route path="/(search|inbox)/:listingId" component={ListingPage}/>
            <Route path="/search/criteoProfile" component={ListingPage}/>
            <Route path='/static' component={StaticFile}/>
            <Route path="/AadhaarVerf" component={AadhaarVerf}/>
            <Route path="/success_stories" component={SuccessStoriesPage}/>
            <Route path="/home" component={HomePage}/>
            <Route path="/deleteProfile" component={DeleteProfile}/>
            <Route component={PageNotFound}/>

          </Switch>
        </div>
      }
    } else if ((SPA_PAGE.indexOf(this.props.location.pathname) === -1)) {
      return <div>
        <Switch>
          <Route exact path="/?forceAbTest=A" render={(props) => {
             return <NewHomePage {...props} />
            }
          }/>
          <Route path='/profile/mainmenu.php'  render={(props) => {
            if (homepageFlowAB!== 'A') {
              return <HomePage {...props} />
            } else {
              return <NewHomePage {...props} />
            }
          }
          }/>
          <Route path='/P/logout.php' component={LoginPage}/>
          <Route path='/jsmb/login_home.php' component={LoginPage}/>
          <Route path='/listing' component={LoginPage}/>
          <Route path="/(search|inbox)/:listingId" component={LoginPage}/>
          <Route path='/static' component={StaticFile}/>
          <Route path="/success_stories" component={SuccessStoriesPage}/>
          <Route path='/home'
                 render={(props) => {
                   if (homepageFlowAB!== 'A') {
                     return <HomePage {...props} />
                   } else {
                     return <NewHomePage {...props} />
                   }
                 }
                 }/>
          <Route path="/deleteProfile" component={LoginPage}/>
          <Route path="/registration2" component={MarketingRegistration}/>
          <Route path="/register/newjsms" component={AboutMeAndFamilyFlow}/>
          <Route path="/register/newjsmsreg" component={CompleteRegistration}/>
          <Route path="/register/page1" component={Registration}/>
          <Route path="/profile/registration_new.php" component={Registration}/>
          <Route path="/register/family" component={FamilyInfoContainer}/>
          <Route path="/register/:any1" component={Registration}/>
          <Route path="/register/:any1/:any2" component={Registration}/>
          <Route path="/myjs/jspcPerform" component={LoginPage}/>
          <Route component={PageNotFound}/>
        </Switch>
      </div>
    } else {
      return <div>
        <Switch>
          <Route path='/'
                 render={(props) => {
                   if (homepageFlowAB!== 'A') {
                     return <HomePage {...props} />
                   } else {
                     return <NewHomePage {...props} />
                   }
                 }


                 }/>
        </Switch>
      </div>

    }
  }


}

const mapStateToProps = (state, ownProps) => {
  return {
    MyProfile: state.LoginReducer.MyProfile,
  }
}

export default connect(mapStateToProps)(withRouter(EnsureLoggedInContainer))