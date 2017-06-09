import React from 'react';
import { connect } from "react-redux";

class EnsureLoggedInContainer extends React.Component
{
	componentDidMount() {
		const { dispatch, currentURL } = this.props;

		if ( !isLoggedIn )
		{
			dispatch(setRedirectUrl(currentURL));
			this.props.history.push('/login');
		}
	}

	render() {
		if ( isLoggedIn )
		{
			return this.props.children;
		}
		else
		{
			return null;
		}
	}

	function mapStateToProps(state,ownProps)
	{
		return {
			isLoggedIn : state.isLoggedIn;
			currentURL : ownProps.location.pathname
		}
	}

	export default connect(mapStateToProps)(EnsureLoggedInContainer);
}