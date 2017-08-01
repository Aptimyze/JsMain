require ('../style/contact.css')
import React from "react";
import { commonApiCall } from "../../common/components/ApiResponseHandler";

export default class contactEngine extends React.Component{
  constructor(props){
    super();
    this.actionUrl = {"CONTACT_DETAIL":"/api/v2/contacts/contactDetails","INITIATE":"/api/v2/contacts/postEOI","INITIATE_MYJS":"/api/v2/contacts/postEOI","CANCEL":"/api/v2/contacts/postCancelInterest","SHORTLIST":"/api/v1/common/AddBookmark","DECLINE":"/api/v2/contacts/postNotInterested","REMINDER":"/api/v2/contacts/postSendReminder","MESSAGE":"/api/v2/contacts/postWriteMessage","ACCEPT":"/api/v2/contacts/postAccept","WRITE_MESSAGE":"/api/v2/contacts/WriteMessage","IGNORE":"/api/v1/common/ignoreprofile","PHONEVERIFICATION":"/phone/jsmsDisplay","MEMBERSHIP":"/profile/mem_comparison.php","COMPLETEPROFILE":"/profile/viewprofile.php","PHOTO_UPLOAD":'/social/MobilePhotoUpload',"ACCEPT_MYJS":"/api/v2/contacts/postAccept","DECLINE_MYJS":"/api/v2/contacts/postNotInterested","EDITPROFILE":"/profile/viewprofile.php?ownview=1"};
  }

  performAction(button)
  {
      let profilechecksum = this.props.profilechecksum, callBFun =  this.props.callBack;
      var url = `&${button.params}&profilechecksum=${profilechecksum}`;
      return commonApiCall(this.actionUrl[button.action],url,'','POST').then(()=>{if(typeof callBFun=='function') callBFun();});
  }

  render(){
  	if(this.props.pagesrcbtn == "myjs")
      {
        if(this.props.buttonName == "interest_received") {
          return (<div className="brdr8 fl fullwid hgt60">
            <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2" onClick={() => this.performAction(this.props.button[0])}>
              <a className="f15 color2 fontreg">Accept</a>
            </div>
            <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn" onClick={() => this.performAction(this.props.button[1])}>
              <a className="f15 color2 fontlig">Decline</a>
            </div>
            <div className="clr"></div>
          </div>);
        }
        else {
          return(<div className="brdr8 fullwid hgt60">
           <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => this.performAction()}>
             <span className="f15 color2 fontreg">Send Interest</span>
           </div>
           <div className="clr"></div>
         </div>);
      }
    }
    }
}
