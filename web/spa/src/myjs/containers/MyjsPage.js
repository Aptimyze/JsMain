import React from "react";
import MyjsHeadHTML from "../components/MyjsHeader";
import EditBar from "./MyjsEditBar";
import AcceptCount from './MyjsAcceptcount';
import ProfileVisitor from './MyjsProfileVisitor'
import {MyjsApi} from "../actions/MyjsApiAction";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants'

require ('../style/jsmsMyjs_css.css');



export  class MyjsPage extends React.Component {
	constructor(props) {
  		super();
			this.state=
			{
				apiSent :false,
				dataLoaded:false
			}

  	}
		componentWillReceiveProps(nextProps)
		{
			console.log("props recieved",nextProps);
		}

		componentDidMount(){

			this.props.hitApi();


			this.setState({
				apiSent:true
			})

		}
  	render() {

		return(
		  <div id="mainContent">
				  <div className="perspective" id="perspective">
							<div className="" id="pcontainer">
							<MyjsHeadHTML bellResponse={this.props.reducerData.apiData.BELL_COUNT} fetched={this.props.reducerData.fetched} />;
							<AcceptCount/>
							 <ProfileVisitor/>





							</div>
					</div>
					<button onClick = {this.printProp.bind(this)}>click</button>
			</div>
		);
	}

	printProp(){
		console.log('myujs');
		console.log(this.props);
	
	}
}




const mapStateToProps = (state) => {




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
