import {setItem, getItem} from "../services/localStorage";
import RegSliderBinding from '../components/common/RegSliderBinding';
//set parent containers according to display
export const setScreen = () => {
  let scnHeight = getItem('screenInitialHeight');
  let slick = document.getElementsByClassName('slick-track');
  if (scnHeight && scnHeight >= window.innerHeight) {
    slick[0].style.height = scnHeight + "px";
    let cont = document.getElementsByClassName('slickContainer');
    if (cont.length > 0) {
      for (let i = 0; i < cont.length; i++) {
        cont[i].style.height = scnHeight + "px";
      }
    }
  } else {
    slick[0].style.height = window.innerHeight + "px";
    let cont = document.getElementsByClassName('slickContainer');
    if (cont.length > 0) {
      for (let i = 0; i < cont.length; i++) {
        cont[i].style.height = window.innerHeight + "px";
      }
    }
    setItem('screenInitialHeight', window.innerHeight)
  }


};

export const scrollOnTop = (element) => {
  let elm = document.getElementById(element);
  if (elm) {
    setTimeout(() => {
      elm.scrollTop = 0;
    }, 100)
  }
};

// set scroll to bottom of container
export const scrollToBottom = (element, val) => {
  let scnHeight = getItem('screenInitialHeight');
  let elm = document.getElementById(element);
  if (elm) {
    if (scnHeight && scnHeight >= window.innerHeight) {
      setTimeout(() => {
        if (val) {
          elm.scrollTop += val;
        } else {
          elm.scrollTop = elm.scrollHeight;
        }

      }, 500)
    } else {
      setTimeout(() => {
        if (val) {
          elm.scrollTop += val;
        } else {
          elm.scrollTop = elm.scrollHeight;
        }
      }, 500)
    }
  }
};

// reset specific page container on resize  - (call only from register.js win resize fn)
export const resizeContainer = (element, src) => {
  let scnHeight = getItem('screenInitialHeight');
  let elm = document.getElementById(element);

  if (elm) {
    if (scnHeight && scnHeight >= window.innerHeight) {
      setTimeout(() => {
        elm.style.height = (scnHeight - 110) + 'px';
      }, 100)
    } else {
      setTimeout(() => {
        elm.style.height = (window.innerHeight - 110) + 'px';
      }, 100)
    }
  }
};

export const focusOnCurrentElement = (id) => {
  let elemList = document.getElementsByClassName('setRD');
  for (let i = 0; i < elemList.length; i++) {
    if (elemList[i].id !== id) {
      elemList[i].setAttribute('readonly', true)
    }
  }
};

export const removeFocusFromAllElements = () => {
  let elemList = document.getElementsByClassName('setRD');
  for (let i = 0; i < elemList.length; i++) {
    elemList[i].removeAttribute('readonly')
  }
};

export const editCssOfContainer = () => {
  // alert('editCssOfContainer',window.innerHeight);
  let cont = document.getElementsByClassName('slickContainer');
  if (cont.length > 0) {
    for (let i = 0; i < cont.length; i++) {
      cont[i].style.height = window.innerHeight + "px";
    }
  }
  document.getElementsByClassName('slick-track')[0].style.height =
    window.innerHeight + "px";
  document.getElementsByClassName('sliderDataContainer')[0].style.height =
    window.innerHeight - 110 + "px";
  document.getElementsByClassName('slickContainer')[0].style.height =
    window.innerHeight + "px";
};

// add pointer-event so that multiple slider doesnt open
export const editClass = (showRegHamburger) => {
  let regSliderBlock = document.getElementsByClassName('regSliderBlock');
  if (showRegHamburger) {
    for (let i = 0; i < regSliderBlock.length; i++) {
      regSliderBlock[i].classList.add('reg_pointnone');
    }
  } else {
    for (let i = 0; i < regSliderBlock.length; i++) {
      regSliderBlock[i].classList.remove('reg_pointnone');
    }
  }
};

export const blurInputs = () => {
  let elm = document.getElementsByClassName('setRD');
  for(let i =0; i < elm.length; i++){
    elm[i].blur()
  }
};

const calculateTopPos = () => {
  let ht = window.innerHeight-110;
  let showP = Math.abs(Math.ceil(ht / 40));
  let up;
  up = Math.floor(showP / 2);
  if (showP % 2 == 0) {
    up = Math.floor(showP / 2);
  }
  up = up - 1;
  return (40 * up);
}

