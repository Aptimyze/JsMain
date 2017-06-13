import React from 'react';
import { connect } from "react-redux";
import {withRouter} from "react-router";
import asyncComponent from '../components/asyncComponent';

// import MyjsPage from '../../myjs/containers/MyjsPage';
const MyjsPage = asyncComponent(() => import('../../myjs/containers/MyjsPage')
  .then(module => module.default), { name: 'MyjsPage' });
const ProfilePage = asyncComponent(() => import('./../../viewProfile/containers/ProfilePage')
  .then(module => module.default), { name: 'ProfilePage' });
import {
  Route,
} from "react-router-dom";

class EnsureLoggedInContainer extends React.Component
{
    componentDidMount() {

        if ( !this.props.AUTHCHECKSUM )
        {
            this.props.history.prevUrl = this.props.location.pathname;
            this.props.history.push('/login');
        }
    }

    render() {
        if ( this.props.AUTHCHECKSUM )
        {
            return <div>
                    <Route exact path="/" component={MyjsPage}/>
                    <Route path='/myjs' component={MyjsPage} />
                    <Route path='/viewProfile' component={ProfilePage} />
                    </div>;
        }
        else
        {
            
           return null;
        }
    }


}
const mapStateToProps = (state,ownProps) => {
    return{
        AUTHCHECKSUM : state.LoginReducer.AUTHCHECKSUM,
    }
}

export default connect(mapStateToProps)(withRouter(EnsureLoggedInContainer))