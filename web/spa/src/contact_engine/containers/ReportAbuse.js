require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import * as CONSTANTS from '../../common/constants/apiConstants';
let API_SERVER_CONSTANTS = require ('../../common/constants/apiServerConstants');
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import Loader from "../../common/components/Loader";
import {getCookie} from '../../common/components/CookieHelper';
import {$i} from '../../common/components/commonFunctions';



export default class ReportAbuse extends React.Component{

    constructor(props){
        super();
        this.state = {
            selectOption: "",
            selectText: "",
            insertError: false,
            errorMessage: "",
            showLoader : false,
            timeToHide: 3000,
            tupleDim : {'width' : window.innerWidth,'height': window.innerHeight},
            fileArray :[],
            srcImage:{}
        }
        if(!getCookie("AUTHCHECKSUM")){
          window.location.href="/login?prevUrl=/myjs";
          return;
        }
        this.arrReportAbuseFiles = [];
        this.bUploadAttachmentInProgress = false;
        this.bUploadingDone = false;
        this.MAX_FILE_SIZE_IN_MB = 6;

    }

    componentDidMount(){
      //  $i("reportAbuseMidDiv").style.height = (window.innerHeight - 50)+"px";
      let topHeadHgt, bottomBtnHeight;
      topHeadHgt = $i('reportAbustop').clientHeight;
      bottomBtnHeight =$i('reportAbusbtm').clientHeight;
      $i('js-reportAbuseMainScreen').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
    //  $i('reportAbuseScreen2').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
    }

    closeAbuseLayer() {
        this.props.closeAbuseLayer();
    }
    componentDidUpdate(){
      let _this=this;
      this.state.fileArray.map((fileObject,index)=>{

              var reader = new FileReader();
              reader.onload = (e)=> { $i("RA_fileImage_"+index).src=e.target.result;};
              reader.readAsDataURL(fileObject);

      });

    }
    listSelected(e) {
        let ul = $i("abuseList");

        let items = ul.getElementsByTagName("li");

        for (let i = 0; i < items.length; i++)
        {
          items[i].getElementsByTagName("i")[0].classList.add("dn");
        }
        e.target.getElementsByTagName("i")[0].classList.remove("dn");
        this.setState({
            selectOption: e.target.id,
            selectText: e.target.innerText
        })
          if (e.target.id != "opt9" && e.target.id != "opt7")
          {
            setTimeout(function(){
              $i("reportAbuseMidDiv").classList.add("ce_rptabu_d");
              //  $i("reportAbuseScreen2").classList.add("animateLeftSlow");
              //  $i("reportAbuseMidDiv").classList.add("dn");
            },300);
          }

    }
    showError(inputString) {
        let _this = this;
        this.setState ({
                insertError : true,
                errorMessage : inputString
        })
        setTimeout(function(){
            _this.setState ({
                insertError : false,
                errorMessage : ""
            })
        }, this.state.timeToHide+100);
    }

  submitAbuse() {
    if(this.state.selectOption == "") {
        this.showError(ErrorConstantsMapping("SelectReason"));
    } else if($i("detailReasonsLayer").value.trim().length < 25 && this.state.selectOption != "opt7" && this.state.selectOption != "opt9") {
        this.showError(ErrorConstantsMapping("enterComments"));
    } else {

      var bUploadSuccessFul = false;
      if(this.arrReportAbuseFiles.length) {
        this.setState({
          showLoader : true
        });
          if(!this.uploadingDone)
          {
            this.uploadAttachment();
            return;
          }

      }

        let reason = $i("detailReasonsLayer").value.trim();
        let mainReason = this.state.selectText;

        // let feed = {};
        // var category = 'Abuse';
        // var mainReason = mainReason;
        let message = this.props.username+' has been reported abuse by '+localStorage.getItem('USERNAME')+' with the following reason:'+reason;
        let profilechecksum = this.props.profilechecksum;

        let _this = this;

        let postData = '?feed[category]=Abuse&feed[mainReason]='+mainReason+'&feed[message]='+message+'&CMDSubmit=1&profilechecksum='+profilechecksum+'&reason='+reason+'&feed[attachment]=1&feed[temp_attachment_id]='+ this.tempAttachmentId;
        _this.setState({
          showLoader : true
        });
        commonApiCall(API_SERVER_CONSTANTS.API_SERVER +  CONSTANTS.ABUSE_FEEDBACK_API + postData,{},'','').then((response)=>{
          _this.setState({
            showLoader : false
          });
          if(response.blockedOnAbuse)
            this.props.setBlockButton();
            _this.showError(response.message);
            setTimeout(function(){
            _this.closeAbuseLayer();
          }, this.state.timeToHide+200);
        });
    }
  }