const dobsliderResizeChanges = (id) => {

  if (document.getElementById(id) != null) {
    let headHeight = 55;
    //height of header
    // clientHeight includes padding also
    let height = window.innerHeight - (headHeight + 55);
    let indh = document.getElementById(id).getElementsByTagName("li")[0]
      .offsetHeight;
    document.getElementById(id).parentElement.style.overflow = "hidden";
    document.getElementById(id).parentElement.style.height = window.innerHeight - 100 + "px";

    let hgt = document.getElementById(id).getElementsByTagName("li")[0]
      .clientHeight; // hgt is the height of one li
    //       let width=document.getElementById(id).children[0].style.width;
    let showP = Math.abs(Math.ceil(height / indh));
    let up, down;
    up = down = Math.floor(showP / 2);

    if (showP % 2 == 0) {
      up = Math.floor(showP / 2);
      down = Math.ceil(showP / 2);
    }
    /*start: so that filterPinkDiv can be in between of screen*/
    up = up - 1;
    down = down - 1;
    /*End*/

    let upArr = [],
      downArr = [];

    for (let i = 0; i < up; i++) {
      upArr[i] = "";
    }
    for (let i = 0; i < down; i++) {
      downArr[i] = "";
    }

    let options = {
      width: "100%",
      height: hgt,
      sliderHeight: indh,
      fakeb: down,
      faket: up,
      startSliderPosition: null,
      sliderType: 'dobSlider',
      startFromMiddle: true
    };
    this.fakeUp = up;
    let ud = getItem('UD');

    if (id == 'HAM_OPTION_1') {
      // let currentDateArr = window.jsMain.dob.date;
      // currentDateArr = [...upArr, ...currentDateArr, ...downArr];
      // window.jsMain.dob.date = currentDateArr;
      options.startSliderPosition = ud.dtofbirth_day ? ud.dtofbirth_day - 1 : null;
      new RegSliderBinding(options, id).init();
    } else if (id == 'HAM_OPTION_2') {
      // let currentMonthArr = window.jsMain.dob.month;
      // currentMonthArr = [...upArr, ...currentMonthArr, ...downArr];
      // window.jsMain.dob.month = currentMonthArr;
      options.startSliderPosition = ud.dtofbirth_month ? ud.dtofbirth_month - 1 : null;
      new RegSliderBinding(options, id).init();
    } else {
      // let currentYearArr = window.jsMain.dob.year;
      // currentYearArr = [...upArr, ...currentYearArr, ...downArr];
      // window.jsMain.dob.year = currentYearArr;
      options.startSliderPosition = ud.dtofbirth_year ? ud.dtofbirth_year - 1948 : 47 - this.fakeUp;
      new RegSliderBinding(options, id).init();
    }
  }
}


window.addEventListener("resize", ()=>{
  let scnHeight = getItem('screenInitialHeight');
  let el = document.getElementById('relationShipDiv');
  if(el){
    el.style.height = window.innerHeight+"px";
    document.getElementById('createProfile').style.height = window.innerHeight+"px";
  }
  let slickTrack = document.getElementsByClassName('slick-track');
  if(slickTrack.length>0){
    setTimeout( ()=> {
      slickTrack[0].style.height = window.innerHeight + "px";
      let slickContainer = document.getElementsByClassName('slickContainer');
      if (slickContainer.length > 0) {
        for (let i = 0; i < slickContainer.length; i++) {
          slickContainer[i].style.height = window.innerHeight + "px";
        }
      }
      let sliderDataContainer = document.getElementsByClassName('sliderDataContainer');
      if (sliderDataContainer.length > 0) {
        for (let i = 0; i < sliderDataContainer.length; i++) {
          sliderDataContainer[i].style.height = window.innerHeight -110 + "px";
        }
      }
    },150)
  }


  let termsAndPrivacyDiv = document.getElementById('termsAndPrivacyDiv');
  if(termsAndPrivacyDiv){
    if(window.innerHeight<481){
      termsAndPrivacyDiv.classList.remove('posAbs');
    }else{
      termsAndPrivacyDiv.classList.add('posAbs');
    }
  }
  let idElm = document.getElementById('Single_Slider');
  if (idElm != null) {
    document.getElementById('ham').style.height = window.innerHeight + "px";
    document.getElementById('hamView').style.height = window.innerHeight + "px";
    idElm.parentElement.parentElement.style.height = window.innerHeight - 55 - 35 + "px";
    idElm.parentElement.style.height = window.innerHeight - 55 - 35 + "px";
  }
  let dobDiv = document.getElementsByClassName('dobDiv');
  if(dobDiv.length>0){
    for (let i = 0; i < dobDiv.length; i++) {
      dobDiv[i].style.height = window.innerHeight -110 + "px";
    }
  }
  let filterPinkDiv = document.getElementsByClassName('filterPinkDiv');
  if(filterPinkDiv.length>0){
    let topPos = calculateTopPos();
    for (let i = 0; i < filterPinkDiv.length; i++) {
      filterPinkDiv[i].style.top = topPos + "px";
    }
  }
  dobsliderResizeChanges('HAM_OPTION_1');
  dobsliderResizeChanges('HAM_OPTION_2');
  dobsliderResizeChanges('HAM_OPTION_3');
  let incompleteContainer = document.getElementById('incompleteContainer');
  if(incompleteContainer){
    incompleteContainer.style.height = window.innerHeight+"px";
  }
  let backDropLogin = document.getElementById('backDropLogin');
  if(backDropLogin){
    backDropLogin.style.height = window.innerHeight + "px";
  }
  setItem('screenInitialHeight', window.innerHeight);
})

export const concatUDstr = () => {
  let curParams = "";
  let ud = localStorage.getItem('UD');
  if(ud){
    ud = JSON.parse(ud);
    for(let i in ud){
      curParams += i.substr(0,4) + ",";
    }
  }
  return curParams;
};

export const loadTime = () =>{
  var dt = new Date();
  var href = window.location.href;
  var reg = new RegExp('[?&]' + 's' + '=([^&#]*)', 'i');
  var num = reg.exec(href);
  document.onreadystatechange = function () {
    var number = 1;
    if(num){
      number = num[1] ? num[1] : 1
    }

    if(num[1]){
      if(+num[1]>=0 && +num[1] < 5){
        number = +num[1] + 1
      }
    }
    if (document.readyState === "complete") {
      var xhttp = new XMLHttpRequest();
      var loadingTime = (new Date() - dt)/1000;
      xhttp.open("POST", `/register/screenLandingTracking?screenNumber=t${number}&regMode=R&loadingTime=${loadingTime}`, true);
      xhttp.send();
      console.log("All resources React!", (new Date() - dt) / 1000);
    }
  };
}

