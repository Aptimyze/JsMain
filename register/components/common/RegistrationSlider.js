import React from 'react';
import DOBSlider from './DOBSlider';
import SingleSlider from './SingleSlider';
import SearchSlider from './SearchSlider';
import DoubleSlider from './DoubleSlider';
import FamilyBasedSlider from './FamilyBasedSlider';
import {CSSTransition} from "react-transition-group";
import PropTypes from 'prop-types';
import {setItem, getItem} from "../../services/localStorage";
import {commonApiCall} from "../../../common/components/ApiResponseHandler";
import {REGISTER_DATA} from "../../../common/constants/apiConstants.js"
import {
  indianCities,
  preProcessInput,
  processFromMultipleArrays,
  simpleProcessData
} from "../../helpers/dataPreprocessor"
import classNames from "classnames";

class RegistrationSlider extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      selectedData1: '',
      level: 1,
      showLoader: false
    };
    this.header2 = '';
    this.hamState = this.hamState.bind(this);
    this.setLocalData = this.setLocalData.bind(this);
    this.setFamilyData = this.setFamilyData.bind(this);
    this.inputDataForSlider2 = [];
    this.hideLoader = this.hideLoader.bind(this);
    this.showLoader = this.showLoader.bind(this);
  }

  showLoader() {
    this.setState({showLoader: true});
  }

  hideLoader() {
    this.setState({showLoader: false});
  }

  hamState(showRegHamburger, hamName, errorRegData) {
    this.props.hamState(showRegHamburger, hamName, errorRegData);
    if (showRegHamburger === false && (hamName === "Reg_Religion_Caste"
      || hamName === "Reg_Marital_Status" || hamName === "Reg_State_City_living_in"
      || hamName === "Reg_Brother" || hamName === "Reg_Sister"
      || hamName === "Reg_Family_based")) {
      // double tap on second slider data sets state to 1 immediately
      setTimeout(() => {
        this.setState({
          level: 1
        })
      }, 300);
    }
  }

  setLocalData(data) {
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    let staticTableData = getItem('staticTableData');
    if (this.state.level == 1) {
      if (this.props.heading === "Reg_State_City_living_in") {
        if (ud.country_res === '51') {
          let reg_city_jspc = getItem('reg_city_jspc');
          this.inputDataForSlider2 = indianCities(reg_city_jspc[51][data.code]);
        }
        setTimeout(() => {
          this.setState({selectedData1: data, level: 2});
        }, 500);
      }
      if (this.props.heading === "Reg_Marital_Status") {
        if (data.code !== "N") {
          // if user has selected same mstatus thn dont clear out the have child field
          // provide data and open second slider for the present data
          if (data.code !== ud.mstatus) {
            this.inputDataForSlider2 = [];
            // delete ud.havechild;
            // delete ud_display.havechild;
            setItem('UD', ud);
            setItem('UD_display', ud_display);
          }
          this.inputDataForSlider2 = preProcessInput(staticTableData.children[0]);
          setTimeout(() => {
            this.setState({selectedData1: data, level: 2});
          }, 500);

        } else {
          this.inputDataForSlider2 = [];
          this.hamState(false, this.props.heading);
          if (ud) {
            ud_display[this.props.localStorageFeildName] = data.name;
            ud[this.props.localStorageFeildName] = data.code;
            delete ud.havechild;
            delete ud_display.havechild;
            setItem('UD', ud);
            setItem('UD_display', ud_display);
          }
        }
      }
      if (this.props.heading === "Reg_Religion_Caste") {
        let regCaste = `reg_caste_${data.code}_`;
        if (data.code == '1' || data.code == '4' || data.code == '9' || data.code == '2' || data.code == '3') {
          // SECOND LEVEL PRESENT
          if (ud.mtongue && data.code == '1') {
            regCaste = `reg_caste_${data.code}_${ud.mtongue}`;
          } else {
            regCaste = `reg_caste_${data.code}_`;
          }
          if (staticTableData.hasOwnProperty(regCaste)) {
            this.inputDataForSlider2 = processFromMultipleArrays(staticTableData[regCaste], '', 'religion');
            setTimeout(() => {
              this.setState({selectedData1: data, level: 2});
            }, 500);

          } else {
            let url = `${REGISTER_DATA}?k=${regCaste}&dataType=json`;
            setTimeout(() => {
              this.showLoader();
            }, 400);
            commonApiCall(url, {}, '', 'GET', '', false).then((response) => {
              staticTableData[regCaste] = response;
              setTimeout(() => {
                this.hideLoader();
              }, 550);
              setItem('staticTableData', staticTableData);
              this.inputDataForSlider2 = processFromMultipleArrays(staticTableData[regCaste], '', 'religion');
              // first data set for 2 level then open
              setTimeout(() => {
                this.setState({selectedData1: data, level: 2});
              }, 500);
            });

          }

          if (data.code == '1' || data.code == '4' || data.code == '9') {
            this.header2 = 'Caste';
          } else if (data.code == '2' || data.code == '3') {
            this.header2 = 'Sect';
          }
        } else {
          // only first level, no second level
          this.inputDataForSlider2 = [];
          if (ud) {
            ud_display[this.props.localStorageFeildName] = data.name;
            ud[this.props.localStorageFeildName] = data.code;
            delete ud.caste;
            delete ud_display.caste;
            setItem('UD', ud);
            setItem('UD_display', ud_display);
            this.hamState(false, this.props.heading);
          }
        }
      }
      if (this.props.heading === "Reg_Brother") {
        if (data.code !== "0") {
          // if user has selected same mstatus thn dont clear out the have child field
          // provide data and open second slider for the present data
          if (data.code !== ud.t_brother) {
            this.inputDataForSlider2 = [];
            delete ud.m_brother;
            delete ud_display.m_brother;
            delete ud.t_brother;
            delete ud_display.t_brother;
            setItem('UD', ud);
            setItem('UD_display', ud_display);
          }

          this.inputDataForSlider2 = simpleProcessData(staticTableData.m_brother[data.code][0][0]);
          setTimeout(() => {
            this.setState({selectedData1: data, level: 2});
          }, 500);

        } else {
          this.inputDataForSlider2 = [];
          this.hamState(false, this.props.heading);
          if (ud) {
            ud_display[this.props.localStorageFeildName] = data.name;
            ud[this.props.localStorageFeildName] = data.code;
            delete ud.m_brother;
            delete ud_display.m_brother;
            setItem('UD', ud);
            setItem('UD_display', ud_display);
          }
        }
      }
      if (this.props.heading === "Reg_Sister") {
        if (data.code !== "0") {
          // if user has selected same mstatus thn dont clear out the have child field
          // provide data and open second slider for the present data
          if (data.code !== ud.t_brother) {
            this.inputDataForSlider2 = [];
            delete ud.m_sister;
            delete ud_display.m_sister;
            delete ud.t_sister;
            delete ud_display.t_sister;

            setItem('UD', ud);
            setItem('UD_display', ud_display);
          }

          this.inputDataForSlider2 = simpleProcessData(staticTableData.m_sister[data.code][0][0]);
          setTimeout(() => {
            this.setState({selectedData1: data, level: 2});
          }, 500);

        } else {
          this.inputDataForSlider2 = [];
          this.hamState(false, this.props.heading);
          if (ud) {
            ud_display[this.props.localStorageFeildName] = data.name;
            ud[this.props.localStorageFeildName] = data.code;
            delete ud.m_sister;
            delete ud_display.m_sister;
            setItem('UD', ud);
            setItem('UD_display', ud_display);
          }
        }
      }
    } else {
      //level is not 1
      // this.hamState(false, this.props.heading, '');
      if (ud) {
        ud_display[this.props.localStorageFeildName] = this.state.selectedData1.name;
        ud_display[this.props.localStorageFeildName2] = data.name;
        ud[this.props.localStorageFeildName] = this.state.selectedData1.code;
        ud[this.props.localStorageFeildName2] = data.code;
        if (this.props.heading === "Reg_Religion_Caste") {
          delete ud.subcaste;
          delete ud_display.subcaste;
        }
        setItem('UD', ud);
        setItem('UD_display', ud_display);
      }
    }
  }

  setFamilyData(data) {
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    let staticTableData = getItem('staticTableData');
    let reg_city_jspc = getItem('reg_city_jspc');
    if (this.state.level == 1) {
      if (data.name === "Select") {
        delete ud.native_country;
        delete ud_display.native_country;
        delete ud.native_city;
        delete ud_display.native_city;
        delete ud.native_state;
        delete ud_display.native_state;
        this.hamState(false, this.props.heading);
        setItem('UD', ud);
        setItem('UD_display', ud_display);
      } else if (data.code === "NI") {
        this.inputDataForSlider2 = preProcessInput(staticTableData.native_country_jsms[0]);
        this.setState({selectedData1: data, level: 2});
      } else {

        this.inputDataForSlider2 = indianCities(reg_city_jspc[51][data.code]);
        this.setState({selectedData1: data, level: 2});
      }
    } else if (this.state.level == 2) {
      if (data.name === "Select") {
        delete ud.native_country;
        delete ud_display.native_country;
        delete ud.native_city;
        delete ud_display.native_city;
        delete ud.native_state;
        delete ud_display.native_state;
        this.hamState(false, this.props.heading);
        setItem('UD', ud);
        setItem('UD_display', ud_display);
      } else if (data.code === "FI") {
        this.inputDataForSlider2 = preProcessInput(staticTableData.native_state_jsms[0]);
        this.setState({selectedData1: data, level: 1});
      } else {
        this.hamState(false, this.props.heading);
        if (ud) {
          if (reg_city_jspc[51][this.state.selectedData1.code]) {
            ud_display[this.props.localStorageFeildName2] = this.state.selectedData1.name;
            ud_display[this.props.localStorageFeildName3] = data.name;
            ud[this.props.localStorageFeildName2] = this.state.selectedData1.code;
            ud[this.props.localStorageFeildName3] = data.code;
            ud_display[this.props.localStorageFeildName] = "India";
            ud[this.props.localStorageFeildName] = "51";
          } else {
            ud_display[this.props.localStorageFeildName] = data.name;
            ud[this.props.localStorageFeildName] = data.code;
            ud['native_state'] = "";
            ud_display['native_city'] = "";
            ud_display['native_state'] = "";
            ud['native_city'] = "";
          }
          setItem('UD', ud);
          setItem('UD_display', ud_display);
        }
      }
    }

  }


  render() {
    return (
      <div>
        <CSSTransition
          in={this.props.dobSlider}
          mountOnEnter
          unmountOnExit
          exit={true}
          timeout={350}
          classNames="slide">
          <DOBSlider hamState={this.hamState}
                     startFromMiddle={true}
                     showRegHamburger={this.props.dobSlider}
                     showHeading={this.props.showHeading}
                     marginLeft={this.props.marginLeft}
                     heading={this.props.heading}/>
        </CSSTransition>


        <CSSTransition
          in={this.props.singleSlider}
          mountOnEnter
          unmountOnExit
          exit={true}
          timeout={350}
          classNames="slide">
          <SingleSlider hamState={this.hamState}
                        startFromMiddle={this.props.startFromMiddle}
                        inputDataForSlider={this.props.inputDataForSlider}
                        localStorageFeildName={this.props.localStorageFeildName}
                        showRegHamburger={this.props.singleSlider}
                        heading={this.props.heading}
                        marginLeft={this.props.marginLeft}
                        header={this.props.header ? this.props.header : this.props.showHeading}/>

        </CSSTransition>

        <CSSTransition
          in={this.props.searchSlider}
          mountOnEnter
          unmountOnExit
          exit={true}
          timeout={350}
          classNames="slide">
          <SearchSlider hamState={this.hamState}
                        startFromMiddle={this.props.startFromMiddle}
                        inputDataForSlider={this.props.inputDataForSlider}
                        localStorageFeildName={this.props.localStorageFeildName}
                        showRegHamburger={this.props.searchSlider}
                        heading={this.props.heading}
                        marginLeft={this.props.marginLeft}
                        header={this.props.header ? this.props.header : this.props.showHeading}
          />
        </CSSTransition>
        <CSSTransition
          in={this.props.doubleSlider}
          mountOnEnter
          unmountOnExit
          exit={true}
          timeout={350}
          classNames="slide">
          <DoubleSlider hamState={this.hamState}
                        showLoader={this.state.showLoader}
                        startFromMiddle={this.props.startFromMiddle}
                        inputDataForSlider={this.state.level == 2 ? this.inputDataForSlider2 : this.props.inputDataForSlider}
                        localStorageFeildName={this.props.localStorageFeildName}
                        localStorageFeildName2={this.props.localStorageFeildName2}
                        setLocalData={this.setLocalData}
                        showRegHamburger={this.props.doubleSlider}
                        prevState={this.state.level}
                        heading={this.props.heading}
                        marginLeft={this.props.marginLeft}
                        showSearch={this.state.level == 1 ? this.props.showSearch1 : this.props.showSearch2}
                        header={this.state.level == 1 ? this.props.header1 : (this.header2 || this.props.header2)}
          />
        </CSSTransition>


        <CSSTransition
          in={this.props.familySlider}
          mountOnEnter
          unmountOnExit
          exit={true}
          timeout={350}
          classNames="slide">
          <FamilyBasedSlider hamState={this.hamState}
                             inputDataForSlider={this.state.level == 2 ? this.inputDataForSlider2
                               : this.props.inputDataForSlider}
                             localStorageFeildName={this.props.localStorageFeildName}
                             localStorageFeildName2={this.props.localStorageFeildName2}
                             localStorageFeildName3={this.props.localStorageFeildName3}
                             setFamilyData={this.setFamilyData}
                             showRegHamburger={this.props.familySlider}
                             prevState={this.state.level}
                             heading={this.props.heading}
                             marginLeft={this.props.marginLeft}
                             header={this.props.header1}
          />
        </CSSTransition>

        <div className="brdr1 bg4 regSliderBlock" id={this.props.heading}
             onClick={e => this.hamState(true, this.props.heading, '')}>
          <div className="pad1">
            <div className="pad2">
              <div className="fl wid94p">

                <div className={classNames(this.props.error ? 'color2' : 'color8', 'f12 fontlig')}>
                  {this.props.showHeading}
                </div>

                {this.props.text === "Not Filled In" ?
                  <div className="color8 f15 pt10 fontlig"
                       dangerouslySetInnerHTML={{__html: this.props.text}}>
                  </div> :
                  <div className="color11 f15 pt10 fontlig"
                       dangerouslySetInnerHTML={{__html: this.props.text}}>
                  </div>}
              </div>
              <div className="fr wid4p pt8">
                <i className="mainsp arow1"/>
              </div>
              <div className="clr"/>
            </div>
          </div>
        </div>
      </div>
    )
  }
}

RegistrationSlider.propTypes = {
  heading: PropTypes.string.isRequired,
  text: PropTypes.string.isRequired,
  data: PropTypes.array,
  hamState: PropTypes.func.isRequired,
  searchSlider: PropTypes.bool,
  singleSlider: PropTypes.bool,
  dobSlider: PropTypes.bool
}

export default RegistrationSlider;