import React from "react";
import ShowNotificationLayer from "../components/ShowNotificationLayer";
require ('../../common/style/common.css');


export default class MyjsHeadHTML extends React.Component
{
  toggleBellView()
 {
 let currentView  = document.getElementById('notificationBellView').style.display;
 let newView = currentView=='block' ? 'none' : 'block';
 document.getElementById('notificationBellView').style.display=newView;

}
constructor(props) {
    super();


  }

  setNotificationView(){


  }

  printProp(){
    console.log('printporp');
    console.log(this.props)
  }
  render(){
      return(
        <div>
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
                          <div id="notificationView" className="posrel" onClick={this.setNotificationView}>
                            <i className="mainsp bellicon" onClick ={this.toggleBellView.bind(this)}></i>
                            <div className="posabs myjstop1">
                              <div className="disptbl oval">
                                <div className="dispcell vertmid color6 f11 txtc">33</div>
                              </div>
                            </div>
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
          <button onClick={this.printProp.bind(this)}>print props</button>
          <ShowNotificationLayer bellResponseFinal={this.props.bellResponse}/>

        </div>
      )
    }


}
