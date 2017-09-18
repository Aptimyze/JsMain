require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import * as CONSTANTS from '../../common/constants/apiConstants';
import * as API_SERVER_CONSTANTS from '../../common/constants/apiServerConstants'
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import axios from "axios";
import Loader from "../../common/components/Loader";



export default class ReportInvalid extends React.Component{

    constructor(props){
        super();
        this.state = {
            selectValue: "",
            insertError: false,
            errorMessage: "",
            showLoader : false,
            timeToHide: 3000,
            tupleDim : {'height': document.getElementById("ProfilePage").clientHeight}
        }
    }

    componentDidMount(){
      console.log("afvabdv",document.getElementById("ProfilePage").clientHeight);
      //  document.getElementById("reportInvalidMidDiv").style.height = (window.innerHeight - 50)+"px";
      let topHeadHgt, bottomBtnHeight;
      topHeadHgt = document.getElementById('reportInvalidtop').clientHeight;
      bottomBtnHeight =document.getElementById('reportInvalidbtm').clientHeight;
      document.getElementById('js-reportInvalidMainScreen').style.height= window.innerHeight - (topHeadHgt+bottomBtnHeight)+"px";
    }

    closeInvalidLayer() {
        this.props.closeInvalidLayer();
    }

    listSelected(e) {

        let ul = document.getElementById("invalidList");

        let items = ul.getElementsByTagName("li");

        for (let i = 0; i < items.length; i++) 
        {
          items[i].getElementsByTagName("i")[0].classList.add("dn");
        }

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
        }, this.state.timeToHide+100);
    }

  submitInvalid() {

    if(this.state.selectValue == "") {
        this.showError(ErrorConstantsMapping("SelectReason"));
    } else if( this.state.selectValue == "5" && document.getElementById("detailReasonsLayer").value.trim() == "") {
        this.showError(ErrorConstantsMapping("EnterReason"));
    } else {
      let otherReasonValue = '';
      if ( this.state.selectValue == "5" )
      {
        otherReasonValue = document.getElementById("detailReasonsLayer").value.trim(); 
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
        _this.setState({
          showLoader : true
        });

        axios({
        method: 'POST',
        url: API_SERVER_CONSTANTS.API_SERVER +  CONSTANTS.CONTACT_ENGINE_API.REPORT_INVALID_API + postData,
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
          setTimeout(function () {
            _this.props.closeInvalidLayer();
          }, this.state.timeToHide+100)
             
        })
        .catch( (error) => {
          console.warn('Actions - fetchJobs - recreived error: ', error)
        })
    }
  }

  render(){
    let errorView,topviewInvalidrLayer,invalidListLayer,invalidButtonLayer;
    let _this = this;

    let InvalidList = [{"key":6,"text":"The number does not exist "}, {"key":1,"text":"Switched off / Not reachable"}, {"key":2,"text":"Not an account holder's phone"}, {"key":4,"text":"Not picking up "}, {"key":3,"text":"Already married / engaged "}, {"key":5,"text":"Other reasons (please specify)"}];
    
    console.log("report invalid this.props",this.props);
    topviewInvalidrLayer =   <div className="pad16 ce_bdr1 hgt85" id="reportInvalidtop">
          <div className="posrel fullwid ">
              <img id="photoReportInvalid" className="srp_box3 fl dispibl" src={this.props.profileThumbNailUrl} />
              <div className="white fontthin f19 txtc dispibl wid70p pt20">Reason for reporting invalid</div>
              <i onClick={() => this.closeInvalidLayer()} className="mainsp com_cross mar200 fr"></i>
          </div>
      </div>

    invalidListLayer =   <div id="reportInvalidMidDiv" className="flowauto ce_rptabu_c">
                            <div className="flowauto reportInvalidScreen clearfix" id="js-reportInvalidMainScreen">
                                <i className="mainsp ce_arow_new fl"></i>
                                <div className="fl wid88p fontthin">
                                    <div className="white fullwid dispibl dashedBorder pad18">Report Invalid</div>
                                    <ul id="invalidList" className="f16 fontthin white mb70">
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

    invalidButtonLayer = <div className="fullwid posfix btm0" id="reportInvalidbtm">
        <div onClick={() => this.submitInvalid()} id="reportInvalidSubmit" className="bg7 white lh30 fullwid dispbl txtc lh50">Report Invalid</div>
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

    console.log("reportInvalidbtm",this.state.tupleDim);
    return(
      <div id="hgkj" className="posabs ce-bg ce_top1 ce_z101 fullwid" style={this.state.tupleDim}>
        <a href="#"  className="ce_overlay ce_z102" > </a>
        <div className="posabs ce_z103 ce_top1 fullwid" style={this.state.tupleDim}>
          <div id="reportInvalidContainer">
            {loaderView}
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
