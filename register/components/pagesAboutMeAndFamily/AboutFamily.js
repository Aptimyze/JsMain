import React from 'react';
import RegistrationHeader from "../common/RegistrationHeader";
import RegistrationFooter from '../common/RegistrationFooter';

import {commonApiCall} from "../../../common/components/ApiResponseHandler";
import {ABOUTFAMILY_REGISTER_DATA} from "../../../common/constants/apiConstants.js"
import {regPage3Fields, regPage3FieldsMapUd} from "../../constant/apiData";
import {contructLoginData} from "../../helpers/dataPreprocessor";
import {getItem, removeItem, setItem} from "../../services/localStorage";
import {
  focusOnCurrentElement,
  removeFocusFromAllElements,
  resizeContainer,
  scrollOnTop
} from '../../helpers/screenHandlers';


import '../../style/switch.css';
import '../../style/aboutme.css';


class AboutFamily extends React.Component {
  constructor(props) {
    super(props);
    this.placeholderTextEng = "Write about your parents and brothers or sisters. Where do they live? What are they doing?";
    this.nextClick = this.nextClick.bind(this);
  }

  componentDidMount() {
    this.setTextDiv()
  }

  setTextDiv() {
    let ud = getItem("UD");
    document.getElementById('txtAbtFamily').innerText = this.placeholderTextEng;
    if (ud) {
      if (ud.familyinfo) {
        document.getElementById('txtAbtFamily').innerText = ud.familyinfo
      }
    }
  }

  parseHashAnds(str){
    if(str){
      if(str.length > 0){
        let text = '';
        for(let i = 0; i<str.length; i++){
          if(str[i]=="#" || str[i]=="&"){
            text +=  encodeURIComponent(str[i]);
          }
          else {
            text +=  str[i];
          }

        }
        return text;
      }
    }
    else return ''
  }

  nextClick(src) {
    let inputs = getItem("UD");
    if(inputs){
      if(inputs['gothra']){
        inputs['gothra'] = this.parseHashAnds(inputs['gothra'])
      }
      if(inputs['familyinfo']){
        inputs['familyinfo'] = this.parseHashAnds(inputs['familyinfo'])
      }
      if(inputs['ancestral_origin']){
        inputs['ancestral_origin'] = this.parseHashAnds(inputs['ancestral_origin'])
      }
      if(src === "skip"){
        inputs['familyinfo'] = '';
      }
    }

    for(let obj in inputs){
      if(!regPage3FieldsMapUd.hasOwnProperty(obj)){
        delete inputs[obj];
      }
    }
    setItem('UD',inputs);
    let aboutMeParams = contructLoginData(inputs, regPage3Fields);
    let queryString = Object.keys(aboutMeParams).map(key => key + '=' + aboutMeParams[key]).join('&');
    let url1 = `${ABOUTFAMILY_REGISTER_DATA}?${queryString}`;
    commonApiCall(url1, {}, '', 'POST', '', false).then((response) => {
      let incomplete = getItem('incomplete');
      if(incomplete == 1){
        window.location.href = '/profile/mainmenu.php';
      }
      else {
        let tsp = getItem('trackServerParams');
        window.location.href = `/profile/mainmenu.php?fromReg=1&groupname=${tsp ? tsp.groupname : ""}&adnetwork1=${tsp ? tsp.adnetwork1 : ""}&source=${tsp ? tsp.source : ""}`;
      }
      removeItem('staticTableData');
      removeItem('statictables');
      removeItem('incomepleteData');
      removeItem('UD_display');
      removeItem('UD');
      removeItem('tabName');
      removeItem('staticData');
      removeItem('trackParams');
      removeItem('reg_city_jspc');
      removeItem('currentIncompleteRegPage');
      removeItem('currentRegPage');
      removeItem('currentIncPage');
      removeItem('hamDetails');
      removeItem('incomplete');
      removeItem('screenInitialHeight');
    });
  }

