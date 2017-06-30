require ('../style/common.css')
require ('../style/appPromo.css')
require ('../style/errorBar.css')
require ('../style/fonts.css')
require ('../style/mediaQuery.css')
import React from 'react';
import asyncComponent from './asyncComponent';
import {getRoutePath} from './UrlDecoder';
import {connect} from 'react-redux';
import {siginFromCookie} from "./../../login/actions/LoginActions";
import {getCookie} from "./CookieHelper";
import {
  BrowserRouter as Router,
  Route,
  browserHistory,
  Redirect,
  Link,
  Switch
} from "react-router-dom";
import EnsureLoggedInContainer from '../containers/EnsureLoggedInContainer';

const LoginPage = asyncComponent(() => import('./../../login/containers/LoginPage')
  .then(module => module.default), { name: 'LoginPage' });
const MyjsPage = asyncComponent(() => import('./../../myjs/containers/MyjsPage')
  .then(module => module.default), { name: 'MyjsPage' });
const ProfilePage = asyncComponent(() => import('./../../viewProfile/containers/ProfilePage')
  .then(module => module.default), { name: 'ProfilePage' });
const PhotoAlbumPage = asyncComponent(() => import('./../../photoAlbum/containers/PhotoAlbumPage')
  .then(module => module.default), { name: 'PhotoAlbumPage' });
const PageNotFound = asyncComponent(() => import('./PageNotFound')
  .then(module => module.default), { name: 'PageNotFound' });
const ForgotPassword = asyncComponent(() => import('./../../forgotPassword/containers/forgotPasswordPage')
  .then(module => module.default), { name: 'ForgotPassword' });

const hash = getRoutePath(window.location.href);

class App extends React.Component
{

  componentWillMount() {
    this.props.MyProfile.AUTHCHECKSUM  = getCookie('AUTHCHECKSUM');
    this.props.MyProfile.GENDER  = localStorage.getItem('GENDER');
    this.props.MyProfile.USERNAME  = localStorage.getItem('USERNAME');
  }


  render() {
      var redirectToHashUrl;
      if ( hash )
      {
        redirectToHashUrl = <Redirect to={hash}/>;
      }
    return (
      <div>
      <Router>
      <div>
      {redirectToHashUrl}
      <Switch>
      <Route path='/login' component= {LoginPage}/>
      <Route path='/profile/viewprofile.php' component={ProfilePage} />
      <Route path='/social/MobilePhotoAlbum' component={PhotoAlbumPage} />
      <Route path="/static/forgotPassword" component={ForgotPassword}/>
      <Route path="/" component={EnsureLoggedInContainer}/>
      <Route component={PageNotFound} />
      </Switch>
      </div>
      </Router>
      </div>);
  }
}

const mapStateToProps = (state) => {
  return{
   MyProfile: state.LoginReducer.MyProfile,
 }
}

const mapDispatchToProps = (dispatch) => {
  return{
    siginFromCookie: (response) => {
      dispatch(siginFromCookie(response));
    }
  }
}

export default connect(mapStateToProps,mapDispatchToProps)(App);
