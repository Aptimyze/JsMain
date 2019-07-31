import React from 'react';
import classNames from 'classnames';
import PropTypes from 'prop-types';
//components
import TopError from '../../../common/components/TopError';
import RegistrationSlider from '../common/RegistrationSlider';
import RegistrationHeader from '../common/RegistrationHeader';
import RegistrationFooter from '../common/RegistrationFooter';
//helpers and services

import {setItem, getItem} from "../../services/localStorage";
import {calculateAge, preProcessInput, processFromMultipleArrays} from "../../helpers/dataPreprocessor";
import {
  scrollToBottom, resizeContainer, focusOnCurrentElement, scrollOnTop,
  removeFocusFromAllElements, editClass, blurInputs
} from "../../helpers/screenHandlers";
import {errorGATracking} from '../../helpers/gaHandler';
//constants
import errorStatements from '../../constant/errorStatements';
//css files
require('../../style/radioBtn.css');

class PersonalDetails extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      dobSlider: false,
      singleSlider: false,
      countrySlider: false,
      citySlider: false,
      stateSlider: false,
      residencySlider: false,
      showErrorReg: false,

      errorRegData: '',
      pinErr: false,
      nextBtnEnable: false,
      errorObj: {}
    };
    this.nextClick = this.nextClick.bind(this);
    this.hamState = this.hamState.bind(this);
    this.showCityAndResidentialStatus = false;
    this.enableAdditionalFields(0);
    // 0 1 2 are the cases 0 -  for default mount, 1 - for city, 2- for states
  }

  componentDidMount() {
    this.enableNextBtn();
  }

  componentWillReceiveProps() {
    this.enableNextBtn();
    this.setState({showErrorReg: false,  showErrorLabel1:false,
      showErrorLabel2:false,
      showErrorLabel3:false,
      showErrorLabel4:false,
      showErrorLabel5:false});
  }


  hamState(showRegHamburger, hamName, errorRegData) {
    let hamDetails = {'showRegHamburger': showRegHamburger, 'hamName': hamName};
    setItem('hamDetails', hamDetails);
    this.inputDataForSlider = [];
    let staticTableData = getItem('staticTableData');
    let reg_city_jspc = getItem('reg_city_jspc');
    let ud = getItem('UD');
    let {errorObj} = this.state;
    if (errorRegData) {
      errorObj.dob = errorRegData;
      this.setState({errorObj}, () => {
        this.setErrorReg();
      });
    }
    else {
      this.setState({errorObj: {}}, () => {
        this.setErrorReg();
      });
    }
    blurInputs();
    editClass(showRegHamburger);
    if (showRegHamburger) {
      focusOnCurrentElement('')
    } else {
      removeFocusFromAllElements()
    }
    switch (hamName) {
      case 'Reg_Date_of_birth': {
        this.setState({dobSlider: showRegHamburger});
        break;
      }
      case 'Reg_Height': {
        if (staticTableData && staticTableData.hasOwnProperty('height')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.height[0]);
        }
        this.setState({singleSlider: showRegHamburger});
        break;
      }
      case 'Reg_Country_living_in': {
        if (staticTableData && staticTableData.hasOwnProperty('country_res') && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.country_res[0]);
        }
        this.setState({countrySlider: showRegHamburger});
        break;
      }
      case 'Reg_State_City_living_in': {
        if (staticTableData && staticTableData.hasOwnProperty('state_res') && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.state_res[0]);
        }
        //country_res
        this.setState({stateSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.state_res) {
            scrollToBottom('personalDetailsContainer');
            if (ud && ud.pincode && staticTableData && ud.city_res && staticTableData.hasOwnProperty('citypincode')) {
              let pinTable = staticTableData['citypincode'][ud.city_res];
              if(pinTable){
                let _x = false;
                for (let i in pinTable[0]) {
                  let inp = pinTable[0][i];
                  let pattern = new RegExp('^(' + inp + ')');
                  let res = pattern.test(ud.pincode);
                  if (res) {
                    _x = true;
                    break;
                  }
                }
                if(_x == false){
                  if(ud['pincode']) delete ud['pincode'];
                  let pincode_block = document.getElementById('pinCode');
                  if(pincode_block && !ud.pincode){
                    pincode_block.value = "";
                    setItem('UD',ud);
                    this.setState({
                      pinErr: false
                    }, () => {
                      this.setErrorReg();
                      this.enableNextBtn()
                    });
                  }
                }
              }

            }
            else {
              if(ud['pincode']) delete ud['pincode'];
              let pincode_block = document.getElementById('pinCode');
              if(pincode_block && !ud.pincode){
                pincode_block.value = "";
                setItem('UD',ud);
                this.setState({
                  pinErr: false
                }, () => {
                  this.setErrorReg();
                  this.enableNextBtn()
                });
              }

            }
          }
        });
        break;
      }
      case 'Reg_City': {
        if (reg_city_jspc && showRegHamburger) {
          this.inputDataForSlider = processFromMultipleArrays(reg_city_jspc, ud.country_res);
        }
        this.setState({citySlider: showRegHamburger});
        break;
      }
      case 'Reg_Residential_Status': {
        if (staticTableData && staticTableData.hasOwnProperty('res_status') && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.res_status[0]);
        }
        this.setState({residencySlider: showRegHamburger});
        break;
      }
    }

    if (ud && ud.country_res !== '51') {
      // show city and res stat
      if (showRegHamburger === false && hamName === "Reg_Country_living_in"
        && this.showCityAndResidentialStatus === false
        && ud.country_res && ud.country_res !== "136") {
        this.enableAdditionalFields(1)
      }
      // dont show city and res stat in case of others
      if (showRegHamburger === false && hamName === "Reg_Country_living_in"
        && ud.country_res === '136') {
        this.enableAdditionalFields(2)
      }

    } else {
      this.showCityAndResidentialStatus = false;
    }


    this.enableNextBtn();

  }

  genderClick(gender) {
    this.setState({gender});
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    // on gender click error is viewed
    this.setState({errorObj: {}}, () => {
      this.setErrorReg();
    });
    // gender is clicked after selection of DOB
    if (ud.dtofbirth_year && gender != ud.gender) {
      let age = calculateAge(`${ud.dtofbirth_month}/${ud.dtofbirth_day}/${ud.dtofbirth_year}`); // Format: MM/DD/YYYY
      if ((age < 21) && gender === 'M') {
        delete ud.dtofbirth_day;
        delete ud.dtofbirth_month;
        delete ud.dtofbirth_year;
        this.forceUpdate()
      }
    }
    if (ud && gender == 'F' && ud.mstatus == 'M') {
      // delete marital status in case of marital status 'married' and then switched gender to female
      delete ud.mstatus;
      delete ud.havechild;
      delete ud_display.mstatus;
      delete ud_display.havechild;
      this.forceUpdate()
    }
    ud.gender = gender;
    setItem('UD', ud);
    setItem('UD_display', ud_display);
    this.enableNextBtn();
  }

  nextClick() {
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    let staticTableData = getItem('staticTableData');
    let pinTable = null;
    if (staticTableData && ud.city_res) {
      pinTable = staticTableData['citypincode'][ud.city_res];
    }
    let errorObj = {};
    errorObj.gender = ud.gender ? '' : errorStatements.GENDER;
    errorObj.dob = ud.dtofbirth_day ? '' : errorStatements.DOB_ERROR_0;
    errorObj.height = ud.height ? '' : errorStatements.HEIGHT;
    errorObj.country_res = ud.country_res ? '' : errorStatements.COUNTRY_RES;
    if (ud.country_res === "51") {
      errorObj.state_res = ud.state_res ? '' : errorStatements.STATE_RES;
      if (pinTable && !ud.pincode) {
        errorObj.pincode = (() => {
          let result = errorStatements.PINCODE_ERROR_1;
          if (ud_display && ud_display.city_res) {
            result = `${errorStatements.PINCODE_ERROR_1} that belongs to ${ud_display.city_res}`
          }
          return result
        })()
      }
    } else if (ud.country_res && ud.country_res !== "51" && ud.country_res !== "136") {
      errorObj.city_res = ud.city_res ? '' : errorStatements.CITY_RES;
      errorObj.res_status = ud.res_status ? '' : errorStatements.RESIDENTIAL_STATUS;
    }


    this.setState({errorObj}, () => {
      for (let i in errorObj) {
        if (!errorObj[i]) delete errorObj[i]
      }
      this.setErrorReg();
      let err = '';
      for (let obj in this.state.errorObj) {
        err += this.state.errorObj[obj] + ' ';
      }
      errorGATracking('s2', err);
    });
    //this.props.onIconClick('nextPage');
  }


  enableAdditionalFields(fieldNo) {
    let ud = getItem('UD');
    switch (fieldNo) {
      case 0: {
        if (ud && ud.country_res !== "51") {
          if (ud.country_res && ud.country_res !== "136") {
            this.showCityAndResidentialStatus = true;
          }
        }
        break;
      }
      case 1: {
        scrollToBottom('personalDetailsContainer');
        this.showCityAndResidentialStatus = true;
        break
      }
      case 2: {
        this.showCityAndResidentialStatus = false;
        break
      }
    }

  }


  setErrorReg() {
    // console.log('this.state.errorObj', this.state.errorObj);
    if (Object.keys(this.state.errorObj).length >= 1) {
      this.setState({showErrorReg: true});
    } else this.setState({showErrorReg: false});
  }

  enableNextBtn() {
    let ud = getItem("UD");
    let staticTableData = getItem('staticTableData');

    if (staticTableData && ud && ud.gender && ud.height && ud.country_res
      && ud.dtofbirth_day && ud.city_res) {
      // case for checking country india with city without pincode
      if (ud.country_res === "51"
        && ((!staticTableData['citypincode'].hasOwnProperty(ud.city_res)))) {
        this.setState({nextBtnEnable: true});
      }
      // case for checking country india with city with pincode
      else if (ud.country_res === "51") {
        if (staticTableData['citypincode'].hasOwnProperty(ud.city_res) && ud.pincode) {
          this.setState({nextBtnEnable: true});
        } else {
          this.setState({nextBtnEnable: false});
        }
      }
      // case for checking country other then india
      else if (ud.country_res !== "51" && ud.res_status) {
        this.setState({nextBtnEnable: true});
      }
    } else if (ud && ud.gender && ud.height && ud.country_res === "136" && ud.dtofbirth_day) {
      this.setState({nextBtnEnable: true});
    } else {
      this.setState(() => ({
        nextBtnEnable: false
      }));
    }

  }


  render() {
    // resizeContainer('personalDetailsContainer');
    let pinTable = null;
    let ud_display = getItem('UD_display');
    let ud = getItem('UD');
    let staticTableData = getItem('staticTableData');
    if (ud && staticTableData && ud.city_res && staticTableData.hasOwnProperty('citypincode')) {
      pinTable = staticTableData['citypincode'][ud.city_res];
    }

    // let height = this.setDataOnReRender(ud,'height')
    let monthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    return (
      <div className="posabs fw">
        {this.state.showErrorReg &&
        <TopError timeToHide={3000} errorObj={this.state.errorObj} topPosition={55} leftAlign={true}/>}
        <div className="fw bg1" onClick={() => {
          scrollOnTop('personalDetailsContainer')
        }}>
          <RegistrationHeader headerData="Personal Details" page="1"
                              onIconClick={this.props.onIconClick}/>
        </div>
        <div className='bg4 sliderDataContainer'
             style={{
               height: (window.innerHeight - 110) + 'px', overflow: 'auto'
             }}
             id="personalDetailsContainer">

          {(ud &&
            ud.hasOwnProperty('relationship') &&
            (ud.relationship == "1" || ud.relationship == "4" || ud.relationship == "5"))
          &&
          <div className="pad19r bg4 brdr1" id="gender">
            <div className={classNames(this.state.errorObj.gender ? 'color2' : 'color8', 'f12 fontlig')}>
              Gender
            </div>
            <div className="md-radio md-radio-inline">
              <input id="3" type="radio" name="g2"
                     checked={ud.gender === "F"}
                     onChange={() => this.genderClick('F')}/>
              <label className="color11 fontlig"
                     htmlFor="3">Female</label>
            </div>
            <div className="md-radio md-radio-inline">
              <input id="4" type="radio" name="g2"
                     checked={ud.gender === "M"}
                     onChange={() => this.genderClick('M')}/>
              <label className="color11 fontlig"
                     htmlFor="4">Male</label>
            </div>
            <div className="clr"/>
          </div>
          }
          <RegistrationSlider heading='Reg_Date_of_birth'
                              showHeading='Date of birth'
                              text={ud && ud.dtofbirth_day ? `${ud.dtofbirth_day} ${monthArray[ud.dtofbirth_month - 1]}
                               ${ud.dtofbirth_year}` : 'Not Filled In'}
                              hamState={this.hamState}
                              startFromMiddle={true}
                              error={this.state.errorObj.dob}
                              dobSlider={this.state.dobSlider}/>
          <RegistrationSlider heading='Reg_Height'
                              showHeading='Height'
                              text={ud_display && ud_display.height ? ud_display.height : "Not Filled In"}
                              error={this.state.errorObj.height}
                              inputDataForSlider={this.inputDataForSlider}
                              localStorageFeildName='height'
                              hamState={this.hamState}
                              startFromMiddle={true}
                              singleSlider={this.state.singleSlider}/>

          <RegistrationSlider heading='Reg_Country_living_in'
                              showHeading='Country living in'
                              error={this.state.errorObj.country_res}
                              text={ud_display && ud_display.country_res ? ud_display.country_res : "Not Filled In"}
                              localStorageFeildName='country_res'
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              hamState={this.hamState}
                              searchSlider={this.state.countrySlider}/>
          {ud && ud.country_res === '51' ?
            <RegistrationSlider heading='Reg_State_City_living_in'
                                showHeading='State-City living in'
                                text={ud_display && ud_display.state_res &&
                                ud_display.city_res ? `${ud_display.state_res}
                                                                - ${ud_display.city_res}` : "Not Filled In"}
                                startFromMiddle={false}
                                localStorageFeildName='state_res'
                                localStorageFeildName2='city_res'
                                inputDataForSlider={this.inputDataForSlider}
                                hamState={this.hamState}
                                doubleSlider={this.state.stateSlider}
                                showSearch1={true}
                                error={this.state.errorObj.state_res}
                                showSearch2={true}
                                header1='State'
                                header2='City'/> : ''}

          {this.showCityAndResidentialStatus &&
          <RegistrationSlider heading='Reg_City'
                              showHeading='City'
                              error={this.state.errorObj.city_res}
                              text={ud_display && ud_display.city_res ? ud_display.city_res
                                : "Not Filled In"}
                              localStorageFeildName='city_res'
                              startFromMiddle={false}
                              inputDataForSlider={this.inputDataForSlider}
                              hamState={this.hamState}
                              searchSlider={this.state.citySlider}/>}

          {this.showCityAndResidentialStatus &&
          <RegistrationSlider heading='Reg_Residential_Status'
                              showHeading='Residential Status'
                              error={this.state.errorObj.res_status}
                              text={ud_display && ud_display.res_status ? ud_display.res_status
                                : "Not Filled In"}
                              localStorageFeildName='res_status'
                              startFromMiddle={false}
                              inputDataForSlider={this.inputDataForSlider}
                              hamState={this.hamState}
                              singleSlider={this.state.residencySlider}/>}


          {staticTableData && pinTable && <div className="brdr1 bg4">
            <div className="pad1">
              <div className="pad2" onClick={()=>{
                document.getElementById('pinCode').focus()
              }}>
                <div className="fl wid60p">
                  <div
                    className={classNames(this.state.errorObj.pincode ? 'color2' : 'color8', 'f12 fontlig')}>Area
                    Pincode
                  </div>
                  <input defaultValue={ud.pincode ? ud.pincode : ''}
                         id="pinCode"
                         maxLength="6"
                         className="color11 f15 pt10 fontlig registrationInput regSliderBlock setRD"
                         onFocus={e => {
                           focusOnCurrentElement('pinCode')
                         }}
                         onChange={e => {
                           resizeContainer('personalDetailsContainer');
                           let val = e.target.value;
                           if (val.length > 6 || val.length == 6) {
                             e.target.blur();
                           }
                         }}
                         onKeyUp={(e) => {
                           let val = e.target.value;
                           let value = '';
                           if (val && val.length > 6) {
                             value = parseInt(val.toString().substring(0, 6));
                             e.target.value = value;
                           } else if (ud.pincode || val.length < 6) {
                             delete ud.pincode;
                             setItem('UD', ud);
                           }
                         }}
                         onBlur={e => {
                           let val = e.target.value;
                           if (val && val.length === 6) {
                             for (let i in pinTable[0]) {
                               let inp = pinTable[0][i];
                               let pattern = new RegExp('^(' + inp + ')');
                               let res = pattern.test(val);
                               if (res) {
                                 ud['pincode'] = val;
                                 setItem('UD', ud);
                                 this.setState({
                                   pinErr: false,
                                   errorObj: {}
                                 }, () => {
                                   this.setErrorReg();
                                   this.enableNextBtn()
                                 });
                                 break;
                               } else {
                                 if (ud['pincode']) delete ud['pincode'];
                                 setItem('UD', ud);
                                 this.setState({
                                   pinErr: true,
                                   errorObj: {
                                     pincode: (() => {
                                       let result = errorStatements.PINCODE_ERROR_1;
                                       if (ud_display && ud_display.city_res) {
                                         result = `${errorStatements.PINCODE_ERROR_1} that belongs to ${ud_display.city_res}`
                                       }
                                       return result
                                     })()
                                   }
                                 }, () => {
                                   this.setErrorReg();
                                   this.enableNextBtn()
                                 });
                               }
                             }
                           } else {
                             this.setState({
                               pinErr: true,
                               errorObj: {
                                 pincode: errorStatements.PINCODE_ERROR_2
                               }
                             }, () => {
                               this.setErrorReg();
                               this.enableNextBtn()
                             });
                           }
                           resizeContainer('personalDetailsContainer');
                           scrollToBottom('personalDetailsContainer');
                           removeFocusFromAllElements();
                         }}
                         type="number" placeholder="Your area pincode"/>

                </div>

                {this.state.pinErr &&
                <div className="fr wid4p pt8 mar10right">
                  <i className="mainsp reg_errorIcon"/></div>}
                <div className="clr"/>
              </div>
            </div>
          </div>}

        </div>
        {this.state.nextBtnEnable ?
          <div className='bg7 fw'
               onClick={() => {
                 this.props.onIconClick('nextPage', 2)
               }}>
            <RegistrationFooter text={"Next"}/>
          </div> :

          <div className='bggrey fw'
               onClick={this.nextClick}>
            <RegistrationFooter text={"Next"}/>
          </div>
        }
      </div>
    )
  }
}

PersonalDetails.propTypes = {
  onIconClick: PropTypes.func.isRequired
};

export default PersonalDetails;
