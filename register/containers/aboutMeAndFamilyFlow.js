import React from 'react';
import Slider from "react-slick";
//components
import Loader from "../../common/components/Loader";
import AboutMePage from '../components/pagesAboutMeAndFamily/AboutMePage';
import FamilyDetails from '../components/pagesAboutMeAndFamily/FamilyDetails';
import AboutFamily from '../components/pagesAboutMeAndFamily/AboutFamily';
import GA from '../../common/components/GA';
//services and helpers
import {commonApiCall} from "../../common/components/ApiResponseHandler";
import {getItem, removeItem, setItem} from "../services/localStorage";
import {setScreen, concatUDstr, loadTime} from "../helpers/screenHandlers";
import {s6IncompleteHandeler} from '../helpers/gaHandler';
import Inspectlet from "../../common/components/Inspectlet";
//contants
import {REGISTER_DATA} from "../../common/constants/apiConstants.js"
//css files
require('../style/regms.css');
require('../style/slick.css');
require('../style/slick.theme.css');

export default class AboutMeAndFamilyFlow extends React.Component {
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
      "6": {
        pNum: 6,
        pName: 'about',
        sliderIdx: 0
      },
      "9": {
        pNum: 9,
        pName: 'familyDetails',
        sliderIdx: 1
      },
      "10": {
        pNum: 10,
        pName: 'familyDetailsAbout',
        sliderIdx: 2
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
          // console.log('ga abtme pop',"P", `JSMS_REG_S${9}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${9}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${9}_R`);
        } else if (getItem('currentIncompleteRegPage') == '9')
          this.constructAndPushUrl("9", "screenLandingTracking")
        else if (getItem('currentIncompleteRegPage') == '6')
          this.constructAndPushUrl("6", "screenLandingTracking")
      }
    };
    // console.log('ga',"E", "jsms","new","1");
    this.GAObject.regTrackGA("E", "jsms", "new", "1");
    // console.log('ga',"U");
    this.GAObject.regTrackGA("U");
    let currentRegPage = +getItem('currentIncompleteRegPage');
    if (currentRegPage && currentRegPage == 10) {
      // on refresh of about family page slider moves to s9 and ga hit of s_9
      let sliderVal = this.pageStatics[currentRegPage - 1].sliderIdx;
      // console.log('ga abtmefamily',"P", `JSMS_REG_S9`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S9`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S9_R`);
      this.slider.slickGoTo(sliderVal);
      this.constructAndPushUrl(currentRegPage - 1);
    } else if (currentRegPage && currentRegPage != 10) {
      let sliderVal = this.pageStatics[currentRegPage].sliderIdx;
      if (currentRegPage == 6) {
        s6IncompleteHandeler()
      } else if (currentRegPage > 5 && currentRegPage < 11) {
        // console.log('ga abtmefamily',"P", `JSMS_REG_S${currentRegPage}`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S${currentRegPage}`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S${currentRegPage}_R`);
      }
      this.slider.slickGoTo(sliderVal);
      this.constructAndPushUrl(currentRegPage);
    } else {
      removeItem('currentIncompleteRegPage');
      removeItem('currentRegPage');
      removeItem('currentIncPage');
      setItem('currentIncompleteRegPage', '6');
      this.constructAndPushUrl(6);
      s6IncompleteHandeler();
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
    }, 1000);
    loadTime();
  }

  constructAndPushUrl(number, ham) {
    let num = number < 6 ? 6 : number;
    let tracker = getItem('trackParams');
    let incomplete = getItem('incomplete');
    let urlPath = '';
    if (incomplete == 1) {
      urlPath = `/register/newjsms?incompleteUser=1&s=${num}`;
    } else {
      urlPath = `/register/newjsms?source=${tracker ? tracker.source : ''}&s=${num}`;
    }
    num = num + '';
    setItem('currentIncompleteRegPage', num);
    if (ham !== "screenLandingTracking") {
      let curParams = concatUDstr();

      let screenLandingTracking =`/register/screenLandingTracking?screenNumber=s${num}&regMode=R&curParams=${curParams}`;
      if(incomplete == 1 && num == 6){
        screenLandingTracking =`/register/screenLandingTracking?screenNumber=s${num}I&regMode=R&curParams=${curParams}`;
      }

      commonApiCall(screenLandingTracking, {}, '', 'POST', '', false).then((response) => {
        console.log("res", response);
      });
    }
    this.props.history.push(urlPath);
  }

  handleCurrentStep(currentPage, pagenumber) {
    this.setPages(currentPage, pagenumber);
  }

  setPages(currentPage, pagenumber) {
    let slideNum = +getItem('currentIncompleteRegPage');
    if (currentPage == "6") {
      slideNum = 6;
      // pagenumber in create profile page to remove multiple tap issue on icons
      if (pagenumber) {
        setItem('currentIncompleteRegPage', pagenumber);
        this.constructAndPushUrl(pagenumber);
        this.slider.slickGoTo(pagenumber);
        if (pagenumber == 6) {
          s6IncompleteHandeler();
        } else {
          // console.log('ga--', "P", `JSMS_REG_S${+pagenumber}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${+pagenumber}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${+pagenumber}_R`);
        }
      } else {
        setItem('currentIncompleteRegPage', slideNum);
        this.constructAndPushUrl(pagenumber);
        this.slider.slickNext();
        if (slideNum == 6) {
          s6IncompleteHandeler();
        } else {
          // console.log('ga--', "P", `JSMS_REG_S${slideNum}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}`);
          this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}_R`);
        }
      }

    } else if (currentPage == "9") {
      slideNum = '9';
      setItem('currentIncompleteRegPage', slideNum);
      this.constructAndPushUrl(slideNum);
      this.slider.slickNext();
      // console.log('ga--', "P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}_R`);
    } else if (currentPage == "10") {

      slideNum = '10';
      setItem('currentIncompleteRegPage', slideNum);
      this.constructAndPushUrl(slideNum)
      this.slider.slickNext();
      // console.log('ga--', "P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}_R`);
    } else {

      slideNum = '9';
      setItem('currentIncompleteRegPage', slideNum);
      this.constructAndPushUrl(slideNum)
      this.slider.slickPrev();
      // console.log('ga--', "P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}_R`);
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
        <Inspectlet/>
        {this.state.showLoader && <Loader show="page" opacity={1}/>}
        <Slider ref={slider => (this.slider = slider)} {...settings}>
          <div className="fw bg4 slickContainer">
            <AboutMePage properties={this.props}
                         onIconClick={this.handleCurrentStep}/>
          </div>
          <div className="fw bg4 slickContainer">
            <FamilyDetails onIconClick={this.handleCurrentStep}
                           margin="8%"
                           ref="familyDetails"
            />
          </div>
          <div className="fw bg4 slickContainer">
            <AboutFamily onIconClick={this.handleCurrentStep}/>
          </div>

        </Slider>

      </div>
    )
  };
}