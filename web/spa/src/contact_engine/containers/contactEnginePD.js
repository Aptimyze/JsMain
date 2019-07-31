import {Criteo} from "../../common/components/GTManager";

require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import ThreeDots from "./ThreeDots"
import WriteMessage from "./WriteMessage"
import {performAction, cssMap} from './contactEngine';
import ContactDetails from '../components/ContactDetails';
import BlockPage from './BlockPage';
import ReportAbuse from './ReportAbuse';
import ReportInvalid from './ReportInvalid';
import GA from "../../common/components/GA";
import {removeProfileLocalStorage} from "../../common/components/CacheHelper";
import * as COMMON_CONSTANTS from "../../common/constants/CommonConstants";
import PreMessage from "./PreMessage";
import {getSearchParameters} from "../../register/helpers/dataPreprocessor";
import {getCookie} from "../../common/components/CookieHelper";
let sendInterestCriteoTracking = 0;
export class contactEnginePD extends React.Component{
  constructor(props){
    super(props);
    this.layerCount = 0;
    this.state = {
      pageSource : this.props.pageSource
        };
    this.GAObject = new GA();

  }


  /**
   * changing buttons in listing using this function.
   */
  getProfileCheckSum(profilechecksum)
  {
    let temp1 = this.props.listingData;
    for (let key in temp1) {
      if ( typeof(temp1[key]) == 'object' && temp1[key].profiles)
      {
        for( let key_profile in temp1[key].profiles )
        {
          if ( temp1[key].profiles[key_profile].profilechecksum == profilechecksum)
          {
          }
          // console.log("shahjahan profilechecksum",temp1[key].profiles[key_profile].profilechecksum);

        }
      }
    }
  }

callBack(responseButtons,button,index)
{
  console.log("callBack");
    if(this.props.profileCacheUrl){
            removeProfileLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,this.props.profileCacheUrl);

    }
          this.props.hideLoaderDiv();
    if(this.props.pageSource=='myjs')
      this.postActionMyjs(button,responseButtons,index);
    else this.postAction(button,responseButtons,index);

}