  handleDivClick() {
    let screenInitialHeight = getItem('screenInitialHeight');
    let ta = document.getElementById('txtAbtFamilyTA');
    let td = document.getElementById('txtAbtFamily');
    let height = (screenInitialHeight ? screenInitialHeight - 120 : window.innerHeight - 120) + 'px';
    td.style.display = 'none';
    ta.className = "fullwid color12 f17 fontlig lh30 bg11 setRD";
    ta.style.height = height;
    ta.style.overflow = 'none';
    ta.style.display = 'block';
    ta.focus();
    let ud = getItem("UD");
    ta.placeholder = this.placeholderTextEng;
    if (ud) {
      if (ud.familyinfo) {
        ta.value = ud.familyinfo
      }
    }
    ta.addEventListener('change', (e) => {
      let ud = getItem('UD');
      ud['familyinfo'] = e.target.value;
      setItem('UD', ud)

    })
    ta.addEventListener('focus', (e) => {
      let placeholder = e.target.placeholder;
      focusOnCurrentElement('txtAbtFamilyTA');
      if (placeholder == this.placeholderTextEng) {
        e.target.placeholder = '';
      }

    })
    ta.addEventListener('blur', (e) => {
      if (e.target.value == '') {
        e.target.placeholder = this.placeholderTextEng;
      }
      removeFocusFromAllElements();
      ta.style.display = 'none';
      td.style.display = 'block';
      this.setTextDiv();
      resizeContainer('aboutFamilyContainer');

    })

  }
  // secondaryBtnFn(){
  //   let incomplete = getItem('incomplete');
  //   if(incomplete == 1){
  //     window.location.href = '/profile/mainmenu.php';
  //   }
  //   else {
  //     let tsp = getItem('trackServerParams');
  //     window.location.href = `/profile/mainmenu.php?fromReg=1&groupname=${tsp ? tsp.groupname : ""}&adnetwork1=${tsp ? tsp.adnetwork1 : ""}&source=${tsp ? tsp.source : ""}`;
  //   }
  //   removeItem('staticTableData');
  //   removeItem('statictables');
  //   removeItem('incomepleteData');
  //   removeItem('UD_display');
  //   removeItem('UD');
  //   removeItem('tabName');
  //   removeItem('staticData');
  //   removeItem('trackParams');
  //   removeItem('reg_city_jspc');
  //   removeItem('currentIncompleteRegPage');
  //   removeItem('currentRegPage');
  //   removeItem('currentIncPage');
  //   removeItem('hamDetails');
  //   removeItem('incomplete');
  //   removeItem('screenInitialHeight');
  // }

  render() {
    let screenInitialHeight = getItem('screenInitialHeight');
    return (
      <div>
        <div className="fw bg1" onClick={() => {
          scrollOnTop('aboutFamilyContainer')
        }}>
          <RegistrationHeader headerData="About Family"
                              showSecondaryBtn={true}
                              secondaryBtnFn={this.nextClick.bind(this, "skip")}
                              secondaryBtn = {'Skip'}
                              secondaryBtnCss = {{
                                "position": "absolute",
                                "right": "0",
                                "top": "15px",
                              }}
                              onIconClick={this.props.onIconClick}/>
        </div>
        <div className='bg4 sliderDataContainer'
             style={{
               height: (screenInitialHeight ? screenInitialHeight - 110 : window.innerHeight - 110) + 'px',
               overflow: 'auto'
             }}
             id="aboutFamilyContainer">
          <div className="pad1 brdr1 bg11 fullheight">
            <div className="pt10">
              <div id="txtAbtFamily"
                   className="fullwid color12 f17 fontlig lh30 bg11 wordBreak"
                   onClick={this.handleDivClick.bind(this)}
                   style={{
                     height: (screenInitialHeight ? screenInitialHeight - 150 : window.innerHeight - 150) + 'px',
                     overflow: 'auto'
                   }}
              />
              <textarea style={{display: 'none'}} id="txtAbtFamilyTA"/>
            </div>
          </div>

        </div>
        <div className='bg7 fw'
             onClick={this.nextClick}>
          <RegistrationFooter text={"Done"}/>
        </div>
      </div>
    )
  }
}

export default AboutFamily;