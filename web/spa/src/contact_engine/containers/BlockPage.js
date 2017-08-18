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
            tupleDim : {'width' : window.innerWidth,'height': window.innerHeight}
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
    console.log('in block');
    console.log(this.props);
    let image3dot = <div id="3DotProPic" className="txtc">
                      <div id="photoIDDiv" className="photoDiv">
                        <img id="ce_photo" className="srp_box2 mr6" src={this.props.profileThumbNailUrl} />
                      </div>
                  </div>;
    let blockLayerButton=   <div className="posfix btmo fullwid" id="bottomElement">
                              <div className="txtc">
                                <a href="#" className="white fontlig f16 forHide lh50" id="bottomMsg">Unblock</a>
                              </div>
                              <a href="#" className="dispbl bg7 white txtc f16 pad2 fontlig forHide" id="footerButton">Close</a>
                            </div>;


    return(
      <div className="posabs ce-bg ce_top1 ce_z101" style={this.state.tupleDim}>
        <a href="#"  className="ce_overlay ce_z102" > </a>
        <div className="posabs ce_z103 ce_top1 fullwid" style={this.state.tupleDim}>

          <div className="top_r1 white fullwid" id="commonOverlayTop">
            {image3dot}
            <div className="f16 white fontlig opa80 pad18 txtc" id="topMsg">{this.props.blockdata.message}</div>
          </div>
          {blockLayerButton}
        </div>
      </div>
    );
  }

}
