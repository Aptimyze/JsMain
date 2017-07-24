require ('../style/contact.css')
import React from "react";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';

export default class ReportAbuse extends React.Component{
  constructor(props){
    super();
  }
  componentDidMount(){
    document.getElementById("reportAbuseMidDiv").style.height = (window.innerHeight - 50)+"px";
  }
  closeAbuseLayer() {
    this.props.closeAbuseLayer();
  }
  render(){
    return(
        <div className="reportAbuseContainer" id="reportAbuseContainer">
          <div className="fullwid fontlig">
            <div className="pad16 brdr_new hgt85">
                <div className="posrel fullwid ">
                    <img id="photoReportAbuse" className="srp_box3 fl dispibl" src="https://mediacdn.jeevansathi.com/6001/18/120038519-1497775059.jpeg" />
                    <div className="white fontthin f19 txtc dispibl margin20">Report Abuse</div>
                    <i onClick={() => this.closeAbuseLayer()} className="mainsp com_cross mar200 fr"></i>
                </div>
            </div>

            <div id="reportAbuseMidDiv" className="fullwid flowauto">

                <div className="selectOptions fullheight reportAbuseScreen fl" id="js-reportAbuseMainScreen">
                    <i className="mainsp arow_new fl"></i>
                    <div className="white reportAbuseTitle dashedBorder pad18 dispibl">Let Jeevansathi know what is wrong with this profile. </div>

                    <ul className="f16 fontthin white">
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt1">
                            <div className="fullwid posrel">
                                One or more of Profile Details are incorrect
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt2">
                            <div className="fullwid posrel">
                                Photo on profile doesn't belong to the person
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt3">
                            <div className="fullwid posrel">
                                User is using abusive/indecent language <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt4">
                            <div className="fullwid posrel">
                                User is stalking me with messages/calls
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt5">
                            <div className="fullwid posrel">
                                User is asking for money
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt6">
                            <div className="fullwid posrel">
                                User has no intent to marry
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt7">
                            <div className="fullwid posrel">
                                User is already married / engaged
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="notOpen">
                            <div className="fullwid posrel">
                                User is not picking up phone calls
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>

                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt9">
                            <div className="fullwid posrel">
                                Person on Phone denied owning this profile
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>

                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="notOpen">
                            <div className="fullwid posrel">
                                User's phone is switched off/not reachable
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>

                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="opt11">
                            <div className="fullwid posrel">
                                User's phone is invalid
                                <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                        <li className="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="js-otherReasons">
                            <div className="fullwid posrel">
                                Other reasons (please specify) <i className="RAcorrectImg dispnone vpro_sprite vpro_correct dn"></i>
                            </div>
                        </li>
                    </ul>
                </div>
                <div className="reportAbuseScreen">
                    <textarea className="dispnone pad18 fullheight fullwid f18 fontthin" id="js-otherReasonsLayer" placeholder="Please elaborate further in your own words about the issue. Please be as detailed as possible...."></textarea>
                </div>
            </div>
            <div className="posfix fullwid scrollhid pos1_c1">
                <div id="reportAbuseSubmit" className="bg7 white lh30 fullwid dispbl txtc lh50">Report Abuse</div>
            </div>
        </div>
    </div>

    ); 
  }
  	
}