componentDidUpdate(prevProps)
{
if(prevProps.buttondata!=this.props.buttondata)
  this.props.setParentLayer(this.getOverLayDataDisplay());
}
bindAction(button,index)
{

    switch(button.action)
    {

      case 'REPORT_ABUSE':
        this.showLayerCommon({showReportAbuse:true},'showReportAbuse');
      break;
      case 'REPORT_INVALID':
        this.showLayerCommon({showReportInvalid:true,reportType:button.type},'showReportInvalid');
      break;

      case 'MEMBERSHIP':
        window.location= CONSTANTS.CONTACT_ENGINE_API[button.action];
      break;

      case 'WRITENOW':
        window.location= CONSTANTS.CONTACT_ENGINE_API[button.action];
      break;

      case 'EDITPROFILE':
        window.location= CONSTANTS.CONTACT_ENGINE_API[button.action];
      break;

      case 'PRE_MESSAGE':
        let params_n = '';
        params_n = '&pagination=1';
        var temp = performAction({profilechecksum:this.props.profiledata.profilechecksum,callBFun:(res)=>{this.callBack(res,button,index);if(typeof this.props.callBack=='function')this.props.callBack()},button:button,extraParams:"&pageSource="+this.state.pageSource+params_n});

      break;

      default:

        let params = '';
        if(button.action == 'WRITE_MESSAGE')
           params = '&pagination=1';
        // this.GAObject.trackJsEventGA("Profile Description-jsms",button.label,this.GAObject.getGenderForGA());
        if(this.props.fromPhonebook)
        {
          var temp = performAction({profilechecksum:this.props.profiledata.profilechecksum,callBFun:(res)=>{this.callBack(res,button,index);},button:button,extraParams:"fromPhonebook=1&actionName=ContactDetails"});
        }
        else
        {
          console.log('bind-a1');
          console.log(button);
          var temp = performAction({profilechecksum:this.props.profiledata.profilechecksum,callBFun:(res)=>{this.callBack(res,button,index);if(typeof this.props.callBack=='function')this.props.callBack()},button:button,extraParams:"&pageSource="+this.state.pageSource+params});

        }
        if(!temp)return;
        this.props.showLoaderDiv();
        if(this.props.pageSource!='myjs')this.props.resetMyjsData();
      break;
    }
  }

  getNewButtons(newButton,index){
    var temp=this.props.buttondata.buttons.slice(0);
    temp[index] = newButton;
    return temp;
  }
  postAction(actionButton,responseButtons,index)
  {
    console.log("postAction");
    console.log(actionButton);
    console.log(responseButtons);
    console.log("=========");
    let GAActionName = actionButton.action;

    let newButtonData=null;
    if ( responseButtons.responseStatusCode == 4)
    {
      this.props.showError(responseButtons.responseMessage)
    }
    else
    {
      switch(actionButton.action)
      {
        case 'SHORTLIST':
          var newButtons = this.getNewButtons(responseButtons.buttondetails.button,index);
          this.replaceSingleButton(newButtons,responseButtons.buttondetails.topmsg);
          if(actionButton.params.indexOf("shortlist=false")>-1){
            GAActionName = "SHORTLIST_ADD";
          }else{
            GAActionName = "SHORTLIST_REMOVE";
          }
          break;
        case 'IGNORE':
            if(actionButton.params.indexOf("ignore=0")!=-1)
            {
              this.props.historyObject.pop(true);
              this.props.historyObject.pop(true)
              GAActionName = "UNBLOCK";
            }
            else {
              this.showLayerCommon({blockLayerdata:responseButtons,showBlockLayer: true   },'showBlockLayer');
              GAActionName = "BLOCK";
            }
            newButtonData = {newButtonDetails:responseButtons.buttondetails,profilechecksum:this.props.profiledata.profilechecksum,action:actionButton.action};
            this.props.modifyButtonState(newButtonData,'REPLACE_BUTTONS');
        break;

        case 'CONTACT_DETAIL':
            this.showLayerCommon({contactDetailData:responseButtons.actiondetails,selfIsd: responseButtons.selfIsd,showContactDetail:true},'showContactDetail');
        break;

        case 'WRITE_MESSAGE':
            this.showLayerCommon({showWriteMsgLayerData:responseButtons,showMsgLayer: true,fromEOI:false},'showMsgLayer');
            GAActionName = "";
        break;

        case 'PRE_MESSAGE':
            this.showLayerCommon({showPreMsgLayerData:responseButtons,showPreMsgLayer: true,fromEOI:false},'showPreMsgLayer');
            GAActionName = "";
        break;

        case 'REPORT_INVALID':
        break;
        case 'ACCEPT':
          if(typeof this.props.hidePanel == "function"){
            this.props.hidePanel();
          }

        case 'DECLINE':
          if(typeof this.props.hidePanel == "function"){
            this.props.hidePanel();
          }

        default:
          if(responseButtons.actiondetails.errmsglabel){
            this.showLayerCommon({commonOvlayLayer:true,commonOvlayData:responseButtons.actiondetails},'commonOvlayLayer');
          }
          else
          {
                  if(responseButtons.actiondetails.writemsgbutton){
                    let onClose = actionButton.action=='INITIATE' ? this.goToViewSimilar.bind(this) : null;
                    this.showLayerCommon({showWriteMsgLayerData:responseButtons,showMsgLayer: true,fromEOI:true, onClose : onClose},'showMsgLayer');

                  }
                  if(responseButtons.buttondetails.buttons){
                    newButtonData = {newButtonDetails:responseButtons.buttondetails,profilechecksum:this.props.profiledata.profilechecksum,action:actionButton.action};
                    this.props.modifyButtonState(newButtonData,'REPLACE_BUTTONS');
                  }
                  else if(responseButtons.buttondetails.button)
                  {
                    var newButtons = this.getNewButtons(responseButtons.buttondetails.button,index);
                    this.replaceSingleButton(newButtons,responseButtons.buttondetails.topmsg);
                  }



          }
          // for decline and cancel cases
          if( responseButtons.buttondetails.confirmLabelMsg && responseButtons.buttondetails.confirmLabelHead){
            this.showLayerCommon({cancelDeclineLayer:true,commonOvlayData:responseButtons.buttondetails, currentAction:actionButton.action},'cancelDeclineLayer');

          }

        break;


      }



      if(actionButton.action=='INITIATE' && responseButtons.buttondetails.button && responseButtons.buttondetails.button.label.indexOf('Saved')!=-1){
        this.underScreened = 1;
        this.replaceSingleButton(Array(responseButtons.buttondetails.button),responseButtons.buttondetails.topmsg);
      }
      if(actionButton.action=='INITIATE' && !responseButtons.actiondetails.writemsgbutton &&  window.location.href.search("viewprofile")!=-1 && !responseButtons.actiondetails.errmsglabel)
      {
        this.goToViewSimilar();
      }
      if(actionButton.action=='ACCEPT'  &&  window.location.href.search("viewprofile")!=-1 && !responseButtons.actiondetails.errmsglabel)
      {
        this.goToViewSimilar('fromAccept=1');
      }


  }
      if(responseButtons.responseStatusCode==0 && COMMON_CONSTANTS.STOPLISTINGBURST.indexOf(actionButton.action)==-1)
      {
        this.performListingUpdatelogic(actionButton.action,newButtonData);
      }
      else if ( COMMON_CONSTANTS.STOPLISTINGBURST.indexOf(actionButton.action) != -1 )
      {
         let listingId = COMMON_CONSTANTS.STOPLISTINGBURST_SELF[actionButton.action.toString()];
         if ( listingId && this.props.listingId.toString() != listingId.toString() && this.props.listingData[listingId])
         {
            this.props.deleteApi_LISTING(listingId);
         }
      }
    if(this.props.GAData && GAActionName.length > 0){
      this.GAObject.trackJsEventGA(this.props.GAData.pageName,GAActionName,this.GAObject.getGenderForGA());
    }

    if(getCookie("AUTHCHECKSUM")){
      this.sendCriteo();
    }
}

