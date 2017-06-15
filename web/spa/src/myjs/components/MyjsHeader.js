import React from "react";
import ShowNotificationLayer from "./ShowNotificationLayer";
require ('../../common/style/common.css');


export class ShowCount extends React.Component{
    constructor(props){
      super();
    }
    render() {
      //if()console.
      if(!this.props.param)  return (<div></div>);
      else if(this.props.param.TOTAL_NEW==0) return (<div></div>);
      return(
          <div className="posabs myjstop1">
            <div className="disptbl oval">
              <div className="dispcell vertmid color6 f11 txtc">{this.props.param.TOTAL_NEW}</div>
            </div>
          </div>
        )
    }
}

export default class MyjsHeadHTML extends React.Component
{

  constructor(props)
  {
    super(props);
  }
  setNotificationView()
   {
     let currentView  = document.getElementById('notificationBellView');
     currentView.classList.toggle('dispnone');

     console.log(currentView.className.indexOf('dispnone'));

     let element = document.getElementById('darkSection');
     element.classList.toggle('tapoverlay');

     if(currentView.className.indexOf('dispnone')===-1)
     {
       document.getElementById("mainContent").style.height = window.innerHeight+"px";
       document.getElementById("mainContent").style.overflow = "hidden";
     }
     else
     {
       document.getElementById("mainContent").style.overflow = "auto";
     }
   }



  render(){
      return(
          <div className="posrel">
            <div className="fullwid bg1 pad1">
                <div className="rem_pad1 clearfix">
                    <div className="fl wid20p">
                      <div id="hamburgerIcon">
                        <i className="loaderSmallIcon dn"></i>
                        <i id="hamburgerIcon" className="dispbl mainsp baricon"></i>
                      </div>
                    </div>
                    <div id="myJsHeadingId" className="fl wid60p txtc color5  fontthin f19">Home</div>
                    <div className="fr">
                      <div className="fullwid">
                        <div className="fl padr15 posrel">
                            <div id="notificationView" className="posrel" onClick ={this.setNotificationView.bind(this)}>
                              <i className="mainsp bellicon" ></i>
                              <ShowCount param={this.props.bellResponse} />
                            </div>
                        </div>
                        <a id="calltopSearch" href="/search/topSearchBand?isMobile=Y&amp;stime=1496993783592">
                          <div className="fl">
                            <i className="mainsp srchicon"></i>
                          </div>
                        </a>
                      </div>
                    </div>
                </div>
            </div>
            <ShowNotificationLayer layerCount={this.props}  />
            <div id="darkSection" className="posabs"></div>

        </div>

      )
    }


}
