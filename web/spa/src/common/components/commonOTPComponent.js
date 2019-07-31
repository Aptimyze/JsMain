// CONSTANTS.RESET_PASSWORD_API

// require ('../style/forgot.css')
import React from "react";
import TopError from "../../common/components/TopError";
import {validateInput, validateEmail, validatePasswords} from "../../common/components/commonValidations"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import * as CONSTANTS from '../../common/constants/apiConstants';
import MetaTagComponents from '../../common/components/MetaTagComponents';
import {getCookie,setCookie} from '../../common/components/CookieHelper';
import GA from "../../common/components/GA";
import {Link} from "react-router-dom";

export class CommonOTPComponent extends React.Component {

  constructor(props) {
    super();
    this.GAObject = new GA();
    this.state = {
      insertError: false,
      errorMessage: "",
      timeToHide: 4000,
      showLoader: true,
      LayerHeading: "VERIFY",

      layerToShow : "basicLayer",
      inputOption : "email",

      showResendOtpText : true,
      
      InputOtp: ""
    };
    this.gaData = {
      forgot_password_first_layer : ["Provide Email/Phone", "Get OTP on email/Get OTP on phone", "From Reset password/From Forgot password"],
      otp_verify_layer : ["Verification screen", "", "Success/Failure"],
      reset_password_layer : ["Reset Password", "Set password", "From Reset password/From Forgot password"]
    };
    this.validInputText = "";
    this.forgotPasswordCode = "";

    this.UrlSend = '/api/v1/common/commonOTP';
    this.UrlMatch = "/api/v1/common/matchOTP";
    this.ErrText = "You have exceeded maximum number of OTP attempts. Please try tomorrow.";
    this.InnerHeight = window.innerHeight;
  }

  componentDidMount() {
    
  }

  componentDidUpdate(){
    
    
  }

  componentWillReceiveProps(nextProps) {
  
  }
  componentWillMount(){
    // send otp first hit
    
    // layerHeading
    // SMSSent
    // trialsOver
    // sentToTex
    // SMSLimitOvert
    // OTPVerifyToken
    // matched = 'true' 'false'

    this.resendAction();
  }

  resendAction(){
    commonApiCall(this.UrlSend, {'phoneType': "M", 'OTPType' : this.props.OTPType}, ''/*reducer*/, 'POST'/*, this.setState({showLoader : false})*/).then((response) => {
        if(response.responseStatusCode == 0){
          if(response.trialsOver == "Y"){
            this.state.layerToShow = "failure";
          }else{
            this.state.layerToShow = "basicLayer";
          }

          if(response.SMSLimitOver == "Y"){
            this.state.showResendOtpText = false;
          }else{
            this.state.showResendOtpText = true;
            this.showError("OTP sent to registered Email & Phone.");
          }
          

        }else{
          this.showError(response.responseMessage);
          this.props.closeCDLayer();
        }

      let layerHeading = response.layerHeading;
      if(typeof layerHeading != "undefined" && layerHeading.length > 0){
        this.state.LayerHeading = layerHeading;
      }
      this.setState({showLoader: false});  
    });
  }

  
  showError(inputString) {
    let _this = this;
    this.setState ({
      insertError : true,
      errorMessage : inputString
    })
    setTimeout(function(){
      _this.setState ({
        insertError : false,
        errorMessage : ""
      })
    }, this.state.timeToHide+100);
  }


 
    // commonApiCall(CONSTANTS.FORGOT_PASSWORD_API + "?"+ this.forgotPasswordCode, {"password" : password1}, 'SEND_FORGOT_LINK', 'POST'/*, this.setState({showLoader : false})*/).then((response) => {
      
    //   if(response.responseStatusCode == 0){
    //     window.location.replace('/');
    //   }else{
    //     this.setState({
    //       layerToShow : "basicLayer",
    //       inputOption : "email",
    //       showLoader : false
    //     });
    //     this.showError(response.responseMessage);
    //   }

