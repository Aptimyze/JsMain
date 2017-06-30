require ('../style/forgot.css')
import React from "react";
import { connect } from "react-redux";
import TopError from "../../common/components/TopError"
import { validateEmail } from "../../common/components/commonValidations"
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

    componentWillReceiveProps(nextProps)
    {

       
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

        return (
            <div id="ForgotPassword" className="outerdiv bg4">
                <div id="overlayHead" className="fullwid bg1">
                    <div className="headingPad clearfix white">
                        <Link to={"/"}>
                            <div className="fl f14 fontlig wid20p txtl pt6 white">
                                Cancel
                            </div>
                        </Link>
                        <div className="fl fontthin f19 wid60p txtc">
                            Forgot Password
                        </div>
                    </div>
                </div>  
                <div className="headingPad frm_ele">
                    <textarea id="useremail" name="in_field" className="fullwid f19 r_f1 fontthin color20" placeholder="Enter your registered Email ID or Primary Mobile Number">
                    </textarea>
                </div>  
                <div className="posfix btmo fullwid bg7">
                    <div id="sendLink" className="dispbl lh50 txtc white">
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
        doLogin: (email,password,g_recaptcha_response,captcha) => {
            /*let call_url = CONSTANTS.LOGIN_CALL_URL+'?email='+email+'&password='+password;
            if ( g_recaptcha_response && captcha )
            {
                call_url += '&g_recaptcha_response='+g_recaptcha_response+'&captcha='+captcha;
            }

            dispatch(commonApiCall(call_url,{},'SET_AUTHCHECKSUM','GET'));*/
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(ForgotPassword)
