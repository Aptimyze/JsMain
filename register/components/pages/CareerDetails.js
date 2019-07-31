import React from 'react';
//components
import RegistrationHeader from '../common/RegistrationHeader';
import RegistrationFooter from '../common/RegistrationFooter';
import RegistrationSlider from "../common/RegistrationSlider";
//helpers and services
import {preProcessInput, processFromMultipleArrays, determineCourses} from "../../helpers/dataPreprocessor";
import {
  resizeContainer,
  scrollToBottom,
  focusOnCurrentElement,
  removeFocusFromAllElements, editClass, scrollOnTop, blurInputs
} from "../../helpers/screenHandlers";
import {getItem, setItem} from "../../services/localStorage";
import {errorGATracking} from '../../helpers/gaHandler';
//constants
import errorStatements from "../../constant/errorStatements";
import TopError from "../../../common/components/TopError";

class CareerDetails extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      enableRegBtn: false,
      errorObj: {},
      errorRegData: '',
      highestEducationSlider: false,
      workAreaSlider: false,
      showErrorReg:false,
      employedInSlider: false,
      annualIncomeSlider: false,
      ugCollegeSlider: false,
      pgCollegeSlider: false,
      nextBtnEnable: false,
    };
    this.hamState = this.hamState.bind(this);
    this.nextClick = this.nextClick.bind(this);
  }


  componentDidMount() {
    this.enableNextBtn();
  }

  componentWillReceiveProps() {
    this.enableNextBtn();
    this.setState({showErrorReg: false});
  }

  hamState(showRegHamburger, hamName, errorRegData) {
    let hamDetails = {'showRegHamburger':showRegHamburger  , 'hamName':hamName};
    setItem('hamDetails',hamDetails);
    this.inputDataForSlider = [];
    let staticTableData = getItem('staticTableData');
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    this.setState({errorObj: {}}, () => {
      this.setErrorReg();
    });
    blurInputs();
    editClass(showRegHamburger);
    if (showRegHamburger) {
      focusOnCurrentElement('')
    } else {
      removeFocusFromAllElements()
    }
    switch (hamName) {
      case 'Reg_Highest_Education': {
        if (staticTableData && staticTableData.hasOwnProperty('edu_level_new')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.edu_level_new[0]);
        }
        if (staticTableData && staticTableData.hasOwnProperty('edu_level_new')
          && showRegHamburger === false) {
          let courseType = determineCourses(ud['edu_level_new'], staticTableData['degree_grouping_reg'][0]);
          if (courseType === 'pg') {
            ud['degree_pg'] = ud['edu_level_new'];
            // ud_display['degree_pg'] = ud_display['edu_level_new'];
          } else if (courseType === 'ug' || courseType === 'g') {
            if (ud['edu_level_new'] !== '23'
              && ud.edu_level_new !== '24'
              && ud.edu_level_new !== '9') {
              ud['degree_ug'] = ud['edu_level_new'];
              ud_display['degree_ug'] = ud_display['edu_level_new'];
              delete ud['degree_pg'];
              delete ud_display['degree_pg'];
            } else {
              delete ud['degree_pg'];
              delete ud_display['degree_pg'];
              delete ud['degree_ug'];
              delete ud_display['degree_ug'];
              delete ud['pg_college'];
              delete ud_display['pg_college'];
              delete ud['college'];
              delete ud_display['college'];
            }

          }
          ud_display['courseType'] = courseType;
          setItem("UD", ud);
          setItem("UD_display", ud_display);

        }
        this.setState({highestEducationSlider: showRegHamburger});
        break;
      }

      case 'Reg_PG_degree': {
        if (staticTableData && staticTableData.hasOwnProperty('degree_pg')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.degree_pg[0]);
        }
        this.setState({pgCollegeSlider: showRegHamburger});
        break;
      }

      case 'Reg_UG_degree': {
        if (staticTableData && staticTableData.hasOwnProperty('degree_ug')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.degree_ug[0]);
        }
        this.setState({ugCollegeSlider: showRegHamburger}, () => {
          if (!showRegHamburger && ud.degree_ug) {
            scrollToBottom('CareerDetailsContainer');
          }
        });
        break;
      }
      case 'Reg_Employed_In': {
       if (staticTableData && staticTableData.hasOwnProperty('employed_in')
          && showRegHamburger) {
          this.inputDataForSlider = preProcessInput(staticTableData.employed_in[0]);
        }
        this.setState({employedInSlider: showRegHamburger});
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
        this.setState({workAreaSlider: showRegHamburger});
        break;
      }


      case 'Reg_Annual_Income': {
        if (staticTableData && staticTableData.hasOwnProperty('income')
          && showRegHamburger) {
          this.inputDataForSlider = processFromMultipleArrays(staticTableData.income, ud.country_res, 'income');
        }
        this.setState({annualIncomeSlider: showRegHamburger});
        break;
      }
    }
    this.enableNextBtn();
  }

  nextClick() {
    let ud = getItem('UD');
    let staticTableData = getItem('staticTableData');
    let errorObj = {};

    errorObj.edu_level_new = ud.edu_level_new ? '' : errorStatements.HIGHEST_EDUCATION;
    if (staticTableData && staticTableData.hasOwnProperty('occupation_old') === false) {
      errorObj.employed_in = ud.employed_in ? '' : errorStatements.EMPLOYED_IN;
      if (ud.employed_in) {
        errorObj.occupation = ud.occupation ? '' : errorStatements.WORK_AREA;
      }
    } else {
      errorObj.occupation = ud.occupation ? '' : errorStatements.WORK_AREA;
    }
    errorObj.income = ud.income ? '' : errorStatements.INCOME;
    this.setState({errorObj}, () => {
      for (let i in errorObj) {
        if (!errorObj[i]) delete errorObj[i]
      }
      let err='';
      for(let obj in this.state.errorObj){
        err+= this.state.errorObj[obj]+ ' ';
      }
      errorGATracking('s3',err);
      this.setErrorReg();
    });
  }

  setErrorReg() {
    if (Object.keys(this.state.errorObj).length >= 1) this.setState({showErrorReg: true});
    else this.setState({showErrorReg: false});
  }


  enableNextBtn() {
    let ud = getItem("UD");
    let staticTableData = getItem('staticTableData');
    if (ud && ud.edu_level_new && ud.income && staticTableData) {
      if (staticTableData.hasOwnProperty('occupation_old') === false) {
        if (ud.occupation) {
          this.setState({nextBtnEnable: true});
        } else {
          this.setState({nextBtnEnable: false});
        }
      } else if (staticTableData.hasOwnProperty('occupation_old') === true) {
        if (ud.occupation) {
          this.setState({nextBtnEnable: true});
        } else {
          this.setState({nextBtnEnable: false});
        }
      }
    } else {
      this.setState(() => ({
        nextBtnEnable: false
      }));
    }
  }

  render() {
    // resizeContainer('CareerDetailsContainer');
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    let staticTableData = getItem('staticTableData');
    return (
      <div className="posabs fw">
        {this.state.showErrorReg &&
        <TopError timeToHide={3000} errorObj={this.state.errorObj} topPosition={55} leftAlign={true}/>}
        <div className="fw bg1" onClick={() => {
          scrollOnTop('CareerDetailsContainer')
        }}>
          <RegistrationHeader headerData="Career Details" page="2"
                              onIconClick={this.props.onIconClick}/>
        </div>
        <div className='bg4 sliderDataContainer'
             style={{
               height: (window.innerHeight - 110) + 'px', overflow: 'auto'
             }}
             id="CareerDetailsContainer">
          <RegistrationSlider heading="Reg_Highest_Education"
                              showHeading='Highest Qualification'
                              error={this.state.errorObj.edu_level_new}
                              text={ud_display && ud_display.edu_level_new ? ud_display.edu_level_new : "Not Filled In"}
                              localStorageFeildName='edu_level_new'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              searchSlider={this.state.highestEducationSlider}/>


          {ud
          && (ud.edu_level_new === "42" || ud.edu_level_new === "21")
          && (ud.edu_level_new !== '23' && ud.edu_level_new !== '24' && ud.edu_level_new !== '9')
          && <RegistrationSlider heading="Reg_PG_degree"
                                  showHeading='PG degree (optional)'
                                 error={false}
                                 text={ud_display && ud_display.degree_pg ? ud_display.degree_pg : "Not Filled In"}
                                 localStorageFeildName='degree_pg'
                                 hamState={this.hamState}
                                 inputDataForSlider={this.inputDataForSlider}
                                 startFromMiddle={false}
                                 singleSlider={this.state.pgCollegeSlider}/>
          }


          {/*{ud*/}
          {/*&& (ud.degree_pg || ud.edu_level_new === "42" || ud.edu_level_new === "21")*/}
          {/*&& (ud.edu_level_new !== '23' && ud.edu_level_new !== '24' && ud.edu_level_new !== '9')*/}
          {/*&& <div className="brdr1 bg4">*/}
            {/*<div className="pad1">*/}
              {/*<div className="pad2">*/}
                {/*<div className='color8 f12 fontlig'>PG college (optional)</div>*/}
                {/*<input id='pg_college'*/}
                       {/*defaultValue={ud.pg_college ? ud.pg_college : ''}*/}
                       {/*className="color11 f15 pt10 fontlig fullwid regSliderBlock setRD"*/}
                       {/*onFocus={e => {*/}
                         {/*focusOnCurrentElement('pg_college');*/}
                       {/*}}*/}
                       {/*onKeyUp={(e) => {*/}
                         {/*ud['pg_college'] = e.target.value;*/}
                         {/*setItem("UD", ud);*/}
                       {/*}}*/}
                       {/*onBlur={e => {*/}
                         {/*ud['pg_college'] = e.target.value;*/}
                         {/*setItem("UD", ud);*/}
                         {/*resizeContainer('CareerDetailsContainer');*/}
                         {/*// scrollToBottom('CareerDetailsContainer');*/}
                         {/*removeFocusFromAllElements()*/}
                       {/*}}*/}
                       {/*type="text" placeholder="Not Filled In"/>*/}
                {/*<div className="clr"/>*/}
              {/*</div>*/}
            {/*</div>*/}
          {/*</div>}*/}

          {ud && ud.degree_pg && (ud.edu_level_new !== '23' && ud.edu_level_new !== '24' && ud.edu_level_new != '22' && ud.edu_level_new !== '9') &&
          <RegistrationSlider heading="Reg_UG_degree"
                              showHeading='UG degree (optional)'
                              error={false}
                              text={ud_display && ud_display.degree_ug ? ud_display.degree_ug : "Not Filled In"}
                              localStorageFeildName='degree_ug'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.ugCollegeSlider}/>
          }


          {/*{ud && (ud.degree_ug || ud.degree_pg) && (ud.edu_level_new !== '23' && ud.edu_level_new !== '24' && ud.edu_level_new !== '9') &&*/}
          {/*<div className="brdr1 bg4">*/}
            {/*<div className="pad1">*/}
              {/*<div className="pad2">*/}
                {/*<div className='color8 f12 fontlig'>UG college (optional)</div>*/}
                {/*<input id='college'*/}
                       {/*defaultValue={ud.college ? ud.college : ''}*/}
                       {/*className="color11 f15 pt10 fontlig fullwid regSliderBlock setRD"*/}
                       {/*onFocus={e => {*/}
                         {/*focusOnCurrentElement('college');*/}
                       {/*}}*/}
                       {/*onKeyUp={(e) => {*/}
                         {/*ud['college'] = e.target.value;*/}
                         {/*setItem("UD", ud);*/}
                       {/*}}*/}
                       {/*onBlur={e => {*/}
                         {/*ud['college'] = e.target.value;*/}
                         {/*setItem("UD", ud);*/}
                         {/*resizeContainer('CareerDetailsContainer');*/}
                         {/*//scrollToBottom('CareerDetailsContainer');*/}
                         {/*removeFocusFromAllElements();*/}
                       {/*}}*/}
                       {/*type="text" placeholder="Not Filled In"/>*/}
                {/*<div className="clr"/>*/}
              {/*</div>*/}
            {/*</div>*/}
          {/*</div>}*/}


          {staticTableData && staticTableData.hasOwnProperty('occupation_old') === false &&
          <RegistrationSlider heading="Reg_Employed_In"
                              showHeading='Employed In'
                              error={this.state.errorObj.employed_in}
                              text={ud_display && ud_display.employed_in ? ud_display.employed_in : "Not Filled In"}
                              localStorageFeildName='employed_in'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.employedInSlider}/>
          }


          {staticTableData && staticTableData.hasOwnProperty('occupation_old') === false && ud && ud.employed_in &&
          <RegistrationSlider heading="Reg_Occupation"
                              showHeading='Occupation'
                              error={this.state.errorObj.occupation}
                              text={ud_display && ud_display.occupation ? ud_display.occupation : "Not Filled In"}
                              localStorageFeildName='occupation'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              searchSlider={this.state.workAreaSlider}/>}

          {staticTableData && staticTableData.hasOwnProperty('occupation_old') === true &&
          <RegistrationSlider heading="Reg_Work_Area"
                              error={this.state.errorObj.occupation}
                              showHeading='Work Area'
                              text={ud_display && ud_display.occupation ? ud_display.occupation : "Not Filled In"}
                              localStorageFeildName='occupation'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              header='Work Area'
                              searchSlider={this.state.workAreaSlider}/>}

          <RegistrationSlider heading="Reg_Annual_Income"
                              error={this.state.errorObj.income}
                              showHeading='Annual Income'
                              text={ud_display && ud_display.income ? ud_display.income : "Not Filled In"}
                              localStorageFeildName='income'
                              hamState={this.hamState}
                              inputDataForSlider={this.inputDataForSlider}
                              startFromMiddle={false}
                              singleSlider={this.state.annualIncomeSlider}/>

        </div>
        {this.state.nextBtnEnable ?
          <div className='bg7 fw'
               onClick={() => {
                 this.props.onIconClick('nextPage',3)
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

export default CareerDetails;