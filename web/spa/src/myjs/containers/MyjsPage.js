import React from "react";
import MyjsHeadHTML from "../components/MyjsHeader";
import EditBar from "../components/MyjsEditBar";
import MyjsSlider from "../components/MyjsSliderBar";
import AcceptCount from '../components/MyjsAcceptcount';
import ProfileVisitor from '../components/MyjsProfileVisitor';
import InterestExp from '../components/MyjsInterestExp';
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import {DISPLAY_PROPS}  from "../../common/constants/CommonConstants";
import * as CONSTANTS from '../../common/constants/apiConstants';
import { removeCookie } from '../../common/components/CookieHelper';
import Loader from "../../common/components/Loader";

require ('../style/jsmsMyjs_css.css');



export  class MyjsPage extends React.Component {
	constructor(props) {
        super();
        this.state = {
            showLoader: true,
        };
    }
	componentDidMount(){
			this.props.hitApi();
		}
	componentWillReceiveProps(nextProps){
		this.setState ({
                showLoader : false
            })
		
		if(nextProps.reducerData.apiData.responseStatusCode == 9){
			removeCookie('AUTHCHECKSUM');
			this.props.history.push('/login');
		}
	}
	componentWillMount(){
			this.CssFix();
	}
	CssFix()
	{
			// create our test div element
			var div = document.createElement('div');
			// css transition properties
			var props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
			// test for each property
			for (var i in props) {
					if (div.style[props[i]] !== undefined) {
						var cssPrefix = props[i].replace('Perspective', '');
							this.setState({
								cssProps:{
									cssPrefix : cssPrefix,
									animProp :cssPrefix + 'Transform'
								}
					});
			}
	};
}

  	render() {
  			if(this.state.showLoader)
	        {
	          return (<div><Loader show="page"></Loader></div>)
	        }

  		return(
		  <div id="mainContent">
				  <div className="perspective" id="perspective">
								<div className="" id="pcontainer">
								<MyjsHeadHTML bellResponse={this.props.reducerData.apiData.BELL_COUNT} fetched={this.props.reducerData.fetched}/>
								<EditBar cssProps={this.state.cssProps}  profileInfo ={this.props.reducerData.apiData.my_profile} fetched={this.props.reducerData.fetched}/>
								<AcceptCount fetched={this.props.reducerData.fetched} acceptance={this.props.reducerData.apiData.all_acceptance} justjoined={this.props.reducerData.apiData.just_joined_matches}/>

								<InterestExp fetched={this.props.reducerData.fetched} int_exp_list={this.props.reducerData.apiData.interest_expiring}  />



								<ProfileVisitor fetched={this.props.reducerData.fetched} responseMessage={this.props.reducerData.apiData.responseMessage} visitor={this.props.reducerData.apiData.visitors}/>




			</div>
	</div>

			</div>
		);
	}

}

const mapStateToProps = (state) => {
	console.log(state);
    return{
       reducerData: state.MyjsReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        hitApi: () => {
            dispatch(commonApiCall(CONSTANTS.MYJS_CALL_URL,{},'SET_MYJS_DATA','POST'));
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(MyjsPage)
