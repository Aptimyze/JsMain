import React from "react";
import MyjsHeadHTML from "../components/MyjsHeader";
import EditBar from "../components/MyjsEditBar";
import MyjsSlider from "../components/MyjsSliderBar";
import AcceptCount from '../components/MyjsAcceptcount';
import MyjsProfileVisitor from '../components/MyjsProfileVisitor';
import InterestExp from '../components/MyjsInterestExp';
import NodataBlock from '../components/MyjsNodataBlock';
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import {DISPLAY_PROPS}  from "../../common/constants/CommonConstants";
import * as CONSTANTS from '../../common/constants/apiConstants';
import { removeCookie } from '../../common/components/CookieHelper';
import { redirectToLogin } from '../../common/components/RedirectRouter';
import GA from "../../common/components/GA";
import Loader from "../../common/components/Loader";
import MetaTagComponents from '../../common/components/MetaTagComponents';
import * as jsb9Fun from '../../common/components/Jsb9CommonTracking';
require ('../style/jsmsMyjs_css.css');



export class CheckDataPresent extends React.Component{
	render(){

	 if(!this.props.fetched)
		{
			return (<div className="nodatafetch"></div>)
		}

		switch (this.props.blockname) {
			case "int_exp":
						if( (this.props.data===undefined)  || (this.props.data.tuples===null))
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
					return (<div>nodata</div>);

		}
	}
}




export  class MyjsPage extends React.Component {
	constructor(props) {
  		super();
			jsb9Fun.recordBundleReceived(this,new Date().getTime());
			this.state=
			{

			}

  	}

  	componentDidMount()
  	{
		if(!this.props.myjsData.fetched || true ) // caching conditions go here in place of true
			this.props.hitApi(this);
	}
componentDidUpdate(){
	jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
}
	componentWillReceiveProps(nextProps)
	{
		redirectToLogin(this.props.history,nextProps.myjsData.apiData.responseStatusCode);

		this.setState ({
			showLoader : false
		})
	}

	componentWillMount(){
			this.CssFix();
	}
	componentWillUnmount(){
		this.props.jsb9TrackRedirection(new Date().getTime(),this.url);
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
					
					this.trackJsb9 = 1;
  		return(
		  <div id="mainContent">
		  	<MetaTagComponents page="MyjsPage"/>
		  		<GA ref="GAchild" />
				  <div className="perspective" id="perspective">
								<div className="" id="pcontainer">

									<MyjsHeadHTML bellResponse={this.props.myjsData.apiData.BELL_COUNT} fetched={this.props.myjsData.fetched}/>

									<EditBar cssProps={this.state.cssProps}  profileInfo ={this.props.myjsData.apiData.my_profile} fetched={this.props.myjsData.fetched}/>

									<AcceptCount fetched={this.props.myjsData.fetched} acceptance={this.props.myjsData.apiData.all_acceptance} justjoined={this.props.myjsData.apiData.just_joined_matches}/>

									<CheckDataPresent fetched={this.props.myjsData.fetched} blockname={"int_exp"} data={this.props.myjsData.apiData.interest_expiring}/>

									<CheckDataPresent fetched={this.props.myjsData.fetched} blockname={"prf_visit"} data={this.props.myjsData.apiData.visitors}/>


									<MyjsSlider cssProps={this.state.cssProps}  fetched={this.props.myjsData.fetched} displayProps = {DISPLAY_PROPS} title={this.state.DR} listing ={this.props.myjsData.apiData.interest_received} listingName = 'interest_received' />

									<NodataBlock fetched={this.props.myjsData.fetched} data={this.props.myjsData.apiData}/>

								</div>
							</div>
			</div>
		);
	}

}

const mapStateToProps = (state) => {
    return{
       myjsData: state.MyjsReducer,
			 listingData :  state.listingReducer,
			 Jsb9Reducer : state.Jsb9Reducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        hitApi: (containerObj) => {return commonApiCall(CONSTANTS.MYJS_CALL_URL,{},'SET_MYJS_DATA','POST',dispatch,true,containerObj);},
				jsb9TrackRedirection : (time,url) => jsb9Fun.recordRedirection(dispatch,time,url)

    }
}

export default connect(mapStateToProps,mapDispatchToProps)(MyjsPage)