postActionMyjs(actionButton,responseButtons,index)
  {
    // ga tracking
    this.GAObject.trackJsEventGA(this.props.pageSource+'_'+this.GAObject.getActualGAKey(this.props.listingName), actionButton.action, this.GAObject.getGenderForGA());



    let newButtonData=null;
    if ( responseButtons.responseStatusCode == 4)
    {
      this.props.showError(responseButtons.responseMessage)
    }
    else
    {
          if(responseButtons.buttondetails.buttons){
            newButtonData = {newButtonDetails:responseButtons.buttondetails,profilechecksum:this.props.profiledata.profilechecksum,action:actionButton.action};
          }
        this.performListingUpdatelogic(actionButton.action,newButtonData);
    }
}

  sendCriteo(){
    let pSum = getSearchParameters();
    if (window.location.pathname === "/search/criteoProfile"){
      pSum = pSum ? pSum.profilechecksum : '';
      sendInterestCriteoTracking += 1;
      Criteo({
          event:"CriteoJsmsSendInterest",
          profileChecksum:pSum,
          count:sendInterestCriteoTracking
      })
    }
    else {
      pSum = pSum ? pSum.profilechecksum : '';
      sendInterestCriteoTracking += 1;
      Criteo({
        event:"CriteoJsmsSendInterest_generic",
        profileChecksum:localStorage.getItem('userProfileChecksum'),
        count:sendInterestCriteoTracking
      })
    }
  }


performListingUpdatelogic(action,newButtonData){
      if ( COMMON_CONSTANTS.CONTACT_ENGINE_ACTION_LISTING_BURST[action])
      {
        for( let listingId of COMMON_CONSTANTS.CONTACT_ENGINE_ACTION_LISTING_BURST[action] )
        {
           if ( (this.props.listingId &&  (this.props.listingId.toString() != listingId.toString())  )  && this.props.listingData[listingId] )
           {
              // console.log("shahjahan deleting: ",listingId);
              this.props.deleteApi_LISTING(listingId);
           }
        }
      }

      //adding listings
      if ( this.props.listingId )
      {
        this.props.addListingBurstArray(this.props.listingId);
      }

      if(newButtonData){
            this.updateDataInRedux(newButtonData,'REPLACE_BUTTONS');
      }



    }



  render(){
    return (
      <div style={this.props.extStyleJson} className={this.props.extStyleClasses}>
        {this.props.messageWithEoiPanel ? this.props.messageWithEoiPanel : null}
        {[this.getFrontButton()]}
      </div>
  );
  }
