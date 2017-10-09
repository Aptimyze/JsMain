import React from 'react';
import { connect } from "react-redux";
import {withRouter} from "react-router";
import asyncComponent from '../components/asyncComponent';
import {LOGGED_OUT_PAGE} from "../../common/constants/CommonConstants";
import {SPA_PAGE} from "../../common/constants/CommonConstants";
import {stripTrailingSlash} from '../../common/components/UrlDecoder';

import MyjsPage from '../../myjs/containers/MyjsPage';

const ProfilePage = asyncComponent(() => import('./../../viewProfile/containers/ProfilePage')
  .then(module => module.default), { name: 'ProfilePage' });
const PageNotFound = asyncComponent(() => import('./../components/PageNotFound')
  .then(module => module.default), { name: 'PageNotFound' });
const LoginPage = asyncComponent(() => import('./../../login/containers/LoginPage')
  .then(module => module.default), { name: 'LoginPage' });
const LogoutPage = asyncComponent(() => import('./../../login/containers/LogoutPage')
  .then(module => module.default), { name: 'LogoutPage' });


import {
  Route,Switch
} from "react-router-dom";

class EnsureLoggedInContainer extends React.Component
{
    componentDidMount() {
        var url = stripTrailingSlash(this.props.location.pathname);

        if ( !this.props.MyProfile.AUTHCHECKSUM && !(LOGGED_OUT_PAGE.indexOf(url) !== -1) && (SPA_PAGE.indexOf(url) !== -1))
        {
            this.props.history.prevUrl = url;
            this.props.history.push('/login');
        }
    }

    render() {
        if ( this.props.MyProfile.AUTHCHECKSUM )
        {
            if(this.props.location.state)
            {
                window.location.href = this.props.location.state;
                return null;
            }
            else
            {
                return <div>
                        <Switch>
                        <Route exact path="/" component={MyjsPage}/>
                        <Route path='/myjs' component={MyjsPage} />
                        <Route path='/profile/mainmenu.php' component={MyjsPage} />
                        <Route path='/P/logout.php' component={MyjsPage} />
                        <Route component={PageNotFound} />
                        </Switch>
                        </div>
            }
        }
        else if((SPA_PAGE.indexOf(this.props.location.pathname)=== -1))
        {
               return <div>
                        <Switch>
                        <Route path='/profile/mainmenu.php' component={LoginPage} />
                        <Route path='/P/logout.php' component={LoginPage} />
                        <Route component={PageNotFound} />
                        </Switch>
                       </div>
        }
        else
        {
            this.props.history.push('/login');
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
