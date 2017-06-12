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
    console.log("Response is: ")
    console.log('AUTHCHECKSUM');
    this.props.siginFromCookie(response);  
  }
  render() {
    return (<div>
    <Router>
      <div>
        <Route path='/login' component={LoginPage}/>
        <Route path='/myjs' component={MyjsPage}/>
        <Route path='/viewProfile' component={ProfilePage} />
      </div>
    </Router>
  </div>);
  }
}

const mapStateToProps = (state,ownProps) => {
  return{
    isLoggedIn : state.isLoggedIn,
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
