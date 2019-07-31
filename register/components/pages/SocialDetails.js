import React from 'react';
import classNames from 'classnames';
//components
import RegistrationHeader from '../common/RegistrationHeader';
import RegistrationFooter from '../common/RegistrationFooter';
import RegistrationSlider from "../common/RegistrationSlider";
//helpers and services
import {preProcessInput, processFromMultipleArrays} from "../../helpers/dataPreprocessor";
import {
  scrollToBottom, resizeContainer, scrollOnTop, editClass, blurInputs
} from "../../helpers/screenHandlers";
import {getItem, setItem} from "../../services/localStorage";
import {errorGATracking} from '../../helpers/gaHandler';
//constants
import errorStatements from "../../constant/errorStatements";
import TopError from "../../../common/components/TopError";
class SocialDetails extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      enableRegBtn: false,
      errorObj: {},
      errorRegData: '',
      casteNoBar: false,
      maritalStatusSlider: false,
      motherTongueSlider: false,
      religionCasteSlider: false,
      showErrorReg: false,
      jamaatSlider: false,
      casteSlider: false,
      nextBtnEnable: false,
      subcasteSlider: false,
      horoscopeSlider: false
    };
    this.hamState = this.hamState.bind(this);
    this.nextClick = this.nextClick.bind(this);
    this.nextEnableClick = this.nextEnableClick.bind(this);
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
    let ud_display = getItem('UD_display');
    this.setState({errorObj: {}}, () => {
      this.setErrorReg();
    });
    blurInputs();
    editClass(showRegHamburger);
    switch (hamName) {
      case 'Reg_Marital_Status': {
        if (staticTableData && staticTableData.hasOwnProperty('reg_mstatus')
          && showRegHamburger) {
          let gender = ud.gender ? ud.gender : "F";
          this.inputDataForSlider = processFromMultipleArrays(staticTableData.reg_mstatus, gender, '');
          if(gender==='M'){
            if(this.inputDataForSlider){
              let married = {
                code:"M",
                name: "Married"
              };
              this.inputDataForSlider = this.inputDataForSlider.filter((item)=>{
                if(item.code !== "M") return item;
              });
              this.inputDataForSlider.push(married);
            }
          }
        }
        this.setState({maritalStatusSlider: showRegHamburger}, ()=>{
          this.enableNextBtn();
        });
        break;
      }

      case 'Reg_Mother_Tongue': {
        if (staticTableData && staticTableData.hasOwnProperty('mtongue')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.mtongue[0]);
        }
        this.setState({motherTongueSlider: showRegHamburger});
        break;
      }

      case 'Reg_Religion_Caste': {
        if (staticTableData && staticTableData.hasOwnProperty('religion')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.religion[0]);
        }
        this.setState({religionCasteSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.religion) {
            scrollToBottom('SocialDetailsContainer');
          }
          this.enableNextBtn();
        });
        if (showRegHamburger === false) {
          if (ud.religion == '1') {
            //hindu
            //todo subcaste
            delete ud.sect;
            delete ud.jamaat;
            delete ud_display.sect;
            delete ud_display.jamaat;

          } else if (ud.religion == '2') {
            //muslim
            delete ud.casteNoBar;
            delete ud.subcaste;
            delete ud.horoscope_match;
            delete ud_display.casteNoBar;
            delete ud_display.subcaste;
            delete ud_display.horoscope_match;
            if (ud.caste == '151') {
              //shia
              delete ud.jamaat;
              delete ud_display.jamaat;
            }
          } else if (ud.religion == '4') {
            //sikh
            delete ud.sect;
            delete ud.subcaste;
            delete ud.jamaat;
            delete ud_display.sect;
            delete ud_display.subcaste;
            delete ud_display.jamaat;
          } else if (ud.religion == '3' || ud.religion == '7' || ud.religion == '5' || ud.religion == '6' || ud.religion == '10') {
            //christian - 3, buddhist - 7, parsi - 5, jewish - 6, bahai - 10
            delete ud.casteNoBar;
            delete ud.sect;
            delete ud.subcaste;
            delete ud.jamaat;
            delete ud_display.casteNoBar;
            delete ud_display.sect;
            delete ud_display.subcaste;
            delete ud_display.jamaat;
            if (ud.religion != '3') {
              delete ud.caste;
              delete ud_display.caste;
            }
            if (ud.religion != '7') {
              delete ud.horoscope_match;
              delete ud_display.horoscope_match;
            }
          } else if (ud.religion == '9') {
            //jain
            delete ud.sect;
            delete ud_display.sect;

            if (ud.caste == '246') {
              delete ud.subcaste;
              delete ud_display.subcaste;
            }
            delete ud.jamaat;
            delete ud_display.jamaat;
          }
          setItem('UD', ud);
          setItem('UD_display', ud_display);
        }
        break;
      }

      case 'Reg_Jamaat': {
        if (staticTableData && staticTableData.hasOwnProperty('jamaat')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.jamaat[0]);
        }
        this.setState({jamaatSlider: showRegHamburger});
        break;
      }
      case 'Reg_Caste': {
        if (staticTableData && staticTableData.hasOwnProperty('sect')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.sect[ud.religion][0]);
        }
        this.setState({casteSlider: showRegHamburger});
        break;
      }

      case 'Reg_Sub_Caste': {
        if (staticTableData && staticTableData.hasOwnProperty('subcaste')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.subcaste[ud.caste][0]);
        }
        this.setState({subcasteSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.subcaste) {
            scrollToBottom('SocialDetailsContainer');
          }
        });
        break;
      }
      case 'Reg_Horoscope': {
        if (staticTableData && staticTableData.hasOwnProperty('horoscope_match')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.horoscope_match[0]);
        }
        this.setState({horoscopeSlider: showRegHamburger});
        break;
      }
    }
    this.enableNextBtn();
  }

  nextEnableClick() {
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
        this.props.onIconClick('nextPage',4);
      }
    } else {
      this.props.onIconClick('nextPage',4);
    }
  }

  nextClick() {
    let ud = getItem('UD');
    let errorObj = {};
    errorObj.mstatus = ud.mstatus ? '' : errorStatements.MERITAL_STATUS;


    // errorObj.mstatus = ud.mstatus ? (ud.mstatus == 'M' && ud.religion=='2' ? '':errorStatements.MARRIED_MUSLIM) : errorStatements.MERITAL_STATUS;
    errorObj.mtongue = ud.mtongue ? '' : errorStatements.MOTHER_TONGUE;
    errorObj.religion = ud.religion ? '' : errorStatements.RELIGION_CASTE;

    this.setState({errorObj}, () => {
      for (let i in errorObj) {
        if (!errorObj[i]) delete errorObj[i]
      }
      this.setErrorReg();
      let err='';
      for(let obj in this.state.errorObj){
        err+= this.state.errorObj[obj]+ ' ';
      }
      errorGATracking('s4',err);
    });
  }

  setErrorReg() {
    if (Object.keys(this.state.errorObj).length >= 1) this.setState({showErrorReg: true});
    else this.setState({showErrorReg: false});
  }

  enableNextBtn() {
    let ud = getItem("UD");
    if (ud && ud.mstatus && ud.mtongue && ud.religion) {
      this.setState({nextBtnEnable: true});
    } else {
      this.setState(() => ({
        nextBtnEnable: false
      }));
    }
  }

  handleCheckboxChange() {
    let ud = getItem('UD');
    if (document.getElementById('casteNoBar_check').checked) {
      ud.casteNoBar = 'true';
    } else {
      ud.casteNoBar = 'false';
    }
    this.setState({casteNoBar: ud.casteNoBar});
    setItem('UD', ud);
  }


  render() {
    // resizeContainer('SocialDetailsContainer');
    let ud = getItem('UD');
    let staticTableData = getItem('staticTableData');
    let ud_display = getItem('UD_display');
    let religion = '';
    let mStatus = '';
    if (ud_display) {
      if (ud_display.mstatus) {
        mStatus = ud_display.mstatus
      }
      if (ud.havechild) {
        switch (ud.havechild) {
          case 'N' :
            mStatus += '- ' + 'With no children';
            break;
          case 'YT' :
            mStatus += '- ' + 'With children living together';
            break;
          case 'YS':
            mStatus += '- ' + 'With children living separately';
            break;
        }

      }
      if (ud_display.religion) {
        religion = ud_display.religion;
      }
      if (ud_display.caste) {
        religion += '-' + ud_display.caste;
      }
    }
    let checkDetail = false;
    if (ud && staticTableData && staticTableData.hasOwnProperty('subcaste')) {
      checkDetail = (Object.keys(staticTableData.subcaste).includes(ud.caste));
    }


    return (
      <div className="posabs fw">
        {this.state.showErrorReg &&
        <TopError timeToHide={3000} errorObj={this.state.errorObj}
                  topPosition={55} leftAlign={true}/>}
        <div className="fw bg1" onClick={() => {
          scrollOnTop('SocialDetailsContainer')
        }}>
          <RegistrationHeader headerData="Social Details" page="3"
                              onIconClick={this.props.onIconClick}/>
        </div>
        <div className='bg4 sliderDataContainer'
             style={{
               height: (window.innerHeight - 110) + 'px', overflow: 'auto'
             }}
             id="SocialDetailsContainer">
          <RegistrationSlider heading="Reg_Marital_Status"
                              showHeading='Marital Status'
                              text={mStatus ? mStatus : "Not Filled In"}
                              startFromMiddle={false}
                              error={this.state.errorObj.mstatus}
                              localStorageFeildName='mstatus'
                              localStorageFeildName2='havechild'
                              inputDataForSlider={this.inputDataForSlider}
                              hamState={this.hamState}
                              showSearch1={false}
                              showSearch2={false}
                              doubleSlider={this.state.maritalStatusSlider}
                              header1='Marital Status'
                              header2='Have Children'/>


          <RegistrationSlider heading="Reg_Mother_Tongue"
                              showHeading='Mother Tongue'
                              text={ud_display && ud_display.mtongue ? ud_display.mtongue : "Not Filled In"}
                              error={this.state.errorObj.mtongue}
                              localStorageFeildName='mtongue'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              searchSlider={this.state.motherTongueSlider}/>

          <RegistrationSlider heading="Reg_Religion_Caste"
                              showHeading='Religion-Caste'
                              text={religion ? religion : "Not Filled In"}
                              startFromMiddle={false}
                              localStorageFeildName='religion'
                              localStorageFeildName2='caste'
                              inputDataForSlider={this.inputDataForSlider}
                              hamState={this.hamState}
                              showSearch1={false}
                              showSearch2={true}
                              error={this.state.errorObj.religion}
                              doubleSlider={this.state.religionCasteSlider}
                              header1='Religion'
                              header2='Caste'/>

          {ud && (ud.religion == '1' || ud.religion == '4' || ud.religion == '9') ? <div className="brdr1"
                                                                                         id="reg_casteNoBar">
            <div className="pad1">
              <div className="pad2">
                <div className="fl reg_wid90 casteNoBar_check">
                  <input type="checkbox" id="casteNoBar_check"
                         checked={ud && ud.casteNoBar && ud.casteNoBar == 'true' ? true : false}
                         onChange={this.handleCheckboxChange.bind(this)}/>
                  <label className="fontlig"
                         htmlFor="casteNoBar_check">
                         Caste No Bar
                         <br/>
                         <span className="pHelp">
                           I am open to marry people of all castes
                         </span>
                    </label></div>
                <div className="clr"/>
              </div>
            </div>
          </div> : null
          }

          {staticTableData && ud && checkDetail ?
            <RegistrationSlider heading="Reg_Sub_Caste"
                                showHeading='Sub-Caste'
                                text={ud_display && ud_display.subcaste ? ud_display.subcaste : "Not Filled In"}
                                error={false}
                                localStorageFeildName='subcaste'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                singleSlider={this.state.subcasteSlider}/> : null
          }

          {ud && (ud.religion == '2') && (ud.caste == '152') ?
            <RegistrationSlider heading="Reg_Jamaat"
                                showHeading='Jamaat'
                                text={ud_display && ud_display.jamaat ? ud_display.jamaat : "Not Filled In"}
                                error={false}
                                localStorageFeildName='jamaat'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                singleSlider={this.state.jamaatSlider}
                                header='Jamaat'/> : null
          }

          {ud && (ud.religion == '2') && (ud.caste == '152' || ud.caste == '151') ?
            <RegistrationSlider heading="Reg_Caste"
                                showHeading='Caste'
                                text={ud_display && ud_display.sect ? ud_display.sect : "Not Filled In"}
                                error={false}
                                localStorageFeildName='sect'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                searchSlider={this.state.casteSlider}
                                header='Caste'/> : null
          }
          {ud && (ud.religion == '1' || ud.religion == '4' || ud.religion == '7' || ud.religion == '9') ?
            <RegistrationSlider heading="Reg_Horoscope"
                                showHeading='Horoscope match is necessary? (optional)'
                                text={ud_display && ud_display.horoscope_match ? ud_display.horoscope_match : "Not Filled In"}
                                error={false}
                                localStorageFeildName='horoscope_match'
                                hamState={this.hamState}
                                inputDataForSlider={this.inputDataForSlider}
                                startFromMiddle={false}
                                singleSlider={this.state.horoscopeSlider}
                                header='Horoscope match is necessary?'/> : null
          }

        </div>
        {this.state.nextBtnEnable ?
          <div className='bg7 fw'
               onClick={this.nextEnableClick}
          >
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

export default SocialDetails;