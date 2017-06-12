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
  Link
} from "react-router-dom";
const LoginPage = asyncComponent(() => import('./../../login/containers/LoginPage')
  .then(module => module.default), { name: 'LoginPage' });

import EnsureLoggedInContainer from '../containers/EnsureLoggedInContainer';

const MyjsPage = asyncComponent(() => import('./../../myjs/containers/MyjsPage')
  .then(module => module.default), { name: 'MyjsPage' });
const ProfilePage = asyncComponent(() => import('./../../viewProfile/containers/ProfilePage')
  .then(module => module.default), { name: 'ProfilePage' });
// const EnsureLoggedInContainer = asyncComponent(() => import('../containers/EnsureLoggedInContainer')
//   .then(module => module.default), { name: 'EnsureLoggedInContainer' });


const hash = getRoutePath(window.location.href);

class App extends React.Component
{

  componentDidMount() {
    var response;
    response = {
      'AUTHCHECKSUM' : getCookie('AUTHCHECKSUM'),
    };
    this.props.siginFromCookie(response);
  }

  componentWillReceiveProps(nextProps)
  {

  }
  render() {
    console.log("I am in app.js");
    return (<div>
      <Router>
      <div>
      <Route path="/" component={EnsureLoggedInContainer}/>
      <Route path='/viewProfile' component={ProfilePage} />
      <Route path='/login' component={LoginPage}/>
      </div>
      </Router>
      </div>);
  }
}

const mapStateToProps = (state) => {
  return{
   AUTHCHECKSUM: state.AUTHCHECKSUM,
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
