import React from "react";
import {Link} from "react-router-dom";
import {connect} from "react-redux";
import Loader from "../components/Loader";
import {getCookie} from '../components/CookieHelper';
import {commonApiCall} from "../components/ApiResponseHandler.js";
import TopError from "../components/TopError";
import VerifiedVisit from "../../verifiedVisit/containers/verifiedVisit"
import * as CONSTANTS from '../../common/constants/apiConstants';
import {removeProfileLocalStorage,getProfileKeyLocalStorage} from "../../common/components/CacheHelper";


export class PhotoView extends React.Component {
    constructor(props) {
        super();
        let verificationCount = props.verification_status;
        if(props.verification_status == 3)
        {
            verificationCount = 2;
        }
        else if(props.verification_status)
        {
            verificationCount = 1;
        }
        this.state = {
            showLoader: false,
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showVerified: false,
            picLabel : props.picData ? props.picData.label :"",
            picUrl : props.picData.url ? props.picData.url : "",

            verificationCount:verificationCount,
            isVideoPresent: props.isVideoPresent
        };
    }
    componentWillReceiveProps(nextProps) {
    }

    componentDidMount(){
        this.state.isVideoPresent?this.handleImageLoaded():()=>{};
    }

setResponse(response){
        let _this = this;
        if(response.responseMessage == 'Successful'){
//            response.imageButtonDetail = {label : nextProps.photoAction.imageButtonDetail.label};
            localStorage.removeItem('currentDataUrl');
            localStorage.removeItem('currentData');
        }
        this.setState({
            showLoader:false
        });
        if(this.props.picData.label) {
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
                    this.setState({picLabel:response.imageButtonDetail.label});
                }
            }
        }
    }

    requestPhoto(e) {
        e.preventDefault();
        if(this.props.picData.label == this.state.picLabel) {
            if(getCookie("AUTHCHECKSUM")) {
                this.setState({
                    showLoader:true
                });
                e.target.classList.add("dn");
                this.doPhotoAction(this.props.profilechecksum,e.target);
            } else {
                this.doPhotoAction(this.props.profilechecksum,e.target);
                // e.target.innerHTML = "Please Login to Continue"
            }
        }

    }
    showVerification(e) {
        e.preventDefault();
        var verifyLayer;
        verifyLayer = <VerifiedVisit   profilechecksum={this.props.profilechecksum} verification_status={this.props.verification_status} listingId={this.props.listingId}></VerifiedVisit>;
        this.props.setParentLayer(verifyLayer);
        this.props.historyObject.push(()=>{this.props.setParentLayer(<div></div>);return true;},"#vv");
    }
    handleImageLoaded() {
        (this.props.imageLoaded || this.state.isVideoPresent) ? this.props.imageLoaded() : ()=>{};
    }
    handleImageError(e) {
      this.setState({picUrl : this.props.defaultPhoto,isVideoPresent:false});
    }

    render() {
        var errorView;
        if(this.state.insertError)
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }

        var galleryIcon;
        if(!(this.state.isVideoPresent) &&this.props.picData.url && this.props.picData.pic_count) {
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

        var actionIcon = '';
        if(!(this.state.isVideoPresent)){
        if(this.props.picData.action) {
            actionIcon= <div className="posabs fullwid vpro_40PerTop">
                <div className="disptbl">
                    <div className="dispcell txtc">
                        <div   onClick={(e) => this.requestPhoto(e)} className="white fontthin f18 lh30 dispbl txtc bgTransGrey srp_pad1">
                            {this.state.picLabel}
                        </div>
                    </div>
                </div>
            </div>
        } else if(this.props.picData.label) {
            actionIcon = <div className="posabs fullwid vpro_40PerTop">
                <div className="disptbl">
                    <div className="dispcell txtc">
                        <div  className="white fontthin f18 lh30 dispbl txtc bgTransGrey srp_pad1">
                            {this.state.picLabel}
                        </div>
                    </div>
                </div>
            </div>
        }
    }

        var verificationView;
        if(!(this.state.isVideoPresent) && this.props.verification_status)
        {
            verificationView =  <div className="posabs srp_pos3 searchNavigation showDetails" id="id1">
                <div id="album1">
                <div className="posabs outerAlbumIcon">
                <div className="bg4 txtc disptbl crBoxCount">
                <div className="f14 color6 dispcell vertmid">{this.state.verificationCount}</div>
                </div>
                </div>
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

        // let blurClass = ((getCookie("AUTHCHECKSUM") == false && this.props.picData.pic_count > 0 ) ?
        //        "vpro_w100Per filterBlur" :
         
        //        "vpro_w100Per");
        

        return (

            

            <div id="PhotoView" className="posrel">
                {errorView}
                <div id="picContent" style={{"height":this.state.isVideoPresent?202:window.innerWidth, "width":window.innerWidth}}>
                    {this.state.isVideoPresent? 
                <video style={{"height":202, "width":window.innerWidth}} onLoad={() => this.handleImageLoaded()}  controls  onError={(e) => this.handleImageError(e)} controlsList="nodownload" poster="/images/videoPreview.jpg">
                <source src={(this.props.video_url.indexOf('http://')==-1)?("http://"+this.props.video_url ): this.props.video_url}/>
              </video>
                        :<img id="profilePic" style={{"minHeight":window.innerWidth}} onError={() => this.handleImageError()} onLoad={() => this.handleImageLoaded()} className="vpro_w100Per" src={this.state.picUrl} />}


                        {this.state.showLoader && <div className="posabs fullwid vpro_40PerTop fullheight txtc">
                        {loaderView}
                    </div>}
                    
                </div>
                {galleryIcon}
                {verificationView}
                {actionIcon}
            </div>
        );
    }
        doPhotoAction(profilechecksum,trgt){
            let call_url = "/api/v1/social/requestPhoto?profileChecksum="+profilechecksum;
            commonApiCall(call_url,{}).then((response)=>{
                  this.setResponse(response);
                  trgt.classList.remove("dn");
                  removeProfileLocalStorage(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY,this.props.currentApi);
            });
        }


}


const mapStateToProps = (state) => {
    return{
       historyObject : state.historyReducer.historyObject,
    }
}

const mapDispatchToProps = (dispatch) => {
    return{

      }
}

export default connect(mapStateToProps,mapDispatchToProps)(PhotoView)
