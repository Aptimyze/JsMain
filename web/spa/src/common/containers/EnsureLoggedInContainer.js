import React from 'react';
import { connect } from "react-redux";
import {withRouter} from "react-router";
import asyncComponent from '../components/asyncComponent';
import {LOGGED_OUT_PAGE} from "../../common/constants/CommonConstants";

// import MyjsPage from '../../myjs/containers/MyjsPage';
const MyjsPage = asyncComponent(() => import('../../myjs/containers/MyjsPage')
  .then(module => module.default), { name: 'MyjsPage' });
const ProfilePage = asyncComponent(() => import('./../../viewProfile/containers/ProfilePage')
  .then(module => module.default), { name: 'ProfilePage' });
const PageNotFound = asyncComponent(() => import('./../components/PageNotFound')
  .then(module => module.default), { name: 'PageNotFound' });
const LoginPage = asyncComponent(() => import('./../../login/containers/LoginPage')
  .then(module => module.default), { name: 'LoginPage' });

import {
  Route,Switch
} from "react-router-dom";

class EnsureLoggedInContainer extends React.Component
{
    componentDidMount() {
        if ( !this.props.MyProfile.AUTHCHECKSUM && !LOGGED_OUT_PAGE.includes(this.props.location.pathname) )
        {
            this.props.history.prevUrl = this.props.location.pathname;
            this.props.history.push('/login');
        }
    }

    render() {
        if ( this.props.MyProfile.AUTHCHECKSUM )
        {
            return <div>
                    <Switch>
                    <Route exact path="/" component={MyjsPage}/>
                    <Route path='/myjs' component={MyjsPage} />
                    <Route path='/viewProfile' component={ProfilePage} />
                    <Route component={PageNotFound} />
                    </Switch>
                    </div>
        }
        else
        {
             return null;         
        }
    }


}
const mapStateToProps = (state,ownProps) => {
    return{
        MyProfile : state.LoginReducer.MyProfile,
    }
}

export default connect(mapStateToProps)(withRouter(EnsureLoggedInContainer))