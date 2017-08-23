require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import axios from "axios";


export default class ReportInvalid extends React.Component{

    constructor(props){
        super();
        this.state = {
            selectValue: "",
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            tupleDim : {'width' : window.innerWidth,'height': window.innerHeight}
        }
    }

    componentDidMount(){
      //  document.getElementById("reportInvalidMidDiv").style.height = (window.innerHeight - 50)+"px";
      let topHeadHgt, bottomBtnHeight;
      topHeadHgt = document.getElementById('reportAbustop').clientHeight;
      bottomBtnHeight =document.getElementById('reportAbusbtm').clientHeight;
      document.getElementById('reportInvalidMidDiv').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
    //  document.getElementById('reportInvalidScreen2').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
    }

    closeInvalidLayer() {
        this.props.closeInvalidLayer();
    }

    listSelected(e) {

        e.target.getElementsByTagName("i")[0].classList.remove("dn");
       
        this.setState({
            selectValue: e.target.attributes.getNamedItem('data-value').value
        })
         if( e.target.id == "opt5")
          {
            setTimeout(function(){
                document.getElementById("reportInvalidMidDiv").classList.add("ce_rptabu_d");
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
            });
            _this.props.closeInvalidLayer();
        }, this.state.timeToHide+100);
    }

  submitInvalid() {

    console.log(this.state.selectValue);
    console.log("reportType",this.props.reportType);
    if(this.state.selectValue == "") {
        this.showError(ErrorConstantsMapping("SelectReason"));
    } else if( this.state.selectValue == "5" && document.getElementById("detailReasonsLayer").value == "") {
        this.showError(ErrorConstantsMapping("EnterReason"));
    } else {
      let otherReasonValue = '';
      if ( this.state.selectValue == "5" )
      {
        otherReasonValue = document.getElementById("detailReasonsLayer").value; 
      }
    
        let profilechecksum = this.props.profilechecksum;

        let _this = this;

        let mobile = 'N';
        let phone = 'N';

        if ( this.props.reportType == 'mobile')
        {
          mobile = 'Y';
        }
        else
        {
          phone = 'Y';
        }

        let postData = '?mobile='+mobile+'&phone='+phone+'&profilechecksum='+profilechecksum+'&reasonCode='+_this.state.selectValue+'&otherReasonValue='+otherReasonValue;

        axios({
        method: 'POST',
        url: CONSTANTS.API_SERVER +  CONSTANTS.CONTACT_ENGINE_API.REPORT_INVALID_API + postData,
        data: {},
        headers: {
          'Accept': 'application/json',
          'withCredentials':true,
          'X-Requested-By': 'jeevansathi',
          'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
        },
      }).then( (response) => {
            _this.showError(response.data.message);
             
        })
        .catch( (error) => {
          console.warn('Actions - fetchJobs - recreived error: ', error)
        })
    }
  }

  render(){
    console.log('report Invalid');
    console.log(this.props);
    let errorView,topviewInvalidrLayer,invalidListLayer,invalidButtonLayer;
    let _this = this;

    let InvalidList = [{"key":6,"text":"The number does not exist "}, {"key":1,"text":"Switched off / Not reachable"}, {"key":2,"text":"Not an account holder's phone"}, {"key":4,"text":"Not picking up "}, {"key":3,"text":"Already married / engaged "}, {"key":5,"text":"Other reasons (please specify)"}];
    

    topviewInvalidrLayer =   <div className="pad16 ce_bdr1 hgt85" id="reportAbustop">
          <div className="posrel fullwid ">
              <img id="photoReportInvalid" className="srp_box3 fl dispibl" src={this.props.profileThumbNailUrl} />
              <div className="white fontthin f19 txtc dispibl wid70p pt20">Report Invalid</div>
              <i onClick={() => this.closeInvalidLayer()} className="mainsp com_cross mar200 fr"></i>
          </div>
      </div>

    invalidListLayer =   <div id="reportInvalidMidDiv" className="flowauto ce_rptabu_c">
                            <div className="reportInvalidScreen clearfix" id="js-reportInvalidMainScreen">
                                <i className="mainsp ce_arow_new fl"></i>
                                <div className="fl wid88p fontthin">
                                    <div className="white fullwid dispibl dashedBorder pad18">Report Invalid</div>
                                    <ul className="f16 fontthin white mb70">
                                        {InvalidList.map(function(name, index){
                                            return <li key={index}  className="reportInvalidOption dispibl dashedBorder pad18 fullwid">
                                                <div onClick={(e) => this.listSelected(e)} data-value ={name.key} id={"opt"+index} className="fullwid posrel InvalidLi">
                                                    {name.text}
                                                    <i className="RAcorrectImg vpro_sprite ce_abu_tick dn"></i>
                                                </div>
                                            </li>;
                                        },this)}
                                    </ul>
                                </div>
                            </div>
                            <div id="reportInvalidScreen2" className="reportInvalidScreen">
                                <textarea className="pad18 fullheight bgTrans fullwid f18 fontthin" id="detailReasonsLayer" placeholder="Describe your concern for this number"></textarea>
                            </div>
                         </div>;

    invalidButtonLayer = <div className="fullwid posfix btm0" id="reportAbusbtm">
        <div onClick={() => this.submitInvalid()} id="reportInvalidSubmit" className="bg7 white lh30 fullwid dispbl txtc lh50">Report Invalid</div>
    </div>


    if(this.state.insertError == true)
    {
        errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
    }


    return(
      <div className="posabs ce-bg ce_top1 ce_z101" style={this.state.tupleDim}>
        <a href="#"  className="ce_overlay ce_z102" > </a>
        <div className="posabs ce_z103 ce_top1 fullwid" style={this.state.tupleDim}>
          <div id="reportInvalidContainer">
            {errorView}
            {topviewInvalidrLayer}
            {invalidListLayer}
            {invalidButtonLayer}

          </div>
        </div>
      </div>

    );
  }

}
