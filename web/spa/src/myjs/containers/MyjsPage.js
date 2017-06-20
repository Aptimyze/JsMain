import React from "react";
import MyjsHeadHTML from "../components/MyjsHeader";
import EditBar from "../components/MyjsEditBar";
import MyjsSlider from "../components/MyjsSliderBar";
import AcceptCount from '../components/MyjsAcceptcount';
import MyjsProfileVisitor from './MyjsProfileVisitor'
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import {DISPLAY_PROPS}  from "../../common/constants/CommonConstants";
import * as CONSTANTS from '../../common/constants/apiConstants';
import { removeCookie } from '../../common/components/CookieHelper';

require ('../style/jsmsMyjs_css.css');



export  class MyjsPage extends React.Component {
	constructor(props) {
  		super();
			this.state=
			{
				showPD1: false
			}

  	}

  	getStateValue(val){
  			console.log('h1');
  			console.log(val);
  			return val;
   	}

   	loadPDPage(){   		
   		this.setState({
   			showPD1: this.getStateValue()
   		});
   		// this.props.reducerData.showPD = this.getStateValue();
   		// console.log('dsdsds');
   		// return this.props.reducerData.showPD;
   	}

  	componentDidMount(){
			this.props.hitApi();
		}

	componentWillReceiveProps(nextProps){
		if(nextProps.reducerData.apiData.responseStatusCode == 9){
			removeCookie('AUTHCHECKSUM');
			this.props.history.push('/login');
		}			
		this.loadPDPage();
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
						var cssPrefix = props[i].replace('Perspective', '').toLowerCase();
							this.setState({
								cssProps:{
									cssPrefix : cssPrefix,
									animProp : '-' + cssPrefix + '-transform'
								}
					});
			}
	};
}

  	render() {
  			if(this.state.showPD1)
  				return(<div></div>);
			if(!this.props.reducerData.fetched)
			return (<div></div>);
  		return(
		  <div id="mainContent">
				  <div className="perspective" id="perspective">
							<div className="" id="pcontainer">
							<MyjsHeadHTML bellResponse={this.props.reducerData.apiData.BELL_COUNT} fetched={this.props.reducerData.fetched}/>
							<EditBar cssProps={this.state.cssProps}  profileInfo ={this.props.reducerData.apiData.my_profile} fetched={this.props.reducerData.fetched}/>
							<AcceptCount fetched={this.props.reducerData.fetched} acceptance={this.props.reducerData.apiData.all_acceptance} justjoined={this.props.reducerData.apiData.just_joined_matches}/>
							<MyjsProfileVisitor responseMessage={this.props.reducerData.apiData.responseMessage} fetched={this.props.reducerData.fetched}/>
							<div id="interestReceivedPresent" className="setWidth sliderc1">
									<div className="pad1">
											<MyjsSlider fetched={this.props.reducerData.fetched} displayProps = {DISPLAY_PROPS} title={this.state.DR} listing ={this.props.reducerData.apiData.interest_received} showPD={this.getStateValue} />
										</div>
						</div>


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
