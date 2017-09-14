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


export class contactEnginePD extends React.Component{
  constructor(props){
    super(props);
    this.layerCount = 0;
    this.state = {
      showMessageOverlay:false,
      pageSource : this.props.pageSource
        };
  }

  componentDidMount(){
  }

  componentWillReceiveProps(nextProps){
  }
  closeMessageLayer() {
    this.setState({showMessageOverlay: false})
  }

  bindAction(button,index){

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

      case 'EDITPROFILE':
        window.location= CONSTANTS.CONTACT_ENGINE_API[button.action];
      break;

      default:
          let callBack = (responseButtons)=>{
          this.props.hideLoaderDiv();
          this.postAction(button,responseButtons,index);
        }
        let params = '';
        if(button.action == 'WRITE_MESSAGE')
           params = '&pagination=1';
        var temp = performAction({profilechecksum:this.props.profiledata.profilechecksum,callBFun:callBack.bind(this),button:button,extraParams:"&pageSource="+this.state.pageSource+params});
        if(!temp)return;
        this.props.showLoaderDiv();
        this.props.resetMyjsData();
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
          this.props.replaceSingleButton(newButtons);
          break;
        case 'IGNORE':
            if(actionButton.params.indexOf("ignore=0")!=-1)
            {
              this.props.historyObject.pop();
              this.props.historyObject.pop()
            }
            else {
              this.showLayerCommon({blockLayerdata:responseButtons,showBlockLayer: true   },'showBlockLayer');
            }
            this.props.replaceOldButtons(responseButtons);

            //var newButtons = this.getNewButtons(responseButtons.buttondetails.button,index);
            //this.props.replaceSingleButton(newButtons);
        break;

        case 'CONTACT_DETAIL':
            this.showLayerCommon({contactDetailData:responseButtons.actiondetails,showContactDetail:true},'showContactDetail');
        break;

        case 'WRITE_MESSAGE':
            this.showLayerCommon({showWriteMsgLayerData:responseButtons,showMsgLayer: true,fromEOI:false},'showMsgLayer');
        break;

        case 'REPORT_INVALID':
        break;

        default:
          if(responseButtons.actiondetails.errmsglabel){
            this.showLayerCommon({commonOvlayLayer:true,commonOvlayData:responseButtons.actiondetails},'commonOvlayLayer');
          }
          else
          {
          if(responseButtons.actiondetails.writemsgbutton){
            this.showLayerCommon({showWriteMsgLayerData:responseButtons,showMsgLayer: true,fromEOI:true},'showMsgLayer');

          }
          if(responseButtons.buttondetails.buttons){
            this.props.replaceOldButtons(responseButtons);}
          else if(responseButtons.buttondetails.button)
          {
            var newButtons = this.getNewButtons(responseButtons.buttondetails.button,index);
            this.props.replaceSingleButton(newButtons);
          }
          }
          // for decline and cancel cases
          if(responseButtons.buttondetails.confirmLabelMsg && responseButtons.buttondetails.confirmLabelHead){
            this.showLayerCommon({cancelDeclineLayer:true,commonOvlayData:responseButtons.buttondetails},'cancelDeclineLayer');

          }

        break;
      }
    }
  }
  render(){

    return (
    <div>{[this.getFrontButton(),
        this.getOverLayDataDisplay()]
  }</div>
  );
  }

