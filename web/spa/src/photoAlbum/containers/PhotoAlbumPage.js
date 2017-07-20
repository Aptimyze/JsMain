import React from "react";
import axios from "axios";
import {getCookie} from '../../common/components/CookieHelper';
import MyjsSliderBinding from "../../myjs/components/MyjsSliderBinding";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
require ('../style/albumcss.css');



export default class PhotoAlbumPage extends React.Component {

  constructor(props) {
      super();
      this.sliderTupleStyle = {'whiteSpace': 'nowrap','fontSize':'0px','overflowX':'hidden','display': 'table'};
      this.state={
        getRes: null,
        recAlbumlink: false,
        setCont: 0,
        'sliderStyle' :this.sliderTupleStyle,
        tupleWidth : {'width' : window.innerWidth}
      }
      this.CssFix();


  }
  componentDidMount(){

    let _this = this;
    if(getCookie("AUTHCHECKSUM"))
    {
      console.log(this.props.location.search);
      axios.get('/api/v1/social/getAlbum'+ this.props.location.search + '&AUTHCHECKSUM='+ getCookie("AUTHCHECKSUM") )
        .then(function(response){
          _this.setState({
              getRes: response.data,
              recAlbumlink: true
          });

        })
    }
  }

componentDidUpdate(){
if(!this.state.recAlbumlink || this.sliderBound) return;
  this.sliderBound =1;
  let elem = document.getElementById('galleryContainer');
  this.obj = new MyjsSliderBinding(elem,this.state.getRes.albumUrls,this.alterCssStyle.bind(this),1);
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


  _onLoad(e) {
    let imgW_a, imgH_a, adjusted_height, getIDn;
    imgW_a = e.target.offsetWidth;
    imgH_a = e.target.offsetHeight;
    getIDn = e.target.id.split("_");
    adjusted_height = parseInt(window.innerWidth * ( imgH_a/imgW_a ));
    document.getElementById(e.target.id).style.height = adjusted_height;
    document.getElementById("loader_"+getIDn[1]).style.display="none";
    document.getElementById(e.target.id).style.visibility = "visible";
  }
  goBack()
  {
      this.props.history.goBack();
  }


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

      return(

          <div className="posrel">
            <i className="up_sprite puback posabs z1 bckpos" onClick={() => this.goBack()}></i>
            <div className="bg14" id="galleryContainer" style={this.state.sliderStyle} >


            {this.state.getRes.albumUrls.map((urllist, index) => {
              return <div className="dispcell vertmid txtc" style={this.state.tupleWidth} key={urllist.pictureid}>
                  <img id={"loader_"+urllist.pictureid} className="loadrpos" src="https://static.jeevansathi.com/images/jsms/commonImg/loader.gif"/>
                  <img id={"image_"+urllist.pictureid} style={this.state.tupleWidth} src={urllist.url} onLoad={this._onLoad} className="imghid"  />
              </div>;
            })}

            </div>
          </div>


          );

      }
    }




}
