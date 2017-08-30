import React from "react";
import axios from "axios";
import {getCookie} from '../../common/components/CookieHelper';
import MyjsSliderBinding from "../../myjs/components/MyjsSliderBinding";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
require ('../style/albumcss.css');



export default class PhotoAlbumPage extends React.Component {

  constructor(props) {
      super();
      this.sliderTupleStyle = {'whiteSpace': 'nowrap','fontSize':'0px','overflowX':'hidden','display': 'table','height':window.innerHeight+'px'};
      this.state={
        getRes: null,
        recAlbumlink: false,
        setCont: 0,
        'sliderStyle' :this.sliderTupleStyle,
        tupleWidth : {'width' : window.innerWidth},
        intialACount: 1
      }
      this.CssFix();


  }
  componentDidMount(){

    console.log('albuk');
    let newPchksum, _this = this;
    //console.log(_this.props.location.search.replace('profilechecksum','profileChecksum').substr(1));
     let str = _this.props.location.search.replace('profilechecksum','profileChecksum');
     if(str.indexOf("&")>-1)
     {
       let b = str.split("&");
       newPchksum = b[0]
     }
     else {
       newPchksum = "&"+_this.props.location.search.replace('profilechecksum','profileChecksum').substr(1);
     }
     commonApiCall(CONSTANTS.PHOTALBUM_API,newPchksum,'','POST').then(function(response){
          console.log('albumdata', response);
          _this.setState({
                      getRes: response,
                      recAlbumlink: true
                  });
          console.log(response);
       });

  }

componentDidUpdate(){
if(!this.state.recAlbumlink || this.sliderBound) return;
  this.sliderBound =1;
  let elem = document.getElementById('galleryContainer');
  //onstructor(parent,tupleObject,styleFunction,notMyjs,indexElevate,nextPageHit,pagesrc)
  this.obj = new MyjsSliderBinding(elem,this.state.getRes.albumUrls,{nxtSlideFun:this.incrCount.bind(this),prvSlideFun:this.decrCount.bind(this),styleFunction:this.alterCssStyle.bind(this)},1,'','',"Palbum");
  this.obj.initTouch();



}
  alterCssStyle(duration, transform){
      this.setState((prevState)=>{
        var styleArr = Object.assign({}, prevState.sliderStyle);
        styleArr[this.cssProps.cssPrefix + 'TransitionDuration'] = duration + 'ms';
        var propValue = 'translate3d(' + transform + 'px, 0, 0)';
        styleArr[this.cssProps.animProp] =  propValue;
        prevState.sliderStyle =styleArr;
        return prevState;
      });
    }


  incrCount(){
    let count = this.state.intialACount;
    if(count>=this.state.getRes.albumUrls.length)return;
    ++count;
    this.setState({intialACount:count});
  }
  decrCount(){
    let count = this.state.intialACount;
    if(count<=1)return;
    --count;
    this.setState({intialACount:count});
  }

    CssFix(){
  			// create our test div element
  			var div = document.createElement('div');
  			// css transition properties
  			var props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
  			// test for each property
  			for (var i in props) {
  					if (div.style[props[i]] !== undefined) {
  							var cssPrefix = props[i].replace('Perspective', '');
  							this.cssProps = {
  									cssPrefix : cssPrefix,
  									animProp : cssPrefix + 'Transform'
  								}
  			}
  		};
  	}


  _onLoad(e) {console.log(e.target.id);
    let imgW_a, imgH_a, adjusted_height, getIDn;
    imgW_a = e.target.offsetWidth;
    imgH_a = e.target.offsetHeight;
    getIDn = e.target.id.split("_");
    adjusted_height = parseInt(window.innerWidth * ( imgH_a/imgW_a ));
    document.getElementById(e.target.id).style.height = adjusted_height;
    document.getElementById("albumLoader_"+getIDn[1]).style.display="none";
    document.getElementById(e.target.id).style.visibility = "visible";
  }
  goBack()
  {
      this.props.history.goBack();
  }
  // changeAlbumCount(total){
  //   console.log("======");
  //
  //
  //   console.log('count chage');
  //   console.log(total);
  //   console.log(window.innerWidth);
  //   let totalAlb_W = total * window.innerWidth;
  //   console.log(totalAlb_W);
  //
  //   let test = document.getElementById("galleryContainer").style.transform;
  //
  //   console.log(test);
  //
  //   let regex = /translate3d\(\s*([^ ,]+)\s*,\s*([^ ,]+)\s*,\s*([^ )]+)\s*\)/;
  //
  //   var result = test.split(regex);
  //
  //   console.log(result)
  //
  //
  //   console.log("======");
  // }


  render() {

    if(!this.state.recAlbumlink){
      return(<div className="noData album"></div>)
    }
    else
    {

      let setcell={
        width: window.innerWidth
      }
      let setouter={
        width : window.innerWidth*this.state.getRes.albumUrls.length,
        height: window.innerHeight,
        display: "table"
      }
      //console.log('render');
      //this.changeAlbumCount(this.state.getRes.albumUrls.length);


      return(



          <div className="posrel">
            <i className="up_sprite puback posabs z1 bckpos" onClick={() => this.goBack()}></i>

            <div className="posabs z1 bckpos1 fontlig f18 white">
              {this.state.intialACount}/{this.state.getRes.albumUrls.length}
            </div>


            <div className="bg14" id="galleryContainer" style={this.state.sliderStyle} >


            {this.state.getRes.albumUrls.map((urllist, index) => {
              return <div  className="dispcell vertmid txtc" style={this.state.tupleWidth} key={index}>
                  <img id={"albumLoader_"+index} className="loadrpos" src="https://static.jeevansathi.com/images/jsms/commonImg/loader.gif"/>
                  <img id={"albumImage_"+index} style={this.state.tupleWidth} src={urllist} onLoad={this._onLoad} className="imghid"  />
              </div>;
            })}

            </div>
          </div>


          );

      }
    }




}
