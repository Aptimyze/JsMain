require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";

export default class ReportAbuse extends React.Component{
  
    constructor(props){
        super();
        this.state = {
            selectOption: "",
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
        }
    }
  
    componentDidMount(){
        document.getElementById("reportAbuseMidDiv").style.height = (window.innerHeight - 50)+"px";
    }
  
    closeAbuseLayer() {
        this.props.closeAbuseLayer();
    }
  
    listSelected(e) {
        e.target.getElementsByTagName("i")[0].classList.remove("dn");
        this.setState({
            selectOption: e.target.id
        })
        setTimeout(function(){
            document.getElementById("reportAbuseScreen2").classList.add("animateLeftSlow");
            document.getElementById("reportAbuseMidDiv").classList.add("dn");
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
    } else if(document.getElementById("detailReasonsLayer").value == "") {
        this.showError(ErrorConstantsMapping("enterComments"));  
    } else {
        console.log("do further")
    }
  }
  
  render(){
    let errorView;
    if(this.state.insertError == true)
    {
        errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
    }

    let abuseList = ["Let Jeevansathi know what is wrong with this profile","One or more of Profile Details are incorrect","Photo on profile doesn't belong to the person","User is using abusive/indecent language"," User is stalking me with messages/calls","User is asking for money","User has no intent to marry","User is already married / engaged","User is not picking up phone calls","Person on Phone denied owning this profile","User's phone is switched off/not reachable","User's phone is invalid","Other reasons (please specify)"];
    return(
        <div className="reportAbuseContainer" id="reportAbuseContainer">
            {errorView}
            <div className="fullwid fontlig">
                <div className="pad16 brdr_new hgt85">
                    <div className="posrel fullwid ">
                        <img id="photoReportAbuse" className="srp_box3 fl dispibl" src={this.props.profileThumbNailUrl} />
                        <div className="white fontthin f19 txtc dispibl margin20">Report Abuse</div>
                        <i onClick={() => this.closeAbuseLayer()} className="mainsp com_cross mar200 fr"></i>
                    </div>
                </div>

                <div id="reportAbuseMidDiv" className="fullwid flowauto">

                    <div className="selectOptions fullheight reportAbuseScreen fl" id="js-reportAbuseMainScreen">
                        <i className="mainsp arow_new fl"></i>
                        <div className="white reportAbuseTitle dashedBorder pad18 dispibl">Let Jeevansathi know what is wrong with this profile
                        </div>

                        <ul className="f16 fontthin white mb70">
                            {abuseList.map(function(name, index){
                                return <li key={index} className="reportAbuseOption dispibl dashedBorder pad3015 fullwid">
                                    <div onClick={(e) => this.listSelected(e)} id={"opt"+index} className="fullwid posrel abuseLi">
                                        {name}
                                        <i className="RAcorrectImg vpro_sprite vpro_correct dn"></i>
                                    </div>
                                </li>;
                            },this)}
                        </ul>
                    </div>
                </div>
                <div id="reportAbuseScreen2" className="posRight100p fullwid fullheight posabs top87">
                    <textarea className="pad18 fullheight bgTrans fullwid f18 fontthin" id="detailReasonsLayer" placeholder="Please elaborate further in your own words about the issue. Please be as detailed as possible...."></textarea>
                </div>
                <div className="posfix fullwid scrollhid pos1_c1">
                    <div onClick={() => this.submitAbuse()} id="reportAbuseSubmit" className="bg7 white lh30 fullwid dispbl txtc lh50">Report Abuse</div>
                </div>
            </div>
        </div>

    ); 
  }
  	
}
