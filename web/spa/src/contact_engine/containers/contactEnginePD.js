require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import ThreeDots from "./ThreeDots"
import WriteMessage from "./WriteMessage"
import {performAction} from './contactEngine';
import ContactDetails from '../components/ContactDetails';
import BlockPage from './BlockPage';
import ReportAbuse from './ReportAbuse';
import ReportInvalid from './ReportInvalid';


export class contactEnginePD extends React.Component{
  constructor(props){
    super(props);
    this.state = {
      showMessageOverlay:false,
      layerCount:0,
      pageSource : this.props.pageSource
        };
    this.actionUrl = {
      "INITIATE":"/api/v2/contacts/postEOI",
      "ACCEPT": "/api/v2/contacts/postAccept",
      "DECLINE":"/api/v2/contacts/postNotInterested",
      "REMINDER":"/api/v2/contacts/postSendReminder",
      "WRITE_MESSAGE":"/api/v2/contacts/WriteMessage",
      "CANCEL":"/api/v2/contacts/postCancelInterest",
      "SHORTLIST":"/api/v1/common/AddBookmark",
      "MESSAGE":"/api/v2/contacts/postWriteMessage",
      "CONTACT_DETAIL":"/api/v2/contacts/contactDetails"
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
      console.log('ra case');
        this.showLayerCommon({showReportAbuse:true});

      break;
      case 'REPORT_INVALID':
        console.log("bindAction REPORT_INVALID");
        console.log("bindAction REPORT_INVALID button",button);
        this.showLayerCommon({showReportInvalid:true,reportType:button.type});
      break;
      
      default:
      console.log(button);
      console.log(index);
          let callBack = (responseButtons)=>{
          this.props.hideLoaderDiv();
          this.postAction(button,responseButtons,index);
        }
        this.props.showLoaderDiv();
        performAction({profilechecksum:this.props.profiledata.profilechecksum,callBFun:callBack.bind(this),button:button,extraParams:"&pageSource="+this.state.pageSource});
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
    console.log('post action');
    if ( responseButtons.responseStatusCode == 4)
    {
      this.props.showError(responseButtons.responseMessage)
    }
    else
    {
      switch(actionButton.action)
      {
        case 'SHORTLIST':
          var newButtons = this.getNewButtons(responseButtons.buttondetails.button,index);console.log(newButtons);
          this.props.replaceSingleButton(newButtons);
          break;
        case 'IGNORE':

            console.log('in ignore',actionButton);
            if(actionButton.params.indexOf("ignore=0")!=-1)
            {
              this.hideLayerCommon({showBlockLayer: false   });
              this.hideLayerCommon({showThreeDots: false   });
            }
            else {
              this.showLayerCommon({blockLayerdata:responseButtons,showBlockLayer: true   });
            }
            this.props.replaceOldButtons(responseButtons);

            //var newButtons = this.getNewButtons(responseButtons.buttondetails.button,index);
            //this.props.replaceSingleButton(newButtons);
        break;

        case 'CONTACT_DETAIL':
            this.showLayerCommon({contactDetailData:responseButtons.actiondetails,showContactDetail:true});
        break;

        case 'WRITE_MESSAGE':
            this.showLayerCommon({showWriteMsgLayerData:responseButtons,showMsgLayer: true});
        break;

        case 'REPORT_INVALID':
            console.log("REPORT_INVALID");
        break;

        default:
          if(responseButtons.actiondetails.errmsglabel){
            this.showLayerCommon({commonOvlayLayer:true,commonOvlayData:responseButtons.actiondetails});
          }

          else
          this.props.replaceOldButtons(responseButtons);
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
  if(otherButtons[0].action == 'ACCEPT' && otherButtons[1].action == 'DECLINE')
  {

  return(<div key='1' id="buttons1" className="view_ce fullwid">

    <div className="wid50p bg7 dispibl txtc pad5new brdr6" id="primeWid_1" onClick={() => this.bindAction(otherButtons[0])}>

      <div id="btnAccept" className="fontlig f13 white cursp dispbl">
        <i className="ot_sprtie ot_chk"></i>
        <div className="white">{otherButtons[0].label}</div>
      </div>
    </div>
    <div className="wid50p bg7 dispibl txtc pad5new fr" id="primeWid_2" onClick={() => this.bindAction(otherButtons[1])}>
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
  threeDots =(<div onClick={()=>this.showLayerCommon({showThreeDots: true})} className="posabs srp_pos2"><a href="javascript:void(0)"><i className={"mainsp "+(otherButtons[0].action=='DEFAULT' ? "srp_pinkdots" : "threedot1")}></i></a></div>);
}
if(primaryButton.enable==true)
{

    return (<div id="buttons1" className="view_ce fullwid">
      <div className="fullwid bg7 txtc pad5new posrel" onClick={() => this.bindAction(primaryButton)}>
        <div className="wid60p">
          <i className="mainsp msg_srp"></i>
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

showLayerCommon(data){
  console.log("In showLayerCommon ");
  console.log(data);
  this.layerCount++;
  this.props.unsetScroll();
  this.setState({
    ...data
  });

}
hideLayerCommon(data){
  if(this.layerCount>0)
    this.layerCount--;
  if(!this.layerCount)this.props.setScroll();
  this.setState({
    ...data
  });
//  if(!this.state.showThreeDots && !this.state.showThreeDots & !this.state.showThreeDots)
}





getOverLayDataDisplay(){

    let layer = [];
      if(this.state.showThreeDots)
        layer= (<ThreeDots bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} buttondata={this.props.buttondata} closeThreeDotLayer ={()=>this.hideLayerCommon({showThreeDots: false})} username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />);
      if(this.state.showReportAbuse)
        layer= (<ReportAbuse
                    username={this.props.profiledata.username}
                    profilechecksum={this.props.profiledata.profilechecksum}
                    closeAbuseLayer={() => this.hideLayerCommon({showReportAbuse: false})}
                    profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />);

      if(this.state.showContactDetail)
        layer=  (<ContactDetails bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} actionDetails={this.state.contactDetailData} profilechecksum={this.props.profiledata.profilechecksum} closeCDLayer={() => this.hideLayerCommon({'showContactDetail':false})} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} />);

      if(this.state.showReportInvalid)
      {
        console.log("showReportInvalid is true.");
        layer= (<ReportInvalid username={this.props.profiledata.username} profilechecksum={this.props.profiledata.profilechecksum} closeInvalidLayer={() => this.hideLayerCommon({showReportInvalid: false})} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} reportType={this.state.reportType} />);
      }

      if(this.state.showMsgLayer)
      {
        layer= (<WriteMessage username={this.props.profiledata.username} closeWriteMsgLayer={()=>this.hideLayerCommon({showMsgLayer: false})}  buttonData={this.state.showWriteMsgLayerData} profilechecksum={this.props.profiledata.profilechecksum}/>);
      }
      if(this.state.commonOvlayLayer)
      {
        layer= (this.getCommonOverLay(this.state.commonOvlayData));
      }
      if(this.state.showBlockLayer)
      {
        layer= (<BlockPage blockdata={this.state.blockLayerdata} closeBlockLayer={()=>{this.hideLayerCommon({showBlockLayer:false});this.hideLayerCommon({showThreeDots:false});}} profileThumbNailUrl={this.props.buttondata.profileThumbNailUrl} bindAction={(buttonObject,index) => this.bindAction(buttonObject,index)} />);
      }
      return (  <div key="2">{layer}</div>)
  }

getCommonOverLay(actionDetails){
  console.log('---getCommonOverLay');
  console.log(actionDetails);
  return (<div className="posabs ce-bg ce_top1 ce_z101" style={{width:'100%',height:window.innerHeight}}>
            <a href="#"  className="ce_overlay" > </a>
              <div className="posabs ce_z103 ce_top1 fullwid" >

                <div className="white fullwid" id="commonOverlayTop">
                        <div id="3DotProPic" style={{ paddingTop:'20%'}} className="txtc">
                          <div id = "photoIDDiv" style={{border: '1px solid rgba(255,255,255,0.2)',  overflow:'hidden', width: '90px', height: '90px', borderRadius: '45px'}}><img id="ce_photo" src={this.props.profileThumbNailUrl}  className="srp_box2 mr6"/></div>
                          <div className="pt20 white f18 fontthin" id="topMsg">{actionDetails.errmsglabel}</div>
                        </div>
                </div>
              </div>
              <div className="posfix btmo fullwid" id="bottomElement">
                <div className="pt15">
                    <div className="brdr22 white txtc f16 pad2 fontlig " id="closeLayer" onClick={()=>this.hideLayerCommon({commonOvlayLayer:false})} style={{borderTop: '1px solid rgb(255, 255, 255)',borderTop: '1px solid rgba(255, 255, 255, .2)',WebkitBackgroundClip: 'padding-box', /* for Safari */ 'backgroundClip': 'padding-box'}} >Close</div>
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
