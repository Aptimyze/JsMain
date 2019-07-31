import React from 'react';
//components
import RegistrationHeader from '../common/RegistrationHeader';
import RegistrationFooter from '../common/RegistrationFooter';
import RegistrationSlider from "../common/RegistrationSlider";
//helpers and services
import {preProcessInput, processFromMultipleArrays} from "../../helpers/dataPreprocessor";
import {
  editClass,
  focusOnCurrentElement,
  removeFocusFromAllElements,
  resizeContainer,
  scrollOnTop
} from "../../helpers/screenHandlers";
import {getItem, removeItem, setItem} from "../../services/localStorage";
import {errorGATracking} from '../../helpers/gaHandler';
//constants
import errorStatements from "../../constant/errorStatements";
import TopError from "../../../common/components/TopError";


class MissingDetail extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      enableRegBtn: false,
      errorObj: {},
      errorRegData: '',
      highestEducationSlider: false,
      dobSlider:false,
      workAreaSlider: false,
      employedInSlider: false,
      annualIncomeSlider: false,
      nextBtnEnable: false,
      heightSlider: false,
      stateSlider: false,
      citySlider: false,
      maritalStatusSlider: false,
      motherTongueSlider: false,
      religionCasteSlider: false,
      showErrorReg: false,
      DTOFBIRTH:false,
      HEIGHT: false,
      COUNTRY_RES: false,
      MSTATUS: false,
      RELIGION: false,
      MTONGUE: false,
      EDU_LEVEL_NEW: false,
      OCCUPATION: false,
      INCOME: false,


    };
    this.hamState = this.hamState.bind(this);
    this.nextClick = this.nextClick.bind(this);
    this.incompleteDataKeys = [];
  }


  componentDidMount() {
    this.enableNextBtn();
  }

  componentWillReceiveProps() {
    this.enableNextBtn();
    this.setState({showErrorReg: false});
  }

  hamState(showRegHamburger, hamName, errorRegData) {
    let hamDetails = {'showRegHamburger': showRegHamburger, 'hamName': hamName};
    setItem('hamDetails', hamDetails);
    this.inputDataForSlider = [];
    let staticTableData = getItem('staticTableData');
    let ud = getItem('UD');
    let reg_city_jspc = getItem('reg_city_jspc');
    let staticData = getItem('staticData');
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
    editClass(showRegHamburger);
    if (showRegHamburger) {
      focusOnCurrentElement('')
    } else {
      removeFocusFromAllElements()
    }
    switch (hamName) {
      case 'Reg_Date_of_birth': {
        this.setState({dobSlider: showRegHamburger}, () => {
          this.enableNextBtn();
        });
        break;
      }
      case 'Reg_Height': {
        if (staticTableData && staticTableData.hasOwnProperty('height')
            && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.height[0]);
        }
        this.setState({heightSlider: showRegHamburger});
        break;
      }


      case 'Reg_Country_living_in': {
        if (staticTableData && staticTableData.hasOwnProperty('country_res') && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.country_res[0]);
        }
        this.setState({countrySlider: showRegHamburger})
        break;
      }
      case 'Reg_State_City_living_in': {
        if (staticTableData && staticTableData.hasOwnProperty('state_res') && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.state_res[0]);
        }
        //country_res
        this.setState({stateSlider: showRegHamburger})
        break;
      }
      case 'Reg_City': {
        if (reg_city_jspc && showRegHamburger) {
          this.inputDataForSlider = processFromMultipleArrays(reg_city_jspc, ud.country_res);
        }
        this.setState({citySlider: showRegHamburger})
        break;
      }
      case 'Reg_Highest_Education': {
        if (staticTableData && staticTableData.hasOwnProperty('edu_level_new')
            && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.edu_level_new[0]);
        }
        this.setState({highestEducationSlider: showRegHamburger})
        break;
      }

      case 'Reg_Employed_In': {
        if (staticTableData && staticTableData.hasOwnProperty('employed_in')
            && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.employed_in[0]);
        }
        this.setState({employedInSlider: showRegHamburger})
        break;
      }

      case 'Reg_Work_Area':
      case 'Reg_Occupation': {
        if (staticTableData && showRegHamburger) {
          if (staticTableData.hasOwnProperty('occupation_old')) {
            this.inputDataForSlider = preProcessInput(staticTableData.occupation_old[0]);
          } else if (staticTableData.hasOwnProperty('occupation')) {
            let occupationData = staticTableData.occupation[ud.employed_in];
            this.inputDataForSlider = preProcessInput(occupationData);
          }
        }
        this.setState({workAreaSlider: showRegHamburger})
        break;
      }


      case 'Reg_Annual_Income': {
        let preFilledCountry = ud.country_res;
        //selfCountry
        if (staticData && staticData.hasOwnProperty('selfCountry') && staticData['selfCountry'] != 0) {
          preFilledCountry = staticData['selfCountry'];
        }
        if (staticTableData && staticTableData.hasOwnProperty('income')
            && showRegHamburger) {
          this.inputDataForSlider = processFromMultipleArrays(staticTableData.income, preFilledCountry, 'income');
        }
        this.setState({annualIncomeSlider: showRegHamburger});
        break;
      }

      case 'Reg_Marital_Status': {
        if (staticTableData && staticTableData.hasOwnProperty('reg_mstatus')
            && showRegHamburger) {
          let sex = 'M';
          if (!ud.gender) {
            sex = staticData['selfGender'];
          }
          let gender = ud.gender ? ud.gender : sex;
          this.inputDataForSlider = processFromMultipleArrays(staticTableData.reg_mstatus, gender, '');
        }
        this.setState({maritalStatusSlider: showRegHamburger}, () => {
          this.enableNextBtn();
        });
        break;
      }

      case 'Reg_Mother_Tongue': {
        if (staticTableData && staticTableData.hasOwnProperty('mtongue')
            && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.mtongue[0]);
        }
        this.setState({motherTongueSlider: showRegHamburger})
        break;
      }

      case 'Reg_Religion_Caste': {
        if (staticTableData && staticTableData.hasOwnProperty('religion')
            && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.religion[0]);
        }
        this.setState({religionCasteSlider: showRegHamburger}, () => {
          this.enableNextBtn();
        });
        break;
      }
    }
    this.enableNextBtn();
  }


  nextClick() {
    let ud = getItem('UD');
    let errorObj = {};
    errorObj.dob = !this.incompleteDataKeys.includes('DTOFBIRTH') || ud.dtofbirth_day ? '' : errorStatements.DOB_ERROR_0;
    errorObj.height = !this.incompleteDataKeys.includes('HEIGHT') || ud.height ? '' : errorStatements.HEIGHT;
    errorObj.country_res = !this.incompleteDataKeys.includes('COUNTRY_RES') || ud.country_res ? '' : errorStatements.COUNTRY_RES;
    if (ud.country_res === "51") {
      errorObj.state_res = !this.incompleteDataKeys.includes('STATE_RES') || ud.state_res ? '' : errorStatements.STATE_RES;
    } else if ((!this.incompleteDataKeys.includes('COUNTRY_RES') || ud.country_res) && ud.country_res !== "51" && ud.country_res !== "136") {
      errorObj.city_res = !this.incompleteDataKeys.includes('CITY_RES') || ud.city_res ? '' : errorStatements.CITY_RES;
    }
    errorObj.edu_level_new = !this.incompleteDataKeys.includes('EDU_LEVEL_NEW') || ud.edu_level_new ? '' : errorStatements.HIGHEST_EDUCATION;
    errorObj.employed_in = !this.incompleteDataKeys.includes('EMPLOYED_IN') || ud.employed_in ? '' : errorStatements.EMPLOYED_IN;
    if (ud.employed_in) errorObj.occupation = !this.incompleteDataKeys.includes('OCCUPATION') || ud.occupation ? '' : errorStatements.WORK_AREA;
    errorObj.income = !this.incompleteDataKeys.includes('INCOME') || ud.income ? '' : errorStatements.INCOME;
    errorObj.mstatus = !this.incompleteDataKeys.includes('MSTATUS') || ud.mstatus ? '' : errorStatements.MERITAL_STATUS;
    errorObj.mtongue = !this.incompleteDataKeys.includes('MTONGUE') || ud.mtongue ? '' : errorStatements.MOTHER_TONGUE;
    errorObj.religion = !this.incompleteDataKeys.includes('RELIGION') || ud.religion ? '' : errorStatements.RELIGION_CASTE;


    this.setState({errorObj}, () => {
      for (let i in errorObj) {
        if (!errorObj[i]) delete errorObj[i]
      }
      this.setErrorReg();
      let err='';
      for(let obj in this.state.errorObj){
        err+= this.state.errorObj[obj]+ ' ';
      }
      errorGATracking('s2',err);
    });
  }


  setErrorReg() {
    if (Object.keys(this.state.errorObj).length >= 1) this.setState({showErrorReg: true});
    else this.setState({showErrorReg: false});
  }

  editClass(showRegHamburger) {
    let regSliderBlock = document.getElementsByClassName('regSliderBlock');
    if (showRegHamburger) {
      for (let i = 0; i < regSliderBlock.length; i++) {
        regSliderBlock[i].classList.add('reg_pointnone');
      }
    } else {
      for (let i = 0; i < regSliderBlock.length; i++) {
        regSliderBlock[i].classList.remove('reg_pointnone');
      }
    }
  }

  enableNextBtn() {
    let enableBtn = true;
    let ud = getItem("UD");
    let elem = document.getElementsByClassName('regSliderBlock');
    if (ud && elem && elem.length > 0) {
      for (let i = 0; i < elem.length; i++) {
        if (elem[i].innerText) {
          if (elem[i].innerText.includes('Not Filled In')) {
            //console.log(elem[i].innerText);
            enableBtn = false;
            break
          }
        }
      }
    }
    if (enableBtn) {
      this.setState({nextBtnEnable: true});
    } else {
      this.setState({nextBtnEnable: false});
    }
  }


  fillMissingDetails() {
    let ud = getItem('UD');
    let errorObj = {};
    if ((ud && ud.mstatus) == 'M') {
      if (ud.religion !== '2') {
        errorObj.mstatus = errorStatements.MARRIED_MUSLIM;
        this.setState({errorObj}, () => {
          for (let i in errorObj) {
            if (!errorObj[i]) delete errorObj[i]
          }
          this.setErrorReg();
        });
      } else {
        this.props.onIconClick('nextPage', 6);
        removeItem('hamDetails');
      }
    } else {
      this.props.onIconClick('nextPage', 6);
      removeItem('hamDetails');
    }

  }

  handleBack() {
    this.props.onIconClick('previousPage', 7);
  }

  render() {
    // resizeContainer('MissingDetailPage');
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    let staticUserData = getItem('staticData');
    this.incompleteDataKeys = staticUserData.Incomplete.map((data) => {
      return data.key;
    });
    let monthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    let religion = '';
    let mStatus = '';
    if (ud_display) {
      if (ud_display.mstatus) {
        mStatus = ud_display.mstatus
      }
      if (ud_display.havechild) {
        mStatus += '-' + ud_display.havechild;
      }
      if (ud_display.religion) {
        religion = ud_display.religion;
      }
      if (ud_display.caste) {
        religion += '-' + ud_display.caste;
      }
    }
    return (

        <div className="posabs fw">
          {this.state.showErrorReg &&
          <TopError timeToHide={3000} errorObj={this.state.errorObj} leftAlign={true} topPosition={55}/>}
          <div className="fw bg1" onClick={() => {
            scrollOnTop('MissingDetailPage')
          }}>
            <RegistrationHeader headerData="Provide Missing Details"
                                onIconClick={this.handleBack.bind(this)}/>
          </div>
          <div className='bg4 sliderDataContainer'
               style={{
                 height: (window.innerHeight - 110) + 'px', overflow: 'auto'
               }}
               id="MissingDetailPage">
            {this.incompleteDataKeys.includes('DTOFBIRTH') &&
            <RegistrationSlider heading='Reg_Date_of_birth'
                                showHeading='Date of birth'
                                marginLeft={this.props.margin}
                                text={ud && ud.dtofbirth_day ? `${ud.dtofbirth_day} ${monthArray[ud.dtofbirth_month - 1]}
                               ${ud.dtofbirth_year}` : 'Not Filled In'}
                                hamState={this.hamState}
                                startFromMiddle={true}
                                error={this.state.errorObj.dob}
                                dobSlider={this.state.dobSlider}/>}

            {this.incompleteDataKeys.includes('HEIGHT') &&
            <RegistrationSlider heading="Reg_Height"
                                showHeading='Height'
                                marginLeft={this.props.margin}
                                error={this.state.errorObj.height}
                                text={ud_display && ud_display.height ? ud_display.height : "Not Filled In"}
                                inputDataForSlider={this.inputDataForSlider}
                                localStorageFeildName='height'
                                hamState={this.hamState}
                                startFromMiddle={true}
                                singleSlider={this.state.heightSlider}/>}

            {this.incompleteDataKeys.includes('COUNTRY_RES') &&
            <RegistrationSlider heading="Reg_Country_living_in"
                                error={this.state.errorObj.country_res}
                                showHeading='Country living in'
                                marginLeft={this.props.margin}
                                text={ud_display && ud_display.country_res ? ud_display.country_res : "Not Filled In"}
                                localStorageFeildName='country_res'
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                hamState={this.hamState}
                                searchSlider={this.state.countrySlider}/>}

            {ud && ud.country_res === '51' ?
                <RegistrationSlider heading='Reg_State_City_living_in'
                                    error={this.state.errorObj.state_res}
                                    showHeading='State-City living in'
                                    marginLeft={this.props.margin}
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
                                    showSearch2={true}
                                    header1='State'
                                    header2='City'/> : ''}

            {ud && ud.country_res && ud.country_res != '51' && ud.country_res != "136" &&
            <RegistrationSlider heading='Reg_City'
                                error={this.state.errorObj.city_res}
                                showHeading='City'
                                text={ud_display && ud_display.city_res ? ud_display.city_res
                                    : "Not Filled In"}
                                localStorageFeildName='city_res'
                                startFromMiddle={false}
                                marginLeft={this.props.margin}
                                inputDataForSlider={this.inputDataForSlider}
                                hamState={this.hamState}
                                searchSlider={this.state.citySlider}/>}


            {this.incompleteDataKeys.includes('EDU_LEVEL_NEW') &&
            <RegistrationSlider heading="Reg_Highest_Education"
                                error={this.state.errorObj.edu_level_new}
                                showHeading='Highest Qualification'
                                text={ud_display && ud_display.edu_level_new ? ud_display.edu_level_new : "Not Filled In"}
                                marginLeft={this.props.margin}
                                localStorageFeildName='edu_level_new'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                searchSlider={this.state.highestEducationSlider}/>}


            {this.incompleteDataKeys.includes('EMPLOYED_IN') &&
            <RegistrationSlider heading='Reg_Employed_In'
                                showHeading='Employed In'
                                marginLeft={this.props.margin}
                                text={ud_display && ud_display.employed_in ? ud_display.employed_in : "Not Filled In"}
                                localStorageFeildName='employed_in'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                singleSlider={this.state.employedInSlider}/>
            }

            {this.incompleteDataKeys.includes('OCCUPATION') &&
            ud && ud.employed_in &&
            <RegistrationSlider heading="Reg_Occupation"
                                showHeading='Occupation'
                                marginLeft={this.props.margin}
                                error={this.state.errorObj.occupation}
                                text={ud_display && ud_display.occupation ? ud_display.occupation : "Not Filled In"}
                                localStorageFeildName='occupation'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                searchSlider={this.state.workAreaSlider}/>}


            {this.incompleteDataKeys.includes('INCOME') &&
            <RegistrationSlider heading="Reg_Annual_Income"
                                showHeading='Annual Income'
                                marginLeft={this.props.margin}
                                error={this.state.errorObj.income}
                                text={ud_display && ud_display.income ? ud_display.income : "Not Filled In"}
                                localStorageFeildName='income'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                singleSlider={this.state.annualIncomeSlider}/>}

            {this.incompleteDataKeys.includes('MSTATUS') &&
            <RegistrationSlider heading="Reg_Marital_Status"
                                showHeading='Marital Status'
                                error={this.state.errorObj.mstatus}
                                text={mStatus ? mStatus : "Not Filled In"}
                                startFromMiddle={false}
                                localStorageFeildName='mstatus'
                                localStorageFeildName2='havechild'
                                inputDataForSlider={this.inputDataForSlider}
                                hamState={this.hamState}
                                showSearch1={false}
                                marginLeft={this.props.margin}
                                showSearch2={false}
                                doubleSlider={this.state.maritalStatusSlider}
                                header1='Marital Status'
                                header2='Have Children'/>}

            {this.incompleteDataKeys.includes('MTONGUE') &&
            <RegistrationSlider heading="Reg_Mother_Tongue"
                                showHeading='Mother Tongue'
                                marginLeft={this.props.margin}
                                error={this.state.errorObj.mtongue}
                                text={ud_display && ud_display.mtongue ? ud_display.mtongue : "Not Filled In"}
                                localStorageFeildName='mtongue'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                searchSlider={this.state.motherTongueSlider}/>}

            {this.incompleteDataKeys.includes('RELIGION') &&
            <RegistrationSlider heading="Reg_Religion_Caste"
                                showHeading='Religion-Caste'
                                error={this.state.errorObj.religion}
                                text={religion ? religion : "Not Filled In"}
                                startFromMiddle={false}
                                localStorageFeildName='religion'
                                localStorageFeildName2='caste'
                                marginLeft={this.props.margin}
                                inputDataForSlider={this.inputDataForSlider}
                                hamState={this.hamState}
                                showSearch1={false}
                                showSearch2={true}
                                doubleSlider={this.state.religionCasteSlider}
                                header1='Religion'
                                header2='Caste'/>}


          </div>
          {this.state.nextBtnEnable ?
              <div className='bg7 fw'
                   onClick={this.fillMissingDetails.bind(this)}>
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

export default MissingDetail;

