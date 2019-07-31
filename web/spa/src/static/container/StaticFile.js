import React from "react";
import {Link} from "react-router-dom";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as COMMON_CONSTANTS from '../../common/constants/apiConstants';
import { getParameterByName } from "../../common/components/UrlDecoder";

import { connect } from "react-redux";

require ('../style/staticfile.css');


export class StaticFile extends React.Component{
  constructor(props){
    super(props);
    this.state = {
      prfID: '',
      personalVerif: '',
      text : 'Schedule Visit'
    }
    localStorage.removeItem('lastProfilePageLocation');
    let listingId = getParameterByName(window.location.href,"listingId");
    if(listingId) props.markListingVisit(listingId);
  }

  componentDidMount()
  {
    //console.log('component mount');
    let getPrfId,getIindex,prfSum;
    prfSum = this.props.location.search;
    getIindex = prfSum.indexOf('i');
    getPrfId = prfSum.substr(getIindex+1);
    //console.log("this.state.prfID ",this.state.prfID );
    this.setState({
      prfID:getPrfId
    })

    let _this = this;
    commonApiCall('/static/ApiJsmsVerificationStaticPage?',{},'','POST').then(function(response){
            _this.setState({
                   personalVerif: response.personalVerif
          });
    });

  }

  goBack()
  {
      this.props.history.goBack();
  }

  StaticHeaderView()
  {
    let HeadView;

    HeadView = <div className="fullwid header clearfix bg4">
                <div className="sta_p1 fl">
                  <i className="mainsp backIcon" onClick={() => this.goBack()}></i>
                </div>
                <div className="padd22 f16 posabs dispibl" style={{"left":"32%"}}>Verified Profiles</div>
               </div>;

    return HeadView;
  }
  StaticBody()
  {
    let midHTML;

    midHTML = <div className="pad5 f13 bg4">
                <div>Genuine &amp; Verified Profiles at Jeevansathi.com</div>
                <div className="color2 pt25"> Who is a Relationship Executive?</div>
                <div className="pt8" dangerouslySetInnerHTML={{ __html: "A Jeevansathi relationship executive is sent by Jeevansathi.com to meet you and verify your details. After a user registers a profile in jeevansathi.com, a relationship executive is allocated to the user. He would call the user within 48 hours and schedule a verification visit at the user's home/ office address. He would collect required documents and help you utilize our website in the best way." }}></div>
                <div className="color2 pt25">What is user verification?</div>
                <div className="pt8" dangerouslySetInnerHTML={{ __html: "User verification is a process in which a newly registered profile is checked for its credibility and genuineness. It involves a face to face interation with a relationship executive from jeevansathi.com at user's home or office address. The relationship executive verifies some key details in the profile and collects some documents listed below. These documents will not be displayed on the website and are fully secured with us."}}></div>

                <table className="f10 tableBorder">
                  <tbody>
                    <tr>
                      <td className="bgColor">Proof of Date of Birth</td>
                      <td>PAN Card/Driving License/Passport</td>
                    </tr>
                    <tr>
                      <td className="bgColor">Proof of Address</td>
                      <td>Ration Card/Passport/Voter ID/ Rent agreement</td>
                    </tr>
                    <tr>
                      <td className="bgColor">Proof of Highest Qualification</td>
                      <td>Mark sheet / Certificate for every degree</td>
                    </tr>
                    <tr>
                      <td className="bgColor">Proof of Occupation/Income</td>
                      <td>If applicable</td>
                    </tr>
                    <tr>
                      <td className="bgColor">Proof of Divorce</td>
                      <td>If applicable</td>
                    </tr>
                  </tbody>
                </table>
                <div className="color2 pt8">Benefits of Verification </div>
                <table className="f10 fullwid pt10">
                  <tbody>
                    <tr>
                      <td className="txtc">
                        <i className="mainsp rightIcon"></i>
                      </td>
                      <td className="txtc">
                        <i className="mainsp mailIcon"></i>
                      </td>
                      <td className="txtc" style={{"width": "35%"}}>
                        <i className="mainsp faceIcon"></i>
                      </td>
                    </tr>
                    <tr>
                      <td className="padr10 f10 txtc">
                        Your Profile is marked 'verified'
                      </td>
                      <td className="padl10 padr10 f10 txtc">
                        You get more &amp; better responses
                      </td>
                      <td className="padl10 f10 txtc">
                        Get to meet genuine &amp; verified profiles like you
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div className="color2 pt25">How can i get a profile verified?</div>
                <div className="pt8">Your profile can be verified by scheduling a home visit with your relationship executive when he calls. Please keep copies of required documents ready and submit to him.
</div>

              </div>;
    return midHTML;
  }
  ButtonHTML()
  {
    let btn;
    if(this.state.personalVerif)
    {
      btn = <div id="scheduleVisitDiv" className="bg7 fullwid txtc mt20 sta_lh1">
              <i className="mainsp callIcon"></i>
             <a href="tel:1800-419-6299" className="white fontlig sta_vt dispibl sta_mt5">Call for more details</a>
        </div>;
    }
    else{
      btn = <div id="scheduleVisitDiv" className="bg7 fullwid txtc mt20 sta_lh1" >
              <span className="white fontlig" onClick={()=>this.scheduleVisit(this.state.prfID)}>{this.state.text}</span>
            </div>;

    }

    return btn;
  }

  scheduleVisit(param){
      let _this = this;
      commonApiCall(COMMON_CONSTANTS.SCHEDULE_VISIT+'?profileid='+param,{},'','POST').then(function(response){
           _this.setState({
                    text: 'Visit Scheduled'
            });
      });
  }

  render(){
    return(
      <div>
        <div className="posrel" style={{"height":"45px"}}>
          {this.StaticHeaderView()}
        </div>
        <div className="bg4 pt20">
          {this.StaticBody()}
          {this.ButtonHTML()}
        </div>

      </div>
    );
  }

}

const mapStateToProps = (state) => {
    return{
       listingData: state.ListingReducer,
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        
        markListingVisit : (listingId)=> {
                dispatch({type:'MARK_LISTING_VISIT',payload:{listingId}});
            }
      }
}

export default connect(mapStateToProps,mapDispatchToProps)(StaticFile);