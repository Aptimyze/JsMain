import React from "react";


export class ShowNotificationLayer extends React.Component {
  render(){
    return(
      // <div className="bg4" >
      //
      //   <a href="/search/perform?justJoinedMatches=1">
      //     <div className="fullwid fontthin f14 color3 pad18 brdr1 clearfix">
      //       <div className="fl wid92p">
      //         <div className="fullwid txtc">Just Joined Matches</div>
      //       </div>
      //       <div className="fr wid8p">
      //         <div className="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">27</div>
      //       </div>
      //     </div>
      //   </a>

      <div>

        <ul className="shownotiful bg4" id="notificationBellView">
          <li>
            <div className="fullwid clearfix posrel txtc">

            </div>
          </li>

          <li>

          </li>

        </ul>

      </div>






//</div>
    )
  }


}

export default class MyjsHeadHTML extends React.Component{

  setNotificationView(){


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
                            <i className="mainsp bellicon"></i>
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
          <ShowNotificationLayer/>
        </div>
      )
    }


}
