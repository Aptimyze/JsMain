require ('../style/login.css')
import React from "react";
import { connect } from "react-redux";
import TopError from "../../common/components/TopError"
import { validateInput } from "../../common/components/commonValidations"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
import Loader from "../../common/components/Loader";
import AppPromo from "../../common/components/AppPromo";
import { withRouter } from 'react-router';
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import {getCookie,setCookie} from '../../common/components/CookieHelper';
import {SITE_KEY,VERIFY_URL} from "../../common/constants/CaptchConstants";
import {LOGIN_ATTEMPT_COOKIE} from "../../common/constants/CommonConstants";
import * as CONSTANTS from '../../common/constants/apiConstants';
import MetaTagComponents from '../../common/components/MetaTagComponents';
import GA from "../../common/components/GA";
import PropTypes from 'prop-types';
import {Link} from "react-router-dom";
import {recordServerResponse, recordDataReceived,setJsb9Key} from "../../common/components/Jsb9CommonTracking";
import * as jsb9Fun from '../../common/components/Jsb9CommonTracking';
import HamMain from "../../Hamburger/containers/HamMain";
import { getAndroidVersion, getIosVersion} from "../../common/components/commonFunctions";


class LoginPage extends React.Component {

    constructor(props) {
        super();
        jsb9Fun.recordBundleReceived(this,new Date().getTime());
        jsb9Fun.recordDataReceived(this,new Date().getTime());
        jsb9Fun.setJsb9Key(this,'JSNEWMOBLOGINURL');
        jsb9Fun.recordServerResponse(this,'-1');
        this.GAObject = new GA();
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            showPromo: false,
            showCaptchDiv: false,
            showRegisterationMessage: false
        };
        let prevUrl = window.location.href.split("prevUrl=")[1];
        if ( prevUrl )
        {
            prevUrl = prevUrl.replace (/^[a-z]{0,5}\:*\/{0,2}[a-z0-9\.\-]{1,}\:*[0-9]{0,4}.(.*)/, '$1');
            props.history.prevUrl = "/"+prevUrl;
            this.state.showRegisterationMessage = true;
            localStorage.removeItem('lastProfilePageLocation');

        }    
    }
    componentWillMount() {
        if ( document.cookie.indexOf(LOGIN_ATTEMPT_COOKIE) !== -1 )
        {
            this.setState ({
                showCaptchDiv : true
            })
            this.addCaptchaDiv();
        }
    }
    componentDidMount() {
        jsb9Fun.recordDidMount(this,new Date().getTime(),this.props.Jsb9Reducer);
        let _this = this;
        document.getElementById("LoginPage").style.height = window.innerHeight+"px";
        setTimeout(function(){
            _this.setState ({
                showPromo : true
            });
        }, 1200);

        if ( document.cookie.indexOf(LOGIN_ATTEMPT_COOKIE) !== -1 )
        {
            this.setState ({
                showCaptchDiv : true
            })
        }

        if ( this.props.MyProfile.AUTHCHECKSUM && getCookie('AUTHCHECKSUM')) {
            this.props.history.push('/myjs');
       }
       if ( this.state.showCaptchDiv )
       {
           this.addCaptchaDiv();
       }
       _this.GAObject.trackJsEventGA("jsms","new","1");
    }

    componentWillReceiveProps(nextProps)
    {
       if ( nextProps.MyProfile.AUTHCHECKSUM ) {
            //user is logged in, let us delete the profile local storage cache
            try
            {
                localStorage.removeItem(CONSTANTS.PROFILE_LOCAL_STORAGE_KEY);
            }
            catch(e)
            {
                console.log(e);
            }
                
            if ( (this.props.history.prevUrl) && ((this.props.history.prevUrl).indexOf('/login') === -1) && ((this.props.history.prevUrl).indexOf('/spa/dist/index.html') === -1)  )
            {
                this.props.history.push(this.props.history.prevUrl);
            }
            else
            {
                this.props.history.push('/myjs');
            }
       }
       else {

            if ( document.cookie.indexOf(LOGIN_ATTEMPT_COOKIE) !== -1 )
            {
                this.setState ({
                    showCaptchDiv : true
                })
            }

            this.setState ({
                showLoader : false
            })
            if(nextProps.MyProfile.responseMessage) {
                this.showError(nextProps.MyProfile.responseMessage);
            }
       }
    }

    addCaptchaDiv()
    {
        if ( this.state.showCaptchDiv || document.cookie.indexOf(LOGIN_ATTEMPT_COOKIE) !== -1 )
        {
            if ( document.getElementById("showCaptchDivId") != null )
            {
                document.getElementById("showCaptchDivId").innerHTML= '';
            }
            var script = document.createElement("script");
            script.src = "https://www.google.com/recaptcha/api.js";
            script.async = true;
            document.body.appendChild(script);
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

    doLogin() {
        let emailVal = document.getElementById("email").value;
        let passVal = document.getElementById("password").value;
        let g_recaptcha_response;
        let captcha;
        if ( document.getElementById("g-recaptcha-response") )
        {
            g_recaptcha_response = document.getElementById("g-recaptcha-response").value;
            captcha = 1;
        }

        this.GAObject.trackJsEventGA("Login-jsms","Login",this.GAObject.getGenderForGA());
        var validate = validateInput('email',emailVal);
        if(emailVal.length == 0 && passVal.length == 0) {
            this.showError(ErrorConstantsMapping("LoginDetails"));
        } else if(email.length == 0) {
            this.showError(ErrorConstantsMapping("EnterEmail"));
        } else if(validate.responseCode == 1) {
            this.showError(validate.responseMessage);
            document.getElementById("emailErr1").classList.remove("dn");
        } else if(passVal.length == 0) {
	       this.showError(ErrorConstantsMapping("EnterPass"));
        } else {
            this.props.doLogin(this,emailVal,passVal,g_recaptcha_response,captcha,this.addCaptchaDiv.bind(this));
            this.setState ({
                showLoader : true
            })
        }
    }

    handlePasswordChange(e) {
        if(e.target.value.length != 0) {
            document.getElementById("showHide").classList.remove("dn");
        } else {
            document.getElementById("showHide").classList.add("dn");
        }
    }

    handleEmailChange(e) {
        if(e.target.value.length != 0) {
            document.getElementById("emailErr1").classList.add("dn");
        }
    }

    showPass(e) {
        let passElem = document.getElementById("password");
        if(passElem.type == "text") {
            passElem.type = "password";
            e.target.innerText = "Show";
        } else {
            passElem.type = "text";
            e.target.innerText = "Hide";
        }
    }

    componentDidUpdate(prevprops) {
        if(prevprops.location) {
            if(prevprops.location.search.indexOf("ham=1") != -1 && window.location.search.indexOf("ham=1") == -1) {
                this.refs.Hamchild.getWrappedInstance().hideHam();
            }
        }
    }

    showHam()
    {

        if(window.location.search.indexOf("ham=1") == -1) {
            if(window.location.search.indexOf("?") == -1) {
                this.props.history.push(window.location.pathname+"?ham=1");
            } else {
                this.props.history.push(window.location.pathname+window.location.search+"&ham=1");
            }

        }

        this.GAObject.trackJsEventGA("Login-jsms","showHamburger",this.GAObject.getGenderForGA());
        this.refs.Hamchild.getWrappedInstance().openHam();
    }

    removePromoLayer() {
        this.setState ({
            showPromo : false
        });
        document.getElementById("mainContent").classList.remove("ham_b100");
    }

    render() {
        let errorView;
        if(this.state.insertError == true)
        {
          errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
        }

        let loaderView;
        if(this.state.showLoader)
        {
          loaderView = <Loader show="page"></Loader>;
        }

        let promoView;
        if(this.state.showPromo)
        {
            promoView = <AppPromo parentComp="LoginPage" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }
        let appDownloadView;
        if(getAndroidVersion()) {
            appDownloadView = <div id="appLinkAndroid" className="txtc pad2">
                <a href="/static/appredirect?type=androidMobFooter" onClick={()=>this.GAObject.trackJsEventGA("Login-jsms","Download APP Android",this.GAObject.getGenderForGA())} className="f15 white fontlig">Download App | 3MB only</a>
            </div>;
        } else if(getIosVersion()) {
            appDownloadView = <div id="appLinkIos" className="txtc pad2">
                <a href="/static/appredirect?type=iosMobFooter" onClick={()=>this.GAObject.trackJsEventGA("Login-jsms","Download APP IOS",this.GAObject.getGenderForGA())} className="f15 white fontlig">Download App</a>
            </div>;
        }

        let formInput = <div id ="formInput">
                            <div className="fullwid brdr9 brdr10 lgin_inp_pad">
                                <div className="fl padr10 wid8p">
                                    <div className="icons1 uicon"></div>
                                </div>
                                <div className="fl clasone wid80p">
                                    <input  onChange={(e) => this.handleEmailChange(e)} type="email" id="email" className="color9 fullwid fontlig f15" name="email" placeholder="Email" />
                                </div>
                                <div id="emailErr1" className="fl wid10p txtr dn">
                                    <i className="mainsp err2_icon vertmid"></i>
                                </div>
                                <div className="clr"></div>
                            </div>
                            <div className="fullwid brdr10 lgin_inp_pad">
                                <div className="fl padr10 wid8p pt3">
                                    <div className="icons1 key"></div>
                                </div>
                                <div className="fl classNameone wid80p">
                                    <input onChange={(e) => this.handlePasswordChange(e)} type="password" id="password" autoComplete="off" className="color9 fullwid fontlig f15" maxLength="40" name="password" placeholder="Password" />
                                </div>
                                <div id="showHide" onClick={(e) => this.showPass(e)} className="fl f12 white fontlig wid10p txtr dn">
                                    <span id="vertmid">Show</span>
                                </div>
                                <div className="clr"></div>
                            </div>
                        </div>;

        let buttonView = <div id = "buttonView">
                            <div className="posrel scrollhid">
                                <div id="loginButton" className="bg7 fullwid txtc pad2">
                                    <div onClick={() => this.doLogin()} className="white f18 fontlig">Login</div>
                                </div>
                            </div>
                            <div id="afterCaptcha" className="txtc pad12">
                                <Link id="forgotPasswordLink" to={"/static/forgotPassword"} className="white f14 fontlig">
                                    Forgot Password
                                </Link>
                            </div>
                            <div className="bg10 fullwid mt5">
                                <div id="registerLink" className="wid49p fl brdr11 txtc pad12">
                                    <a href="/register/page1?source=mobreg4" onClick={()=>this.GAObject.trackJsEventGA("Login-jsms","Register",this.GAObject.getGenderForGA())} className="f17 fontlig white">
                                        Register
                                    </a>
                                </div>
                                <div id="searchLink" className="wid49p fl txtc pad12 posrel scrollhid">
                                    <a id="calltopSearch" href="/search/topSearchBand?isMobile=Y&amp;stime=1496377022985" className=" f17 fontlig white">
                                        Search
                                    </a>
                                </div>
                                <div className="clr"></div>
                            </div>
                        </div>;

        let captchDiv ='';
        if(this.state.showCaptchDiv)
        {
            captchDiv = <div className="captchaDiv pad2"><div id="showCaptchDivId" className="g-recaptcha" data-sitekey={SITE_KEY}></div></div>;
        }

        let registeredMessageDiv = "";

        if (this.state.showRegisterationMessage)
        {
            registeredMessageDiv = <div className="txtc pad25 f15 white fontlig">You need to be a Registered Member <br></br>to connect with this user</div>;
        }

        let newHref = CONSTANTS.HINDI_SITE, langText = "हिंदी में";
        let url = window.location.href;
        url = url.split(".")[0];
        if(url.indexOf('hindi') !== -1 || url.indexOf('marathi') !== -1){
            newHref = CONSTANTS.SITE_URL + "/P/logout.php";
            langText = "In English";
        }
        return (
            <div className="scrollhid" id="LoginPage">
                <MetaTagComponents page="LoginPage"/>                
                <HamMain ref="Hamchild" page="Login"></HamMain>
                {promoView}
                {errorView}
                {loaderView}
                <div className="fullheight overAuto headerimg1" id="mainContent">
                    <div className="perspective fullheight" id="perspective">
                        <div className="fullheight" id="pcontainer">
                            <div id="headerimg1" className="rel_c">
                                <div className="op_pad1">
                                    <div className="lgin_pad1">
                                        <div className="fl HamiconLogin">
                                            <i  onClick={() => this.showHam()} id="hamburgerIcon" className="dispbl mainsp baricon"></i>
                                        </div>
                                        <img className="loginLogo" src="https://static.jeevansathi.com/images/jsms/commonImg/mainLogoNew.png" />
                                    </div>
                                    <div>
                                        {registeredMessageDiv}

                                        {formInput}

                                        <div className="abs_c fwid_c mt20">
                                            {captchDiv}
                                            {buttonView}
                                            {appDownloadView}

                                            <div className="txtc pad2">
                                                <a id="hindiLinkOnLogin1" href={newHref} onClick={()=>this.GAObject.trackJsEventGA("Login-jsms","Hindi Site",this.GAObject.getGenderForGA())} className="f16 white fontlig">{langText}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    translateSite(translateURL)
    {
        if(translateURL.indexOf('hindi')!=-1){
            setCookie("jeevansathi_hindi_site_new","Y",100,".jeevansathi.com");
        } else {
            setCookie("jeevansathi_hindi_site_new","N",100,".jeevansathi.com");
        }
    }
}



const mapStateToProps = (state) => {
    return{
       MyProfile: state.LoginReducer.MyProfile,
       Jsb9Reducer: state.Jsb9Reducer
    }
}

LoginPage.propTypes = {
   MyProfile: PropTypes.object,
   doLogin: PropTypes.func
}

const mapDispatchToProps = (dispatch) => {
    return{
        doLogin: (curretObj,email,password,g_recaptcha_response,captcha,addCaptchaDiv) => {
            let call_url = CONSTANTS.LOGIN_CALL_URL+'?';
            let capchaOb = {};
            if ( g_recaptcha_response && captcha )
            {
                capchaOb = {g_recaptcha_response,captcha};
            }

            commonApiCall(call_url,{email:email,password:password,'rememberme':'Y',...capchaOb},'SET_AUTHCHECKSUM','POST',dispatch).then((response)=>
            {

                if ( response.responseStatusCode == 1)
                {
                    addCaptchaDiv();
                }
            });
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(LoginPage)
