import React from 'react';
import Slider from "react-slick";
//components
import Loader from "../../common/components/Loader";
import FamilyDetails from '../components/pagesAboutMeAndFamily/FamilyDetails';
import AboutFamily from '../components/pagesAboutMeAndFamily/AboutFamily';
import GA from '../../common/components/GA';
//services and helpers
import {commonApiCall} from "../../common/components/ApiResponseHandler";
import {getItem, removeItem, setItem} from "../services/localStorage";
import {concatUDstr, loadTime, setScreen} from "../helpers/screenHandlers";
//contants
import {REGISTER_DATA} from "../../common/constants/apiConstants.js"
//css files
require('../style/regms.css');
require('../style/slick.css');
require('../style/slick.theme.css');

export default class FamilyInfoContainer extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      showLoader: false
    };
    this.GAObject = new GA();
    this.showLoader = this.showLoader.bind(this);
    this.hideLoader = this.hideLoader.bind(this);
    this.handleCurrentStep = this.handleCurrentStep.bind(this);
    this.pageStatics = {
      "9": {
        pNum: 9,
        pName: 'familyDetails',
        sliderIdx: 0
      },
      "10": {
        pNum: 10,
        pName: 'familyDetailsAbout',
        sliderIdx: 1
      }
    }
  }

  // get api data and set height of container
  componentDidMount() {
    let scrnHeight = getItem('screenInitialHeight');
    if (!scrnHeight) setItem('screenInitialHeight', window.innerHeight);
    this.showLoader();
    window.onpopstate = () => {
      let hamDetails = getItem('hamDetails');
      if (hamDetails && hamDetails.showRegHamburger) {
        switch (getItem('currentIncompleteRegPage')) {
          case '9':
            this.refs.familyDetails.hamState(false, hamDetails.hamName);
            break;
        }
        this.constructAndPushUrl(9, "screenLandingTracking")
      } else {
        if (getItem('currentIncompleteRegPage') == '10') {
          this.constructAndPushUrl('9');
          this.slider.slickPrev();
          // console.log('ga fam pop',"P", `JSMS_REG_S${9}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${9}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${9}_R`);
        } else if (getItem('currentIncompleteRegPage') == '9')
          this.constructAndPushUrl(9, "screenLandingTracking")
          // console.log('ga fam pop',"P", `JSMS_REG_S${9}`);
          // this.GAObject.regTrackGA("P", `JSMS_REG_S${9}`);
      }
      // if(getItem('currentIncompleteRegPage') == '9')
      //   this.constructAndPushUrl(9);
    };

    let currentRegPage = 9;
    // console.log('ga',"E", "jsms","new","1");
    this.GAObject.regTrackGA("E", "jsms","new","1");
    if (currentRegPage) {
      let sliderVal = this.pageStatics[currentRegPage].sliderIdx;
      // console.log('ga compreg',"P", `JSMS_REG_S${currentRegPage}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${currentRegPage}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${currentRegPage}_R`);
      this.slider.slickGoTo(sliderVal);
      this.constructAndPushUrl(currentRegPage);
    } else {
      removeItem('currentIncompleteRegPage');
      removeItem('currentRegPage');
      removeItem('currentIncPage');
      setItem('currentIncompleteRegPage', 9);
      this.constructAndPushUrl(9);
      // console.log('ga compreg',"P", `JSMS_REG_S9`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S9`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S9_R`);
    }


    let url1 = `${REGISTER_DATA}?k=reg_city_jspc&dataType=json`;
    commonApiCall(url1, {}, '', 'GET', '', false).then((response) => {
      setItem('reg_city_jspc', {});
      if (response) {
        setItem('reg_city_jspc', response)
      }
    });
    let url = `${REGISTER_DATA}?l=family_type,family_income,native_country_jsms,native_state_jsms,mother_occ,family_values,family_status,family_back,native_country_jsms,t_brother,m_brother,t_sister,m_sister,reg_city_go_&dataType=json`;
    commonApiCall(url, {}, '', 'GET', '', false).then((response) => {
      setItem('staticTableData', {});
      if (response) {
        setItem('staticTableData', response)
      }
    });
    let ud = getItem('UD');
    if (!ud) {
      setItem('UD', {});
      setItem('UD_display', {});
    }
    setScreen();
    setTimeout(() => {
      this.hideLoader()
    }, 2000);
    loadTime();
  }

  constructAndPushUrl(number, ham) {
    let urlPath = `/register/family?s=${number}`;
    setItem('currentIncompleteRegPage', number);

    if(ham !== "screenLandingTracking"){
      let curParams = concatUDstr();
      let screenLandingTracking = `/register/screenLandingTracking?screenNumber=s${number}&regMode=R&curParams=${curParams}`;
      commonApiCall(screenLandingTracking, {}, '', 'POST', '', false).then((response) => {
        console.log("res",response);
      });
    }
    this.props.history.push(urlPath);
  }

  handleCurrentStep(currentPage, pagenumber) {
    this.setPages(currentPage, pagenumber);
  }


  setPages(currentPage, pagenumber) {

    if (currentPage == 10) {
      this.constructAndPushUrl(10);
      this.slider.slickNext();
      // console.log('ga--', "P", `JSMS_REG_S${10}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${10}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${10}_R`);
    } else if (currentPage == "previousPage") {
      this.slider.slickPrev();
      this.constructAndPushUrl(9);
      // console.log('ga--', "P", `JSMS_REG_S${9}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${9}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${9}_R`);
    }
  }

  showLoader() {
    this.setState({showLoader: true});
  }

  hideLoader() {
    this.setState({showLoader: false});
    document.getElementById('tmpRegForm').style.display = "none"
  }

  render() {

    let settings = {
      dots: false,
      swipe: false,
      touchMove: false,
      infinite: false,
      arrows: false,
      speed: 500,
      slidesToShow: 1,
      slidesToScroll: 1
    };
    return (
      <div id="main-content">
        {this.state.showLoader && <Loader show="page" opacity={1}/>}
        <Slider ref={slider => (this.slider = slider)} {...settings}>
          <div className="fw bg4 slickContainer">
            <FamilyDetails onIconClick={this.handleCurrentStep}
                           margin="10%"
                           ref="familyDetails"/>
          </div>
          <div className="fw bg4 slickContainer">
            <AboutFamily onIconClick={this.handleCurrentStep}
                         properties={this.props}/>
          </div>

        </Slider>

      </div>
    )
  };
}