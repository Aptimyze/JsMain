require ('../style/forgot.css')
import React from "react";
import { connect } from "react-redux";
import TopError from "../../common/components/TopError"
import {validateInput,validateEmail} from "../../common/components/commonValidations"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import Loader from "../../common/components/Loader";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import * as CONSTANTS from '../../common/constants/apiConstants';
import MetaTagComponents from '../../common/components/MetaTagComponents';
import GA from "../../common/components/GA";
import {Link} from "react-router-dom";

class ForgotPassword extends React.Component {

    constructor(props) {
        super();
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false
        };
    }

    componentDidMount() {
        document.getElementById("ForgotPassword").style.height = window.innerHeight+"px";
    }

    componentWillReceiveProps(nextProps) {
        this.setState({
            showLoader:false
        });
        if(nextProps.forgotData.responseStatusCode == "0") {
            document.getElementById("textChange").innerHTML = "<div class='f19 r_f1 fullwid fontthin color20 f19'>"+nextProps.forgotData.responseMessage+"</div>";
            document.getElementById("sendLinkParent").innerHTML = "<a href='/' class='dispbl lh50 txtc white'>Continue</a>";
            document.getElementById("cancelBtn").classList.add("dn");
            document.getElementById("header").classList.remove("wid60p");
            document.getElementById("header").classList.add("fullwid");
        } else {
            this.showError(nextProps.forgotData.responseMessage)
        } 
    }

    initForgot() {
        let inpText = document.getElementById("useremail").value;
        let isd;
        if(inpText.length == 0){
            this.showError(ErrorConstantsMapping("EnterEmailnPass"));
        }
        else if(validateEmail(inpText) == false && validateInput("phone",inpText) ==  false) {
            this.showError(ErrorConstantsMapping("ValidEmailnPass"));
        } else {
            this.setState({
                showLoader:true
            });
            if(validateEmail(inpText)) {
                this.props.sendPassLink(inpText); 
            } else {
                if(inpText.indexOf("-") != -1) {
                    isd= inpText.split("-")[0];
                    inpText = inpText.split("-")[1];
                    if(isd.indexOf("+") != -1) {
                        isd = isd.split("+")[1];
                    }    
                }

                this.props.sendPassLink(inpText,isd);
            }  
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

    render() {
        var errorView;
        if(this.state.insertError)
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }

        var loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="page"></Loader>;
        }

        return (
            <div id="ForgotPassword" className="outerdiv bg4">
                {loaderView}
                {errorView}
                <div id="overlayHead" className="fullwid bg1">
                    <div className="headingPad clearfix white">
                        <Link to={"/"}>
                            <div id="cancelBtn" className="fl f14 fontlig wid20p txtl pt6 white">
                                Cancel
                            </div>
                        </Link>
                        <div id="header" className="fl fontthin f19 wid60p txtc">
                            Forgot Password
                        </div>
                    </div>
                </div>  
                <div id="textChange" className="headingPad frm_ele">
                    <textarea id="useremail" name="in_field" className="fullwid f19 r_f1 fontthin color20" placeholder="Enter your registered Email ID or Primary Mobile Number">
                    </textarea>
                </div>  
                <div id="sendLinkParent" className="posfix btmo fullwid bg7">
                    <div onClick={() => this.initForgot()}  id="sendLink" className="dispbl lh50 txtc white">
                        Email/Sms Link To Reset
                    </div>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return{
       forgotData: state.ForgotPasswordReducer.forgotData,
    }
}


const mapDispatchToProps = (dispatch) => {
    return{
        sendPassLink: (inpText,isd) => {
            let call_url;
            if(isd == undefined) {
                call_url = "/api/v1/api/forgotlogin?email="+inpText+"&phone="+inpText;   
            } else {
                call_url = "/api/v1/api/forgotlogin?email="+inpText+"&phone="+inpText+"&isd="+isd; 
            }
            dispatch(commonApiCall(call_url,{},'SEND_FORGOT_LINK','POST'));
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(ForgotPassword)
