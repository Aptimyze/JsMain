require ('../style/verified.css')

import React from "react";
import {connect} from "react-redux";
import {Link} from "react-router-dom";
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";
import {getCookie} from '../../common/components/CookieHelper';

class VerifiedVisit extends React.Component {

    constructor(props) {
        super();
        this.state = {
            documentsVerified: "",
            dataLoaded: false  
        };
        props.showVerifiedData(props.profilechecksum);

    }

    componentWillMount() {
        // console.log(this.props);
        this.props.historyObject.push(()=>this.closeOverlay(),"#layer");
    }

    componentWillReceiveProps(nextProps)
    {   
        if(nextProps.documentsVerified) {
            this.setState({
                documentsVerified : nextProps.documentsVerified
            });    
        }  
        this.setState({
            dataLoaded: true
        });
    }
    navigateStatic(e) {
        e.preventDefault();
        window.location.href = "https://www.jeevansathi.com/static/jsmsVerificationStaticPage";
    }
    closeOverlay() {
        // e.preventDefault();
        this.props.closeOverlay();
        return true;
    }
    preventOverlay(e) {
        e.preventDefault();
    }

    render() {
        var docsData;
        if(this.state.dataLoaded == true && this.state.documentsVerified != "") 
        {
            docsData = <div>
                <div className="pt25 f13 color1 docProvided">
                    Documents Provided
                </div>
                <div className="pt10 wid90p resf1 color11">
                    {this.state.documentsVerified}
                </div>
            </div>;
        }
        return(
            <div className="vOverlay js-docVerified" id="js-docVerified" onClick={(e) => this.preventOverlay(e)}>
                <div className="centerDiv">
                    <div className="textDiv fullwid app_txtc">
                        <div className="f15 fb color11">Profile is verified by visit
                        </div>
                    <div className="loadStaticPage">
                        <div onClick={(e) => this.navigateStatic(e)} className="f13 color2 pt10">What is this?</div>
                    </div>
                    {docsData}
                </div>
                <div onClick={(e) => this.props.historyObject.pop(true)} className="bottonDiv fullwid color2 app_txtc cursp pad4 f18">
                    <span className="okClick dispibl wid150">Ok</span>
                </div>
                </div>
            </div>
        );
    }
}
const mapStateToProps = (state) => {
    return{
       verifiedData: state.verifiedVisitReducer.verifiedData,
       historyObject : state.historyReducer.historyObject
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        showVerifiedData: (profilechecksum) => {
            let call_url = "/api/v1/common/verificationData?profilechecksum="+profilechecksum;
            commonApiCall(call_url,{},'SHOW_VERIFIED_INFO','GET',dispatch,false);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(VerifiedVisit)
