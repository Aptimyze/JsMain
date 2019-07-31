import React from 'react';
import Slider from "react-slick";
import IdleTimer from 'react-idle-timer'
import RegistrationFooter from '../common/RegistrationFooter';
import RegistrationHeader from '../common/RegistrationHeader';
import classNames from 'classnames';
import CardSlider from './CardSlider';
import TopError from '../../../common/components/TopError';
import errorStatements from '../../constant/errorStatements';
import {getItem, removeItem, setItem} from "../../services/localStorage";
import {commonApiCall} from "../../../common/components/ApiResponseHandler";
import {ABOUTME_REGISTER_DATA, EDIT_SUBMIT} from "../../../common/constants/apiConstants.js"
import {regPage2Fields} from "../../constant/apiData";
import {cardData} from "../../constant/aboutMeCards";
import {contructLoginData, trimNewLine, myTrim} from "../../helpers/dataPreprocessor";
import {focusOnCurrentElement, removeFocusFromAllElements, resizeContainer} from '../../helpers/screenHandlers';
import {errorGATracking} from '../../helpers/gaHandler';
import GA from '../../../common/components/GA';

import '../../style/aboutme.css';

class AboutMeDetail extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      nextBtnEnable: true,
      switchChecked: false,
      numberOfCharacters: 0,
      textOfTextarea: "",
      showHelpToolTip: false,
      enableWhiteOverlay: false,
      showError: false,
      showRegComplete: false,
      showLoader: false
    };
    this.GAObject = new GA();
    this.timerId = null;
    this.onActive = this._onActive.bind(this);
    this.onIdle = this._onIdle.bind(this);
    this.handleCurrentCard = this.handleCurrentCard.bind(this);
    this.nextClick = this.nextClick.bind(this);
    this.showLoaderfn = this.showLoaderfn.bind(this);
    this.hideLoader = this.hideLoader.bind(this);
    // this.handleClose = this.handleClose.bind(this);
    this.placeholderTextEng = "Introduce yourself (Don't mention your name). Write about your values, beliefs/goals, aspirations/interests and hobbies.This text will be screened by our team";
    this.placeholderTextHindi = "हमें और अपने जीवन साथी को अपने और अपने व्यक्तित्व के बारे में बताएं।हिंदी में लिखने के लिए अपना कीबोर्ड बदलें।इस पाठ का परीक्षण हमारी टीम द्वारा किया जाएगा।";
    this.incompleteDataKeys = [];
  }

  componentWillReceiveProps() {
    this.setState({showError: false});
  }

  showLoaderfn() {
    this.setState({showLoader: true, errorArray: [errorStatements.ABOUT_ME_TEXT]});
  }

  hideLoader() {
    this.setState({showLoader: false});
  }

  _onActive(e) {
    clearInterval(this.timerId);
    this.setState({showHelpToolTip: false});
  }

  _onIdle(e) {
    this.setState({showError: false});
    this.timerId = setInterval(() => {
      this.setState({showHelpToolTip: !this.state.showHelpToolTip});
    }, 10000);
    this.setState({showHelpToolTip: true});
  }

  handleSwitch(e) {
    if (e.target.checked) {
      this.setState({switchChecked: true, showError: false}, () => {
        // console.log('ga--',"E","jsms","ABOUTME_TOGGLE_LANG","about me toggle English to Hindi");
        this.GAObject.regTrackGA("E", "jsms", "ABOUTME_TOGGLE_LANG", "about me toggle  English to Hindi");
      });
    } else {
      this.setState({switchChecked: false, showError: false}, () => {
        // console.log('ga--',"E","jsms","ABOUTME_TOGGLE_LANG","about me toggle Hindi to English");
        this.GAObject.regTrackGA("E", "jsms", "ABOUTME_TOGGLE_LANG", "about me toggle  Hindi to English");
      });
    }
    this.slider.slickGoTo(0)
  }

  handleTextChange(e) {
    let text = e.target.value;
    let trimmedNewLine = myTrim(text);
    trimmedNewLine = trimNewLine(trimmedNewLine);

    this.setState({textOfTextarea: e.target.value, numberOfCharacters: trimmedNewLine.length, showError: false});
  }

  showHint(e) {
    if (!e.target.value) {
      e.target.placeholder = this.state.switchChecked ? "यहाँ लिखिए" : "Type Here";
    }
  }

  handleCurrentCard(cardNumber) {
    this.slider.slickGoTo(cardNumber);
  }

  hideHint(e) {
    e.target.placeholder = '';
  }

  handleClose() {
    ///  console.log('close');
    this.setState({enableWhiteOverlay: false})
  }

  nextClick() {
    let ud = getItem('UD');
    let staticData = getItem('staticData');
    if (!ud) ud = {};

    else if (ud.hasOwnProperty('gender')) {
      delete ud.gender;
    }
    this.state.numberOfCharacters > 99 ? this.setState({showError: false}, () => {
      ud.yourinfo = '';
      for (let i = 0; i < this.state.textOfTextarea.length; i++) {
        if (this.state.textOfTextarea[i] == "#" || this.state.textOfTextarea[i] == "&") {
          ud.yourinfo += encodeURIComponent(this.state.textOfTextarea[i]);
        } else {
          ud.yourinfo += this.state.textOfTextarea[i];
        }

      }

      let incomplete = getItem('incomplete');
      if (incomplete) {
        if (incomplete == '1') {
          this.showLoaderfn();
          let tmp = {};
          let arr = [];
          for (let k in staticData.Incomplete) {
            if(staticData.Incomplete[k].key == "DTOFBIRTH"){
             tmp['editFieldArr[DTOFBIRTH][day]']='';
             tmp['editFieldArr[DTOFBIRTH][year]']='';
             tmp['editFieldArr[DTOFBIRTH][month]']='';
             arr.push('DTOFBIRTH_DAY','DTOFBIRTH_YEAR','DTOFBIRTH_MONTH')
            }
            else {
              tmp[`editFieldArr[${staticData.Incomplete[k].key}]`] = '';
              arr.push(staticData.Incomplete[k].key);
            }

          }
          if (arr) {
            for (let i in ud) {
              if(ud.hasOwnProperty(i)){
                if (arr.indexOf(i.toUpperCase()) == -1) {
                  delete ud[i];
                }
              }
            }
          }
          delete tmp['editFieldArr[GENDER]'];
          setItem('UD', ud);
          let aboutMeParams = contructLoginData(ud, tmp, 'incomplete');
          //console.log(aboutMeParams, 123);
          let url1 = `${EDIT_SUBMIT}?incomplete=Y`;
          commonApiCall(url1, aboutMeParams, '', 'POST', '', false).then((response) => {
            if (response) {
              if (response.responseMessage === "login succesful") {
                this.hideLoader();
                setItem('selfCountry', response.selfCountry);

                this.setState({showRegComplete: true, errorArray: [errorStatements.REGISTRATION_COMPLETE]}, () => {
                  // console.log('ga');
                  errorGATracking('s6', errorStatements.REGISTRATION_COMPLETE);
                });
                removeItem('currentIncPage');
                removeItem('showHeaderBackButton');
                setItem('UD', {});
                setItem('UD_display', {});
                setTimeout(() => {
                  //new route here
                  this.props.properties.history.push(`/register/family?s=9`);
                }, 3000);
              } else if (response.status == 500 || !response.status) {
                this.hideLoader();
                errorGATracking('s6', errorStatements.SOMETHING_WRONG);
              }
            } else {
              this.hideLoader();
              errorGATracking('s6', errorStatements.SOMETHING_WRONG);
            }
          });
        }

      } else {
        for (let i in ud) {
          if (i != "yourinfo") {
            delete ud[i];
          }
        }
        setItem('UD', ud);
        let aboutMeParams = contructLoginData(ud, regPage2Fields);
        let queryString = Object.keys(aboutMeParams).map(key => key + '=' + aboutMeParams[key]).join('&');
        let url1 = `${ABOUTME_REGISTER_DATA}?${queryString}`;
        this.showLoaderfn();
        commonApiCall(url1, {}, '', 'POST', '', false, '', '', '', '').then((response) => {
          if (response) {
            if (response.responseStatusCode === "0") {
              setItem('selfCountry', response.selfCountry);
              this.setState({showRegComplete: true, errorArray: [errorStatements.REGISTRATION_COMPLETE]}, () => {
                // console.log('ga');
                errorGATracking('s6', errorStatements.REGISTRATION_COMPLETE);
              });
              setTimeout(() => {
                this.hideLoader();
                this.props.onIconClick('9');
                setItem('UD', {});
                setItem('UD_display', {});
              }, 3000);
            } else if (response.status == 500 || !response.status) {
              this.hideLoader();
              errorGATracking('s6', errorStatements.SOMETHING_WRONG);
            }
          } else {
            this.hideLoader();
            errorGATracking('s6', errorStatements.SOMETHING_WRONG);
          }
        });
      }

    }) : (this.setState({showError: true, errorArray: [errorStatements.ABOUT_ME_TEXT]}, () => {
      // console.log('ga');
      errorGATracking('s6', errorStatements.ABOUT_ME_TEXT);
    }));
  }

  handleHelpButton() {
    // console.log('ga--',"E","jsms","ABOUTME_SHOW_CARD","show visual card");
    this.GAObject.regTrackGA("E", "jsms", "ABOUTME_SHOW_CARD", "show visual card")
    this.setState({enableWhiteOverlay: true, showError: false});
  }

  handleBack() {
    this.props.onIconClick('previousPage', 8);
  }

  render() {
    let settings = {
      dots: true,
      swipe: true,
      touchMove: true,
      infinite: false,
      // arrows: true,
      speed: 500,
      slidesToShow: 1,
      slidesToScroll: 1
    };
    let showBackInHeader = false;
    let screenInitialHeight = getItem('screenInitialHeight');
    let tabName = getItem('tabName');
    let nextFeildName = "Create My Profile";
    if (tabName) {
      nextFeildName = "Complete your Profile";
      // showBackInHeader = false
      // showBackInHeader = this.props.showbackbutton;
      showBackInHeader = getItem('showHeaderBackButton');
    }
    tabName = tabName ? tabName : " About me";
    return (
      <div>
        <IdleTimer
          ref={ref => {
            this.idleTimer = ref
          }}
          element={document}
          onActive={this.onActive}
          onIdle={this.onIdle}
          onAction={this.onAction}
          // debounce={250}
          timeout={15000}/>
        <div className="fw bg1">
          <div className="fw bg1">
            <RegistrationHeader onIconClick={this.handleBack.bind(this)}
                                headerData={tabName}
                                hideBack={!showBackInHeader}/>
          </div>
        </div>

        <div>
          {(this.state.showError || this.state.showRegComplete) &&
          <TopError timeToHide={3000}
                    leftAlign={true}
                    errorArray={this.state.errorArray}
                    topPosition={55}
                    width={window.innerWidth + 'px'}/>}
        </div>
        {this.state.showLoader && <div className="abtloader simple dark loaderimage"/>}
        <div className='bg4 sliderDataContainer'
             style={{
               height: (screenInitialHeight ? screenInitialHeight - 110 : window.innerHeight - 110) + 'px',
               overflow: 'auto'
             }}
             id="aboutMeContainer">
          {/* start subheading */}
          <div className="fullwid clearfix pad3">
            <div id="toggleAbout" className="fl wid61p">
              <div className="wrap fl">
                <input type="checkbox" id="id-name--1" name="set-name" className="switch-input"
                       onChange={(e) => this.handleSwitch(e)}/>
                <label htmlFor="id-name--1" className="switch-label"><span
                  className="toggle--on fl fontreg f16 color22">Write in English</span><span
                  className="toggle--off fl fontreg f16 color22">हिंदी में लिखें</span></label></div>
            </div>
            {/* start questionmark  */}
            <div className="fr wid10p txtr posrel">

              {!this.state.enableWhiteOverlay ? <div className="posrel">
                <i className="helIconAbout" onClick={e => {
                  this.handleHelpButton()
                }}/>
                <span className={classNames(this.state.showHelpToolTip ? '' : 'dispnone')}>
                    <span style={{width: '184px'}} id="js-eng-Htext"
                          onClick={e => {
                            e.preventDefault();
                            this.setState({
                              showHelpToolTip: false
                            });
                          }
                          }
                          className={classNames(!this.state.switchChecked && !this.state.enableWhiteOverlay ? "" : "dispnone ", "dispibl tooltiptextAB posrel")}>Take help to write this</span>
                    <span style={{width: '120px'}} id="js-hindi-Htext"
                          onClick={e => {
                            e.preventDefault();
                            this.setState({
                              showHelpToolTip: false
                            })
                          }
                          }
                          className={classNames(!this.state.switchChecked && !this.state.enableWhiteOverlay ? "dispnone" : "", "dispibl tooltiptextAB posrel")}>सहायता लें</span>
                  </span>
              </div> : ''}


            </div>
            {/* end questionmark */}
          </div>
          {/* end subheading */}
          {/* start textarea */}
          <div className="pad1 brdr1 bg11">
            <div className="pt15 pb10 fullwid">
              <div className="fl color12 f12">Type min 100 Chars</div>
              <div className="fr color12 f12">Count - <span
                className={classNames(this.state.numberOfCharacters > 99 ? "colorGreen" : "color2")}> {this.state.numberOfCharacters}</span>
              </div>
              <div className="clr"/>
            </div>
            <div className="pt10">
                <textarea id="txtAbtMe" className="fullwid color12 f17 fontlig lh30 hgt180 bg11 setRD"
                          value={this.state.textOfTextarea}
                          placeholder={this.state.switchChecked ? "यहाँ लिखिए" : "Type Here"}

                          onChange={e => {
                            this.handleTextChange(e);
                          }}
                          onFocus={e => {
                            focusOnCurrentElement('txtAbtMe');
                            this.hideHint(e)
                          }}
                          onBlur={e => {
                            this.showHint(e);
                            removeFocusFromAllElements();
                            resizeContainer('aboutMeContainer');
                          }}/>
            </div>
          </div>
          {/* end textarea */}
          <div style={{
            padding: '14px',
            color: '#9a9a9a'
          }}>
            <strong>
              {this.state.switchChecked ?  "सुझाव:" : "Hints:"}
            </strong>
            <div style={{marginTop: '6px'}}>
              {this.state.switchChecked ? this.placeholderTextHindi : this.placeholderTextEng}
            </div>
          </div>
          {/* start white overlay */}
          <div id="whiteoverlay"
               className={classNames(this.state.enableWhiteOverlay ? "posfix top55 bg4" : "dispnone", "whiteoverlay")}
               style={{
                 height: (screenInitialHeight ? screenInitialHeight : window.innerHeight - 55) + 'px',
                 overflow: 'auto',
                 width: window.innerWidth + 'px'
               }}>

            <div className="">

              <div className="vc_close" onClick={e => {
                this.handleClose()
              }}/>

              <Slider ref={slider => (this.slider = slider)} {...settings}>
                {cardData.map((data, index) => {
                  return <div className="posrel height440"
                              style={{outline: 'none'}}
                              key={index}>
                    <CardSlider
                      data={data}
                      switchChecked={this.state.switchChecked}
                      key={index}
                      cardNumber={index}
                      handleCurrentCard={this.handleCurrentCard}
                    />
                  </div>
                })}
              </Slider>
            </div>
          </div>

          {/* end white overlay */}
        </div>
        {!this.state.enableWhiteOverlay ?
          <div className='bg7 fw posfix btm0'
               onClick={
                 this.nextClick
               }>
            <RegistrationFooter text={nextFeildName}/>
          </div> : ''}
      </div>
    )
  }
}

export default AboutMeDetail;