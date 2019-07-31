import React from 'react';
//components
import RegistrationHeader from '../common/RegistrationHeader';
import RegistrationFooter from '../common/RegistrationFooter';
import RegistrationSlider from "../common/RegistrationSlider";
//helpers and services
import {preProcessInput, processFromMultipleArrays, simpleProcessData} from "../../helpers/dataPreprocessor";
import {
  blurInputs,
  editClass,
  focusOnCurrentElement,
  removeFocusFromAllElements,
  resizeContainer,
  scrollToBottom
} from "../../helpers/screenHandlers";
import {getItem, setItem} from "../../services/localStorage";

class FamilyDetails extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      familyBackSlider: false,
      familyIncomeSlider: false,
      familyStatusSlider: false,
      familyTypeSlider: false,
      familyValuesSlider: false,
      fatherOccSlider: false,
      motherOccSlider: false,
      brotherSlider: false,
      sisterSlider: false,
      familyBasedSlider: false
    };
    this.hamState = this.hamState.bind(this);
  }

  componentDidMount() {
    let ud = getItem('UD');
    if (!ud) {
      setItem('UD', {});
      setItem('UD_display', {});
    }
  }

  hamState(showRegHamburger, hamName, errorRegData) {
    let hamDetails = {'showRegHamburger': showRegHamburger, 'hamName': hamName};
    setItem('hamDetails', hamDetails);
    this.inputDataForSlider = [];
    let staticTableData = getItem('staticTableData');
    let ud = getItem('UD');
    blurInputs();
    editClass(showRegHamburger);
    if (showRegHamburger) {
      focusOnCurrentElement('')
    } else {
      removeFocusFromAllElements()
    }
    switch (hamName) {
      case 'Reg_Family_Type': {
        if (staticTableData && staticTableData.hasOwnProperty('family_back')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.family_type[0]);
        }
        this.setState({familyTypeSlider: showRegHamburger})
        break;
      }

      case 'Reg_Family_Values': {
        if (staticTableData && staticTableData.hasOwnProperty('family_values')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.family_values[0]);
        }
        this.setState({familyValuesSlider: showRegHamburger})
        break;
      }

      case 'Reg_Family_Status': {
        if (staticTableData && staticTableData.hasOwnProperty('family_status')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.family_status[0]);
        }
        this.setState({familyStatusSlider: showRegHamburger})
        break;
      }

      case 'Reg_Family_Income': {
        if (staticTableData && staticTableData.hasOwnProperty('family_income')
          && showRegHamburger) {
          let item = getItem('selfCountry');
          let country = '51';
          if (item) {
            country = item
          }
          this.inputDataForSlider = processFromMultipleArrays(staticTableData.family_income, country, 'income');
        }
        this.setState({familyIncomeSlider: showRegHamburger})
        break;
      }

      case "Reg_Father_Occupation": {
        if (staticTableData && staticTableData.hasOwnProperty('family_back')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.family_back[0]);
        }
        this.setState({fatherOccSlider: showRegHamburger})
        break;
      }

      case "Reg_Mother_Occupation": {
        if (staticTableData && staticTableData.hasOwnProperty('mother_occ')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.mother_occ[0]);
        }
        this.setState({motherOccSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.mother_occ) {
            scrollToBottom('FamilyDetailsContainer', 80);
          }
        });
        break;
      }

      case 'Reg_Brother': {
        if (staticTableData && staticTableData.hasOwnProperty('t_brother')
          && showRegHamburger) {
          this.inputDataForSlider = simpleProcessData(staticTableData.t_brother[0][0]);
        }
        this.setState({brotherSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.t_brother) {
            scrollToBottom('FamilyDetailsContainer', 80);
          }
        });
        break;
      }

      case 'Reg_Sister': {
        if (staticTableData && staticTableData.hasOwnProperty('t_sister')
          && showRegHamburger) {
          this.inputDataForSlider = simpleProcessData(staticTableData.t_sister[0][0]);
        }
        this.setState({sisterSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.t_sister) {
            scrollToBottom('FamilyDetailsContainer', 80);
          }
        });
        break;
      }

      case 'Reg_Family_based': {
        if (staticTableData && staticTableData.hasOwnProperty('native_state_jsms')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.native_state_jsms[0]);
        }
        this.setState({familyBasedSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.native_state_jsms) {
            scrollToBottom('FamilyDetailsContainer');
          }
        });
        break;
      }
    }
  }


  render() {
    // resizeContainer('FamilyDetailsContainer');
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    let text1 = "";
    if (ud && ud.hasOwnProperty('native_country')) {
      if (ud.native_country == "51") {
        text1 = `${ud_display.native_state}-${ud_display.native_city}`
      } else if (ud.native_country !== "51") {
        text1 = `${ud_display.native_country}`
      }
    }
    let text2 = "";
    if (ud && ud.hasOwnProperty('t_sister')) {
      if (ud.t_sister == "0") {
        text2 = `${ud_display.t_sister}`
      } else if (ud.t_sister != "0") {
        text2 = `${ud_display.t_sister} sister(s) of which married ${ud_display.m_sister}`
      }
    }

    let text3 = "";
    if (ud && ud.hasOwnProperty('t_brother')) {
      if (ud.t_brother == "0") {
        text3 = `${ud_display.t_brother}`
      } else if (ud.t_brother != "0") {
        text3 = `${ud_display.t_brother} brother(s) of which married ${ud_display.m_brother}`
      }
    }

    return (
      <div className="posabs fw">
        <div className="fw bg1">
          <RegistrationHeader headerData="Family Details" hideBack={true}/>
        </div>
        <div className='bg4 sliderDataContainer'
             style={{
               height: (window.innerHeight - 110) + 'px', overflow: 'auto'
             }}
             id="FamilyDetailsContainer">

          <RegistrationSlider heading="Reg_Family_Type"
                              showHeading='Family Type'
                              error={false}
                              text={ud_display && ud_display.family_type ? ud_display.family_type : "Not Filled In"}
                              localStorageFeildName='family_type'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              marginLeft={this.props.margin}
                              singleSlider={this.state.familyTypeSlider}/>
          <RegistrationSlider heading="Reg_Family_Values"
                              showHeading='Family Values'
                              error={false}
                              marginLeft={this.props.margin}
                              text={ud_display && ud_display.family_values ? ud_display.family_values : "Not Filled In"}
                              localStorageFeildName='family_values'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.familyValuesSlider}/>
          <RegistrationSlider heading="Reg_Family_Status"
                              showHeading='Family Status'
                              error={false}
                              marginLeft={this.props.margin}
                              text={ud_display && ud_display.family_status ? ud_display.family_status : "Not Filled In"}
                              localStorageFeildName='family_status'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.familyStatusSlider}/>
          <RegistrationSlider heading="Reg_Family_Income"
                              showHeading='Family Income'
                              error={false}
                              marginLeft={this.props.margin}
                              text={ud_display && ud_display.family_income ? ud_display.family_income : "Not Filled In"}
                              localStorageFeildName='family_income'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.familyIncomeSlider}/>
          <RegistrationSlider heading="Reg_Father_Occupation"
                              showHeading="Father's Occupation"
                              text={ud_display && ud_display.family_back ? ud_display.family_back : "Not Filled In"}
                              localStorageFeildName='family_back'
                              hamState={this.hamState}
                              error={false}
                              marginLeft={this.props.margin}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.fatherOccSlider}/>
          <RegistrationSlider heading="Reg_Mother_Occupation"
                              showHeading="Mother's Occupation"
                              text={ud_display && ud_display.mother_occ ? ud_display.mother_occ : "Not Filled In"}
                              error={false}
                              marginLeft={this.props.margin}
                              localStorageFeildName='mother_occ'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.motherOccSlider}/>

          <RegistrationSlider heading="Reg_Brother"
                              showHeading='Brother(s)'
                              text={text3 ? text3 : "Not Filled In"}
                              startFromMiddle={false}
                              localStorageFeildName='t_brother'
                              localStorageFeildName2='m_brother'
                              inputDataForSlider={this.inputDataForSlider}
                              hamState={this.hamState}
                              showSearch1={false}
                              error={false}
                              marginLeft={this.props.margin}
                              showSearch2={false}
                              doubleSlider={this.state.brotherSlider}
                              header1='Brother(s)'
                              header2='Of which married'/>

          <RegistrationSlider heading="Reg_Sister"
                              error={false}
                              showHeading='Sister(s)'
                              text={text2 ? text2 : "Not Filled In"}
                              startFromMiddle={false}
                              localStorageFeildName='t_sister'
                              localStorageFeildName2='m_sister'
                              inputDataForSlider={this.inputDataForSlider}
                              hamState={this.hamState}
                              showSearch1={false}
                              showSearch2={false}
                              marginLeft={this.props.margin}
                              doubleSlider={this.state.sisterSlider}
                              header1='Sister(s)'
                              header2='Of which married'/>


          <RegistrationSlider heading="Reg_Family_based"
                              error={false}
                              showHeading='Family based out of'
                              text={text1 ? text1 : "Not Filled In"}
                              localStorageFeildName='native_country'
                              localStorageFeildName2='native_state'
                              localStorageFeildName3='native_city'
                              inputDataForSlider={this.inputDataForSlider}
                              hamState={this.hamState}
                              marginLeft={this.props.margin}
                              familySlider={this.state.familyBasedSlider}
                              header1='Family based out of'/>

          {ud && ud.native_city == '0' &&
          <div className="brdr1 bg4">
            <div className="pad1">
              <div className="pad2">
                <div className='color8 f12 fontlig'>Please specify (city)</div>
                <input id='specifycity'
                       defaultValue={ud && ud.ancestral_origin ? ud.ancestral_origin : ''}
                       className="color11 f15 pt10 fontlig fullwid regSliderBlock setRD"
                       onFocus={e => {
                         focusOnCurrentElement('specifycity');
                       }}
                       onKeyUp={(e) => {
                         ud['ancestral_origin'] = e.target.value;
                         setItem("UD", ud);
                       }}
                       onBlur={e => {
                         ud['ancestral_origin'] = e.target.value;
                         setItem("UD", ud);
                         resizeContainer('FamilyDetailsContainer');
                         scrollToBottom('FamilyDetailsContainer');
                         removeFocusFromAllElements();
                       }}
                       type="text" placeholder="Not Filled In"/>
                <div className="clr"/>
              </div>
            </div>
          </div>
          }

          <div className="brdr1 bg4">
            <div className="pad1">
              <div className="pad2">
                <div className='color8 f12 fontlig'>Gothra</div>
                <input id='gothra'
                       defaultValue={ud && ud.gothra ? ud.gothra : ''}
                       className="color11 f15 pt10 fontlig fullwid regSliderBlock setRD"
                       onFocus={e => {
                         focusOnCurrentElement('gothra');
                       }}
                       onKeyUp={(e) => {
                         ud['gothra'] = e.target.value;
                         setItem("UD", ud);
                       }}
                       onBlur={e => {
                         ud['gothra'] = e.target.value;
                         setItem("UD", ud);
                         resizeContainer('FamilyDetailsContainer');
                         scrollToBottom('FamilyDetailsContainer');
                         removeFocusFromAllElements();
                       }}
                       type="text" placeholder="Not Filled In"/>
                <div className="clr"/>
              </div>
            </div>
          </div>

        </div>
        <div className='bg7 fw'
             onClick={() => {
               this.props.onIconClick(10)
             }}>
          <RegistrationFooter text={"Next"}/>
        </div>
      </div>
    )
  }
}

export default FamilyDetails;