getButtonsForMyjs(){

      if(this.props.listingName == "interest_received") {
         return (<div className="brdr8 fl fullwid hgt60">
           <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2" onClick={() => this.bindAction(this.props.buttondata.buttons[0],0)}>
             <a className="f15 color2 fontreg">Accept</a>
           </div>
           <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn" onClick={() => this.bindAction(this.props.buttondata.buttons[1],0)}>
             <a className="f15 color2 fontlig">Decline</a>
           </div>
           <div className="clr"></div>
         </div>);
      }
      else {
          return(<div className="brdr8 fullwid hgt60">
           <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => this.bindAction(this.props.buttondata.buttons[0],0)}>
             <span className="f15 color2 fontreg">Send Interest</span>
           </div>
           <div className="clr"></div>
         </div>);
      }

}
getFrontButton(){
  if(this.props.pageSource=='myjs')return this.getButtonsForMyjs();
  let primaryButton = this.props.buttondata.buttons[0];
  let threeDots = (<div></div>);
  let otherButtons = this.props.buttondata.buttons;
  if(otherButtons[0].action == 'ACCEPT' && otherButtons[1].action == 'DECLINE' && otherButtons[1].enable)
  {

  return(<div key='0' id="buttons1" >

    <div className="wid50p bg7 dispibl txtc pad5new brdr6" id="primeWid_1" onClick={() => this.bindAction(otherButtons[0],0)}>

      <div id="btnAccept" className="fontlig f13 white cursp dispbl">
        <i className="ot_sprtie ot_chk"></i>
        <div className="white">{otherButtons[0].label}</div>
      </div>
    </div>
    <div className="wid50p bg7 dispibl txtc pad5new fr" id="primeWid_2" onClick={() => this.bindAction(otherButtons[1],1)}>
      <div id="btnDecline" className="fontlig f13 whitecursp dispbl">
        <i className="ot_sprtie newitcross"></i>
        <div className="white">{otherButtons[1].label}</div>
      </div>
    </div>
  </div>
  );
}
  if(this.props.buttondata.buttons && this.props.buttondata.buttons.length>1)
  {
  threeDots =(<div onClick={()=>this.showLayerCommon({showThreeDots: true},'showThreeDots')} className="posabs srp_pos2 threeWid"><i className={"mainsp "+(!
    otherButtons[0].enable ? "srp_pinkdots" : "threedot1")}></i></div>);
}
if(primaryButton.enable==true)
{
  let iView = <i className={cssMap[primaryButton.iconid]}></i>;
  if ( this.props.pageSource == "VSP")
  {
    iView = '';
  }
    return (<div key="0" id="buttons1">
      <div className="fullwid bg7 txtc pad5new posrel" onClick={() => this.bindAction(primaryButton,0)}>
        <div className="fontlig f13 white cursp dispbl">
          {iView}
          <div className="white">{primaryButton.label}</div>
        </div>
        </div>
        {threeDots}

    </div>);
}
else
{
  if ( this.props.pageSource == "VSP")
  {
  return (<div key="0" id="buttons1" >
 <div className="fullwid srp_bg1 txtc pad5new posrel" >
   <div className="wid60p">
     <span className="fontlig f15 color7 dispbl">{primaryButton.label}</span>
   </div>
   </div>
</div>);
}

     return (<div key="0" id="buttons1" >
      <div className="fullwid srp_bg1 txtc pad18 posrel" >
        <div className="wid60p">
          <span className="fontlig f15 color7 dispbl">{primaryButton.label}</span>
        </div>
        {threeDots}
        </div>
    </div>
  );
}
}

