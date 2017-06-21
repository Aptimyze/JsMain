import React from "react";
import MyjsHeadHTML from "../components/MyjsHeader";
import EditBar from "../components/MyjsEditBar";
import MyjsSlider from "../components/MyjsSliderBar";
import AcceptCount from '../components/MyjsAcceptcount';
import MyjsProfileVisitor from '../components/MyjsProfileVisitor';
import InterestExp from '../components/MyjsInterestExp';
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import {DISPLAY_PROPS}  from "../../common/constants/CommonConstants";
import * as CONSTANTS from '../../common/constants/apiConstants';
import { removeCookie } from '../../common/components/CookieHelper';
import Loader from "../../common/components/Loader";

require ('../style/jsmsMyjs_css.css');



export class CheckDataPresent extends React.Component{
	render(){
	 if(!this.props.fetched)
		{
			return (<div className="nodatafetch"></div>)
		}
		switch (this.props.blockname) {
			case "int_exp":
						if(this.props.data===undefined)
						{
							  return (<div className="noData Intexp"></div>);
						}
						return(<InterestExp int_exp_list={this.props.data}  />);
						break;
			case "prf_visit":
						if(this.props.data.tuples===null)
						{
							return (<div className="noData prfvisit"></div>);
						}
						return(<MyjsProfileVisitor responseMessage={this.props.data}/>);
			default:
					return <div>nodata</div>

		}
	}
}



export  class MyjsPage extends React.Component {
	constructor(props) {
  		super();
			this.state=
			{
			}

  	}

  	componentDidMount(){
  			console.log("I am in componentDidMount.");
			this.props.hitApi();
		}

	componentWillReceiveProps(nextProps){
		this.setState ({
                showLoader : false
            })

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
									animProp : cssPrefix + 'Transform'
								}
					});
			}
	};
}

  	render() {
  			if(!this.props.myjsData.fetched)
	        {
	          return (<div><Loader show="page"></Loader></div>)
	        }

  		return(
		  <div id="mainContent">
				  <div className="perspective" id="perspective">
								<div className="" id="pcontainer">
								<MyjsHeadHTML bellResponse={this.props.myjsData.apiData.BELL_COUNT} fetched={this.props.myjsData.fetched}/>
								<EditBar cssProps={this.state.cssProps}  profileInfo ={this.props.myjsData.apiData.my_profile} fetched={this.props.myjsData.fetched}/>
								<AcceptCount fetched={this.props.myjsData.fetched} acceptance={this.props.myjsData.apiData.all_acceptance} justjoined={this.props.myjsData.apiData.just_joined_matches}/>


							<CheckDataPresent fetched={this.props.myjsData.fetched} blockname={"int_exp"} data={this.props.myjsData.apiData.interest_expiring}/>

							<CheckDataPresent fetched={this.props.myjsData.fetched} blockname={"prf_visit"} data={this.props.myjsData.apiData.visitors}/>

								<MyjsSlider cssProps={this.state.cssProps}  fetched={this.props.myjsData.fetched} displayProps = {DISPLAY_PROPS} title={this.state.DR} listing ={this.props.myjsData.apiData.interest_received} listingName = 'interest_received' />
							</div>
						</div>
			</div>
		);
	}

}

const mapStateToProps = (state) => {
    return{
       myjsData: state.MyjsReducer,
			 listingData :  state.listingReducer
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
