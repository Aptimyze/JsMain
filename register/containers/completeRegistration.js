import React from 'react';
import Slider from "react-slick";
//components
import Loader from "../../common/components/Loader";
import Complete from '../components/loginPages/Complete';
import MissingDetail from '../components/loginPages/MissingDetail';
import GA from '../../common/components/GA';
//services and helpers
import {getItem, removeItem, setItem} from "../services/localStorage";
import {concatUDstr, loadTime, setScreen} from "../helpers/screenHandlers";
import {getQueryString} from "../helpers/dataPreprocessor";
import AboutMePage from "../components/pagesAboutMeAndFamily/AboutMePage";
import {commonApiCall} from "../../common/components/ApiResponseHandler";
import Inspectlet from "../../common/components/Inspectlet";
//contants
//css files
require('../style/regms.css');
require('../style/slick.css');
require('../style/slick.theme.css');

let ud = {};
export default class CompleteRegistration extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      UD: {},
      showLoader: false,
      // showbackbutton:false
    };
    this.GAObject = new GA();
    this.handleCurrentStep = this.handleCurrentStep.bind(this);
    this.showLoader = this.showLoader.bind(this);
    this.hideLoader = this.hideLoader.bind(this);
    // this.headerBackbutton = this.headerBackbutton.bind(this);

  }

  // get api data and set height of container
  componentDidMount() {
    this.showLoader();
    removeItem('aBCounter');
    let currentIncPage = +getItem('currentIncPage');
    let scrnHeight = getItem('screenInitialHeight');
    if (!scrnHeight) setItem('screenInitialHeight', window.innerHeight);
    let trackParams = {
      source: getQueryString('source', '')
    };
    setItem('trackParams', trackParams);

    removeItem('currentIncompleteRegPage');
    removeItem('currentRegPage');

    setScreen();
    // console.log('ga',"E", "jsms","new","1");
    this.GAObject.regTrackGA("E", "jsms","new","1");
    // console.log('ga',"U");
    this.GAObject.regTrackGA("U");
    if (currentIncPage) {
      let sliderVal = 0;
      if (currentIncPage == 8) {
        sliderVal = 1
      } else if (currentIncPage == 6) {
        sliderVal = 2
      }
      this.slider.slickGoTo(sliderVal);
      this.constructAndPushUrl(currentIncPage);
      if(currentIncPage ==6){
        // console.log('ga compreg',"P", `JSMS_REG_S6_Incomplete`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete_R`);
      }else if(currentIncPage !=7 && currentIncPage>5 && currentIncPage<11){
        // console.log('ga compreg',"P", `JSMS_REG_S${currentIncPage}`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S${currentIncPage}`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S${currentIncPage}_R`);
      }
    } else {
      setItem('currentIncPage', 7);
      this.constructAndPushUrl(7);
      // console.log('ga compreg',"P", `JSMS_REG_S${7}`);
      // this.GAObject.regTrackGA("P", `JSMS_REG_S${7}`);
    }

    window.onpopstate = () => {
      let slideNum = +getItem('currentIncPage');
      this.slider.slickPrev();
      this.constructAndPushUrl(slideNum);
      if(slideNum !=7&& slideNum>5 && slideNum<11){
      // console.log('ga compreg pop',"P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${slideNum}_R`);
      }
    };

    setTimeout(() => {
      this.hideLoader()
    }, 2500);
    loadTime();
  }


  handleCurrentStep(currentPage, pagenumber) {
    ud = getItem('UD');
    this.setState({
      UD: ud
    });
    this.setPages(currentPage, pagenumber);
  }

  setPages(currentPage, pagenumber) {
    if (currentPage === "nextPage") {
      this.constructAndPushUrl(pagenumber);
      this.slider.slickNext();
      if(pagenumber ==6){
        // console.log('ga compreg',"P", `JSMS_REG_S6_Incomplete`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete_R`);
      }else{
        // console.log('ga--', "P", `JSMS_REG_S${+pagenumber}`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S${+pagenumber}`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S${+pagenumber}_R`);
      }
    } else if (currentPage === "previousPage") {
      this.slider.slickPrev();
      this.constructAndPushUrl(pagenumber);
      if(pagenumber == 6){
        // console.log('ga compreg',"P", `JSMS_REG_S6_Incomplete`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete`);
        this.GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete_R`);
      }else if(+pagenumber != 7){
      // console.log('ga--', "P", `JSMS_REG_S${+pagenumber}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${+pagenumber}`);
      this.GAObject.regTrackGA("P", `JSMS_REG_S${+pagenumber}_R`);
     }
    }
  }


  showLoader() {
    this.setState({showLoader: true});
  }

  hideLoader() {
    this.setState({showLoader: false});
    document.getElementById('tmpRegForm').style.display = "none"
  }

  constructAndPushUrl(number) {
    setItem('currentIncPage', number);
    let urlPath = `/register/newjsmsreg?incompleteUser=1&s=${number}`;
    let curParams = concatUDstr();

    let screenLandingTracking =`/register/screenLandingTracking?screenNumber=s${number}&regMode=R&curParams=${curParams}`;
    if(number == 6){
      screenLandingTracking =`/register/screenLandingTracking?screenNumber=s${number}I&regMode=R&curParams=${curParams}`;
    }
    commonApiCall(screenLandingTracking, {}, '', 'POST', '', false).then((response) => {
      console.log("res",response);
    });
    this.props.history.push(urlPath);

  }

  // headerBackbutton(showbackbutton){
  //   this.setState({showbackbutton});
  // }

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
            <Complete properties={this.props}
                      onIconClick={this.handleCurrentStep} />
          </div>
          <div className="fw bg4 slickContainer">
            <MissingDetail properties={this.props}
                           margin="7%"
                           onIconClick={this.handleCurrentStep}/>
          </div>
          <div className="fw bg4 slickContainer">
            <AboutMePage properties={this.props}
                           onIconClick={this.handleCurrentStep} />
          </div>
        </Slider>

      </div>
    )
  };
}
