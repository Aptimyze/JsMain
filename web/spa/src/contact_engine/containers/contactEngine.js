require ('../style/contact.css')
import React from "react";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';


export const performAction = (data) =>
{
    if(!data.button.enable)return false;
    var params = (data.button.params ? data.button.params : "") + (data.extraParams ? data.extraParams: "");
    var url = `?&${params}&profilechecksum=${data.profilechecksum}`;
    return commonApiCall(CONSTANTS.CONTACT_ENGINE_API[data.button.action]+url,{},'','POST').then((response)=>{if(typeof data.callBFun=='function') data.callBFun(response);});
}

export const cssMap={'001':'mainsp msg_srp2','003':'mainsp srtlist','004':'mainsp shortlisted','083':'ot_sprtie ot_bell','007':'mainsp vcontact','085':'ot_sprtie ot_chk','084':'deleteDecline','086':'mainsp ot_msg cursp','018':"mainsp srp_phnicon",'020':'mainsp srp_phnicon','ignore':'mainsp ignore','088':'deleteDeclineNew','089':'newitcross','090':'newitchk','099':'reportAbuse mainsp'};
export default class contactEngine extends React.Component{
  constructor(props){
    super();
  }



  render(){
      if(this.props.buttonName == "interest_received") {
         return (<div className="brdr8 fl fullwid hgt60">
           <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2" onClick={() => performAction({profilechecksum:this.props.profilechecksum,callBFun:this.props.callBack,button:this.props.button[0]})}>
             <a className="f15 color2 fontreg">Accept</a>
           </div>
           <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn" onClick={() => performAction({profilechecksum:this.props.profilechecksum,callBFun:this.props.callBack,button:this.props.button[1]})}>
             <a className="f15 color2 fontlig">Decline</a>
           </div>
           <div className="clr"></div>
         </div>);
      }
      else {
          return(<div className="brdr8 fullwid hgt60">
           <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => performAction({profilechecksum:this.props.profilechecksum,callBFun:this.props.callBack,button:this.props.button[0]})}>
             <span className="f15 color2 fontreg">Send Interest</span>
           </div>
           <div className="clr"></div>
         </div>);
      }
    }
}
