require ('../style/login.css')
import React from "react";
import { connect } from "react-redux";
import TopError from "../../common/components/TopError"
import { validateEmail } from "../../common/components/commonValidations"
import {signin} from "../actions/LoginActions"
import { ErrorConstant } from "../../common/constants/ErrorConstants";
import Loader from "../../common/components/Loader";
import AppPromo from "../../common/components/AppPromo";

class LoginPage extends React.Component {

    constructor(props) {
        super();
        this.state = {
            insertError: false,
            errorMessage: "",
            timeToHide: 3000,
            showLoader: false,
            showPromo: false
        };
    }

    componentDidMount() {
        let _this = this;
        document.getElementById("LoginPage").style.height = window.innerHeight+"px"; 
        setTimeout(function(){ 
            _this.setState ({
                showPromo : true
            });  
        }, 1200); 
        console.log(this.props.AUTHCHECKSUM);
        if ( this.props.AUTHCHECKSUM ) {
            this.props.history.push('/myjs');     
       }   
    } 

    componentWillReceiveProps(nextProps)
    {
        console.log("In nextProps.");
        console.log(nextProps);
       if ( nextProps.AUTHCHECKSUM ) {
            this.props.history.push('/myjs');     
       }
       else {
            this.setState ({
                showLoader : false
            })
            this.showError(nextProps.responseMessage);
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
        if(emailVal.length != 0 && validateEmail(emailVal) == false) {
            this.showError(ErrorConstant("ValidEmail"));
            document.getElementById("emailErr1").classList.remove("dn");
        } else if(emailVal.length == 0 && passVal.length == 0) {
            this.showError(ErrorConstant("LoginDetails"));   
        } else if(emailVal.length == 0) {
            this.showError(ErrorConstant("EnterEmail"));
        } else if(passVal.length == 0) {
	       this.showError(ErrorConstant("EnterPass"));
        } else {
            this.props.doLogin(emailVal,passVal);
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

    removePromoLayer() {
        this.setState ({
            showPromo : false
        });  
        document.getElementById("mainContent").classList.remove("ham_b100");
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

        var promoView;
        if(this.state.showPromo)
        {
            promoView = <AppPromo parentComp="LoginPage" removePromoLayer={() => this.removePromoLayer()} ></AppPromo>;
        }

        var formInput = <div id ="formInput">
                            <div className="fullwid brdr9 brdr10 lgin_inp_pad">
                                <div className="fl padr10 wid8p">
                                    <div className="icons1 uicon"></div>
                                </div>
                                <div className="fl clasone wid80p">
                                    <input  onChange={(e) => this.handleEmailChange(e)} type="email" id="email" className="color9 fullwid fontlig" name="email" placeholder="Email" />
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
                                    <input onChange={(e) => this.handlePasswordChange(e)} type="password" id="password" autoComplete="off" className="color9 fullwid fontlig" maxLength="40" name="password" placeholder="Password" />
                                </div>
                                <div id="showHide" onClick={(e) => this.showPass(e)} className="fl f12 white fontlig wid10p txtr dn">
                                    <span id="vertmid">Show</span>
                                </div>
                                <div className="clr"></div>
                            </div>
                        </div>; 

        var buttonView = <div id = "buttonView">
                            <div className="posrel scrollhid">
                                <div id="loginButton" className="bg7 fullwid txtc pad2">
                                    <div onClick={() => this.doLogin()} className="white f18 fontlig">Login</div>
                                </div>
                            </div>
                            <div className="bg10 fullwid mt5">
                                <div className="wid49p fl brdr11 txtc pad12">
                                    <a href="/register/page1?source=mobreg4" className="f17 fontlig white">Register</a>
                                </div>
                                <div className="wid49p fl txtc pad12 posrel scrollhid">
                                    <a id="calltopSearch" href="https://www.jeevansathi.com/search/topSearchBand?isMobile=Y&amp;stime=1496377022985" className=" f17 fontlig white">Search</a>
                                </div>
                                <div className="clr"></div>
                            </div>
                        </div>;

        return (
            <div id="LoginPage">
                {promoView}
                {errorView}
                {loaderView}
                <div className="fullheight" id="mainContent">
                    <div className="perspective fullheight" id="perspective">
                        <div className="headerimg1 fullheight" id="pcontainer">
                            <div id="headerimg1" className="rel_c">
                                <div className="op_pad1">
                                    <div className="lgin_pad1">
                                        <div className="fl HamiconLogin">
                                            <i id="hamburgerIcon" className="dispbl mainsp baricon"></i>
                                        </div>
                                        <img className="loginLogo" src="https://static.jeevansathi.com/images/jsms/commonImg/mainLogoNew.png" />
                                    </div>
                                    <div>
                                        
                                        {formInput}
                                        
                                        <div id="afterCaptcha" className="txtc pad12">
                                            <a href="/static/forgotPassword" className="white f14 fontlig">Forgot Password</a>
                                        </div>
                                        <div className="abs_c fwid_c mt20">
                                            
                                            {buttonView}

                                            <div id="appLinkAndroid" className="txtc pad2 dn">
                                                <a href="/static/appredirect?type=androidMobFooter" className="f15 white fontlig">Download App | 3MB only</a>
                                            </div>

                                            <div id="appLinkIos" className="txtc pad2 dn">
                                                <a href="/static/appredirect?type=iosMobFooter" className="f15 white fontlig">Download App</a>
                                            </div>

                                            <div className="txtc pad2">
                                                <a href="#" className="f16 white fontlig">हिंदी में</a>
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
}

const mapStateToProps = (state) => {
    return{
       AUTHCHECKSUM: state.AUTHCHECKSUM,
       responseMessage: state.responseMessage
    }
}


const mapDispatchToProps = (dispatch) => {
    return{
        doLogin: (email,password) => {
            dispatch(signin(email,password));
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(LoginPage)


    
















