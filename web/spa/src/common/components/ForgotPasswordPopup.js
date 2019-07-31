import React from "react";
import {Link} from "react-router-dom";

export default class ForgotPasswordPopup extends React.Component { 
  constructor(props) {
    super();
    this.state = {
    };
  }


  popupMoveAnimation(ElementId) {
    var elem = document.getElementById(ElementId);   
    var windowInnerHeight = window.innerHeight;
    var startPos = 0;
    var endPos = Math.max((windowInnerHeight/2 - 100), 210);
    // var id = setInterval(frame, 5);
    
    // function frame() {
    //   if (startPos == endPos) {
    //     clearInterval(id);
    //   } else {
    //     startPos++; 
        elem.style.marginTop = endPos + 'px';
    //   }
    // }
  }

  componentDidMount() {
    this.popupMoveAnimation("ForgotPasswordPopupBox");
    // setTimeout(function(){ }, 10);
  } 
  
  render() {
    return (
      <div id="ForgotPasswordPopup">
        <div  className="web_dialog_overlay" ></div>

        <div id="ForgotPasswordPopupBox"  className="overlay_1_e page transition CancelOverlay top_2">
          <div style={{position:"relative"}}>
            <div className="txtc" style={{padding: "20px"}}>
              
              <div  className="f14 color3 pt4 fontlig pb30 nl_p10">Login details provided are incorrect, would you like to reset password ?</div>
            </div>
            <div style={{borderTop:"1px solid #dbdbdb"}}>
              <div className="fullwid">
                <div  className="fl txtc pad2 wid49p brdr2">
                  <div id="forgotPasswordLink2"  className="white f14 fontlig" onClick={() => {
                    this.props.history.push("/static/forgotPasswordV2/?user="+document.getElementById('email').value);
                  }}>
                    <div className="fontthin f17 color2">YES</div>
                  </div>
                </div>
                <div  className="fl txtc pad2 wid49p">
                  <div  className="fontthin f17 color2" onClick={this.props.closePopup}>NO</div>
                </div>
                <div className="clr"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    );
  }
}
