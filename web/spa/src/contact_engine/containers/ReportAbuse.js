require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import * as API_SERVER_CONSTANTS from '../../common/constants/apiServerConstants'
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import axios from "axios";
import Loader from "../../common/components/Loader";



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
            tupleDim : {'width' : window.innerWidth,'height': window.innerHeight}
        }
    }

    componentDidMount(){
      //  document.getElementById("reportAbuseMidDiv").style.height = (window.innerHeight - 50)+"px";
      let topHeadHgt, bottomBtnHeight;
      topHeadHgt = document.getElementById('reportAbustop').clientHeight;
      bottomBtnHeight =document.getElementById('reportAbusbtm').clientHeight;
      document.getElementById('js-reportAbuseMainScreen').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
    //  document.getElementById('reportAbuseScreen2').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
    }

    closeAbuseLayer() {
        this.props.closeAbuseLayer();
    }

    listSelected(e) {
        e.target.getElementsByTagName("i")[0].classList.remove("dn");
        console.log(e.target.innerText)
        this.setState({
            selectOption: e.target.id,
            selectText: e.target.innerText
        })
        setTimeout(function(){
          document.getElementById("reportAbuseMidDiv").classList.add("ce_rptabu_d");
          //  document.getElementById("reportAbuseScreen2").classList.add("animateLeftSlow");
          //  document.getElementById("reportAbuseMidDiv").classList.add("dn");
        },300);

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
    } else if(document.getElementById("detailReasonsLayer").value.trim() == "") {
        this.showError(ErrorConstantsMapping("enterComments"));
    } else {

        let reason = document.getElementById("detailReasonsLayer").value.trim();
        let mainReason = this.state.selectText;

        // let feed = {};
        // var category = 'Abuse';
        // var mainReason = mainReason;
        let message = this.props.username+' has been reported abuse by '+localStorage.getItem('USERNAME')+' with the following reason:'+reason;
        let profilechecksum = this.props.profilechecksum;

        let _this = this;

        let postData = '?feed[category]=Abuse&feed[mainReason]='+mainReason+'&feed[message]='+message+'&CMDSubmit=1&profilechecksum='+profilechecksum+'&reason='+reason;
        _this.setState({
          showLoader : true
        });
        axios({
        method: 'POST',
        url: API_SERVER_CONSTANTS.API_SERVER +  CONSTANTS.ABUSE_FEEDBACK_API + postData,
        data: {},
        headers: {
          'Accept': 'application/json',
          'withCredentials':true,
          'X-Requested-By': 'jeevansathi',
          'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
        },
      }).then( (response) => {
          _this.setState({
            showLoader : false
          });
            _this.showError(response.data.message);
            setTimeout(function(){
            _this.closeAbuseLayer();
          }, this.state.timeToHide+200);
        })
        .catch( (error) => {
          console.warn('Actions - fetchJobs - recreived error: ', error)
        })
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
                                    <ul className="f16 fontthin white mb70">
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
      <div className="posabs ce-bg ce_top1 ce_z101" style={this.state.tupleDim}>
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

}