showLayerCommon(data,key)
{
  this.layerCount++;
  this.setState({
    ...data
  },()=>this.props.setParentLayer(this.getOverLayDataDisplay()));
  this.props.historyObject.push(()=>this.hideLayerCommon({[key]:false}),"#layer");
}
hideLayerCommon(data){
  if(this.layerCount>0)
    this.layerCount--;
  this.setState({
    ...data
  },()=>this.props.setParentLayer(this.getOverLayDataDisplay()));

  return true;
//  if(!this.state.showThreeDots && !this.state.showThreeDots & !this.state.showThreeDots)
}
closeAllOpenLayers(){
  if(!this.layerCount)return;
  while(this.layerCount)this.props.historyObject.pop(true);
}




getOverLayDataDisplay()
{

    let layer = [];
      if(this.state.showThreeDots && !this.underScreened)
      {
          layer= (<ThreeDots bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} buttondata={this.props.buttondata} closeThreeDotLayer ={()=>this.props.historyObject.pop(true)} username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} />);
      }

      if(this.state.showReportAbuse)
      {
        layer= (<ReportAbuse setBlockButton={this.setBlockButton.bind(this)}
                    username={this.props.profiledata.username}
                    profilechecksum={this.props.profiledata.profilechecksum}
                    closeAbuseLayer={() => {this.props.historyObject.pop(true);this.props.historyObject.pop(true);}}
                    profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} />);
      }

      if(this.state.showContactDetail)
      {
        // console.log("in3");
          layer=  (<ContactDetails bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} actionDetails={this.state.contactDetailData} selfIsd={this.state.selfIsd} profilechecksum={this.props.profiledata.profilechecksum} closeCDLayer={() => this.props.historyObject.pop(true)} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} topmsg={this.props.buttondata.topmsg} />);
      }


      if(this.state.showReportInvalid)
      {
        // console.log("in4");
        layer= (<ReportInvalid username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} closeInvalidLayer={() => this.hideLayerCommon({showReportInvalid: false})} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} reportType={this.state.reportType} />);
      }

      if(this.state.showMsgLayer)
      {
        // console.log("in5");
        let GATrackFunction = ()=>{if(this.props.GAData)this.GAObject.trackJsEventGA(this.props.GAData.pageName,"MESSAGE",this.GAObject.getGenderForGA());};
        layer= (<WriteMessage GATrackFunction={GATrackFunction} bindAction={this.bindAction.bind(this)} fromEOI={this.state.fromEOI} username={this.props.profiledata.username} closeWriteMsgLayer={()=>{this.props.historyObject.pop(true);if(this.state.onClose)this.state.onClose();}}  buttonData={this.state.showWriteMsgLayerData} profilechecksum={this.props.profiledata.profilechecksum}/>);
      }
      if(this.state.commonOvlayLayer)
      {
        // console.log("in6");
        layer= (this.getCommonOverLay(this.state.commonOvlayData));
      }
      if(this.state.cancelDeclineLayer)
      {
        // console.log("in7");
        layer= (this.getCancelDeclineLayer(this.state.commonOvlayData));
      }

      if(this.state.showBlockLayer)
      {
        // console.log("in8");
        layer= (<BlockPage blockdata={this.state.blockLayerdata} closeBlockLayer={()=>{this.props.historyObject.pop(true);this.props.historyObject.pop(true);}} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} />);
      }

      if(this.state.showPreMsgLayer)
      {
        // console.log("in9-----");

        console.log(this.props.pageSource);
        let stvar = this.props.buttondata.buttons[this.props.buttondata.buttons.length-1].params;


        layer = <PreMessage sTypeVar={stvar} psource={this.props.pageSource} bindAction={this.bindAction.bind(this)} fromEOI={this.state.fromEOI} username={this.props.profiledata.username} closeWriteMsgLayer={()=>{this.props.historyObject.pop(true);if(this.state.onClose)this.state.onClose();}}  buttonData={this.state.showPreMsgLayerData} profilechecksum={this.props.profiledata.profilechecksum} />
      }

      return (  <div key="2">{layer}</div>)
  }

