import React from "react";
import {Link} from "react-router-dom";
import {connect} from "react-redux";
import Loader from "../components/Loader";
import {getCookie} from '../components/CookieHelper';
import {commonApiCall} from "../components/ApiResponseHandler.js";
import TopError from "../components/TopError";
import VerifiedVisit from "../../verifiedVisit/containers/verifiedVisit"

class PhotoView extends React.Component {
    constructor(props) {
        super();
        this.state = {
            showLoader: false,
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showVerified: false
        };
    }
    componentWillReceiveProps(nextProps) {
        let response = nextProps.photoAction;
        let _this = this;
        response.responseMessage = "Successful";
        response.imageButtonDetail = {label : "Photo Requested"};
        this.setState({
            showLoader:false
        });
        if(document.getElementById("label1")) {
            document.getElementById("label1").classList.remove("dn");
            if(response.actionDetails && response.actionDetails.errmsglabel)
                {
                    this.setState ({
                        insertError : true,
                        errorMessage : response.actionDetails.errmsglabel
                })
                setTimeout(function(){
                    _this.setState ({
                        insertError : false,
                        errorMessage : ""
                    })
                }, this.state.timeToHide+100);
            }
            else if(response.responseMessage== "Successful" && response.imageButtonDetail.label) {
                if(getCookie("AUTHCHECKSUM"))
                {
                    document.getElementById("label1").innerHTML = response.imageButtonDetail.label;
                }
            }
        }
    }
    requestPhoto(e) {
        e.preventDefault();
        if(this.props.picData.label == document.getElementById("label1").innerHTML) {
            if(getCookie("AUTHCHECKSUM")) {
                this.setState({
                    showLoader:true
                });
                e.target.classList.add("dn");
                this.props.doPhotoAction(this.props.profilechecksum);
            } else {
                this.props.doPhotoAction(this.props.profilechecksum);
                // e.target.innerHTML = "Please Login to Continue"
            }
        }

    }
    showVerification(e) {
        e.preventDefault();
        this.setState({
            showVerified: true
        });
    }
    closeOverlay() {
        this.setState({
            showVerified: false
        });
    }
    handleImageLoaded() {
        this.props.imageLoaded();
    }
    handleImageError() {
      console.log()
      if(this.props.genderPic=="Male")
      {
         document.getElementById("profilePic").src = "https://static.jeevansathi.com/images/picture/450x450_m.png?noPhoto";
      }
      else {
         document.getElementById("profilePic").src = "https://static.jeevansathi.com/images/picture/450x450_f.png?noPhoto";
      }
    }

    render() {
        var errorView;
        if(this.state.insertError)
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }

        var galleryIcon;
        if(this.props.picData.url && this.props.picData.pic_count) {
            galleryIcon = <div className="posabs vpro_pos4">
                    <div className="posabs outerAlbumIcon">
                        <div className="bg4 txtc disptbl crBoxCount">
                            <div className="f14 color6 dispcell vertmid">
                                {this.props.picData.pic_count}
                            </div>
                        </div>
                    </div>
                    <div className="bg13 opa50 txtc white opa70 fontreg crBoxIcon">
                        <div className="pt13">
                            <i className="mainsp galleryCamera"></i>
                        </div>
                    </div>
            </div>
        }

        var actionIcon
        if(this.props.picData.action) {
            actionIcon= <div className="posabs fullwid vpro_40PerTop">
                <div className="disptbl">
                    <div className="dispcell txtc">
                        <div id="label1"  onClick={(e) => this.requestPhoto(e)} className="white fontthin f18 lh30 dispbl txtc bgTransGrey srp_pad1">
                            {this.props.picData.label}
                        </div>
                    </div>
                </div>
            </div>
        } else if(this.props.picData.label) {
            actionIcon = <div className="posabs fullwid vpro_40PerTop">
                <div className="disptbl">
                    <div className="dispcell txtc">
                        <div id="label1"  className="white fontthin f18 lh30 dispbl txtc bgTransGrey srp_pad1">
                            {this.props.picData.label}
                        </div>
                    </div>
                </div>
            </div>
        }

        var verificationView;
        if(this.props.verification_status == 1)
        {
            verificationView =  <div className="posabs srp_pos3 searchNavigation showDetails" id="id1">
                <div id="album1">
                    <div className="bg13 opa50 txtc white opa70 fontreg crBoxIcon">
                        <div className="pt8" onClick={(e) => this.showVerification(e)}>
                            <i className="mainsp verified"></i>
                        </div>
                    </div>
                </div>
            </div>
        }

        var loaderView = "";
        if(this.state.showLoader)
        {
            loaderView = <Loader show="div"></Loader>;
        }

        var verifyLayer;
        if(this.state.showVerified == true) {
            verifyLayer = <VerifiedVisit closeOverlay={()=>this.closeOverlay()} profilechecksum={this.props.profilechecksum}></VerifiedVisit>
        }


        return (
            <div id="PhotoView" className="posrel">
                {errorView}
                {verifyLayer}
                <div id="picContent">
                    <img id="profilePic" onError={() => this.handleImageError()} onLoad={() => this.handleImageLoaded()} className="vpro_w100Per" src={this.props.picData.url} />
                    <div className="posabs fullwid vpro_40PerTop fullheight txtc">
                        {loaderView}
                    </div>
                </div>
                {galleryIcon}
                {verificationView}
                {actionIcon}
            </div>
        );
    }
}
const mapStateToProps = (state) => {
    return{
       photoAction: state.AlbumReducer.photoAction
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        doPhotoAction: (profilechecksum) => {
            let call_url = "/api/v1/social/requestPhoto?profilechecksum="+profilechecksum;
            commonApiCall(call_url,{},'PHOTO_ACTION','GET',dispatch,false);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(PhotoView)
