require ('../style/contact.css');
import React from "react";
export default class ContactDetails extends React.Component{

  constructor(props){
    super(props);
    this.state=this.getContactDetails(this.props.actionDetails);
    this.state.tupleDim = {width:'100%','height': document.getElementById("ProfilePage").clientHeight};

  }

componentWillReceiveProps(nextProps){
var newState = this.getContactDetails(nextProps.actionDetails);
this.setState({...newState});
}
render(){

return (<div className="posabs ce-bg ce_top1 ce_z101" style={this.state.tupleDim}>
          <a href="#"  className="ce_overlay ce_z102" > </a>
            <div className={"posabs ce_z103 ce_top1 fullwid "} style={this.state.tupleDim}>

<div className="white fullwid" id="commonOverlayTop">

<div id="3DotProPic" style={{ paddingTop:'20%'}} className="txtc">
  <div id = "photoIDDiv" style={{border: '1px solid rgba(255,255,255,0.2)',  overflow:'hidden', width: '90px', height: '90px', borderRadius: '45px'}}><img id="ce_photo" src={this.props.profileThumbNailUrl}  className="srp_box2 mr6"/></div>
  <div className={"f14 white fontlig opa80 pt10 "+this.state.topMsgTextShow} id="topMsg">{this.state.topMsgText}</div>

  <div className={"f16 pt10 lh25 fontlig white opa80 "+this.state.topMsg2TextShow} id="topMsg2" style={{paddingLeft:'15px', 'paddingRight':'15px'}}>{this.state.topMsg2Text}</div>

</div>
            <div className="fullwid pad18 txtc f16 opa80 fontlig white pt10" id="loaderDisplay" style={{display:'none'}}>
  <img src="/images/jsms/commonImg/loader.gif" className="srp_box2 mr6"/>
            </div>
    <div className="fullwid pad1 txtc " id="errorMsgOverlay" style={{display:'none'}}>
        <div className="pt20 white f18 fontthin" id="errorMsgHead">
        </div>
    </div>
<div className={"fullwid fontlig pad1 pt30 " + this.state.cdOvlayShow} id="contactDetailOverlay" style={{overflowY: 'auto', height:this.state.cdOHeight}}>
  {this.getPhoneSection({contact:this.props.actionDetails.contact1,contact_message:this.props.actionDetails.contact1_message,showReportInvalid:true,label:'Phone No.',style:this.state.c1Style,id:'mobile'})}
  <div className={"pt15 " + this.state.vCPreLayerShow} id="ViewContactPreLayer" style={{paddingTop: '20%'}}>
<p id="ViewContactPreLayerText" dangerouslySetInnerHTML={{__html: this.state.preLayerText}} style={{color: '#fff',textAlign: 'center'}}></p>
  </div>

  <div className={"pt15 " + this.state.vCPreLayerNoNumShow} id="ViewContactPreLayerNoNumber" style={{paddingTop: '20%'}}>
<p id="ViewContactPreLayerTextNoNumber" dangerouslySetInnerHTML={{__html: this.state.vCNoNumber}} style={{color: '#fff',textAlign: 'center'}}></p>
  </div>
{this.getPhoneSection({contact:this.props.actionDetails.contact2,contact_message:this.props.actionDetails.contact2_message,showReportInvalid:true,label:'Landline',style:{},id:'phone'})}
{this.getPhoneSection({contact:this.props.actionDetails.contact3,contact_message:this.props.actionDetails.contact3_message,showReportInvalid:false,label:'Alternate No.',style:{},id:'alternateNumber'})}
{this.getEmailInfo({contact:this.props.actionDetails.contact4,contact_message:this.props.actionDetails.contact4_message,showReportInvalid:false,label:'Email',style:{},id:'email'})}

  {this.state.emailInfo}

  <div className="txtc"><a href="#" className=" pb20 white fontlig f16  opa50 " id="bottomMsg2" style={{...this.state.bottomMsgShow2, margin:'20px 9px'}}>{this.state.bottomMsgText2}</a></div>
</div>

<div className="posfix btmo fullwid" id="bottomElement">
  <div className="pt15">
      <div className="txtc"><a href={this.props.actionDetails.bottommsgurl} className={"pb20 white fontlig f16 "+this.state.bottomMsgShow} dangerouslySetInnerHTML={{__html: this.state.bottomMsgText}} id="bottomMsg"></a></div>
      <div className={"brdr22 white txtc f16 pad2 fontlig "+this.state.closeLyrShow} id="closeLayer" onClick={()=>this.props.closeCDLayer()} style={{borderTop: '1px solid rgb(255, 255, 255)',borderTop: '1px solid rgba(255, 255, 255, .2)',WebkitBackgroundClip: 'padding-box', /* for Safari */ 'backgroundClip': 'padding-box'}} >Close</div>
        <a href="#" className="white txtc f16 pad2 fontlig " id="neverMindLayer" onClick={this.props.closeCDLayer} style={this.state.nevMindStyle} >Never Mind</a>
        <a href="javascript:void(0);" className={"brdr23_contact dispbl color2 txtc f16 pad2 fontlig "+this.state.memShow} id="membershipMessageCE" >{this.state.memText}</a>
        <div onClick={()=>this.props.bindAction(this.props.actionDetails.footerbutton)}  className={"bg7 white txtc f16 pad2 fontlig "+this.state.footerBShow} id="footerButton">{this.state.footerBText}</div>
    </div>
</div>

</div>
</div>
{this.getMembershipOvlay()}

</div>
);
}

componentDidMount(){
  console.log("ComponentDidMount  viw contact");
  console.log(this.state.cdOHeight);
  //$("#contactDetailOverlay").height($("#bottomElement").offset().top-$("#contactDetailOverlay").offset().top);
  let getOffset = (ele)=> document.getElementById(ele).clientHeight;
  let sum = getOffset('3DotProPic')+  getOffset('errorMsgOverlay') + getOffset('bottomElement');
  this.setState({
   cdOHeight:(document.getElementById("ProfilePage").clientHeight - parseInt(sum)  ) 
 });

//  $("#bottomElement").offset().top-$("#contactDetailOverlay").offset().top
}

reportInvalid(){

}
getPhoneSection(displayProps){
let reportInvalid=(<div></div>),mobileIconShow='dispnone',mobileValShow='dispnone', mobileValBlur='dispnone',mobileVal='',contactShow='dispnone';//$("#neverMindLayer").show();


  if(displayProps.contact){
    contactShow = '';

    if(displayProps.contact.value=="blur"){
      mobileValBlur = '';
    }
    else
    {
    mobileValShow='';
    if (displayProps.showReportInvalid)
      reportInvalid = (<span  onClick={() => this.props.bindAction({'action':'REPORT_INVALID','type':displayProps.id})} className="reportInvalidjsmsButton invalidMob " style = {{color:'#d9475c'}}> Report Invalid </span>);

    }
          mobileVal = displayProps.contact.value;
                if (displayProps.contact.iconid){
              mobileIconShow = '';
        }
  }
      else if(displayProps.contact_message)
      {
        mobileValShow = '';
        contactShow = '';
        mobileVal = displayProps.contact_message;
        mobileValBlur='dispnone';
      }

return (
  <div style={displayProps.style} className={"pt15 "+ contactShow} >
    <div className="fl white">
      <div className=" f14 lh30 opa50">{displayProps.contact ? (displayProps.contact.label ? displayProps.contact.label : displayProps.label) : displayProps.label}</div>
      <span className={"f16 "+mobileValShow}>{mobileVal}</span>
      {reportInvalid}
      <div className={"pb20 "+mobileValBlur} id="mobileValBlur" ><div className="fontreg" style={{textShadow: '0 0 12px white',color:'transparent',fontSize:'26px'}}>+91 987654321</div> </div>
      <div></div>
    </div>
    <div id="mobileIcon" className={"fr pt15 " + mobileIconShow}  ><a href={'tel:'+mobileVal.toString()}><i  className="mainsp srp_phnicon" ></i></a></div>
    <div className="clr"></div>
  </div>);
}

getEmailInfo(displayProps)
{
  let mobileIconShow='dispnone',emailValShow='dispnone', mobileValBlur='dispnone',emailVal='',contactShow='dispnone';//$("#neverMindLayer").show();

    if(displayProps.contact){
      contactShow = '';

      if(displayProps.contact.value=="blur"){
        mobileValBlur = '';
      }
      else emailValShow='';
      emailVal = displayProps.contact.value;
                  if (displayProps.contact.iconid){
                mobileIconShow = '';
          }
    }

  return (
    <div className={"pt15 "+ contactShow} >
      <div className="fl white">
        <div className=" f14 lh30 opa50">{displayProps.contact ? displayProps.contact.label :''}</div>
        <div className={"f16 "+emailValShow}>{emailVal}</div>
        <div className={"pb20 "+mobileValBlur} id="mobileValBlur" ><div className="fontreg" style={{textShadow: '0 0 12px white',color:'transparent',fontSize:'26px'}}>+91 987654321</div> </div>
        <div></div>
      </div>
      <div  className={"fr pt15 " + mobileIconShow}  ><a href={'mailto:'+emailVal.toString()}><i  className="mainsp srp_msg1" ></i></a></div>
      <div className="clr"></div>
    </div>);

}

getMembershipOvlay(){
  let fButton = this.getFooterButton(this.props.actionDetails.footerbutton);
  return (<div className={"posrel fullwid fullheight overlayPos "+this.state.memOvlayShow} id="membershipOverlay">
      <img src="/images/jsms/membership_img/revamp_bg1.jpg" className="posfix classimg1 bgset" />
      <div className="fullheight fullwid layerOpa posrel" style={{overflow:'auto'}}>
          <div className="memOverlay app_clrw" style={{paddingBottom:'50px'}}>
              <div className="txtc">
                  <div id="photoIDDiv" className="photoDiv">
                    <img id="ce_photo" src={this.props.profileThumbNailUrl}  className="srp_box2 mr6"/>
                  </div>
                  <div className="pad2 f16 fontlig" id="newErrMsg">{this.state.newErrMsg}</div>
                  <div className="pad20 f16 fontlig mt15" id="membershipheading">{this.state.memHeading}</div>
                  <ul style={{paddingLeft:'40px'}} className=" memList f13 fontlig">
                      <li className="tick pad21" id="subheading1">{this.state.sH1}</li>
                      <li className="tick pad21" id="subheading2">{this.state.sH2}</li>
                      <li className="tick pad21" id="subheading3">{this.state.sH3}</li>
                  </ul>
                  <div id="MembershipOfferExists" className={this.state.mOExists} >
                      <div className="pad45_0 f16 fontlig" id="membershipOfferMsg1"></div>
                      <div className="f16 pad20 fontmed" id="membershipOfferMsg2"></div>
                  </div>
              {this.state.lowestOfferDiv}
              </div>

          </div>

      </div>

      {fButton}
  </div>);
}

getFooterButton(fButton){
  if(!fButton) return (<div></div>);
return       (<div id="footerDiv" className="posfix fullwid btmo" style={{background:'black'}}>
              <div id="skipLayer" onClick={this.props.closeCDLayer} className="f16 fontmed app_clrw txtc posSkip">Skip</div>
              <div className="bg7">

              <a onClick={()=>this.props.bindAction(fButton)} id="footerButtonNew" className="fullwid dispbl lh50 txtc f17 fontlig white">{fButton.newlabel}</a>
              </div>
      </div>);

}
getContactDetails(actiondetails){
  var memText, memShow, topMsg2Text='', topMsg2TextShow,topMsg2Text, topMsgTextShow,topMsgText='',
  bottomMsgShow='dispnone',bottomMsgText='',bottomMsgredirectFun=()=>{},bottomMsgShow2={display:'none'},
  bottomMsgText2='', nevMindStyle={display:'none'}, vCPreLayerShow='dispnone', vCPreLayerNoNumShow='dispnone',footerBShow='dispnone',
  footerBText='',preLayerText='',vCPreLayerShow='dispnone',commonOverlayShow='',newErrMsg='',
  memHeading='',sH1,sH2,sH3,mOExists='dispnone', oPShow = 'dispnone', lowestOfferDiv=(<div></div>) ,memOvlayShow='dispnone',closeLyrShow='dispnone',cdOvlayShow='dispnone',primaryMob=(<div></div>), landLine=(<div></div>), alternateMob=(<div></div>),emailInfo=(<div></div>),
  c1Style = {};
    if(actiondetails.errmsglabel)
    {
    topMsg2Text = actiondetails.errmsglabel ;
    var memText, memShow;

    if(actiondetails.footerbutton && actiondetails.footerbutton.text)
      {
          memText = actiondetails.footerbutton.text;
          memShow = '';
      }
      else
        {
          memText = '';
          memShow = 'dispnone';

        }
      commonOverlayShow : ''

    }
else
{
  if(actiondetails.membershipOfferMsg )
    {
        memText = actiondetails.membershipOfferMsg;
        memShow = '';
    }
    else
      {
        memText = '';
        memShow = 'dispnone';

      }

    if(actiondetails.contactdetailmsg){
        topMsg2Text = actiondetails.contactdetailmsg,
        topMsg2TextShow = '';
  }
  else
      topMsg2TextShow = 'dispnone';

  if(this.props.topmsg)
  {
    topMsgText = this.props.topmsg,
    topMsgTextShow = '';
  }
  else
    topMsgTextShow = 'dispnone';

  if(actiondetails.bottommsg){
    bottomMsgShow = 'dispibl';
    bottomMsgText = actiondetails.bottommsg;
    if(actiondetails.bottommsgurl)
      bottomMsgredirectFun = ()=>{window.location.replace(actiondetails.bottommsgurl)}

  }
  if(actiondetails.bottommsg2){
    bottomMsgShow2 = {'display': 'inline-block'};
    bottomMsgText2 = actiondetails.bottommsg2;

  }
    if(actiondetails.contact1 && actiondetails.contact1.value=="blur")
      nevMindStyle = {display:'block'};

    }
        if(actiondetails.footerbutton!=null){
          if(actiondetails.footerbutton.action)nevMindStyle={display:'block'};
          c1Style = {display:'none'};
          footerBShow = 'dispbl';
          footerBText = actiondetails.footerbutton.label;

          if(actiondetails.infomsglabel)
          {
            preLayerText = actiondetails.infomsglabel;
            vCPreLayerShow = '';
          }
          if(actiondetails.newerrmsglabel)
          {
            commonOverlayShow = 'dispnone';
            newErrMsg = actiondetails.newerrmsglabel;
            memHeading  = actiondetails.membershipmsgheading;
            sH1=actiondetails.membershipmsg.subheading1;
            sH2=actiondetails.membershipmsg.subheading2;
            sH3=actiondetails.membershipmsg.subheading3;
            if(typeof(actiondetails.offer) != "undefined" && actiondetails.offer != null)
            {
              mOExists = ''
              var mO1 = actiondetails.offer.membershipOfferMsg1.toUpperCase();
              var mO2 = actiondetails.offer.membershipOfferMsg2;
              if(typeof(actiondetails.strikedprice) != "undefined" && actiondetails.strikedprice != null)
              {
                var oPrice = actiondetails.strikedprice;
                var oPShow = '';
              }


              var lowestOfferDiv = (<div className="f16 fontlig" id="LowestOffer" >Lowest Membership starts @<del id="oldPrice" className={this.state.oPShow}>{this.state.oPrice}</del>&nbsp;<span id="currency">{actiondetails.membershipoffercurrency}</span>&nbsp;<span id="newPrice">{actiondetails.discountedprice}</span>
            </div>);
            }
            else if(typeof(actiondetails.lowestoffer) != "undefined" && actiondetails.lowestoffer != null)
            {
              lowestOfferDiv = (<div className="f16 fontlig mt60" id="LowestOffer" >{actiondetails.lowestoffer}</div>);

            }

            memOvlayShow = '';

          }
          else if(actiondetails.errmsglabel)
          {
            topMsg2TextShow='dispnone';
            var vCNoNumber = actiondetails.errmsglabel;
           vCPreLayerNoNumShow = '';
          }


	}
        else {
            closeLyrShow = '';
        }
        cdOvlayShow = ''
        return {
          memText, memShow, topMsg2Text, topMsg2TextShow,topMsg2Text, topMsgTextShow,topMsgText,
          bottomMsgShow,bottomMsgText,bottomMsgredirectFun, bottomMsgShow2 ,bottomMsgText2,
          nevMindStyle,vCPreLayerShow,vCPreLayerNoNumShow, footerBText,preLayerText,
          commonOverlayShow,newErrMsg,memHeading,sH1,sH2,sH3,mOExists,mO1,mO2,oPrice:'',oPShow:'dispnone'
          , lowestOfferDiv, vCNoNumber,closeLyrShow,cdOvlayShow,primaryMob,landLine,alternateMob,
          emailInfo,memOvlayShow,footerBShow,c1Style

        };
}

}