    // });
  validateOtp(InputOtp){
    let res = {isvalid : false, message : "Invalid OTP."};
    if(/^[0-9][0-9][0-9][0-9]$/.test(InputOtp)){
      res.isvalid = true;
    }else{
      res.isvalid = false;
      res.message = "Invalid OTP.";
    }
    return res;
  }
  submitOtp(){
    let validationObj = this.validateOtp(this.state.InputOtp);
    if(validationObj.isvalid == false){
      this.showError(validationObj.message);
      this.setState({showLoader: false});
      return;
    }

    let PayloadData = {
      'enteredOtp': this.state.InputOtp,
      'phoneType':"M",
      "OTPType": this.props.OTPType};
    commonApiCall(this.UrlMatch, PayloadData, ''/*reducer*/, 'POST'/*, this.setState({showLoader : false})*/).then((response) => {
        if(response.responseStatusCode == 0){
          
          if(response.trialsOver == "Y"){
            this.state.layerToShow = "failure";
          }else{
            this.state.layerToShow = "basicLayer";
          }
          if(response.matched == "true"){
            this.showError(" otp MATCHED");
            this.props.afterMatch(response.OTPVerifyToken);
          }else{
            this.showError("Wrong OTP provided.");
          }

        }else{
          this.showError(response.responseMessage);
          this.props.closeCDLayer();
        }

          
      this.setState({showLoader: false});  
    });

    // console.log("otp submit");
    // this.setState({
    //   showLoader: false
    // });
  }
  // resendAction(){
  //   console.log("resend api hit");
  //   this.setState({
  //     showLoader: false
  //   });
  // }
  
  render() {
    var errorView;
    

    var loaderView;
    
    let errmsglabel = "errmsglabel";
    let confirmLabelHead = "this label";
    let confirmLabelMsg = "msg label";

    let viewFail = 
    <div className="pad3 color7">
      <div className=" txtc f20 fontlig pt30 pb15">
        <p>{this.ErrText}</p>
        <button className="fpmt30 fullwid bg7 lh50 col white fontlig f17 border0" onClick={() => {this.props.closeCDLayer();}}>OK</button>
      </div>
    </div>;
   
    let viewLayer = 
    <div className="pad3 color7">
      <div className=" txtc f14 fontlig pt30 pb15">
        <p>OTP sent to registered Email and Phone no.</p>
        {this.state.showResendOtpText == true ? <div><p className="pt5">Didn't receive OTP? </p><p className="pt5"><div onClick={() => { this.setState({showLoader : true}); this.resendAction();}} className="color2">Resend OTP</div></p></div> : null}
      </div>

      <div className="pt30">
        <input style={{textAlign : "center"}} id="inpOtp" value={this.state.InputOtp} type="tel" max="9999" maxLength="4" className="f18 fontlig fpBdr1 fullwid pb10" placeholder="Enter OTP" required
          onChange={(event)=>{this.setState({InputOtp: event.target.value.trim()}); }}
        />
      </div>
      <button className="fpmt30 fullwid bg7 lh50 col white fontlig f17 border0" onClick={() => {this.setState({showLoader : true});this.submitOtp();}}>SUBMIT</button>
    </div>;

    let Overlay = <div className="posabs ce-bg ce_top1 ce_z101" style={{background: "white", width:'100%',height: this.InnerHeight}}>
            <div key="1" className="fullwid rel_c1 bg4 outerdiv"> 
                {this.state.showLoader ? <Loader show="page"></Loader> : null}
                {this.state.insertError ? <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError> : null}

              <div className="fullwid bg1">
                <div className="">
                  <div className="rem_pad1 posrel">
                    <i onClick={() => {this.props.closeCDLayer()}} className="mainsp arow2 posabs"></i>
                    <div className="txtc white  f16">{this.state.LayerHeading}</div>
                  </div>
                </div>
              </div>

              {this.state.layerToShow == "failure" ? <div>{viewFail}</div> : <div>{viewLayer}</div>}          

            </div>
          </div>;
    return <div><Loader/>
    {Overlay}
    </div>;
  }
}