getCommonOverLay(actionDetails){
  return (<div className="posfix ce-bg ce_top1 ce_z101" style={{width:'100%',height:window.innerHeight}}>
            <a href="#"  className="ce_overlay" > </a>
              <div className="posabs ce_z103 ce_top1 fullwid" >

                <div className="white fullwid" id="commonOverlayTop">
                        <div id="3DotProPic" style={{ paddingTop:'20%'}} className="txtc">
                          <div id = "photoIDDiv" style={{border: '1px solid rgba(255,255,255,0.2)',  overflow:'hidden', width: '90px', height: '90px', borderRadius: '45px'}}><img id="ce_photo" src={this.props.profiledata.profileThumbNailUrl}  className="srp_box2 mr6"/></div>
                          <div className="fullwid pad1 txtc" id="errorMsgOverlay">
                            <div className="pt20 white f18 fontthin" id="topMsg">{actionDetails.errmsglabel}</div>
                          </div>
                        </div>
                </div>
              </div>
              <div className="posfix btmo fullwid" id="bottomElement">
                <div className="pt15">
                    <div className="brdr22 white txtc f16 pad2 fontlig " id="closeLayer" onClick={()=>this.props.historyObject.pop(true)} style={{'borderTop': '1px solid rgb(255, 255, 255)',
                      WebkitBackgroundClip: 'padding-box', /* for Safari */ 'backgroundClip': 'padding-box'}} >Close</div>
                </div>
                {actionDetails.footerbutton != null && actionDetails.footerbutton.action == 'WRITENOW' && <div onClick={()=>this.bindAction(actionDetails.footerbutton)}  className="bg7 white txtc f16 pad2 fontlig dispbl" id="footerButton">{actionDetails.footerbutton.label}</div>}
              </div>
          </div>
);
}

getCancelDeclineLayer(actionDetails){
  return (<div className="posfix ce-bg ce_top1 ce_z101" style={{width:'100%',height:window.innerHeight}}>
            <a href="#"  className="ce_overlay" > </a>
              <div className="posabs ce_z103 ce_top1 fullwid" >

                <div className="white fullwid" id="commonOverlayTop">
                        <div id="3DotProPic" style={{ paddingTop:'20%'}} className="txtc">
                          <div id = "photoIDDiv" style={{border: '1px solid rgba(255,255,255,0.2)',  overflow:'hidden', width: '90px', height: '90px', borderRadius: '45px'}}><img id="ce_photo" src={this.props.profiledata.profileThumbNailUrl}  className="srp_box2 mr6"/></div>
                          <div className="pt20 white f18 fontthin" id="topMsg">{actionDetails.errmsglabel}</div>
                        </div>
                        <div className="fullwid pad18 txtc f16 opa80 fontlig white pt10 " id="confirmationOverlay" >
                            <div className="fontthin f18 " id="confirmMessage0" >{actionDetails.confirmLabelHead}</div>
                            <div className="lh30 top20px " id="confirmMessage1" >{actionDetails.confirmLabelMsg}</div>
                        </div>
                </div>
              </div>
              <div className="posfix btmo fullwid" id="bottomElement">
                <div className="pt15">
                    <div className="brdr22 white txtc f16 pad2 fontlig " id="closeLayer" onClick={()=>{this.props.historyObject.pop(true);this.props.historyObject.pop(true);if(this.state.currentAction=='DECLINE' && typeof(this.props.nextPrevPostDecline)=='function')this.props.nextPrevPostDecline()}}
                         style={{'borderTop': '1px solid rgb(255, 255, 255)',WebkitBackgroundClip: 'padding-box', /* for Safari */ 'backgroundClip': 'padding-box'}} >Close</div>
                </div>
              </div>

          </div>
);
}
  setBlockButton(object){
    this.replaceSingleButton(Array({action:"IGNORE",label: "Unblock", params: "&ignore=0", iconid: "ignore", primary: "true", secondary: null,enable:true}));
    this.performListingUpdatelogic('REPORT_ABUSE',null);
  }

