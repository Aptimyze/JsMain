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
import { redirectToLogin } from '../../common/components/RedirectRouter';
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
						console.log('expired list');
						console.log(this.props);
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
export class NodataBlock extends React.Component{
	render(){
					if(!this.props.fetched)
			 		{
			 			return (<div className="nodatafetch"></div>)
			 		}
					let noDataHtml = '',noDataHtml1 = '', noDataHtml2 = '',noDataHtml3='';

					if(this.props.data.interest_received.tuples===null){
						noDataHtml1= <span id="awaitingResponseAbsent" key="int_rec" >
												 <div className="pad1">
													 <div className="fullwid pt15 pb10">
														 <div className="f17 fontlig color7">{this.props.data.interest_received.title}</div>
													 </div>
													 <div className="pb20" id="eoiAbsent">
														 <div className="bg8">
															 <div className="pad14 txtc">
																 <div className="fontlig f14 color8">
																	 Members Who Showed Interest In Your Profile Will Appear Here
																 </div>
															 </div>
														 </div>
													 </div>
												 </div>
											 </span>
				 }
				 if(this.props.data.visitors.tuples===null){
						 noDataHtml2= <span id="visitorAbsent" key="novisitor">
						 							<div className="pad1">
														<div className="fullwid pt15 pb10">
															<div className="f17 fontlig color7">{this.props.data.visitors.title}</div>
														</div>
														<div className="pb20" id="eoiAbsent">
															<div className="bg8">
																<div className="pad14 txtc">
																	<div className="fontlig f14 color8">
																		Members Who Visited Your Profile Will Appear Here
																	</div>
																</div>
															</div>
														</div>
													</div>
												</span>
					}
					if(this.props.data.match_alert.tuples===null){
						noDataHtml3= <span id="matchalertAbsent" key="nomatchalert">
												 <div className="pad1">
													 <div className="fullwid pt15 pb10">
														 <div className="f17 fontlig color7">{this.props.data.match_alert.title}</div>
													 </div>
													 <div className="pb20" id="eoiAbsent">
														 <div className="bg8">
															 <div className="pad14 txtc">
																 <div className="fontlig f14 color8">
																	Members Matching Your Desired Partner Profile Will Appear Here
																 </div>
															 </div>
														 </div>
													 </div>
												 </div>
											 </span>
					}

			   noDataHtml =[noDataHtml1,noDataHtml2,noDataHtml3]
			   return (<div>{noDataHtml}</div>);
		}
}



export  class MyjsPage extends React.Component {
	constructor(props) {
  		super();
			this.state=
			{
			}

  	}

  	componentDidMount()
  	{
		this.props.hitApi();
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
