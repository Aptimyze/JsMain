import React from 'react';
import classNames from 'classnames';
//components
import RegistrationHeader from '../common/RegistrationHeader';
import RegistrationFooter from '../common/RegistrationFooter';
import TopError from "../../../common/components/TopError";
//helpers and services
import {focusOnCurrentElement, removeFocusFromAllElements, scrollToBottom} from "../../helpers/screenHandlers";
import {getItem, removeItem, setItem} from "../../services/localStorage";
import {contructLoginData} from "../../helpers/dataPreprocessor";
import {commonApiCall} from "../../../common/components/ApiResponseHandler";
import {errorGATracking} from '../../helpers/gaHandler';
import GA from '../../../common/components/GA';
//constants
import errorStatements from "../../constant/errorStatements";
import emailCorrection from "../../constant/emailCorrections";
import {LOGIN_REGISTER_DATA} from "../../../common/constants/apiConstants.js"
import {regPage1Fields, regPage1FieldsMapUd} from "../../constant/apiData";


let privacyValue = "Show to All";

class LoginDetails extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      enableRegBtn: false,
      errorObj: {},
      nextBtnEnable: false,
      settingPopUp: false,
      showTick: "one",
      privacyValue: "Show to All",
      passwordType: "password",
      showPassword: true,
      characterLimit: 8,
      passwordHintCharacterLimit: 1,
      errorArray: [],
      nameError: false,
      emailError: false,
      passwordError: false,
      phoneError: false,
      pHelpError: false

    };
    this.props = props;
    this.GAObject = new GA();
  }


  componentDidMount() {
    this.enableNextBtn();
    // let ud = getItem('UD');
    // if (ud.hasOwnProperty('password')) {
    //   // this.setState({showPassword: true});
    // }
    // let elm = document.getElementById("password");
    // if(elm){
    //   let pwd = elm.value;
    //   this.characterRemaining(pwd);
    // }

  }
  // shouldComponentUpdate(){
  //   return false
  // }
  componentWillReceiveProps() {
    this.enableNextBtn();
    this.setState({showErrorReg: false});
  }

  validateInputFields(key) {
    this.setState({errorObj: {}, errorArray: []}, () => {
      this.setErrorReg();
    });
    let elm = document.getElementById(key);
    let val = elm.value;
    let ud = getItem('UD');
    if (val) {
      if (key === "name_of_user") {
        let name_of_user = val.replace(/\./gi, " ");
        let nameError = '';
        name_of_user = name_of_user.replace(/dr|ms|mr|miss/gi, "");
        name_of_user = name_of_user.replace(/[,']/gi, "");
        name_of_user = name_of_user.trim(name_of_user.replace(/\s+/gi, " "));

        let allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
        if (name_of_user.trim(name_of_user) === "" || !allowed_chars.test(name_of_user.trim(name_of_user))) {
          nameError = errorStatements['FULL_NAME_ERROR_3'];
        } else {
          let nameArr = name_of_user.split(" ");
          if (nameArr.length < 2) {
            nameError = errorStatements['FULL_NAME_ERROR_1'];
          }
        }
        let errorVerdict = {};
        if (nameError) {
          errorVerdict.isError = true;
          errorVerdict.statement = nameError;
          return errorVerdict;
        } else {
          errorVerdict.isError = false;
          errorVerdict.statement = nameError;
          return errorVerdict;
        }
      }
      if (key === "email") {
        let email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
        let domain_regex = /^((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
        let invalidDomainArr = ["jeevansathi", "dontreg", "mailinator", "mailinator2", "sogetthis", "mailin8r",
          "spamherelots", "thisisnotmyrealemail", "jsxyz", "jndhnd"];
        let emailError = '';
        if (val === '') emailError = errorStatements.EMAIL_REQUIRED;
        // check whether user has entered only numbers.
        if(!emailError){
          let newVal = +val;
          if(!Number.isNaN(newVal)){
            emailError = errorStatements.EMAIL_NOT_NUMBER;
          }
        }

        // check for @@
        if(!emailError){
          let stringSearch = "@";
          let count = 0;
          for (let i = 0; i < val.length; i++) {
            if (stringSearch === val[i]) {
              count = count + 1;
              if(count > 1){
                break;
              }
            }
          }
          if(count === 0){
            emailError = errorStatements.EMAIL_NO_AT;
          }
          if(count >1 ){
            emailError = errorStatements.EMAIL_MUL_AT;
          }

        }

        // check for space
        if(!emailError){
            if(val.indexOf(' ') >= 0){
              emailError = errorStatements.EMAIL_NO_SPACES;
            }
        }

        // validate malicious character in email domain
        if(!emailError){
          let testVal = val.split('@');
          let _testDom = testVal[testVal.length - 1];
          let emailPattern = domain_regex.test(_testDom);
          if (!emailPattern) {
            emailError = errorStatements.EMAIL_INVALID_DOMAIN_2;
          }
        }


        //valid emailDomain from array
        if (!emailError) {
          let emailDomain = () => {
            let value = val;
            let start = value.indexOf('@');
            let end = value.lastIndexOf('.');
            let diff = end - start - 1;
            let user = value.substr(0, start);
            let len = user.length;
            let domain = value.substr(start + 1, diff).toLowerCase();

            return {
              key:domain,
              bool:invalidDomainArr.indexOf(domain.toLowerCase()) === -1
            }
          };
          if (!emailDomain().bool) {
            emailError = `${errorStatements.EMAIL_INVALID_DOMAIN} '${emailDomain().key}' provided in email`;
          }
        }




        if (!emailError) {
          let emailPattern = email_regex.test(val);
          if (!emailPattern) {
            emailError = errorStatements.EMAIL_INVALID;
          }
        }
        let errorVerdict = {};
        if (emailError) {
          errorVerdict.isError = true;
          errorVerdict.statement = emailError;
          return errorVerdict;
        } else {
          errorVerdict.isError = false;
          errorVerdict.statement = emailError;
          return errorVerdict;
        }
      }
      if (key === "password") {
        let invalidPasswords = ["jeevansathi", "matrimony", "password", "marriage",
          "vibhor1234", "omsairam", "jaimatadi",
          "abcd1234", "parvezkk", "priyanka", "jeevansathi@123",
          "pytw2560", "waheguru", "jeevansathi123", "js123456", "jeevansathi.com",
          "india@123", "p@ssw0rd", "abhishek", "pass@123", "jeevan123", "welcome@123",
          "mayank2463", "welcome123", "abc123", "password123", "qwertyuiop", "india123",
          "password@123", "nehaavyan123", "abcd@1234", "pd592001", "shaadi@123", "yasu4333",
          "krishna", "jeevan@123", "radhika02", "anik.singh", "jabalpur123", "qwerty",
          "sairam", "singh4345", "rahul123", "sachin", "rahul@123", "iloveyou", "ganesh",
          "saibaba", "jeevansaathi", "harekrishna", "hariom", "himanshu", "shaadi123",
          "pooja123", "singh123", "qwerty123", "kareenakhan23", "sonu1234", "sunita",
          "deepak", "abcdefgh", "sanjay", "mummypapa", "chaman111", "qwerty@123",
          "priyanka123", "kaushal69sc@gmail.com", "goodluck", "rajkumar", "rajusohel", "pankaj"];
        let checkCommonPassword = () => {
          return invalidPasswords.indexOf(val.toLowerCase()) === -1;
        };
        let checkPasswordUserName = () => {
          let email = ud['email'];
          if (typeof email === "undefined") return true;
          let end = email.indexOf('@');
          let username = email.substr(0, end);
          return (String(val) !== String(username) && String(val) !== String(email));
        };
        let passError = '';
        if (val && val.trim === '') {
          passError = errorStatements['PASSWORD_REQUIRED'];
        }
        if (val && val.length < 8) {
          passError = errorStatements['PASSWORD_INVALID'];
        }
        if (!passError) {
          if (!isNaN(parseFloat(val)) && isFinite(val)) {
            passError = errorStatements['PASSWORD_NUMERIC'];
          }
        }
        if (!passError) {
          if (!checkCommonPassword() || !checkPasswordUserName()) {
            passError = errorStatements['PASSWORD_COMMON'];
          }
        }
        let errorVerdict = {};
        if (passError) {
          errorVerdict.isError = true;
          errorVerdict.statement = passError;
          return errorVerdict;
        } else {
          errorVerdict.isError = false;
          errorVerdict.statement = passError;
          return errorVerdict;
        }

      }
      if (key === "phone_mob") {
        let isdCodes = ["0", "91", "+91"];
        let isd_regex = /^[+]?[0-9]+$/;
        let mobile = val;
        let mobileISD = document.getElementById('isd').value;
        let mobileError = '';
        if (!mobile || mobile == '') {
          mobileError = errorStatements['MOBILE_REQUIRED'];
        }
        if (!mobileError) {
          let mobileCheck = () => {
            mobileISD = mobileISD ? mobileISD.toString().trim() : '';
            mobile = mobile ? mobile.toString().trim() : '';
            if (isNaN(mobile) || isdCodes.indexOf(mobileISD) !== -1
              && mobile.length !== 10 || isdCodes.indexOf(mobileISD) === -1
              && mobile && (mobile.length < 6 || mobile.length > 14)) return 1;
            else if (mobileISD == '') return 2;
            else if (mobileISD && !isd_regex.test(mobileISD)) return 3;
          };
          if (mobileCheck()) {
            if (mobileCheck() == 1)
              mobileError = errorStatements['MOBILE_INVALID'];
            else if ((mobileCheck() == 2))
              mobileError = errorStatements['ISD_REQUIRED'];
            else if (mobileCheck() == 3)
              mobileError = errorStatements['ISD_INVALID'];
          }
        }
        let errorVerdict = {};
        if (mobileError) {
          errorVerdict.isError = true;
          errorVerdict.statement = mobileError;
          return errorVerdict;
        } else {
          errorVerdict.isError = false;
          errorVerdict.statement = mobileError;
          return errorVerdict;
        }
      }
    } else {
      if (key === "name_of_user") {
        let errorVerdict = {};
        errorVerdict.isError = true;
        errorVerdict.statement = errorStatements['FULL_NAME_ERROR_2'];
        return errorVerdict;
      }
      if (key === "email") {
        let errorVerdict = {};
        errorVerdict.isError = true;
        errorVerdict.statement = errorStatements['EMAIL_REQUIRED'];
        return errorVerdict;
      }
      if (key === "password") {
        let errorVerdict = {};
        errorVerdict.isError = true;
        errorVerdict.statement = errorStatements['PASSWORD_REQUIRED'];
        return errorVerdict;
      }
      if (key === "phone_mob") {
        let errorVerdict = {};
        errorVerdict.isError = true;
        errorVerdict.statement = errorStatements['MOBILE_REQUIRED'];
        return errorVerdict;
      }
    }
  }


  nextClick() {
    let errorObj = {};
    let ud = getItem("UD");
    let validateName = this.validateInputFields('name_of_user');
    let validateEmail = this.validateInputFields('email');
    let validatePwd = this.validateInputFields('password');
    let validateNumber = this.validateInputFields('phone_mob');
    errorObj.name_of_user = validateName.isError ? validateName.statement : "";
    errorObj.email = validateEmail.isError ? validateEmail.statement : "";
    errorObj.phone_mob = validateNumber.isError ? validateNumber.statement : "";
    errorObj.password = validatePwd.isError ? validatePwd.statement : "";

    if (!validateNumber.isError) {
      let mobileISD = document.getElementById('isd').value;
      let phoneNum = document.getElementById('phone_mob').value;
      ud['phone_mob'] = `${mobileISD},${phoneNum}`;
      setItem('UD', ud)
    }

    if (!validateEmail.isError) {
      ud['email'] = document.getElementById('email').value;
      setItem('UD', ud)
    }

    if (!validatePwd.isError) {
      ud['password'] = document.getElementById('password').value;
      setItem('UD', ud)
    }

    if (!validateName.isError) {
      ud['name_of_user'] = document.getElementById('name_of_user').value;
      setItem('UD', ud)
    }



    if (errorObj.name_of_user) {
      this.setState({nameError: true})
    }
    if (errorObj.email) {
      this.setState({emailError: true})
    }
    if (errorObj.password) {
      this.setState({passwordError: true});
      if(errorObj.password == errorStatements['PASSWORD_INVALID'] ||errorObj.password == errorStatements['PASSWORD_NUMERIC'] ){
        this.setState({pHelpError: true});
      }
    }
    if (errorObj.phone_mob) {
      this.setState({phoneError: true})
    }

    this.setState({errorObj, errorArray: []}, () => {
      for (let i in errorObj) {
        if (!errorObj[i]) delete errorObj[i]
      }
      this.setErrorReg();


      if (Object.keys(this.state.errorObj).length === 0) {
        this.completeUserLogin()
      } else {
        let err = '';
        for (let obj in this.state.errorObj) {
          err += this.state.errorObj[obj] + ' ';
        }
        if(this.state.errorObj && this.state.errorObj.email && (this.state.errorObj.email == errorStatements['EMAIL_INVALID'] || this.state.errorObj.email == errorStatements['EMAIL_INVALID_DOMAIN'])){
          this.GAObject.regTrackGA("E", "jsms", "Invalid Email", document.getElementById('email').value);
        }
        // console.log('ga');
        errorGATracking('s5', err);
      }
    });

  }

  setErrorReg() {
    if (Object.keys(this.state.errorObj).length >= 1) {
      this.setState({showErrorReg: true, errorArray: []});
    } else this.setState({showErrorReg: false, errorArray: []});
  }


  enableNextBtn() {
    let mVal = document.getElementById('phone_mob');
    let val = '';
    if (mVal) {
      val = mVal.value;
    }
    let ud = getItem("UD");
    if (ud && ud.email && (ud.phone_mob || val.length > 1) && ud.password && ud.name_of_user) {
      this.setState({nextBtnEnable: true});
    } else {
      this.setState({nextBtnEnable: false});
    }
  }

  showSetting(show) {
    this.setState({
      showErrorReg: false,
      settingPopUp: show
    })
  }

 

  characterRemaining(pwd) {
    let minLength = 8;
    if(pwd){
      let pLen = pwd.length;
      if (pLen >= 0 && pLen < 9) {
        this.setState({
          characterLimit: minLength - pLen
        })
      }
      if (pLen > 0 && pLen < 9) {
        this.setState({
          passwordHintCharacterLimit: 2, // show 1st hint `character remaining` in pwd
          // showPassword: true
        })
      } else if (pLen >= 8) {
        this.setState({
          passwordHintCharacterLimit: 3 // hide both hints
        })
      } else if (pLen === 0) {
        this.setState({
          passwordHintCharacterLimit: 1, // show first hint `{} more chars`
          // showPassword: false
        })
      }
    } else if (pwd=="") {
      this.setState({
        passwordHintCharacterLimit: 1, // show first hint `{} more chars`
        // showPassword: false,
        characterLimit: minLength
      })
    }
  }

  limitDigits(val, type) {
    let result = null;
    if (val) {
      if (type === "ISD") {
        return result = parseInt(val.toString().substring(0, 4));
      } else if (type === "PHONE") {
        let isdVAl = document.getElementById('isd').value;
        if (isdVAl == 91 && val.length > 10) {
          return result = parseInt(val.toString().substring(0, 10))
        } else if (isdVAl != 91 && val.length > 14) {
          return result = parseInt(val.toString().substring(0, 14))
        }

      }
      return val;
    }

  }

  parseHashAnds(str) {
    if (str) {
      if (str.length > 0) {
        let text = '';
        for (let i = 0; i < str.length; i++) {
          if (str[i] == "#" || str[i] == "&") {
            text += encodeURIComponent(str[i]);
          } else {
            text += str[i];
          }

        }
        return text;
      }
    } else return ''
  }

  completeUserLogin() {
    this.props.showLoader();
    let ud = getItem("UD");
    let temp = getItem('trackParams');
    let ltp = getItem('trackServerParams');
    if (ud) {
      if (ud['college']) {
        ud['college'] = this.parseHashAnds(ud['college'])
      }
      if (ud['pg_college']) {
        ud['pg_college'] = this.parseHashAnds(ud['pg_college'])
      }
    }
    for (let obj in ud) {
      if (!regPage1FieldsMapUd.hasOwnProperty(obj)) {
        delete ud[obj];
      }
    }
    setItem('UD', ud);
    let loginParams = contructLoginData(ud, regPage1Fields);

    if (loginParams.hasOwnProperty('reg[trackingParams]')) {
      delete temp.s;

      temp.var_ab = "R";
      for(let i in temp){
        if (ltp == null || typeof ltp == "undefined") {
          ltp = {};
        }
        if (!ltp.hasOwnProperty(i)) {
          ltp[i] = temp[i];
        }
      }
      loginParams['reg[source]'] = ltp['source'];
      ltp['secondary_source'] = temp['secondary_source'] ? temp['secondary_source'] : 'S';
      loginParams['reg[trackingParams]'] = JSON.stringify(ltp);
    }
    let queryString = Object.keys(loginParams).map(key => key + '=' + loginParams[key]).join('&');
    let url1 = `${LOGIN_REGISTER_DATA}?${queryString}`;
    commonApiCall(url1, {}, '', 'POST', '', false, '', '', '', '').then((response) => {
      if (response) {
        if (!response.error && response['responseMessage']) {
          let tracker = getItem('trackParams');
          if (response.LANDINGPAGE === 'HOMEPAGE') {
            window.location.href = "/profile/mainmenu.php";
          } else if (response.LANDINGPAGE === 'SCREEN_6') {
            this.props.properties.history.push(`/register/newjsms?source=${tracker ? tracker.source : ""}&s=6`);
          }
          setItem('selfCountry', ud.country_res);
          setItem('UD', {});
          setItem('UD_display', {});
          removeItem('currentRegPage');
          removeItem('autoCorrectEmail');
        } else if (response.status == 500 || !response.status) {
          this.props.hideLoader();
          errorGATracking('s5',errorStatements.SOMETHING_WRONG);

        }
        else if (response.data) {
          if (response.data.error) {
            this.props.hideLoader();
            this.setState({
              errorArray: response.data.error,
              showErrorReg: true
            },()=>{
              let err='';
              if(this.state.errorArray.indexOf('Provide your email in proper format, e.g. raj1984@gmail.com')!=-1){
                // console.log('hit');
                this.GAObject.regTrackGA("E", "jsms", "Invalid Email API", document.getElementById('email').value);
              }
              this.state.errorArray.forEach(element => {
                err+= element+' ';
              });
              errorGATracking('s5',err);
            })
          }
        }
      } else {
        this.props.hideLoader();
        errorGATracking('s5',errorStatements.SOMETHING_WRONG);
      }

    });
  }

  render() {
    //resizeContainer('LoginDetailsContainer');
    //scrollToBottom('LoginDetailsContainer');
    let ud = getItem('UD');
    if (!ud) {
      setItem('UD', {});
      setItem('UD_display', {});
    }
    let screenInitialHeight = getItem('screenInitialHeight');
    return (
      <div className="posabs fw">
        {this.state.showErrorReg &&
        <TopError timeToHide={5000} errorObj={this.state.errorObj}
                  leftAlign={true}
                  errorArray={this.state.errorArray}
                  topPosition={55}/>}
        <div className="fw bg1">
          <RegistrationHeader headerData="Login Details" page="4"
                              onIconClick={this.props.onIconClick}/>
        </div>
        <div className='bg4 sliderDataContainer'
             style={{
               height: (screenInitialHeight >= window.innerHeight ? screenInitialHeight - 110 : window.innerHeight - 110) + 'px',
               overflow: 'auto'
             }}
             id="LoginDetailsContainer">
          {/*
          <div className="privacyHelp">{errorStatements.PRIVACY_HELP}
          </div>
           */}
          
          

          <form name="RegistrationForm" autoComplete="new-password" noValidate={true}>

            <div className="brdr1">
              <div className="pad1">
                <div className="pad3" onClick={()=>{
                  document.getElementById('name_of_user').focus()
                }}>
                  <div className="fl wid50p">
                    <div className={classNames(this.state.nameError ? 'color2' : 'color8', 'f12 fontlig')}>Full Name
                    </div>
                    <input defaultValue={ud && ud.name_of_user ? ud.name_of_user : ''}
                           autoComplete="new-password"
                           className="color11 f15 pt10 fontlig fullwid regSliderBlock setRD"
                           id="name_of_user"
                           onFocus={e => {
                             focusOnCurrentElement('name_of_user');
                             this.setState({errorObj: {}, errorArray: []}, () => {
                               this.setErrorReg();
                             });
                           }}
                           onKeyDown={(e) => {
                             if (e.keyCode === 13) {
                               e.target.blur();
                               document.getElementById("email").focus();
                             }
                           }}
                           onKeyUp={e => {
                             let validateName = this.validateInputFields('name_of_user');
                             if (validateName && !validateName.isError) {
                               if (ud) {
                                 ud['name_of_user'] = e.target.value;
                                 setItem("UD", ud);
                               }
                               this.enableNextBtn()
                             } else {
                               let ud = getItem('UD');
                               if (ud) {
                                 delete ud.name_of_user;
                                 setItem("UD", ud);
                                 this.enableNextBtn()
                               }
                             }
                           }}
                           onBlur={e => {
                             removeFocusFromAllElements();
                             this.setState({nameError: false});
                             //resizeContainer('LoginDetailsContainer');
                             //scrollToBottom('LoginDetailsContainer');
                           }}
                           type="text" placeholder="Not Filled In"/>
                  </div>

                  <span className="fr fontlig pt15 "
                        onClick={(e) => {
                          e.stopPropagation();
                          this.showSetting(true)
                        }
                        }>
                  {this.state.privacyValue === "Show to All" ?
                    <span className="vTop cogIcon padr5 f14">Show to All</span>
                    : <span className="vTop cogIcon padr5 f14">Don't Show</span>}
                    <i className="iconImg2 iconSprite"/>
                 </span>

                  {/*{this.state.nameError &&*/}
                  {/*<div className="fr pt8 mar10right">*/}
                    {/*<i className="mainsp reg_errorIcon"/>*/}
                  {/*</div>}*/}

                  <div className="clr"/>
                </div>
              </div>
            </div>


            <div className="brdr1">
              <div className="pad1">
                <div className="pad3" onClick={()=>{
                  document.getElementById('email').focus()
                }}>
                  <div>
                    <div
                      className={classNames(this.state.emailError ? 'color2' : 'color8', 'f12 fontlig')}>Email
                    </div>
                    <input defaultValue={ud && ud.email ? ud.email : ''}
                           className="color11 f15 pt10 fontlig fullwid regSliderBlock setRD"
                           autoComplete="new-password"
                           id="email"
                           onFocus={e => {
                             focusOnCurrentElement('email');
                             this.setState({errorObj: {}, errorArray: []}, () => {
                               this.setErrorReg();
                             });
                           }}

                           onKeyDown={(e) => {
                             if (e.keyCode === 13) {
                               e.target.blur();
                               document.getElementById("phone_mob").focus();
                             }
                           }}

                           onBlur={e => {
                             removeFocusFromAllElements();
                             this.setState({emailError: false});
                             let val = e.target.value;
                             if(val){
                               let domain = val.split('@');
                               let oldDom = domain[1];
                               oldDom = oldDom.toLowerCase();
                               if(!getItem('autoCorrectEmail')){
                                 let gmailTest = oldDom.substr(0,5);
                                 if(gmailTest === "gmail"){
                                   val = domain[0] + '@' + "gmail.com";
                                   ud['email'] = val;
                                   setItem('autoCorrectEmail', true);
                                   setItem("UD", ud);
                                   this.enableNextBtn();
                                   e.target.value = val;
                                   return true;
                                 }
                               }
                             }
                             let email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
                             if (val && email_regex.test(val)) {
                               let domain = val.split('@');
                               let oldDom = domain[1];
                               oldDom = oldDom.toLowerCase();
                               if (!emailCorrection[oldDom]) {
                                 ud['email'] = val;
                                 setItem("UD", ud);
                                 this.enableNextBtn();
                                 return true;
                               }

                               let stringToReplace = emailCorrection[oldDom];
                               if (!getItem('autoCorrectEmail')) {
                                 val = domain[0] + '@' + stringToReplace;
                                 ud['email'] = val;
                                 setItem('autoCorrectEmail', true);
                                 setItem("UD", ud);
                                 this.enableNextBtn();
                                 e.target.value = val;
                               } else {
                                 ud['email'] = val;
                                 setItem("UD", ud);
                                 this.enableNextBtn();
                               }

                             } else {
                               let ud = getItem('UD');
                               if (ud) {
                                 delete ud.email;
                                 setItem("UD", ud);
                                 this.enableNextBtn();
                               }
                             }

                             //resizeContainer('LoginDetailsContainer');
                             //  scrollToBottom('LoginDetailsContainer');
                           }}
                           type="email" placeholder="Not Filled In"/>
                  </div>
                  {/*{this.state.emailError &&*/}
                  {/*<div className="fr pt8 mar10right">*/}
                    {/*<i className="mainsp reg_errorIcon"/>*/}
                  {/*</div>*/}
                  {/*}*/}
                  <div className="clr"/>
                </div>
              </div>
            </div>



            <div className="brdr1">
              <div className="pad1">
                <div className="pad3">
                  <div className={classNames(this.state.phoneError ? 'color2' : 'color8', 'f12 fontlig')}>Phone Number
                  </div>
                  <div className="pt10">
                  <span style={{position: 'relative', top: '1px'}}>+</span>
                  <input id="isd" autoComplete="new-password"
                         defaultValue={ud && ud.phone_mob && ud.phone_mob.split(',').length > 1 ? ud.phone_mob.split(',')[0] : '91'}
                         className="color11 ml2 f15 fontlig regSliderBlock setRD bgGrey padb5 pt5 padl5"
                         maxLength="4" style={{width: '15%', marginRight:"5px"}}
                         onFocus={e => {
                           focusOnCurrentElement('isd')
                         }}
                         onKeyUp={(e) => {
                           let val = e.target.value;
                           if (val) {
                             val = val.trim();
                             if (val.length > 0) {
                               e.target.value = this.limitDigits(val, "ISD");
                               setItem('isd', e.target.value)
                             }
                           }
                         }}
                         onChange={e => {
                           let validateNumber = this.validateInputFields('phone_mob');
                           let mVal = e.target.value;
                           if (mVal) {
                             mVal = mVal.trim();
                             if (validateNumber && !validateNumber.isError) {
                               mVal = this.limitDigits(mVal, "ISD");
                               let mPh = document.getElementById('phone_mob').value;
                               ud['phone_mob'] = `${mVal},${this.limitDigits(mPh, "PHONE")}`;
                               setItem("UD", ud);
                             }
                           } else {
                             let ud = getItem('UD');
                             if (ud) {
                               delete ud.phone_mob;
                               setItem("UD", ud);
                               this.enableNextBtn()
                             }
                           }
                         }}


                         onKeyDown={(e) => {
                           if (e.keyCode === 13) {
                             e.target.blur();
                             document.getElementById("phone_mob").focus();
                           }
                         }}
                         onBlur={e => {
                           removeFocusFromAllElements();
                           //resizeContainer('LoginDetailsContainer');
                           //scrollToBottom('LoginDetailsContainer');
                         }}
                         type="text" placeholder="ISD"/>
                  <span style={{position: 'relative', top: '1px', padding:"0px 5px", display:"inline-block"}}>-</span>
                  <input defaultValue={ud && ud.phone_mob ? ud.phone_mob.split(',')[1] : ''}
                         className="color11 f15 fontlig regSliderBlock setRD bgGrey padb5 pt5 padl5"
                         id="phone_mob"
                         autoComplete="new-password"
                         style={{marginLeft:"5px"}}
                         onFocus={e => {
                           focusOnCurrentElement('phone_mob')
                         }}
                         onKeyUp={(e) => {
                           let mVal = e.target.value;
                           if (mVal) {
                             mVal = mVal.trim();
                             if (mVal.length > 9) {
                               e.target.value = this.limitDigits(mVal, "PHONE");
                             }
                           }
                         }}
                         onKeyDown={(e) => {
                          if (e.keyCode === 13) {
                            e.target.blur();
                            document.getElementById("password").focus();
                          }
                         }}
                         onChange={e => {
                           let validateNumber = this.validateInputFields('phone_mob');
                           let mobileISD = document.getElementById('isd').value;
                           let mVal = e.target.value;
                           if (mVal) {
                             mVal = mVal.trim();
                             if (validateNumber && !validateNumber.isError) {
                               ud['phone_mob'] = `${mobileISD},${this.limitDigits(mVal, "PHONE")}`;
                               setItem("UD", ud);
                             } else if (mobileISD)
                               this.enableNextBtn()
                           } else {
                             let ud = getItem('UD');
                             if (ud) {
                               delete ud.phone_mob;
                               setItem("UD", ud);
                               this.enableNextBtn()
                             }
                           }
                         }}
                         onBlur={e => {
                           removeFocusFromAllElements();
                           this.setState({phoneError: false});
                           //resizeContainer('LoginDetailsContainer');
                           //scrollToBottom('LoginDetailsContainer');
                         }}
                         type="number" placeholder="Not Filled In"/>
                         </div>
                  {/*{this.state.phoneError &&*/}
                  {/*<div className="fr pt8 mar10right">*/}
                    {/*<i className="mainsp reg_errorIcon"/>*/}
                  {/*</div>}*/}
                  <div className="clr"/>
                </div>
              </div>
            </div>

{/* start: create password */}

                        <div className="brdr1">
              <div className="pad1">
                <div className="pad3" onClick={()=>{
                  document.getElementById('password').focus();
                  scrollToBottom('LoginDetailsContainer');
                }}>
                  <div className="fl reg_wid80">
                    <div className={classNames(this.state.passwordError ? 'color2' : 'color8', 'f12 fontlig')}>Create New Password
                      {/*{ud && !ud.password && this.state.passwordHintCharacterLimit === 1 &&*/}
                      {/*<span> (Min {this.state.characterLimit} characters) </span>}*/}

                      {/*{this.state.passwordHintCharacterLimit === 2 &&*/}
                      {/*<span> ({this.state.characterLimit} more characters) </span>}*/}


                    </div>
                    <input autoComplete="new-password"
                           defaultValue={ud && ud.password ? ud.password : ''}
                           id="password"
                           className="color11 f15 pt10 fontlig fullwid regSliderBlock setRD"
                           onChange={(e) => {
                             let pVal = e.target.value;
                             //this.characterRemaining(pVal);
                             let validatePassword = this.validateInputFields('password');
                             if (pVal && pVal.length > 7 && validatePassword && !validatePassword.isError) {
                               ud['password'] = e.target.value;
                               setItem("UD", ud);
                               this.enableNextBtn();
                             } else {
                               let ud = getItem('UD');
                               if (ud) {
                                 delete ud.password;
                                 setItem("UD", ud);
                                 this.enableNextBtn()
                               }
                             }
                           }}
                           onFocus={e => {
                             focusOnCurrentElement('password');
                             scrollToBottom('LoginDetailsContainer');
                           }}
                           onKeyDown={(e) => {
                            scrollToBottom('LoginDetailsContainer');
                             if (e.keyCode === 13) {
                              if (e.keyCode === 13) {
                                e.target.blur();
                                this.nextClick()
                              }
                             }
                           }}
                           onBlur={e => {
                             removeFocusFromAllElements();
                             this.setState({passwordError: false, pHelpError: false});

                             //resizeContainer('LoginDetailsContainer');
                             // scrollToBottom('LoginDetailsContainer');
                           }}
                           type={this.state.passwordType} placeholder="Not Filled In"/>
                  </div>
                  <div className="fr wid14p txtc">

                <span className="fr fontlig">
                  {this.state.showPassword === true &&
                  <span className="color2 cogIcon padr5 f12" onClick={() => {
                    this.setState({
                      showPassword: false,
                      passwordType: 'text',
                      errorObj: {}
                    }, () => {
                      this.setErrorReg();
                    })
                  }}>Show</span>}
                  {this.state.showPassword === false &&
                  <span className="color2 cogIcon padr5 f12" onClick={() => {
                    this.setState({
                      showPassword: true,
                      passwordType: 'password',
                      errorObj: {}
                    }, () => {
                      this.setErrorReg();
                    })
                  }}>Hide</span>}
                 </span>

                    {/*{this.state.passwordError &&*/}
                    {/*<div className="fr mar10right">*/}
                      {/*<i className="mainsp reg_errorIcon"/>*/}
                    {/*</div>}*/}
                  </div>
                  <div className="clr"/>
                </div>

                <div className={classNames(this.state.pHelpError ? 'color2' : 'clrPHelp', 'pHelp')}>
                  Hint: {errorStatements.PASSWORD_HELP}
                </div>
              </div>
            </div>
{/* end: create password */}

          </form>

          {/*page links*/}
          <div id="termsAndPrivacyDiv" className="termsAndPrivacy posAbs">
            <div className="txtc set_btmlink">
              <a href="https://www.jeevansathi.com/static/page/disclaimer"
                 target="_blank">Terms of Use</a>
              <span className="f14">•</span>
              <a href="https://www.jeevansathi.com/static/page/privacypolicy"
                 target="_blank">Privacy Policy</a>
            </div>
          </div>
          {/*page links end*/}

          {/*popup from setting*/}
          {this.state.settingPopUp &&
          <div className="slide-enter-done">
            <div className="backShow z105 fw darkView hamView"  id="backDropLogin"
                 style={{height: (screenInitialHeight >= window.innerHeight ? screenInitialHeight : window.innerHeight) + 'px'}}/>
            <div className="wid90p bg4 popUp">
              <div className="padd1015 f15 fontlig brdr15 txtc">Name Privacy Setting</div>
              <div onClick={() => {
                privacyValue = "Show to All";
                this.setState({errorObj: {}, showTick: 'one'}, () => {
                  let ud = getItem('UD');
                  ud['displayname'] = 'Y';
                  setItem('UD', ud);
                  this.setErrorReg();
                });
              }}
                   className="changeSetting padd22 hgt75 fontlig brdr15">
                <div className="pt8 f15">Show my name to all</div>
                <i className={classNames(this.state.showTick === "one"
                  ? 'tickSelected iconSprite' : "", "fr iconTick")}/>
              </div>
              <div onClick={() => {
                privacyValue = "Don't Show";
                this.setState({errorObj: {}, showTick: 'two'}, () => {
                  let ud = getItem('UD');
                  ud['displayname'] = 'N';
                  setItem('UD', ud);
                  this.setErrorReg();
                });
              }}
                   className="changeSetting padd22 hgt75 fontlig brdr15">
                <div className="f15">Don't show my name</div>
                <i className={classNames(this.state.showTick === "two"
                  ? 'tickSelected iconSprite' : "", "fr iconTick")}/>
                <div className="f13 fl pt6">You will not be able to see
                  names of other members
                </div>
              </div>
              <div id="doneBtn"
                   onClick={() => {
                     this.showSetting(false);
                     this.setState({
                       privacyValue: privacyValue
                     })
                   }}
                   className="padd1015 color2 fullwid txtc f15">Done
              </div>
            </div>
          </div>}
          {/*popup from setting end*/}
        </div>
        {/*next btn*/}
        {this.state.nextBtnEnable ?
          <div className='bg7 fw'
               onClick={() => {
                 this.nextClick();

               }
               }>
            <RegistrationFooter text={"Accept & Continue"}/>
          </div> :
          <div className='bggrey fw'
               onClick={() => {
                 this.nextClick()
               }
               }>
            <RegistrationFooter text={"Accept & Continue"}/>
          </div>
        }
      </div>
    )
  }
}

export default LoginDetails;