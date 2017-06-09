import React from "react";

export default class MyjsHeadHTML extends React.Component{

    render(){
      return(
        <div className="pad1">
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
                      <a href="#" id="notificationView">
                        <i className="mainsp bellicon"></i>
                        <div className="posabs pos1 myjstop1">
                          <div className="posrel"></div>
                        </div>
                      </a>
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
      )
    }


}
