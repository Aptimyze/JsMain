import React from 'react';
import Slider from "react-slick";
//components
import Loader from "../../common/components/Loader";
import MarketingProfileFor from '../components/pages/MarketingProlifeFor';
import PersonalDetails from '../components/pages/PersonalDetails';
import CareerDetails from '../components/pages/CareerDetails';
import SocialDetails from '../components/pages/SocialDetails';
import LoginDetails from '../components/pages/LoginDetails';
import GA from '../../common/components/GA';
//services and helpers
import {commonApiCall} from "../../common/components/ApiResponseHandler";
import {getItem, removeItem, setItem} from "../services/localStorage";
import {concatUDstr, setScreen, loadTime} from "../helpers/screenHandlers";
import {getQueryString, getSearchParameters} from "../helpers/dataPreprocessor";
//contants
import {REGISTER_DATA} from "../../common/constants/apiConstants.js";
import {latestTrackParams} from "../constant/apiData";
import {GTM} from "../../common/components/GTManager";
//css files
require('../style/regms.css');
require('../style/slick.css');
require('../style/slick.theme.css');

let ud = {};
export default class MarketingRegistration extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      UD: {},
      showLoader: false
    };
    this.GAObject = new GA();
    this.handleCurrentStep = this.handleCurrentStep.bind(this);
    this.showLoader = this.showLoader.bind(this);
    this.hideLoader = this.hideLoader.bind(this);
  }

  // get api data and set height of container
  componentDidMount() {
    let termsAndPrivacyDiv = document.getElementById('termsAndPrivacyDiv');
    let regMode = localStorage.getItem('regMode');
    if(termsAndPrivacyDiv){
      if(window.innerHeight<481){
        termsAndPrivacyDiv.classList.remove('posAbs');
      }else{
        termsAndPrivacyDiv.classList.add('posAbs');
      }
    }
    let el = document.getElementById('relationShipDiv');
    if(el.clientHeight>window.innerHeight){
      el.style.height = window.innerHeight+"px";
      document.getElementsByClassName('slick-track')[0].style.height =
        window.innerHeight + "px";
    }
    removeItem('incomplete');
    removeItem('currentIncompleteRegPage');
    removeItem('currentIncPage');
    setItem('timeStamp', (new Date()).getTime());
    setItem('trackServerParams', latestTrackParams);
    //this.showLoader();
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    if (!ud || ud == "null") {
      setItem('UD', {})
    }
    if (!ud_display || ud_display == "null") {
      setItem('UD_display', {})
    }
    let scrnHeight = getItem('screenInitialHeight');
    if (!scrnHeight || scrnHeight != window.innerHeight) {
      setItem('screenInitialHeight', window.innerHeight);
    }
    let trackParams = getItem('trackParams');

    if (!trackParams) {
      trackParams = getSearchParameters();
      setItem('trackParams', trackParams);
    } else if (trackParams['source'] != getQueryString('source', '')) {
      trackParams = getSearchParameters();
      setItem('trackParams', trackParams);
    }



    let currentRegPage = +getItem('currentRegPage');
    // console.log('ga',"E", "jsms","new","1");
    // console.log('ga',"U");

    if (currentRegPage) {
      ud = getItem('UD');
      this.setState({
        UD: ud
      });
      let num = +currentRegPage + 1;
      // first page refresh
      if (currentRegPage < 1) {
        this.GAObject.regTrackGA("P", `JSMS_REG_S1_${regMode}`);
      } else if (num <= 5) {
        this.GAObject.regTrackGA("P", `JSMS_REG_S${num}_${regMode}`);
      }
      this.slider.slickGoTo(currentRegPage);
      this.constructAndPushUrl(currentRegPage);
    } else {
      removeItem('currentIncompleteRegPage');
      removeItem('currentRegPage');
      removeItem('currentIncPage');
      removeItem('staticData');
      setItem('currentRegPage', 0);
      this.constructAndPushUrl(0);
      this.GAObject.regTrackGA("P", `JSMS_REG_S1_${regMode}`);
    }
    if(trackParams){
      if (!trackParams.hasOwnProperty('source')) {
        trackParams.source = "unknown";
        setItem('trackParams', trackParams);
      }
    }
    let sourceTrackMIS = `/register/sourceTrackMIS?source=${trackParams ? trackParams.source : 'unknown'}`;
    commonApiCall(sourceTrackMIS, {}, '', 'POST', '', false).then((response) => {
      if (response && response.sourceGroup) {
        let ltp = getItem('trackServerParams');
        ltp.source = response.sourceGroup.SOURCEID;
        ltp.groupname = response.sourceGroup.GROUPNAME;
        GTM(response.sourceGroup.GROUPNAME, response.sourceGroup.SOURCEID);
        setItem('trackServerParams', ltp);
      }
    });
    let url1 = `${REGISTER_DATA}?k=reg_city_jspc&dataType=json`;
    commonApiCall(url1, {}, '', 'GET', '', false).then((response) => {
      setItem('reg_city_jspc', {});
      if (response) {
        setItem('reg_city_jspc', response)
      }
    });
    let url = `${REGISTER_DATA}?l=children,horoscope_match,sect,jamaat,subcaste,reg_mstatus,religion,citypincode,employed_in,country_res,city_res,height,mtongue,reg_caste_,res_status,state_res,edu_level_new,occupation,income,reg_mstatus,religion,isd,degree_grouping_reg,degree_ug,degree_pg&dataType=json`;
    commonApiCall(url, {}, '', 'GET', '', false).then((response) => {
      setItem('staticTableData', {});
      if (response) {
        setItem('staticTableData', response)
      }
    });

    setScreen();

    window.onpopstate = () => {
      setItem('timeStamp', (new Date()).getTime());
      let hamDetails = getItem('hamDetails');
      let slideNum = +getItem('currentRegPage');
      if (hamDetails && hamDetails.showRegHamburger) {
        switch (slideNum) {
          case 1:
            this.refs.personalDetails.hamState(false, hamDetails.hamName);
            break;
          case 2:
            this.refs.careerDetails.hamState(false, hamDetails.hamName);
            break;
          case 3:
            this.refs.socialDetails.hamState(false, hamDetails.hamName);
            break;
        }
        this.constructAndPushUrl(slideNum, "screenLandingTracking")
      } else {
        this.slider.slickPrev();
        slideNum = slideNum - 1;
        setItem('currentRegPage', slideNum);
        if (slideNum === -1) {
          window.location.href = localStorage.getItem('pageM') ?
            localStorage.getItem('pageM') : '/';
        } else if (slideNum >= 0 && slideNum <= 4) {
          this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum + 1}_${regMode}`);

          this.constructAndPushUrl(slideNum);
        }
      }
    };

    setTimeout(() => {
      // this.hideLoader()
      document.getElementById('tmpRegForm').style.display = "none"
    }, 500);
    loadTime();

    //GTM('facebook', 'fb_Mum_M')
  }


  handleCurrentStep(currentPage, pagenumber) {
    ud = getItem('UD');
    this.setState({
      UD: ud
    });
    this.setPages(currentPage, pagenumber);
  }

  setPages(currentPage, pagenumber) {
    // let slideNum = +getItem('currentRegPage');
    let regMode = localStorage.getItem('regMode')
    this.slider.slickGoTo(+pagenumber);
    this.constructAndPushUrl(+pagenumber);
    this.GAObject.regTrackGA("P", `JSMS_REG_S${+pagenumber + 1}_${regMode}`);
    if (currentPage == 'nextPage') {
      let timeStamp = getItem('timeStamp');
      let now = (new Date()).getTime();
      let timeDiff = (now - timeStamp) / 1000;
      setItem('timeStamp', now);
      this.GAObject.regTrackGA("E", "jsms", `regPageNavigations${+pagenumber}_s${+pagenumber + 1}_${regMode}`, `time_${timeDiff}`);
    } else if (currentPage == 'previousPage') {
      setItem('timeStamp', (new Date()).getTime());
    }
  }


  showLoader() {
    this.setState({showLoader: true});
  }

  hideLoader() {
    this.setState({showLoader: false});
  }

  constructAndPushUrl(number, ham) {
    let num = number < 0 ? 0 : number;
    let regMode = localStorage.getItem('regMode');
    if(!ham){
      let curParams = concatUDstr();

      let screenLandingTracking =`/register/screenLandingTracking?screenNumber=s${+num+1}&regMode=${regMode}&curParams=${curParams}`;
      commonApiCall(screenLandingTracking, {}, '', 'POST', '', false).then((response) => {
        // console.log("res",response);
      });
    }
    let trackParams = getItem('trackParams');
    if(trackParams){
      if (!trackParams.hasOwnProperty('source')) {
        trackParams.source = "unknown";
        setItem('trackParams', trackParams);
      }
    }
    setItem('currentRegPage', num);
    let completePath = this.props.location ? this.props.location.pathname : '/registration2';
    let urlPath = `${completePath}?source=${trackParams ? trackParams.source : 'unknown'}&s=${num}`;
    this.props.history.push(urlPath);
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
          <div className="fw bg4 slickContainer" id="createProfile">
            <MarketingProfileFor onIconClick={this.handleCurrentStep}/>
          </div>
          <div className="fw bg4 slickContainer">
            <PersonalDetails onIconClick={this.handleCurrentStep} properties={this.props}
                             ref="personalDetails"/>
          </div>
          <div className="fw bg4 slickContainer">
            <CareerDetails onIconClick={this.handleCurrentStep} ref="careerDetails"/>
          </div>
          <div className="fw bg4 slickContainer">
            <SocialDetails onIconClick={this.handleCurrentStep} ref="socialDetails"/>
          </div>
          <div className="fw bg4 slickContainer">
            <LoginDetails onIconClick={this.handleCurrentStep}
                          showLoader={() => this.showLoader()}
                          hideLoader={() => this.hideLoader()}
                          properties={this.props}/>
          </div>
        </Slider>

      </div>
    )
  };
}