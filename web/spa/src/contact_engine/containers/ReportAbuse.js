require('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import * as CONSTANTS from '../../common/constants/apiConstants';
let API_SERVER_CONSTANTS = require('../../common/constants/apiServerConstants');
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import Loader from "../../common/components/Loader";
import { getCookie } from '../../common/components/CookieHelper';
import { $i } from '../../common/components/commonFunctions';


export default class ReportAbuse extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            selectOption: "",
            selectText: "",
            insertError: false,
            errorMessage: "",
            showLoader: false,
            timeToHide: 3000,
            tupleDim: { 'width': window.innerWidth, 'height': window.innerHeight },
            fileArray: [],
            srcImage: {},
            headerData: 'Report your bad experience with profile id ' + this.props.username,
            charCount: 0,
            showReportAbusePopUp: false,
            reportAbuseList: '',
            windowHeight: '',
            username: this.props.username,
        }
        this.attachAbuseDocument = this.attachAbuseDocument.bind(this);
        this.updateTextAreaClass = this.updateTextAreaClass.bind(this);
        if (!getCookie("AUTHCHECKSUM")) {
            window.location.href = "/login?prevUrl=/myjs";
            return;
        }
        this.arrReportAbuseFiles = [];
        this.bUploadAttachmentInProgress = false;
        this.bUploadingDone = false;
        this.MAX_FILE_SIZE_IN_MB = 6;

    }

    componentDidMount() {
        this.setState({windowHeight:window.innerHeight});
        window.addEventListener('resize',this.updateTextAreaClass);
        let topHeadHgt, bottomBtnHeight;
        topHeadHgt = $i('reportAbustop').clientHeight;
        bottomBtnHeight = $i('reportAbusbtm').clientHeight;
       

        $i('js-reportAbuseMainScreen').style.height = window.innerHeight - (topHeadHgt + bottomBtnHeight) + "px";
        this.setState({
            showLoader: true
        });
        commonApiCall(CONSTANTS.REPORT_ABUSE, {}, '', '').then((response) => {
            this.setState({
                showLoader: false
            });
            if (response.reportAbuseList) {
                this.setState({ reportAbuseList: response.reportAbuseList, showLoader: false })
            }
        });
    }
    componentWillUnmount(){
        window.removeEventListener('resize',this.updateTextAreaClass);
    }

    closeAbuseLayer() {
        setTimeout(()=>this.props.closeAbuseLayer(),1);
        
    }
    componentDidUpdate() {
        let _this = this;
        this.state.fileArray.map((fileObject, index) => {

            var reader = new FileReader();
            reader.onload = (e) => { $i("RA_fileImage_" + index).src = e.target.result; };
            reader.readAsDataURL(fileObject);

        });

    }

    updateTextAreaClass(){            
                    if (window.innerHeight < this.state.windowHeight) {
                        $i("detailReasonsLayer").classList.add("posfix");
                        $i("detailReasonsLayer").classList.add("topzero");
                        $i("detailReasonsLayer").classList.remove("bgTrans");
                        $i("detailReasonsLayer").classList.add("bg13");
                        $i("detailReasonsLayer").classList.add("wid50p");
                        $i("textAreaConainer").classList.add("ht120");
                        
                    }
                    else {
                        $i("detailReasonsLayer").classList.remove("wid50p");
                        $i("detailReasonsLayer").classList.remove("posfix");
                        $i("detailReasonsLayer").classList.remove("topzero");
                        $i("detailReasonsLayer").classList.remove("bg13");
                        $i("detailReasonsLayer").classList.add("bgTrans");
                    }
                    this.setState({ windowHeight: window.innerHeight })
    }
    
   
    listSelected(e) {
        let ul = $i("abuseList");

        let items = ul.getElementsByTagName("li");

        let radiobtn = e.currentTarget.children[0];
        radiobtn.checked = true;

        this.setState({
            selectOption: e.currentTarget.children[1].id,
            selectText: e.currentTarget.children[1].innerText
        })


    }
    nextClick() {
        let username = this.state.username;
        if (this.state.selectOption == "") {
            this.showError(ErrorConstantsMapping("SelectReason"));
        } else {
            setTimeout(()=> {
                $i("reportAbuseMidDiv").classList.add("ce_rptabu_d");
                let topHeadHgt, bottomBtnHeight;
                topHeadHgt = $i('reportAbustop').clientHeight;
                bottomBtnHeight = $i('reportAbusbtm').clientHeight;
                $i('js-reportAbuseMainScreen').style.height = window.innerHeight - (topHeadHgt + bottomBtnHeight) + "px";
                $i("nextButton").classList.add("dn");
                $i("reportAbuseSubmit").classList.remove("dn");
                this.setState({ headerData: username })

            }, 300);

        }

    }
    showError(inputString) {
        let _this = this;
        this.setState({
            insertError: true,
            errorMessage: inputString
        })
        setTimeout(function () {
            _this.setState({
                insertError: false,
                errorMessage: ""
            })
        }, this.state.timeToHide + 100);
    }

    getOverLayDataDisplay() {
        return (
            <div>
                <div className="web_dialog_overlay" ></div>
                <div className="overlay_1_e page transition CancelOverlay top_2 setmid" style={{ marginTop: '190px' }}>
                    <div style={{ position: "relative" }}>
                        <div className="txtc" style={{ padding: "20px" }}>

                            <div className="f14 color3 pt4 fontlig pb30 nl_p10">"You have not attached any screenshots in support. Would you like to upload them before proceeding?</div>
                        </div>
                        <div style={{ borderTop: "1px solid #dbdbdb" }}>
                            <div className="fullwid">
                                <div className="fl txtc pad2 wid49p brdr2">
                                    <div className="fontthin f17 color2" onClick={this.attachAbuseDocument}>YES</div>
                                </div>
                                <div className="fl txtc pad2 wid49p">
                                    <div className="fontthin f17 color2" onClick={(e) => { this.setState({ showReportAbusePopUp: false }, () => this.finalsubmitAbuse()) }}>NO</div>
                                </div>
                                <div className="clr"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }



    finalsubmitAbuse(attParams) {
        var bUploadSuccessFul = false;
        if (this.arrReportAbuseFiles.length) {
            this.setState({
                showLoader: true
            });
            if (!this.uploadingDone) {
                this.uploadAttachment();
                return;
            }
            attParams = '&feed[attachment]=1&feed[temp_attachment_id]=' + this.tempAttachmentId;
        }

        let reason = $i("detailReasonsLayer").value.trim();
        let mainReason = this.state.selectText;
        let mainReasonKey = '';

        for(let i=0;i<this.state.reportAbuseList.length;i++){
            if(mainReason == this.state.reportAbuseList[i].LABEL){
                mainReasonKey = this.state.reportAbuseList[i].ID;
            }
        }
        // console.log('mainReasonKey',mainReasonKey);
        // let feed = {};
        // var category = 'Abuse';
        // var mainReason = mainReason;
        let message = this.props.username + ' has been reported abuse by ' + localStorage.getItem('USERNAME') + ' with the following reason:' + reason;
        let profilechecksum = this.props.profilechecksum;

        let _this = this;

        let postData = '?feed[category]=Abuse&feed[mainReason]=' + mainReasonKey + '&feed[message]=' + message + '&CMDSubmit=1&profilechecksum=' + profilechecksum + '&reason=' + reason + attParams;
        _this.setState({
            showLoader: true
        });
        commonApiCall(API_SERVER_CONSTANTS.API_SERVER + CONSTANTS.ABUSE_FEEDBACK_API + postData, {}, '', '').then((response) => {
            _this.setState({
                showLoader: false
            });
            if(response.blockedOnAbuse)
                this.props.setBlockButton();
            _this.showError(response.message);
            setTimeout(function () {
                _this.closeAbuseLayer();
            }, this.state.timeToHide + 200);
        });

    }

    submitAbuse() {

        let attParams = '';
        if (this.state.selectOption == "") {
            this.showError(ErrorConstantsMapping("SelectReason"));
        } else if ($i("detailReasonsLayer").value.trim().length < 25 && this.state.selectOption != "opt7" && this.state.selectOption != "opt9") {
            this.showError(ErrorConstantsMapping("enterComments"));
        }
        else if (!this.state.fileArray.length) {
            this.setState({ showReportAbusePopUp: true })
        }
        else {

            this.finalsubmitAbuse(attParams);
        }

    }
    render() {
        let errorView, topviewAbuserLayer, abusiveListLayer, AbusiveButtonLayer;
        let layer;
        layer = this.state.showReportAbusePopUp ? this.getOverLayDataDisplay() : '';
        let reportAbuseListArray = [];
        for(let obj in this.state.reportAbuseList){
            reportAbuseListArray.push(this.state.reportAbuseList[obj])
        }

        topviewAbuserLayer = <div className="pad3" id="reportAbustop">
            <div className="posrel fullwid clearfix fontlig">
                <div className="fullwid disptbl">
                    <div className="dispcell">
                        <img id="photoReportAbuse" className="srp_box3_1 fl" src={this.props.profileThumbNailUrl} />
                    </div>
                    <div className="white txtc f16 wid70p fontreg vertmid dispcell">
                        {this.state.headerData}
                    </div>
                    <div className="vertmid dispcell">
                        <i onClick={() => this.closeAbuseLayer()} className="mainsp com_cross fr"></i>
                    </div>

                </div>



            </div>
        </div>

        abusiveListLayer = <div id="reportAbuseMidDiv" className="ce_rptabu_c">
            <div className="brdrhead">
                <div className="flowauto reportAbuseScreen clearfix" id="js-reportAbuseMainScreen">
                    <div className="fl pad2lr pt10">
                        <ul className="f15 fontreg white opa80" id="abuseList">
                            {this.state.reportAbuseList && this.state.reportAbuseList.map(function (name, index) {
                                return <li key={index} className="reportAbuseOption dispibl pad20 fullwid">
                                    <div onClick={(e) => this.listSelected(e)} className="fullwid posrel abuseLi">
                                        <input type="radio" name="radio-group" />
                                        <label style={{ color: "white" }} id={"opt" + index}> {name.LABEL}</label>
                                    </div>
                                </li>;
                            }, this)}
                        </ul>
                    </div>
                </div>
            </div>
            <div id="reportAbuseScreen2" className="reportAbuseScreen">
                <div className="pad18">
                    <div className="white fontreg f13 opa70 fl">
                        Your feedback on </div><br/>
                    <div className="f15 fontreg white pt2">
                        {this.state.selectText}</div>

                </div>
                <div id="textAreaConainer" >
                    <textarea className="white brdrta6 brdrba6 pad18 fontthin f15 bgTrans  ht120 fullwid" id="detailReasonsLayer" placeholder="Write in Detail and attach screenshot if possible"></textarea>
                </div>
            </div>
            <div id="attachDiv" style={{ overflow: 'auto', right: '0px', width: (window.innerWidth) + 'px', maxHeight: Math.round((window.innerHeight / 2.7) - 50) + 'px' }} className="white fullwid pad3">

                <div id="attachTitle" className="opa50" onClick={this.attachAbuseDocument.bind(this)}>
                    <i className="reportIcon atachIcon"></i>
                    <span>Attach Screenshot</span>
                </div>

                <div id="photoDiv">
                    {this.getPhotoPreview()}
                </div>

            </div>
            <div style={{ display: 'none' }}>
                {this.state.fileInput}
            </div>
        </div>;

        AbusiveButtonLayer = <div className="fullwid posfix btm0" id="reportAbusbtm">
            <div onClick={() => this.nextClick()} id="nextButton" className="bg7 white lh30 fullwid dispbl txtc lh50">Next</div>
            <div onClick={() => this.submitAbuse()} id="reportAbuseSubmit" className="bg7 white lh30 fullwid dispbl txtc lh50 dn">Report</div>
        </div>


        if (this.state.insertError == true) {
            errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }
        let loaderView;
        if (this.state.showLoader) {
            loaderView = <Loader show="page"></Loader>;
        }


        return (
            <div className="posfix ce-bg ce_top1 ce_z101 scrollhid" style={this.state.tupleDim}>
                <a href="#" className="ce_overlay ce_z102" > </a>
                <div className="posabs ce_z103 ce_top1 fullwid" style={this.state.tupleDim}>
                    <div id="reportAbuseContainer">
                        {loaderView}
                        {errorView}
                        {topviewAbuserLayer}
                        {abusiveListLayer}
                        {AbusiveButtonLayer}
                        {layer}

                    </div>
                </div>
            </div>

        );
    }

    /**
     *
     */
    attachAbuseDocument(event) {
        let newInput = (<input onChange={this.onFileChange.bind(this)} id="file" type="file" accept=".jpg,.bmp,.jpeg,.gif,.png" multiple="multiple" />);
        this.setState({ fileInput: newInput, showReportAbusePopUp: false }, () => { $i("file").click(); });
    }

    onCrossClick(event) {
        var result = [];
        var self = event.target.parentNode.parentNode;
        let _this = this;
        for (var itr = 0; itr < this.arrReportAbuseFiles.length; itr++) {

            if (this.arrReportAbuseFiles[itr].myId == event.target.id) {
                continue;
            }

            result.push(this.arrReportAbuseFiles[itr]);
        }
        this.arrReportAbuseFiles = result;
        this.setState({ fileArray: result });
    }


    /**
     *
     */
    getPhotoPreview() {
        /**
         *  <div class="photoEach txtc pad3">
                <i class="reportIcon closeIcon crossPosition"></i>
                <img width="80%" height="100px" src="<IMG PATH>" />
                <div class="f12 white mt5">
                image_name.jpg
                </div>
            </div>
         */
        return this.state.fileArray.map((fileObject, index) => {
            fileObject.myId = "RAAttach_" + index;
            var previewDom = (<div key={index} className="photoEach txtc pad3">
                <i id={fileObject.myId} className="reportIcon closeIcon crossPosition" onClick={this.onCrossClick.bind(this)} />
                <img id={"RA_fileImage_" + index} width="80%" height="100px" />
                <div className="f12 white mt5">{fileObject.name}</div>
            </div>);
            return previewDom;
        });
    }

    /**
     *
     */
    onFileChange(event) {
        let files = event.target.files;
        var existingLength = this.arrReportAbuseFiles.length;
        var validFileTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
        let oldFiles = this.arrReportAbuseFiles.slice();
        //loop on files .. do basic checks like size, type

        for (var key in files) {
            let file = files[key];
            if (files.hasOwnProperty(key)) {

                if ((file.size / 1048576).toFixed(1) > this.MAX_FILE_SIZE_IN_MB) {
                    this.showError(file.name + ' You can attach a proof less than 6 MB in size');
                    return;
                }

                if (validFileTypes.indexOf(file.type) == -1) {
                    this.showError(file.name + ' Invalid type of attachment');
                    return;
                }

                oldFiles.push(file);
                if (oldFiles.length > 5) {
                    this.showError('You can attach maximum 5 proofs');
                    return;
                }

            }
        }

        this.arrReportAbuseFiles = oldFiles;

        if (this.arrReportAbuseFiles.length == 0) {
            this.showError('No valid attachments');
            return;
        }
        let fileArray = [];

        this.arrReportAbuseFiles.map((file, index) => {
            if (file.hasOwnProperty('preview') === false) {
            }
            file.preview = true;
            fileArray.push(file);
        });
        this.setState({ fileArray: fileArray });
    }


    /**
     *
     */
    SendAjax(fileIndex, temp_attachment_id) {
        var apiUrl = "/api/v1/faq/abuseAttachment";
        var formData = new FormData();
        let fileObject = this.state.fileArray[fileIndex];
        formData.append("feed[attachment_1]", fileObject);
        let _this = this;
        if (((typeof temp_attachment_id == "string" && temp_attachment_id.length) || typeof temp_attachment_id == "number") &&
            isNaN(temp_attachment_id) == false
        ) {
            formData.append("feed[attachment_id]", temp_attachment_id);
        }
        commonApiCall(API_SERVER_CONSTANTS.API_SERVER + apiUrl, formData, '', '', '', '', '', { 'Content-Type': 'multipart/form-data' }).then((response) => {
            if (response.responseStatusCode == 0) {
                if (file.hasOwnProperty('error')) {
                    delete file.error;
                }
                _this.tempAttachmentId = response.attachment_id;
                fileObject.uploaded = true;
                if (!_this.checkForAttachments()) _this.SendAjax(fileIndex + 1, _this.tempAttachmentId);
            } else {
                _this.showError(response.message);
                _this.tempAttachmentId = null;
            }
        });
        // error   :  function ( response ) {
        //                 $("#contactLoader,#loaderOverlay").hide();
        //                 fileObject.error = true;
        //                 ShowTopDownError( [ "Something went wrong. Please try again" ], 2000 );
        //             },

    }

    checkForAttachments() {
        let done = true;
        this.arrReportAbuseFiles.map((file) => {
            if (!file.uploaded) done = false;

        });
        if (done) {
            this.uploadingDone = true;
            $i("reportAbuseSubmit").click();
        }
        return done;
    }
    uploadAttachment() {
        let _this = this;
        if (0 == this.arrReportAbuseFiles.length) {
            return true;
        }
        var tempId = ((typeof this.tempAttachmentId == "undefined") || !this.tempAttachmentId) ? "" : this.tempAttachmentId;
        this.SendAjax(0, tempId);
        return true;


    }
}