  render(){
    let errorView,topviewAbuserLayer,abusiveListLayer,AbusiveButtonLayer;

      let abuseList = ["One or more of Profile Details are incorrect","Photo on profile doesn't belong to the person","User is using abusive/indecent language"," User is stalking me with messages/calls","User is asking for money","User has no intent to marry","User is already married / engaged","User is not picking up phone calls","Person on Phone denied owning this profile","User's phone is switched off/not reachable","User's phone is invalid","Other reasons (please specify)"];


    topviewAbuserLayer =   <div className="pad16 ce_bdr1 hgt85" id="reportAbustop">
          <div className="posrel fullwid ">
              <img id="photoReportAbuse" className="srp_box3 fl dispibl" src={this.props.profileThumbNailUrl} />
              <div className="white fontthin f19 txtc dispibl wid70p pt20">Report Abuse</div>
              <i onClick={() => this.closeAbuseLayer()} className="mainsp com_cross mar200 fr"></i>
          </div>
      </div>

    abusiveListLayer =   <div id="reportAbuseMidDiv" className="ce_rptabu_c">
                            <div className="flowauto reportAbuseScreen clearfix" id="js-reportAbuseMainScreen">
                                <i className="mainsp ce_arow_new fl"></i>
                                <div className="fl wid88p fontthin">
                                    <div className="white fullwid dispibl dashedBorder pad18">Let Jeevansathi know what is wrong with this profile. </div>
                                    <ul className="f16 fontthin white mb70" id="abuseList">
                                        {abuseList.map(function(name, index){
                                            return <li key={index} className="reportAbuseOption dispibl dashedBorder pad18 fullwid">
                                                <div onClick={(e) => this.listSelected(e)} id={"opt"+index} className="fullwid posrel abuseLi">
                                                    {name}
                                                    <i className="RAcorrectImg vpro_sprite ce_abu_tick dn"></i>
                                                </div>
                                            </li>;
                                        },this)}
                                    </ul>
                                </div>
                            </div>
                            <div id="reportAbuseScreen2" className="reportAbuseScreen">
                                <textarea className="pad18 fullheight bgTrans fullwid f18 fontthin" id="detailReasonsLayer" placeholder="Please elaborate further in your own words about the issue. Please be as detailed as possible...."></textarea>
                            </div>
                            <div id="attachDiv" style={{overflow: 'auto',right:'0px',width: (window.innerWidth)+'px','borderTop':'1px solid #cbc9c9',maxHeight:Math.round(window.innerHeight/2.5) + 'px'}} className="brdr23 white posabs btmo fullwid pad3">

                            <div id="attachTitle" onClick={this.attachAbuseDocument.bind(this)}>
                                <i className="reportIcon atachIcon"></i>
                                <span>Attach Screenshot</span>
                            </div>

                            <div id="photoDiv">
                              {this.getPhotoPreview()}
                            </div>

                        </div>
                      <div style={{display:'none'}}>
                          {this.state.fileInput}
                      </div>
                         </div>;

    AbusiveButtonLayer = <div className="fullwid posfix btm0" id="reportAbusbtm">
        <div onClick={() => this.submitAbuse()} id="reportAbuseSubmit" className="bg7 white lh30 fullwid dispbl txtc lh50">Report Abuse</div>
    </div>


    if(this.state.insertError == true)
    {
        errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
    }
    let loaderView;
    if(this.state.showLoader)
    {
      loaderView = <Loader show="page"></Loader>;
    }


    return(
      <div className="posabs ce-bg ce_top1 ce_z101 scrollhid" style={this.state.tupleDim}>
        <a href="#"  className="ce_overlay ce_z102" > </a>
        <div className="posabs ce_z103 ce_top1 fullwid" style={this.state.tupleDim}>
          <div id="reportAbuseContainer">
            {loaderView}
            {errorView}
            {topviewAbuserLayer}
            {abusiveListLayer}
            {AbusiveButtonLayer}

          </div>
        </div>
      </div>

    );
  }

  /**
   *
   */
  attachAbuseDocument(event) {
      let newInput = (<input onChange={this.onFileChange.bind(this)} id="file" type="file" accept=".jpg,.bmp,.jpeg,.gif,.png" multiple="multiple"/>);
      this.setState({fileInput : newInput},()=>{      $i("file").click();});
}

onCrossClick(event)  {
    var result = [];
    var self = event.target.parentNode.parentNode;
    let _this=this;
    for(var itr = 0; itr < this.arrReportAbuseFiles.length; itr++) {

        if(this.arrReportAbuseFiles[itr].myId == event.target.id) {
            continue;
        }

        result.push(this.arrReportAbuseFiles[itr]);
    }
    this.arrReportAbuseFiles = result;
    this.setState({fileArray:result});
}