goToViewSimilar(params){
  if(this.props.pageSource!='VDP') return;
  let similarProfileCheckSumTemp = window.location.search.split('similarOf='), similarProfileCheckSum;
  if(typeof similarProfileCheckSumTemp[1]!="undefined" && similarProfileCheckSumTemp[1])
    similarProfileCheckSum = similarProfileCheckSumTemp[1].split("&")[0];
  else
    similarProfileCheckSum = "";

  if(!this.canIShowNext(similarProfileCheckSum,this.props.profiledata.profilechecksum)) return;
  this.closeAllOpenLayers();
  let _this=this;
  setTimeout(
    function(){
      // window.location.href = ;
      _this.props.redirectToSimilarListing(_this.props.profiledata.profilechecksum,params);
      // _this.props.historyObject.History.push("/search/MobSimilarProfiles?profilechecksum="+_this.props.profiledata.profilechecksum+"&fromProfilePage=1&fromSPA_CE=1")

    },1000);
}


canIShowNext(parentUsername,username)
{
  var str = localStorage.getItem("viewSim4");
  var newString='';
        if(str && parentUsername)
        {
                if(str.indexOf(',')!='-1')
                {
                        var res = str.split(",");
                        if(res[0]== parentUsername)
                        {
                                newString = res[0]+","+username;
                        }
      else
      {
        return false;
      }
                }
    else if(str!=username)
    {
                  newString = parentUsername+","+username;
    }
        }
        else{
    newString = username;
}
  if(newString)
    localStorage.setItem("viewSim4",newString);
  return true;
}

replaceSingleButton(newButtons,topMsg)
{
    let data = {newButtons:newButtons,topMsg:topMsg,profilechecksum:this.props.profiledata.profilechecksum};
    this.props.modifyButtonState(data,'REPLACE_BUTTON');
    this.updateDataInRedux(data,'REPLACE_BUTTON');
}

updateDataInRedux(data,action){
  // console.log("shahjahan data",data);
  // console.log("shahjahan action",action);
  // if ( COMMON_CONSTANTS.CONTACT_ENGINE_ACTION_LISTING_BURST[])
  // {
  //  this.props.deleteApi_LISTING();
  // }

  let indexListingMapping = this.getListingsAndIndexes(data.profilechecksum);
  indexListingMapping.map((value,index)=>{
      this.props.modifyButtonStateCommon(
      data,
      action,
      value['listingIndex'],
      value['listingId']
    );
  });
}
 /**
   * changing buttons in listing using this function.
   */
  getListingsAndIndexes(profilechecksum)
  {
    // console.log("profilechecksum In getProfileCheckSum")
    let temp1 = this.props.listingData;
    let data = [];
    // console.log("profilechecksum above",this.props);
    for (let key in temp1) {
      if ( typeof(temp1[key]) == 'object' && temp1[key] && temp1[key].profiles)
      {
        for( let key_profile in temp1[key].profiles )
        {
          if ( temp1[key].profiles[key_profile].profilechecksum == profilechecksum)
          {
            data.push({listingId:key,listingIndex:key_profile});
          }
          // console.log("shahjahan profilechecksum",temp1[key].profiles[key_profile].profilechecksum);

        }
      }
    }
    return data;
  }


}

const mapStateToProps = (state) => {
    return{
      listingData: state.ListingReducer,
      historyObject : state.historyReducer.historyObject
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        resetMyjsData: () => {
          dispatch({type:'RESET_MYJS_TIMESTAMP',payload:{value:-1}});
        },
        deleteApi_LISTING: listingId => {
          dispatch({
            type: "DELETE_LISTING_DATA",
            payload: { listingId: listingId }
          });
        },

        addListingBurstArray: listingId => {
          dispatch({
            type: "ADD_LISTING_BURST_ARRAY",
            payload: { listingId: listingId }
          });
        },

    modifyButtonStateCommon: (data, action, pIndex, listingId) => {

      dispatch({
        type: action,
        payload: { listingId: listingId, pIndex: pIndex, data: data }
      });
    },
    updateLastSeenMsg:(msg,listingId,pIndex)=>
    {

      dispatch({
        type: 'UPDATE_LAST_SEEN_MSG',
        payload: { listingId: listingId, pIndex: pIndex, msg: msg }
      });

    }
  }
}

export default connect(mapStateToProps,mapDispatchToProps,null,{ withRef: true })(contactEnginePD)
