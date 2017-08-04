require ('../style/contact.css')
import React from "react";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';


export const performAction = (profilechecksum,callBFun,button) =>
{
  console.log("final button:");
  console.log("button",button.action);
    var url = `&${button.params}&profilechecksum=${profilechecksum}`;
    return commonApiCall(CONSTANTS.CONTACT_ENGINE_API[button.action],url,'','POST').then((response)=>{if(typeof callBFun=='function') callBFun(response);});
}

export default class contactEngine extends React.Component{
  constructor(props){
    super();
  }



  render(){
      if(this.props.buttonName == "interest_received") {
         return (<div className="brdr8 fl wid90p hgt60">
           <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2" onClick={() => performAction(this.props.profilechecksum,this.props.callBack,this.props.button[0])}>
             <a className="f15 color2 fontreg">Accept</a>
           </div>
           <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn" onClick={() => performAction(this.props.profilechecksum,this.props.callBack,this.props.button[1])}>
             <a className="f15 color2 fontlig">Decline</a>
           </div>
           <div className="clr"></div>
         </div>);
      }
      else {

          return(<div className="brdr8 fullwid hgt60">
           <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => performAction(this.props.profilechecksum,this.props.callBack,this.props.button[0])}>
             <span className="f15 color2 fontreg">Send Interest</span>
           </div>
           <div className="clr"></div>
         </div>);
      }
    }
}
