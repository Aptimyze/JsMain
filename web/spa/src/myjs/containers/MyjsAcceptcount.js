import React from "react";

export default class AcceptCount extends React.Component {
  render(){
    return(
      <div className="bg4 pad1" id="acceptanceCountSection">
        <div className="fullwid pad2">

          <a href="/inbox/2/1">
            <div className="fl wid49p txtc">
              <div className="row bg7 wid75 hgt75 brdr50p posrel" id="acceptedMe">
                <div className="cell vmid white fullwid myjs_f30 fontlig">2</div>
              </div>
              <div className="f12 fontlig color7 pt10">
                <p>All</p>
                <p> Acceptances</p>
              </div>
            </div>
          </a>

          <a href="/search/perform?justJoinedMatches=1">
            <div className="fl wid49p txtc">
              <div className="row bg7 wid75 hgt75 brdr50p posrel" id="iAccepted">
                <div className="cell vmid white myjs_f30 fontlig">48</div>
                <div className="posabs pos3">
                  <div className="bg10 txtc wid20 hgt20 brdr50p">
                    <div className="white f12 fontlig pt1">32</div>
                  </div>
                </div>
              </div>
              <div className="f12 fontlig color7 pt10">
                <p>Just</p>
                <p>Joined Matches</p>
              </div>
            </div>
          </a>

          <div className="clr"></div>

        </div>
      </div>
    )
  }
}
