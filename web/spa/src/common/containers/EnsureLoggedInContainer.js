import React from 'react';
import { connect } from "react-redux";
import {withRouter} from "react-router";
import asyncComponent from '../components/asyncComponent';

// import MyjsPage from '../../myjs/containers/MyjsPage';
const MyjsPage = asyncComponent(() => import('../../myjs/containers/MyjsPage')
  .then(module => module.default), { name: 'MyjsPage' });
import {
  Route,
} from "react-router-dom";

class EnsureLoggedInContainer extends React.Component
{
    componentDidMount() {
        if ( !this.props.AUTHCHECKSUM )
        {
            console.log('Profile checksum is not set.');
            this.props.history.push('/login');
        }
    }

    render() {
        if ( this.props.AUTHCHECKSUM )
        {

            return <div>
                    <Route path='/myjs' component={MyjsPage} />
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
        AUTHCHECKSUM : state.AUTHCHECKSUM,
    }
}

export default connect(mapStateToProps)(withRouter(EnsureLoggedInContainer))