import React from "react";

export default class RegSliderBinding {
  constructor(options, id) {
    // super(props);
    this.id = id;
    // this.callFunction = callFun;
    this.windowWidth = options.width;
    this.startSliderPosition = options.startSliderPosition;

    this.windowHeight = options.height;
    this.sliderHeight = options.sliderHeight;

    this.fakebottom = options.fakeb;
    this.faketop = options.faket;
    this.el = document.getElementById(id);
    this.selectedSliderIndex = -1;
    this.category = options.sliderType;
    this.startFromMiddle = options.startFromMiddle;
    this.childElement = this.el.children;
    this.thresholdWidth = 5;
    this.gender = options.gender;
    this.stTime;
    this.slider = {
      threshold: 10,
      working: false,
      x_threshold: 2,
      movement: false,
      transform: 0,
      index: 0,
      maxindex: 0
    };

    /*For sliding max slider on clicking value of min slider*/
    this.state = {
      valForMaxSlide: "",
      valForMinSlide: "",
      // firstTime: false
    };
  }

  init() {
    this.CssFix();
    this.SliderParent();
    this.AddCssToSelf();
    this.AlterChildrenCss();
    this.initTouch();
  }

  CssFix() {
    // create our test div element
    let div = document.createElement("div");
    // css transition properties
    let props = [
      "WebkitPerspective",
      "MozPerspective",
      "OPerspective",
      "msPerspective"
    ];
    // test for each property
    for (let i in props) {
      if (div.style[props[i]] !== undefined) {
        this.slider.cssPrefix = props[i]
          .replace("Perspective", "")
          .toLowerCase();
        this.slider.animProp =
          "-" + this.slider.cssPrefix + "-transform";
        return true;
      }
    }
  }

  SliderParent() {
    //slider
    this.slider.parent = this.el;
  }

  AddCssToSelf() {
    let width = window.Width;
    let height =
      this.childElement.length * this.sliderHeight + this.thresholdWidth;
    this.el.style.width = width + "px";
    this.el.style.height = height + "px";
  }

  AlterChildrenCss() {
    // this.slider.maxindex -- length of slider
    this.slider.maxindex =
      this.childElement.length - 1 - this.fakebottom - this.faketop;
    for (let i = 0; i < this.childElement.length; i++) {
      if (this.childElement[i].children[0].classList.contains("checked"))
        this.selectedSliderIndex = i - this.faketop;
      this.childElement[i].children[0].classList.remove("checked");

      this.childElement[i].children[0].style.width = this.windowWidth;
      this.childElement[i].children[0].setAttribute("index", i);
    }
  }

  initTouch() {
    // startSliderPosition -- index of pink div
    // mark initial input checked
    if (this.startSliderPosition == null && this.startFromMiddle) {
      this.setPositionProperty(-Math.ceil(this.slider.maxindex / 2) * this.sliderHeight);
      for (let i = 0; i < this.childElement.length; i++) {
        let p = this.childElement[i].children[1];
        if (p.value == this.slider.maxindex / 2 + this.faketop) {
          p.setAttribute("checked", "checked");
        }
      }
    } else {
      this.setPositionProperty(-Math.ceil(this.startSliderPosition) * this.sliderHeight);
      for (let i = 0; i < this.childElement.length; i++) {
        let p = this.childElement[i].children[1];
        if (p.value == this.startSliderPosition + this.faketop) {
          p.setAttribute("checked", "checked");
        }
      }
    }

    this.slider.touch = {
      start: {x: 0, y: 0},
      end: {x: 0, y: 0}
    };
    // this.binderfun2 = _this.onTouchMove.bind(_this)
    let _this = this;
    // this.slider.parent.bind('touchstart', this.onTouchStart());

    this.binderfun1 = _this.onTouchStart.bind(_this);
    this.binderfun2 = _this.onTouchMove.bind(_this);
    this.binderfun3 = _this.onTouchEnd.bind(_this);

    this.slider.parent.addEventListener("touchstart", _this.binderfun1, {
      passive: false
    });
    this.slider.parent.addEventListener("touchmove", _this.binderfun2, {
      passive: false
    });
    // bind a "touchend" event to the viewport
    this.slider.parent.addEventListener("touchend", _this.binderfun3, {
      passive: false
    });
  }

  onTouchStart(e) {
    {

      // record the original position when touch starts
      this.slider.touch.originalPos = {
        top: this.el.getBoundingClientRect().top,
        left: this.el.getBoundingClientRect().left
      };

      let orig = e;
      // record the starting touch x, y coordinates
      this.slider.touch.start.x = orig.changedTouches[0].pageX;
      this.slider.touch.start.y = orig.changedTouches[0].pageY;
      this.stTime = new Date().getTime();
    }
    e.stopPropagation(e);
    // e.preventDefault();
  }

