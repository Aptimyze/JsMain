require ('../style/contact.css');
import React from "react";

export class ContactDetails extends React.Component{
  constructor(props){
    super(props);
    this.state={};
    console.log('view contact');
  }

componentWillMount(){

}
render(){


return (<div><div className={"posrel " + this.state.commonOverlayShow} style="z-index: 110;" id="commonOverlay">
<a href className="contact_dialog_overlay" onClick="popBrowserStack();return false;"> </a>
<div className="srpoverlay_2 top_r1" id="commonOverlayTop">
<input type="hidden" id="selIndexId" value="" />

<div id="3DotProPic" className="txtc">
  <div id = "photoIDDiv" style="border: 1px solid rgb(255,255,255);border: 1px solid rgba(255,255,255,0.2);  overflow:hidden; width: 90px; height: 90px; border-radius: 45px;"><img id="ce_photo"  className="srp_box2 mr6"/></div>
  <div className={"f14 white fontlig opa80 pt10 "+this.state.topMsgTextShow} id="topMsg">{this.state.topMsgText}</div>

  <div className={"f16 pt10 lh25 fontlig white opa80 "+this.state.topMsg2TextShow} id="topMsg2" style='padding-left:15px; padding-right:15px'>{this.state.topMsg2Text}</div>

</div>
            <div className="fullwid pad18 txtc f16 opa80 fontlig white pt10" id="loaderDisplay" style="display:none">
  <img src="/images/jsms/commonImg/loader.gif" className="srp_box2 mr6"/>
            </div>
    <div className="fullwid pad1 txtc " id="errorMsgOverlay" style="display:none">
        <div className="pt20 white f18 fontthin" id="errorMsgHead">
        </div>
    </div>
<div className={"fullwid fontlig pad1 " + this.state.cdOvlayShow} id="contactDetailOverlay" style="overflow-y: auto; height:this.state.cdOHeight">
  {this.state.primaryMob}
  <div className={"pt15 " + this.state.vCPreLayerShow} id="ViewContactPreLayer" style={{paddingTop: '20%'}}>
<p id="ViewContactPreLayerText" style={{color: '#fff',textAlign: 'center'}}>{this.state.preLayerText}</p>
  </div>

  <div className={"pt15 " + this.state.vCPreLayerNoNumShow} id="ViewContactPreLayerNoNumber" style={{paddingTop: '20%'}}>
<p id="ViewContactPreLayerTextNoNumber" style={{color: '#fff',textAlign: 'center'}}>{this.state.vCNoNumber}</p>
  </div>

  {this.state.landLine}
  {this.state.alternateMob}
  {this.state.emailInfo}

  <div className="txtc"><a href="#" className=" pb20 white fontlig f16  opa50 " id="bottomMsg2" style={{...this.state.bottomMsgShow2, margin:'20px 9px'}}>{this.state.bottomMsgText2}</a></div>
</div>

<div className="posfix btmo fullwid" id="bottomElement">
  <div className="pt15">
      <div className="txtc"><a href="#" className={"pb20 white fontlig f16 "+this.state.bottomMsgShow} id="bottomMsg">{this.state.bottomMsgText}</a></div>
      <a href="#" className="dispbl brdr22 white txtc f16 pad2 fontlig " id="closeLayer" style={{display:'none',borderTop: '1px solid rgb(255, 255, 255)',borderTop: '1px solid rgba(255, 255, 255, .2)',webkitBackgroundClip: 'padding-box', /* for Safari */ 'backgroundClip': 'padding-box;'}} >Close</a>
        <a href="#" className="white txtc f16 pad2 fontlig " id="neverMindLayer" style={this.state.nevMindStyle} onClick="popBrowserStack();return false;">Never Mind</a>
        <a href="javascript:void(0);" className={"brdr23_contact dispbl color2 txtc f16 pad2 fontlig "+this.state.memShow} id="membershipMessageCE" >{this.state.memText}</a>
        <div onClick={()=>this.state.memBFunction()}  className={"bg7 white txtc f16 pad2 fontlig "+footerBShow} id="footerButton">{this.state.footerBText}</div>
    </div>
</div>

</div>
<img src="/images/jsms/membership_img/revamp_bg1.jpg" className="posfix classimg1 bgset"/>
</div>
{this.getFooterButton(result.actiondetails)}
</div>
);
}

componentDidMount(){
  //$("#contactDetailOverlay").height($("#bottomElement").offset().top-$("#contactDetailOverlay").offset().top);
  let getOffset = (ele)=> document.getElementById(bottomElement).getBoundingClientRect().top;
  this.setState({cdOHeight:(getOffset('bottomElement')-getOffset('contactDetailOverlay')) });
//  $("#bottomElement").offset().top-$("#contactDetailOverlay").offset().top
}

reportInvalid(){

}
getPhoneSection(displayProps){
let nevMindStyle = {display:'block'}, reportInvalid=(<div></div>),mobileIconShow='dispnone',mobileValShow='dispnone', mobileValBlur='dispnone',mobileVal='';//$("#neverMindLayer").show();

if (displayProps.showReportInvalid)
  reportInvalid = (<span  onClick={()=>this.reportInvalid(displayProps)} className="reportInvalidjsmsButton invalidMob " style = {{color:'#d9475c'}}> Report Invalid </span>);

  if(displayProps.contact){
    mobileValShow = 'dispnone',mobileValBlur ='dispnone';

//    $("#mobileVal,#mobileValBlur").hide();
    //$("#mobileValBlur").hide();
    //$("#mobile").show();
    if(displayProps.contact.value=="blur"){
      mobileValBlur = ''; //$("#mobileValBlur").show();
    }
    else mobileValShow='';//$("#mobileVal").show();
          mobileVal = displayProps.contact.value;
              //  $("#mobileVal").html(displayProps.contact.value+'<span  onclick="reportInvalid(\'M\',this,\''+proCheck+'\')" class="reportInvalidjsmsButton invalidMob " style = "color:#d9475c"> Report Invalid </span>');
                if (displayProps.contact.iconid){
//                   $("#mobileIcon > a").attr('href','tel:'+displayProps.contact.value.toString());
              mobileIconShow = '';
        }
  }
      else if(displayProps.contact_message)
      {
        mobileValShow = '',mobileValBlur ='';
        //$("#mobileVal,#mobile").show();
        mobileVal = result.actiondetails.contact1_message;
//        $("#mobileVal").html(result.actiondetails.contact1_message);
      }

return (
  <div className={"pt15 "+ mobileValShow} >
    <div className="fl white">
      <div className=" f14 lh30 opa50">{displayProps.label}</div>
      <div className={"f16 "+mobileValShow}></div>
      <div className={"pb20 "+mobileValBlur} id="mobileValBlur" ><div className="fontreg" style="text-shadow: 0 0 12px white;color:transparent;font-size:26px;">+91 987654321</div> </div>
      <div></div>
    </div>
    <div id="mobileIcon" className={"fr pt15 " + mobileIconShow}  ><a href={'tel:'+displayProps.contact.value.toString()}><i  className="mainsp srp_phnicon" ></i></a></div>
    <div className="clr"></div>
  </div>);
}

getEmailInfo()
{
  let nevMindStyle = {display:'block'},mobileIconShow='dispnone',mobileValShow='dispnone', mobileValBlur='dispnone',mobileVal='';//$("#neverMindLayer").show();

    if(displayProps.contact){
      mobileValShow = 'dispnone',mobileValBlur ='dispnone';

  //    $("#mobileVal,#mobileValBlur").hide();
      //$("#mobileValBlur").hide();
      //$("#mobile").show();
      if(displayProps.contact.value=="blur"){
        mobileValBlur = ''; //$("#mobileValBlur").show();
      }
      else mobileValShow='';//$("#mobileVal").show();
            mobileVal = displayProps.contact.value;
                //  $("#mobileVal").html(displayProps.contact.value+'<span  onclick="reportInvalid(\'M\',this,\''+proCheck+'\')" class="reportInvalidjsmsButton invalidMob " style = "color:#d9475c"> Report Invalid </span>');
                  if (displayProps.contact.iconid){
  //                   $("#mobileIcon > a").attr('href','tel:'+displayProps.contact.value.toString());
                mobileIconShow = '';
          }
    }

  return (
    <div className={"pt15 "+ mobileValShow} >
      <div className="fl white">
        <div className=" f14 lh30 opa50">{displayProps.label}</div>
        <div className={"f16 "+mobileValShow}></div>
        <div className={"pb20 "+mobileValBlur} id="mobileValBlur" ><div className="fontreg" style="text-shadow: 0 0 12px white;color:transparent;font-size:26px;">+91 987654321</div> </div>
        <div></div>
      </div>
      <div  className={"fr pt15 " + mobileIconShow}  ><a href={'mailto:'+displayProps.contact.value.toString()}><i  className="mainsp srp_msg1" ></i></a></div>
      <div className="clr"></div>
    </div>);

}

getFooterButton(){

  return (<div className={"posrel fullwid fullheight overlayPos "+this.state.memOvlayShow} id="membershipOverlay">
      <img src="/images/jsms/membership_img/revamp_bg1.jpg" className="posfix classimg1 bgset" />
      <div className="fullheight fullwid layerOpa posrel" style="overflow:auto;">
          <div className="memOverlay app_clrw" style="padding-bottom:50px">
              <div className="txtc">
                  <div id="photoIDDiv" className="photoDiv">

                  </div>
                  <div className="pad2 f16 fontlig" id="newErrMsg">{this.state.newErrMsg}</div>
                  <div className="pad20 f16 fontlig mt15" id="membershipheading">{this.state.memHeading}</div>
                  <ul className=" memList f13 fontlig">
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
      <div id="footerDiv" className="posfix fullwid btmo" style="background:black">
              <a href="#" id="skipLayer" className="f16 fontmed app_clrw txtc posSkip" onClick="popBrowserStack();return false;">Skip</a>
              <div className="bg7">

              <a href="#" id="footerButtonNew" className="fullwid dispbl lh50 txtc f17 fontlig white"></a>
              </div>
      </div>


  </div>);

}

getContactDetails(profilechecksum,index){

  //$("#topMsg").hide();
  $("#"+actionTemplate[action]).show();
  /*if(result.footerbutton)
  {
    contactDetailMessage(result,action, index);
    return;
  }*/
    if(actiondetails.errmsglabel)
    {
    this.setState({topMsg2Text : actiondetails.errmsglabel }); //    $("#topMsg2").html(result.actiondetails.errmsglabel);
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
    //  $("#membershipMessageCE").text(actiondetails.footerbutton.text).show();else $("#membershipMessageCE").hide();
    this.setState({
      showTopMsg2, memText,memShow,
      commonOverlayShow : ''
    });//$("#topMsg2, #mobile, #mobileValBlur, #landline, #landlineValBlur").show();

    }
else
{
  var memText, memShow, topMsg2Text='', topMsg2TextShow,topMsg2Text, topMsgTextShow,topMsgText='',
  bottomMsgShow={display:'none'},bottomMsgText='',bottomMsgredirectFun=()=>{},bottomMsgShow2={display:'none'},
  bottomMsgText2='', nevMindStyle={display:'none'}, vCPreLayerShow='dispnone', vCPreLayerNoNumShow='dispnone',footerBShow='dispnone',
  footerBText='',preLayerText='',vCPreLayerShow='dispnone',commonOverlayShow='',newErrMsg='',
  memHeading='',sH1,sH2,sH3,mOExists='dispnone', oPShow = 'dispnone', lowestOfferDiv=(<div></div>) ,memOvlayShow='dispnone',closeLyrShow='dispnone',cdOvlayShow='dispnone';
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

    //if(result.actiondetails.membershipOfferMsg)$("#membershipMessageCE").text(result.actiondetails.membershipOfferMsg).show();else $("#membershipMessageCE").hide();
    if(actiondetails.contactdetailmsg){
        topMsg2Text = actiondetails.contactdetailmsg,
        topMsg2TextShow = '';
        //$("#topMsg2").html(result.actiondetails.contactdetailmsg).show();
  }
  else
      topMsg2TextShow = 'dispnone';

  if(actiondetails.topmsg)
  {
    topMsgText = actiondetails.contactdetailmsg,
    topMsgTextShow = '';
//    $("#topMsg2").html(result.actiondetails.contactdetailmsg).show();
  }
  else
    topMsgTextShow = 'dispnone';


  if(actiondetails.bottommsg){

    bottomMsgShow = {'display': 'inline-block'};
    bottomMsgText = actiondetails.bottommsg;
//    $("#bottomMsg").html(result.actiondetails.bottommsg);
    if(result.actiondetails.bottommsgurl)
      bottomMsgredirectFun = ()=>{window.location.replace(result.actiondetails.bottommsgurl)}

  }
  if(actiondetails.bottommsg2){
    bottomMsgShow2 = {'display': 'inline-block'};
    bottomMsgText2 = actiondetails.bottommsg2;
//    $("#bottomMsg").html(result.actiondetails.bottommsg);

  }
//
// if(result.actiondetails.bottommsg2){
//     $("#bottomMsg2").html(result.actiondetails.bottommsg2).css('display', 'inline-block');
//   }

    let primaryMob = this.getPhoneSection({contact:result.actiondetails.contact1,contact_message:result.actiondetails.contact1_message,showReportInvalid:true});
    let landLine = this.getPhoneSection({contact:result.actiondetails.contact2,contact_message:result.actiondetails.contact2_message,showReportInvalid:true});
    let alternateMob = this.getPhoneSection({contact:result.actiondetails.contact3,contact_message:result.actiondetails.contact3_message,showReportInvalid:false});
    let emailInfo = this.getEmailInfo({contact:result.actiondetails.contact4,contact_message:result.actiondetails.contact4_message,showReportInvalid:false});

    //$("#footerButton").html(result.actiondetails.footerbutton.label);
    //$("#mobile").hide();

    }
        if(result.actiondetails.footerbutton!=null){
          footerBShow = 'dispbl';
          footerBText = result.actiondetails.footerbutton.label;
          if(result.actiondetails.infomsglabel)
          {
            preLayerText = result.actiondetails.infomsglabel;
            //$("#ViewContactPreLayerText").html(result.actiondetails.infomsglabel);
            vCPreLayerShow = '';
            //$("#ViewContactPreLayer").show();
          }
          if(result.actiondetails.newerrmsglabel)
          {
            commonOverlayShow = 'dispnone';
            //$("#commonOverlay").hide();
            newErrMsg = result.actiondetails.newerrmsglabel;
            //$("#newErrMsg").html(result.actiondetails.newerrmsglabel);
            memHeading  = result.actiondetails.membershipmsgheading;
            //$("#membershipheading").html(result.actiondetails.membershipmsgheading);
            sH1=result.actiondetails.membershipmsg.subheading1;
            sH2=result.actiondetails.membershipmsg.subheading2;
            sH3=result.actiondetails.membershipmsg.subheading3;
            // $("#subheading1").html(result.actiondetails.membershipmsg.subheading1);
            // $("#subheading2").html(result.actiondetails.membershipmsg.subheading2);
            // $("#subheading3").html(result.actiondetails.membershipmsg.subheading3);

            if(typeof(result.actiondetails.offer) != "undefined" && result.actiondetails.offer != null)
            {
              mOExists = ''
              //$("#MembershipOfferExists").show();
              var mO1 = result.actiondetails.offer.membershipOfferMsg1.toUpperCase();
              var mO2 = result.actiondetails.offer.membershipOfferMsg2;
              //$("#membershipOfferMsg1").html(result.actiondetails.offer.membershipOfferMsg1.toUpperCase());
              //$("#membershipOfferMsg2").html(result.actiondetails.offer.membershipOfferMsg2);
              if(typeof(result.actiondetails.strikedprice) != "undefined" && result.actiondetails.strikedprice != null)
              {
                var oPrice = result.actiondetails.strikedprice;
                var oPShow = '';
                //$("#oldPrice").html(result.actiondetails.strikedprice);
                //$("#oldPrice").show();
              }


              var lowestOfferDiv = (<div className="f16 fontlig" id="LowestOffer" >Lowest Membership starts @<del id="oldPrice" className={this.state.oPShow}>{this.state.oPrice}</del>&nbsp;<span id="currency">{result.actiondetails.membershipoffercurrency}</span>&nbsp;<span id="newPrice">{result.actiondetails.discountedprice}</span>
            </div>);
              //discntPrice = result.actiondetails.discountedprice;
              lwstOffShow = '';
              // $("#currency").html(result.actiondetails.membershipoffercurrency);
              // $("#newPrice").html(result.actiondetails.discountedprice);
              // $("#LowestOffer").show();
            }
            else if(typeof(result.actiondetails.lowestoffer) != "undefined" && result.actiondetails.lowestoffer != null)
            {
              lowestOfferDiv = (<div className="f16 fontlig mt60" id="LowestOffer" ></div>);

              //$("#LowestOffer").html(result.actiondetails.lowestoffer);
              //$("#LowestOffer").addClass("mt60");
              //$("#LowestOffer").show();
            }

            bindFooterButtonswithId(result,'footerButtonNew');
            memOvlayShow = '';

          }
          else if(result.actiondetails.errmsglabel)
          {
            //////************/$("#topMsg2,#landline").hide();
            //$("#landline").hide();
            //$("#ViewContactPreLayerTextNoNumber").html("You will be able to see the Email Id of "+result.actiondetails.headerlabel+ "but not the phone number. This is because "+result.actiondetails.headerlabel+"'s has chosen to hide phone number.");
            var vCNoNumber = result.actiondetails.errmsglabel;
           //$("#ViewContactPreLayerTextNoNumber").html(result.actiondetails.errmsglabel);
           vCPreLayerNoNumShow = '';
            //$("#ViewContactPreLayerNoNumber").show();
          }


          //footerBFunction = (result.actiondetails.footerButton) =>

	}
        else {
            closeLyrShow = '';
        }
        cdOvlayShow = ''
        this.setState({
          memText, memShow, topMsg2Text, topMsg2TextShow,topMsg2Text, topMsgTextShow,topMsgText,
          bottomMsgShow,bottomMsgText,bottomMsgredirectFun, bottomMsgShow2 ,bottomMsgText2,mobileValShow,
          nevMindStyle,vCPreLayerShow,vCPreLayerNoNumShow, footerButtonShow, footerBText,preLayerText,
          commonOverlayShow,newErrMsg,memHeading,sH1,sH2,sH3,mOExists,mO1,mO2,oPrice:'',oPShow:'dispnone',lwstOffShow,
          memOCurrency,discntPrice, lowestOfferDiv, vCNoNumber,closeLyrShow,cdOvlayShow,primaryMob,landLine,alternateMob,
          emailInfo

        });//$("#topMsg2, #mobile, #mobileValBlur, #landline, #landlineValBlur").show();

}

}
