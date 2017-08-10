require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import axios from "axios";


export default class BlockPage extends React.Component{
  
    constructor(props){
        super();
        this.state = {
            selectOption: "",
            selectText: "",
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
        }
    }
    closeBlockPageLayer() {
        this.props.closeBlockPageLayer();
    }
   unblock() {
        this.props.unblock();
        this.props.closeBlockPageLayer();
    }
  
    
  render(){
    return(
        <div id="vpro_tapoverlay" className="posabs vpro_tapoverlay">
                <div className="threeDotOverlay white fullwid" id="commonOverlayTop">
                    <div id="3DotProPic" className="txtc">
                      <div id="photoIDDiv" className="photoDiv">
                        <img id="ce_photo" className="srp_box2 mr6" src={this.props.profileThumbNailUrl} />
                      </div>
                      <div className="f14 white fontlig opa80 pt10" id="topMsg">{this.props.message}</div>
                    </div>
                </div>
                <div className="txtc"><a href="#" className="white fontlig f16 forHide lh50" id="bottomMsg" onClick={() => this.unblock()} >Unblock</a>
                </div>
                <div className="dispbl bg7 white txtc f16 pad2 fontlig forHide" id="footerButton" onClick={() => this.closeBlockPageLayer()} >Close</div>
        </div>
    ); 
  }
  	
}