getFrontButton(){

  let primaryButton = this.props.buttondata.buttons[0];
  let threeDots = (<div></div>);
  let otherButtons = this.props.buttondata.buttons;
  if(otherButtons[0].action == 'ACCEPT' && otherButtons[1].action == 'DECLINE' && otherButtons[1].enable)
  {

  return(<div key='1' id="buttons1" className="view_ce fullwid">

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
  threeDots =(<div onClick={()=>this.showLayerCommon({showThreeDots: true},'showThreeDots')} className="posabs srp_pos2"><a href="javascript:void(0)"><i className={"mainsp "+(!
    otherButtons[0].enable ? "srp_pinkdots" : "threedot1")}></i></a></div>);
}
if(primaryButton.enable==true)
{

    return (<div id="buttons1" className="view_ce fullwid">
      <div className="fullwid bg7 txtc pad5new posrel" onClick={() => this.bindAction(primaryButton,0)}>
        <div className="wid60p">
          <i className={cssMap[primaryButton.iconid]}></i>
          <div className="white">{primaryButton.label}</div>
        </div>
        </div>
        {threeDots}

    </div>)
}
else
{

     return (<div id="buttons1" className="view_ce fullwid">
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

showLayerCommon(data,key){
  this.layerCount++;
  this.props.unsetScroll();
  this.setState({
    ...data
  });
  this.props.historyObject.push(()=>this.hideLayerCommon({[key]:false}),"#layer");
}
hideLayerCommon(data){
  if(this.layerCount>0)
    this.layerCount--;
  if(!this.layerCount)this.props.setScroll();
  this.setState({
    ...data
  });
  return true;
//  if(!this.state.showThreeDots && !this.state.showThreeDots & !this.state.showThreeDots)
}





getOverLayDataDisplay(){

    let layer = [];
      if(this.state.showThreeDots)
        layer= (<ThreeDots bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} buttondata={this.props.buttondata} closeThreeDotLayer ={()=>this.props.historyObject.pop()} username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} />);
      if(this.state.showReportAbuse)
        layer= (<ReportAbuse
                    username={this.props.profiledata.username}
                    profilechecksum={this.props.profiledata.profilechecksum}
                    closeAbuseLayer={() => this.props.historyObject.pop()}
                    profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} />);

      if(this.state.showContactDetail)
        layer=  (<ContactDetails bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} actionDetails={this.state.contactDetailData} profilechecksum={this.props.profiledata.profilechecksum} closeCDLayer={() => this.props.historyObject.pop()} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} />);

      if(this.state.showReportInvalid)
      {
        layer= (<ReportInvalid username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} closeInvalidLayer={() => this.hideLayerCommon({showReportInvalid: false})} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} reportType={this.state.reportType} />);
      }

      if(this.state.showMsgLayer)
      {
        layer= (<WriteMessage bindAction={this.bindAction.bind(this)} fromEOI={this.state.fromEOI} username={this.props.profiledata.username} closeWriteMsgLayer={()=>this.props.historyObject.pop()}  buttonData={this.state.showWriteMsgLayerData} profilechecksum={this.props.profiledata.profilechecksum}/>);
      }
      if(this.state.commonOvlayLayer)
      {
        layer= (this.getCommonOverLay(this.state.commonOvlayData));
      }
      if(this.state.cancelDeclineLayer)
      {
        layer= (this.getCancelDeclineLayer(this.state.commonOvlayData));
      }

      if(this.state.showBlockLayer)
      {
        layer= (<BlockPage blockdata={this.state.blockLayerdata} closeBlockLayer={()=>{this.props.historyObject.pop();this.props.historyObject.pop();}} profileThumbNailUrl={this.props.profiledata.profileThumbNailUrl} bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} />);
      }
      return (  <div key="2">{layer}</div>)
  }

getCommonOverLay(actionDetails){
  return (<div className="posabs ce-bg ce_top1 ce_z101" style={{width:'100%',height:window.innerHeight}}>
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
                    <div className="brdr22 white txtc f16 pad2 fontlig " id="closeLayer" onClick={()=>this.props.historyObject.pop()} style={{borderTop: '1px solid rgb(255, 255, 255)',borderTop: '1px solid rgba(255, 255, 255, .2)',WebkitBackgroundClip: 'padding-box', /* for Safari */ 'backgroundClip': 'padding-box'}} >Close</div>
                </div>
              </div>

          </div>
);
}
getCancelDeclineLayer(actionDetails){
  return (<div className="posabs ce-bg ce_top1 ce_z101" style={{width:'100%',height:window.innerHeight}}>
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
                    <div className="brdr22 white txtc f16 pad2 fontlig " id="closeLayer" onClick={()=>this.props.historyObject.pop()} style={{borderTop: '1px solid rgb(255, 255, 255)',borderTop: '1px solid rgba(255, 255, 255, .2)',WebkitBackgroundClip: 'padding-box', /* for Safari */ 'backgroundClip': 'padding-box'}} >Close</div>
                </div>
              </div>

          </div>
);
}
  setFrontButtonDisplay(object){
    this.setState({frontButton:object});
  }

}

const mapStateToProps = (state) => {
    return{
      historyObject : state.historyReducer.historyObject
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        resetMyjsData: () => {
          dispatch({type:'RESET_MYJS_TIMESTAMP',payload:{value:-1}});
        },
        replaceOldButtons: (newButtons) => {
          dispatch({
            type: 'REPLACE_BUTTONS',
            payload: {newButtonDetails:newButtons.buttondetails}
          });
        },
        replaceSingleButton: (newButtons) => {
          dispatch({
            type: 'REPLACE_BUTTON',
            payload: {newButtons:newButtons}
          });
        }

    }
}

export default connect(mapStateToProps,mapDispatchToProps)(contactEnginePD)