      /**
       *
       */
      getPhotoPreview () {
          /**
           *  <div class="photoEach txtc pad3">
                  <i class="reportIcon closeIcon crossPosition"></i>
                  <img width="80%" height="100px" src="<IMG PATH>" />
                  <div class="f12 white mt5">
                  image_name.jpg
                  </div>
              </div>
           */
           return this.state.fileArray.map((fileObject, index)=>
           {
          fileObject.myId = "RAAttach_"+index;
          var previewDom = (<div key={index} className="photoEach txtc pad3">
            <i id={fileObject.myId} className = "reportIcon closeIcon crossPosition" onClick = {this.onCrossClick.bind(this)} />
            <img id={"RA_fileImage_"+index}  width = "80%" height = "100px"/>;
            <div className = "f12 white mt5">{fileObject.name}</div>
          </div>);
          return previewDom;
        });
      }

      /**
       *
       */
      onFileChange(event){
          let files = event.target.files;
          var existingLength = this.arrReportAbuseFiles.length;
          var validFileTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
          let oldFiles = this.arrReportAbuseFiles.slice();
          //loop on files .. do basic checks like size, type

            for (var key in files) {
              let file = files[key];
                if (files.hasOwnProperty(key)) {

                  if( ( file.size / 1048576 ).toFixed(1) > this.MAX_FILE_SIZE_IN_MB ) {
                      this.showError(file.name + ' You can attach a proof less than 6 MB in size');
                      return ;
                  }

                  if( validFileTypes.indexOf(file.type) == -1 ) {
                      this.showError(file.name + ' Invalid type of attachment');
                      return;
                  }

                  oldFiles.push(file);
                  if( oldFiles.length > 5 ) {
                      this.showError('You can attach maximum 5 proofs');
                      return;
                  }

                }
            }

          this.arrReportAbuseFiles = oldFiles;

          if(this.arrReportAbuseFiles.length == 0) {
              this.showError('No valid attachments');
              return ;
          }
          let fileArray = [];

          this.arrReportAbuseFiles.map( (file,index)=> {
              if(file.hasOwnProperty('preview') === false) {
              }
              file.preview = true;
              fileArray.push(file);
          });
          this.setState({fileArray: fileArray});
      }


  /**
   *
   */
   SendAjax (fileIndex, temp_attachment_id) {
       var apiUrl = "/api/v1/faq/abuseAttachment";
       var formData = new FormData();
       let fileObject = this.state.fileArray[fileIndex];
       formData.append("feed[attachment_1]", fileObject);
       let _this = this;
       if( ( ( typeof temp_attachment_id == "string" && temp_attachment_id.length ) || typeof temp_attachment_id == "number" ) &&
             isNaN( temp_attachment_id ) == false
               ) {
           formData.append("feed[attachment_id]", temp_attachment_id);
       }
       commonApiCall(API_SERVER_CONSTANTS.API_SERVER +  apiUrl   ,formData,'','','','','',{'Content-Type': 'multipart/form-data'}).then((response)=>{
                           if(response.responseStatusCode == 0) {
                              if(file.hasOwnProperty('error')) {
                                  delete file.error;
                              }
                              _this.tempAttachmentId = response.attachment_id;
                              fileObject.uploaded = true;
                              if(!_this.checkForAttachments()) _this.SendAjax(fileIndex+1,_this.tempAttachmentId) ;
                           } else {
                               _this.showError(  response.message );
                               _this.tempAttachmentId =null;
                           }
                       });
           // error   :  function ( response ) {
           //                 $("#contactLoader,#loaderOverlay").hide();
           //                 fileObject.error = true;
           //                 ShowTopDownError( [ "Something went wrong. Please try again" ], 2000 );
           //             },

}

checkForAttachments(){
    let done = true;
    this.arrReportAbuseFiles.map((file)=>{
        if(!file.uploaded) done=false;

    });
    if(done)
    {
      this.uploadingDone = true;
      $i("reportAbuseSubmit").click();
    }
    return done;
}
  uploadAttachment()
  {
    let _this=this;
    if(0 == this.arrReportAbuseFiles.length) {
        return true;
    }
    var tempId = ((typeof this.tempAttachmentId == "undefined") || !this.tempAttachmentId) ? "" : this.tempAttachmentId ;
    this.SendAjax( 0, tempId );
    return true;


}
}
