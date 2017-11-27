import React from 'react';
import {removeClass, $i, $c} from '../../common/components/commonFunctions';
import CALCommonCall from './CommonCALFunctions';
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
let API_SERVER_CONSTANTS = require ('../../common/constants/apiServerConstants');
import {getParameterByName} from '../../common/components/UrlDecoder';
import {skippableCALS} from './CommonCALFunctions';
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";

import {validateEmail, aadhaarVerificationCheck, validateNameOfUser}  from "../../common/components/commonValidations";
import Loader from "../../common/components/Loader";

require ('../style/CALJSMS_css.css');
import { connect } from "react-redux";

export  class calComp3 extends React.Component{
constructor(props){
  super(props);
  this.calData = this.props.calData;
  this.notMyJs = getParameterByName(window.location.href,'fromEdit')=='1'; // add conditions for multtiple pages here
  this.track = !this.notMyJs;
  this.state = {
    insertError : false,
    errorMessage : '',
    timeToHide  : 3000,
    showListOcc : false,
    showInputOcc : false,
    showSubmitButton: false,

    calCounter    : 10,
    Counter       : 10,// used to set calCounter when its value changed and need to come back to intiial
    layerToShow   : "mainScreen",
    cTextStyle    :   {paddingLeft: "8px" ,overflowY:'hidden'},
    myjsObj : this.notMyJs ?
    ()=>{window.location.href="/profile/viewprofile.php?ownview=1#Details";} :
    props.myjsObj // timerScreen mainScreen successScreen
  };

  this.calIds = {
    "aadhaarNumber"       : "CALaadhaarNumber",
    "userName"            : "CALuserName",
    "consentCheckbox"     : "CALconsentCheckbox",
    "errorConsentCheckbox": "CALerrorConsentCheckbox",
    "errorAadhaarNumber"  : "CALerrorAadhaarNumber",
    "errorUserName"       : "CALerrorUserName"
  };
  this.debug = false;

  this.calText = ["Your Aadhaar number will not be visible on site.", 
  "Your Name (As per Aadhaar)", 
  "Verifying Details...", 
  "Try Again", 
  "Aadhaar Verification Failed",
  "Your Aadhaar Number has been verified"];
  this.statusResponseApiFlag = 0;



  this.errorAadhaarNumberText = "Please provide a valid Aadhaar number";
  this.errorUserNameText = "Please provide a valid name";
  this.errorConsentCheckboxText = "Consent is necessary to proceed";


  this.currentWindowHeight = window.innerHeight;
  this.savedAadhaarNumber = '';
  this.savedUserName = '';
}

hideButtons(){
  let newClassName = (this.currentWindowHeight*0.70 > window.innerHeight ) ? 'dispnone' : '';
    this.setState({
      buttonClass : newClassName
    });
}

componentWillMount(){

  switch(this.calData.LAYERID)
  {
      case '24':
          let _this = this;
          this.hideButtonsFun = _this.hideButtons.bind(_this);
          window.addEventListener("resize", this.hideButtonsFun);
      break;
  }


  let index = skippableCALS.indexOf(this.props.calData.LAYERID);
  if(index!=-1)
  {

    this.props.historyObject.push(()    =>
          this.criticalLayerButtonsAction(this.props.calData.BUTTON2_URL_ANDROID,this.props.calData.JSMS_ACTION2,'B2')
      ,'#cal');
  }
}
componentDidMount(){
  switch(this.props.calData.LAYERID)
  {

  }

  let consentTextHeight = $i("consentText").offsetHeight;
  let scrollableDivHeight = $i("scrollableDiv").offsetHeight;
  let innerDivHeight = $i("innerDiv").offsetHeight;

  if(innerDivHeight - scrollableDivHeight > 1){
    this.setState({cTextStyle:{ height: consentTextHeight - (innerDivHeight - scrollableDivHeight) , overflowY: "hidden"}});
    // $i("consentText").style.maxHeight = consentTextHeight - (innerDivHeight - scrollableDivHeight) - 20;
  }
  else 
    $i('readMoreId').style.display = 'none';

  this.setState({
    
    errorConsentCheckboxStyle : {visibility:'hidden'},
    errorAadhaarNumberStyle   : {visibility:'hidden'},
    errorUserNameStyle        : {visibility:'hidden'},

    tryAgainButtonStyle       : {visibility:'hidden'},
    skipButtonStyle           : {visibility:'hidden'}
  });
}

componentWillUnmount(){
  switch(this.props.calData.LAYERID)
  {
      case '24':
          window.removeEventListener("resize", this.hideButtonsFun);
      break;          
  }



}

showError(inputString) {
    let _this = this;
    this.setState ({
            insertError : true,
            errorMessage : inputString,
            layerToShow : "mainScreen"
    })
    setTimeout(function(){
        _this.setState ({
            insertError : false,
            errorMessage : ""
        })
    }, this.state.timeToHide+200);
}

render()
{
var toReturn;
switch(this.calData.LAYERID)
{
    case '24':
      switch(this.state.layerToShow){
        case "timerScreen":
          toReturn = this.setAadhaarTimerScreen();
        break;
        case "successScreen":
          toReturn = this.setAadhaarFinalScreen();
          break;
        default:
          toReturn = this.setAadhaarCalData();
      }
    break;
}

return (<div>{toReturn}</div>);

}

getApiUrl(UserName, Aadhaar){
  return "/api/v1/profile/aadharVerification?name="+UserName+"&aid="+Aadhaar;
  // return "/api/v1/api/socialsignin"+"?name="+UserName+"&aid="+Aadhaar;
}
getStatusApiUrl(UserName){
   return "/api/v1/profile/aadharVerificationStatus?name="+UserName;
//  return "/api/v1/api/socialsignin"+"?type=1&name="+UserName;
  
}
startTimer(UserName){
        // updateCount(COUNT,COUNTER, UserName);
        let _this = this;
  if(_this.state.calCounter % 2 == 0){
    _this.hitAadhaarStatusApi(_this, UserName);
  }
  let COUNTER = setInterval(function(){
    if ( typeof _this.state.calCounter == "number")
    {
      _this.setState({calCounter : _this.state.calCounter-1});
    }
    if(_this.statusResponseApiFlag == 1){
      clearInterval(COUNTER);
      // _this.setState({skipButtonStyle : {visibility:'visible'}, tryAgainButtonStyle : {visibility:'visible'}});

    }else if(_this.state.calCounter % 2 == 0){
        _this.hitAadhaarStatusApi(_this, UserName);
    }
    if(_this.state.calCounter == 0){
      clearInterval(COUNTER);
      _this.setState({skipButtonStyle : {visibility:'visible'}, tryAgainButtonStyle : {visibility:'visible'}});
    }
  }, 1000);
}
hitAadhaarStatusApi(v, UserName){

  commonApiCall(this.getStatusApiUrl(UserName), {}, '', 'POST').then((response) => {
    switch(response.VERIFIED){
      case 'N':
        this.setState({calCounter : response.MESSAGE});
        v.statusResponseApiFlag = 1;
        v.setState({skipButtonStyle : {visibility:'visible'}, tryAgainButtonStyle : {visibility:'visible'}});
      break;
      case 'Y':
        if(v.statusResponseApiFlag != 1)
        {
          this.setState({layerToShow : "successScreen"});
          this.criticalLayerButtonsAction(this.props.calData.BUTTON1_URL_ANDROID,this.props.calData.JSMS_ACTION2,'B1');
          v.statusResponseApiFlag = 1;
        }
      break;
    }
  });
}
hitAadhaarApi(UserName, Aadhaar){
  this.setState({"layerToShow" : "loadingScreen"});
commonApiCall(this.getApiUrl(UserName, Aadhaar), {}, '', 'POST').then((response) => {

    if(response.responseStatusCode==1){
      this.showError(response.ERROR);
    //   this.CALButtonClicked=0;
    //   return false;
    }else if(response.responseStatusCode == 0){
      this.setState({layerToShow : "timerScreen"});
      this.statusResponseApiFlag = 0;
      this.startTimer(UserName);
    }
    // CALCommonCall(this.props.calData.BUTTON1_URL_ANDROID,this.props.calData.JSMS_ACTION1).then(()=>{this.CALButtonClicked=0;});
    // let msg = "A link has been sent to your email Id "+altEmailUser+', click on the link to verify your email';
    // this.setState({emailVeriConfirmation:true,altEmailMessage:msg});
    // return true;
  });
}
validateAadhaarNumber(v){
  var len = v.length;
  v = ""+parseInt(v);
  if(len == 12 && len == v.length && aadhaarVerificationCheck(v)() && v.length == 12){
    return true;
  }return false;
}
validateUserName(v){
  var r = validateNameOfUser(v);
  if(r.responseCode == 1){
    this.errorUserNameText = r.responseMessage;
  }else if(r){
    return true;
  }
  return false;
}
checkConsentCheckbox(v){
  return v;
}

ErrorConsentCheckboxShowHide(hide=0){
  if(hide){
    return {errorConsentCheckboxStyle:{visibility:'hidden'}};
  }
    return {errorConsentCheckboxStyle:{visibility:'visible'}}; 
}
ErrorUserNameShowHide(hide=0){
  if(hide){
    return {errorUserNameStyle:{visibility:'hidden'}};
  }
    return {errorUserNameStyle:{visibility:'visible'}}; 
}
ErrorAadhaarNumberShowHide(hide=0){
  if(hide){
    // this.setState({/*errorAadhaarNumberText:this.errorAadhaarNumberText,*/ errorAadhaarNumberStyle:{visibility:'hidden'}}) ; return;
    return {/*errorAadhaarNumberText:this.errorAadhaarNumberText,*/ errorAadhaarNumberStyle:{visibility:'hidden'}};
  }
    // this.setState({errorAadhaarNumberStyle:{visibility:'visible'}}) ; 
    return {errorAadhaarNumberStyle:{visibility:'visible'}}; 
    
}

verifyButtonClickHandler(){
  
  this.setState({
  ...this.ErrorConsentCheckboxShowHide(1),
  ...this.ErrorAadhaarNumberShowHide(1),
  ...this.ErrorUserNameShowHide(1)
});


// this.ErrorConsentCheckboxShowHide();
//   this.ErrorAadhaarNumberShowHide();
//   this.ErrorUserNameShowHide();
// return;


  var aadhaarNumber = $i(this.calIds['aadhaarNumber']).value;
  this.savedAadhaarNumber = aadhaarNumber;
  var userName = $i(this.calIds['userName']).value;
  this.savedUserName = userName;
  var consentCheckbox = $i(this.calIds['consentCheckbox']).checked;

  if(this.validateAadhaarNumber(aadhaarNumber)){
    if(this.validateUserName(userName)){
      if(this.checkConsentCheckbox(consentCheckbox)){
        this.hitAadhaarApi(userName, aadhaarNumber);
      }else{
        this.setState({...this.ErrorConsentCheckboxShowHide()});
      }
    }else{
      this.setState({...this.ErrorUserNameShowHide()});
    }
  }else{
    this.setState({...this.ErrorAadhaarNumberShowHide()});
  }
}

criticalLayerButtonsAction(url,clickAction,button) {
     if(this.CALButtonClicked===1)
       return;
    // this.CALButtonClicked=1;
      let _this = this;
     switch(this.props.calData.LAYERID)
     {

        case '24':
          // this.setState({layerToShow : "timerScreen"});
          // this.startTimer();

          if(button === 'B1')
          {
                 if(this.track)
                    this.state.myjsObj();
                else
                 CALCommonCall(url,clickAction).then(()=>{this.CALButtonClicked=0;});
                 return true;
          }// end case
        break;
      }
    if(this.track)
      this.state.myjsObj();
    else
      CALCommonCall(url,clickAction,this.state.myjsObj).then(()=>{this.CALButtonClicked=0;});
     return true;

}

// AADHAAR CAL START
/*
 Open triggers:
 inputAadhaar : onkeyup : checkInputAadhaar, showAadharInvalidError,
 inputName :

 submitButton : onclick : checkInputAadhaarFlag, checkInputName, checkCheckboxConsent,
*/

setAadhaarCalData(){
  let maxHeightScrollabeDiv = (this.currentWindowHeight-105);
  return(
        <div>
        {this.state.insertError == true ? <TopError timeToHide={this.state.errorMessage} message={this.state.errorMessage}></TopError> : null}
        {this.state.layerToShow == "loadingScreen" ? <div><Loader show="page"></Loader></div> : null}
          <div style={{backgroundColor: '#09090b',height: this.currentWindowHeight}}>

            <div id="scrollableDiv" style={{maxHeight: maxHeightScrollabeDiv+'px',overflowY:'scroll'}}>
            <div id="innerDiv">
              <div  className="posrel pad18Incomplete">
                <div className="br50p txtc" style={{'height':'8px'}}>
                </div>
              </div>

              <div className="txtc">
              <div className="fontlig white f18 pb10 color16">{this.props.calData.TITLE}</div>
              <div className="pad1 fontlig f14" style={{color:'#cccccc'}}>{this.props.calData.TEXT}</div>

              <div  className="posrel pt20">
              </div>

              <input  maxLength="12" pattern="([0-9]|[0-9]|[0-9]|[0-9]|[0-9]|[0-9]|[0-9]|[0-9]|[0-9]|[0-9]|[0-9]|[0-9])" tabindex="1" type="tel" id={this.calIds['aadhaarNumber']} style={{width: "80%", fontSize: "1.7em", color:'#cccccc', borderBottom: '1px solid',textAlign:'center'}}  placeholder="Aadhaar No." defaultValue={this.savedAadhaarNumber}/>
              <div className="errorMessage f13 color2" style={{...this.state.errorAadhaarNumberStyle}} id={this.calIds['errorAadhaarNumber']}>
                  {this.errorAadhaarNumberText}
              </div>
              <div className="pad1 lh25 fontlig f14" style={{color:'#cccccc'}}>{this.calText[0]}</div>
              <div  className="posrel pt20"></div>
              <div className="pad1 lh25 fontlig f14" style={{color:'#cccccc'}}>{this.calText[1]}</div>
              <input tabindex="2" id={this.calIds['userName']} style={{color:'#cccccc', borderBottom: '1px solid',textAlign:'center'}} type="text" defaultValue={this.savedUserName == '' ? this.calData.NAME_OF_USER : this.savedUserName}/>
              <img onClick={() => {$i(this.calIds['userName']).focus();}} src="/images/jspc/myjsImg/pencil.png" className="pos-abs" style={{cursor: "pointer",right:"9px",top:"5px"}} />
              <div className="errorMessage f13 color2" style={{...this.state.errorUserNameStyle}} id={this.calIds['errorUserName']}>
                  {this.errorUserNameText}
              </div>
              </div>


              <div className="txtc color16" style={{fontWeight : "bolder"}}>Consent</div>
              <div className="errorMessage f13 padl10 color2" style={{...this.state.errorConsentCheckboxStyle}} id={this.calIds['errorConsentCheckbox']}>{this.errorConsentCheckboxText}</div>
              <div className="txtc fontlig white f14 padl10" style={{color:'#cccccc'}}>
              <input className="fl" style={{height: "17px", width : "17px"}} id={this.calIds['consentCheckbox']} type="checkbox" defaultChecked="checked" />
              <div id="consentText" className="txtl f13" style={this.state.cTextStyle}>{this.props.calData.LEGAL_TEXT}</div>
              <div id="readMoreId" style={{ fontWeight : "bolder", padding: "6px"}} onClick={(e)=>
                {
                this.setState({cTextStyle : {overflowY:'auto',height:'initial'}});
                e.target.style.display = 'none';  
              }
            }>read more</div>
              </div>
            </div>
            </div>

          </div>
            <div  className={this.state.buttonClass} style={{bottom : "0px", position: "fixed", "width": "100%"}}>
              <div id='CALButtonB2' onClick={() => this.props.historyObject.pop(true)}  style={{color:'#cccccc'}} className="pb20 txtc white f14 pt20">{this.props.calData.BUTTON2}</div>
              <div >
                <div id='CALButtonB1' className="bg7 f18 white lh30 fullwid dispbl txtc lh50"  onClick={() => this.verifyButtonClickHandler()}>{this.props.calData.BUTTON1}</div>
              </div>
            </div>
        </div>);
}

setAadhaarTimerScreen(){
  
  return(
      <div>
      {this.state.insertError == true ? <TopError timeToHide={this.state.errorMessage} message={this.state.errorMessage}></TopError> : null}
          <div style={{backgroundColor: '#09090b',height:this.currentWindowHeight}}>
            <div  className="posrel pad18Incomplete">
              <div className="br50p txtc" style={{'height':'100px'}}>
              </div>
            </div>

            <div className="txtc">

            <div className="fontlig white f18 pb10 color16" style={{fontWeight: 'bolder'}}>{typeof this.state.calCounter == "string" ? this.calText[4] : null}</div>
            <div className="fontlig white f18 pb10 color16">{typeof this.state.calCounter == "string" ? this.state.calCounter : null}</div>

            <div className="fontlig white f40 pb10 color16">{typeof this.state.calCounter == "number" ? this.state.calCounter : null}</div>
            <div className="fontlig white f18 pb10 color16">{typeof this.state.calCounter == "number" ? this.calText[2] : null}</div>
            </div>

            <div style={{bottom : "0", position: "fixed", "width": "100%"}}>
              <div id='CALButtonTryAgain' style={{...this.state.tryAgainButtonStyle}}  onClick={() => this.setState({layerToShow : "mainScreen", calCounter : 10, tryAgainButtonStyle : {visibility:'hidden'}, skipButtonStyle : {visibility:'hidden'}})} className="pdt15 pb10 txtc white f14">Try Again</div>
              <div id='CALButtonB2' onClick={() => this.props.historyObject.pop(true)}  style={{...this.state.skipButtonStyle, color:'#cccccc'}}  className="bg7 f18 white lh30 fullwid dispbl txtc lh50">OK</div>
            </div>
            

          </div>
        </div>
    ); 
};




setAadhaarFinalScreen(){
  
  return(
      <div>
      {this.state.insertError == true ? <TopError timeToHide={this.state.errorMessage} message={this.state.errorMessage}></TopError> : null}
          <div style={{backgroundColor: '#09090b',height:window.innerHeight}}>
            <div  className="posrel pad18Incomplete">
              <div className="br50p txtc" style={{'height':'100px'}}>
              </div>
            </div>

            <div className="txtc">
            <div className="fontlig white f18 pb10 color16">{this.calText[5]}</div>
            </div>

            <div style={{bottom : "0", position: "fixed", "width": "100%"}}>
                <div id='CALButtonTryAgain' onClick={this.state.myjsObj} className="bg7 f18 white lh30 fullwid dispbl txtc lh50" >OK</div>
            </div>


          </div>
        </div>
    );
};


// AADHAAR CAL ENDS
}
const mapStateToProps = (state) => {
    return{
      historyObject : state.historyReducer.historyObject

    }
}
const mapDispatchToProps = (state) => {
    return{

    }
}
export default connect(mapStateToProps,mapDispatchToProps)(calComp3)
