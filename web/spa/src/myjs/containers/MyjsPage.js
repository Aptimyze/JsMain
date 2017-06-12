import React from "react";
import MyjsHeadHTML from "./MyjsHeader";
import EditBar from "./MyjsEditBar";
import AcceptCount from './MyjsAcceptcount';
import ProfileVisitor from './MyjsProfileVisitor'
import {MyjsApi} from "../actions/MyjsApi";
import { connect } from "react-redux";

require ('../style/jsmsMyjs_css.css');



export  class MyjsPage extends React.Component {
	constructor(props) {
  		super();
			this.setState({
				apiSent :false


			})
			this.props.hitApi();console.log('apisenr');
  	}
  	render() {
		return(
		  <div id="mainContent">
				  <div className="perspective" id="perspective">
							<div className="" id="pcontainer">
							 <MyjsHeadHTML/>
							 <EditBar/>
							 <AcceptCount/>
							 <ProfileVisitor/>





							</div>
					</div>
					<button onClick = {this.props.printProp.bind(this)}>click</button>
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
            dispatch(MyjsApi());
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(MyjsPage)
