require ('../style/verified.css')

import React from "react";
import {Link} from "react-router-dom";
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";
import {getCookie} from '../../common/components/CookieHelper';
import {connect} from "react-redux";
import StaticFile from "../../static/container/StaticFile";

class VerifiedVisit extends React.Component {

    constructor(props) {
        super();
        this.state = {
            documentsVerified: "",
            dataLoaded: false,
            profileId: ""
        };
        this.showVerifiedData(props.profilechecksum);
    }

    componentWillMount() {
        // console.log(this.props);
    }

    componentWillReceiveProps(nextProps)
    {
    }

    preventOverlay(e) {
        e.preventDefault();
    }
    // showVerfStatuc(param)
    // {
    //   console.log(this.props.profilechecksum);
    //
    //   let getPrfId,getIindex,prfSum;
    //
    //   prfSum = this.props.profilechecksum;
    //   getIindex = prfSum.indexOf('i');
    //   getPrfId  = prfSum.substr(getIindex+1);
    //
    //   this.props.history.prfIDVerf= getPrfId;
    //   this.props.history.push("/static/jsmsVerificationStaticPage");
    //
    //
    //
    //
    //
    //
    //
    //
    // }
    closeVerifiedLayer()
    {
      this.props.historyObject.pop(true);
      if(this.props.listingPage=="1")
        this.props.closeOverlay();
    }

    render() {
        var docsData,aadharData,visitData;
        let urlP = this.props.profilechecksum+"&listingId"+this.props.listingId;
        if(this.props.verification_status == 1 || this.props.verification_status == 3)
        {
            visitData = <div>
                            <div className="f15 fb color11">Profile is verified by visit
                            </div>
                            <div className="loadStaticPage">
                            <Link to={"/static/jsmsVerificationStaticPage?pChecksum="+urlP}  className="f13 color2 pt10">
                                 What is this?
                             </Link>
                            </div>
                        </div>;
        }
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
        if(this.props.verification_status == 2 || this.props.verification_status == 3)
        {
            let padd = "f13 color1 pt5";
            if(this.props.verification_status == 3)
               padd = "f13 color1 pt5 pb10";
           aadharData = <div>
                        <div className="f15 fb">Aadhaar</div>
                    <div className={padd}>Aadhaar number is verified</div>
                    </div>;
        }
        return(
            <div style={{width:window.innerWidth+'px'}} className="vOverlay js-docVerified" id="js-docVerified" onClick={(e) => this.preventOverlay(e)}>
               <div style={{position:'absolute'}} className="centerDiv">
                   <div className="textDiv fullwid app_txtc">
                   {aadharData}
                   {visitData}
                   {docsData}
                    </div>
               <div onClick={() => this.closeVerifiedLayer()} className="bottonDiv fullwid color2 app_txtc cursp pad4 f18">
                   <span className="okClick dispibl wid150">Ok</span>
               </div>
               </div>
           </div>
        );
    }

        showVerifiedData(profilechecksum){
            let call_url = "/api/v1/common/verificationData?profilechecksum="+profilechecksum,documentsVerified='';

            commonApiCall(call_url,{}).then((response)=>{
              if(response.documentsVerified) {
                  documentsVerified = response.documentsVerified;
              }
              this.setState({
                  dataLoaded: true,
                  documentsVerified,
              });
              if(response.profileId){
                  this.setState({
                    profileId: response.profileId
                  });
              }
            });
        }

}
const mapStateToProps = (state) => {
    return{
       historyObject : state.historyReducer.historyObject
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(VerifiedVisit)