  onTouchMove(e) {
    // A list of information for every finger involved in the event
    let orig = e;
    let xMovement = Math.abs(
      orig.changedTouches[0].pageX - this.slider.touch.start.x
    );
    let yMovement = Math.abs(
      orig.changedTouches[0].pageY - this.slider.touch.start.y
    );
    let change = orig.changedTouches[0].pageY - this.slider.touch.start.y;
    if (xMovement) xMovement = 1;
    if (yMovement > xMovement && yMovement > 4) {
      change = this.slider.touch.originalPos.top + change - 50;
      this.setPositionProperty(change);
    }

    e.preventDefault();
    // this.slider.parent.bind('touchend', onTouchEnd);
    e.stopPropagation(e);
  }

  setPositionProperty(value) {

    let propValue = "translate3d(0," + value + "px, 0)";
    this.el.style.transitionDuration = 0 + "s";
    this.el.style.transform = propValue;
  }

  onTouchEnd(e) {
    let orig = e;
    let value = 0;
    // record end x, y positions
    this.slider.touch.end.x = orig.changedTouches[0].pageX;
    this.slider.touch.end.y = orig.changedTouches[0].pageY;
    let distance = 0;
    distance = this.slider.touch.end.y - this.slider.touch.start.y;
    let goto;
    /*start : getting position of pinkFilterDiv*/
    let pinkDivStart;
    if (this.category == 'searchSlider') {
      pinkDivStart = e.currentTarget.parentElement.parentElement.children[0].getBoundingClientRect()
        .top + 35;
    } else {
      pinkDivStart = e.currentTarget.parentElement.parentElement.children[0].getBoundingClientRect()
        .top;
    }

    let pinkDivEnd = pinkDivStart + 50;
    let totalLi = e.currentTarget.children.length;
    // on touch slide
    for (let j = 0; j < totalLi; j++) {
      let liSet = e.currentTarget.children[j];
      let topDiv = liSet.getBoundingClientRect().top;
      if (pinkDivStart <= topDiv && topDiv <= pinkDivEnd) {
        goto = liSet.children[0].getAttribute("index") - this.faketop;

        this.gotoSlide(goto);
        break;
      }
    }
    /*End*/
    // on click slide
    if (-1 <= distance && distance <= 1) {
      let clickedDiv = e.target;
      if (e.target.nodeName == "DIV") clickedDiv = e.target;
      goto = clickedDiv.getAttribute("index") - this.faketop;
    }

    // on click slider should not move
    if (this.category != 'searchSlider') {
      this.gotoSlide(goto);
    }
    let noOfItemsShown;
    if (this.category == 'searchSlider') {
      noOfItemsShown = Math.floor((window.innerHeight - 55 - 35) / this.sliderHeight);
    } else {
      noOfItemsShown = Math.floor((window.innerHeight - 55) / this.sliderHeight);
    }
    // slide up and down infinitely
    if (this.category == 'singleSlider' || this.category == 'searchSlider') {
      if (goto == undefined) {
        if (this.slider.touch.end.y > pinkDivStart) {
          this.gotoSlide(0);
        } else {
          this.gotoSlide(this.slider.maxindex - noOfItemsShown + 1);
        }
      }
      if (goto > (this.slider.maxindex - noOfItemsShown)) {
        this.gotoSlide(this.slider.maxindex - noOfItemsShown + 1);
      }
    }

    if (goto == undefined && this.category == 'dobSlider') {
      if (this.slider.touch.end.y > pinkDivStart) {
        this.gotoSlide(0);
      } else {
        this.gotoSlide(this.slider.maxindex);
      }
    }
  }

  gotoSlide(index, notop) {
    if (notop) index = index - this.faketop;
    if (index < 0 || index > this.slider.maxindex) {
      this.slider.index = this.slider.maxindex;
      if (index < 0) index = 0;
      if (index > this.slider.maxindex) index = this.slider.maxindex;
      this.gotoSlide(index);
      return;
    }
    for (let i = 0; i < this.childElement.length; i++) {
      let liObj = this.childElement[i];
      if (
        liObj
          .getElementsByTagName("INPUT")[0]
          .getAttribute("checked") === "checked"
      ) {
        liObj
          .getElementsByTagName("INPUT")[0]
          .removeAttribute("checked");
      }
    }

    if (!isNaN(index)) {
      let transformx = this.sliderHeight * index;
      // let valTransDur = "-" + this.slider.cssPrefix + "-transition-duration";
      // let valTransForm = this.slider.animProp;
      this.el.style.transitionDuration = 0.5 + "s";
      let propValue = "translate3d(0,-" + transformx + "px, 0)";
      this.el.style.transform = propValue;

      this.slider.index = index;
      for (let i = 0; i < this.childElement.length; i++) {
        let p = this.childElement[i].children[1];
        if (p.value == index + this.faketop) {
          p.setAttribute("checked", "checked");
        }
      }

    }
  }

  render() {
  }
}