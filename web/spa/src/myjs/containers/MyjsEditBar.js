import React from "react";

let divStyle={
  "width":"250%"
}
let divStyle1={
    "width":"16%"
}
let divStyle2={
  "transform": "rotate(-133.2deg)"
}
let divStyle3={
  "transform": "rotate(-180deg)"
}
let divStyle4={
    "width":"10.75%"
}
let divStyle5={
  "height":"35px"
}

export default class EditBar extends React.Component {



  render(){
    return(
      <div className="pad1 preload myjsedit1" id="profileDetailSection">
        <div className="row"  style={divStyle} >


        <div className="cell brdr6" style={divStyle1}>

          <div className="fullwid pad12" id="jsmsProfilePic">
            <div className="posrel fl">
              <div className="hold hold1">
                <div className="pie pie1" style={divStyle2}></div>
              </div>
              <div className="hold hold2">
                <div className="pie pie2" style={divStyle3}></div>
              </div>
              <div className="bg"> </div>
              <img className="image" src="https://mediacdn.jeevansathi.com/4843/9/96869923-1486570212.jpeg" />
            </div>
            <div className="fl  color7 fontlig padl10 pt16" id="percent">87%</div>
            <div className="clr"></div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
            <a href="/profile/viewprofile.php?ownview=1#Family">
              <div style={divStyle5}>
                <i className="mainsp myjs_family"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Family</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
            <a href="/profile/viewprofile.php?ownview=1#Details">
              <div style={divStyle5}>
                  <i className="mainsp basicdetail"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Basic</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Lifestyle">
              <div style={divStyle5}>
                  <i className="mainsp lifestyle_2"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Lifestyle</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Album">
              <div style={divStyle5}>
                  <i className="mainsp camera"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Photos</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Education">
              <div style={divStyle5}>
                  <i className="mainsp myjs_edu"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Education</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Career">
              <div style={divStyle5}>
                  <i className="mainsp myjs_career"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Career</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Kundli">
              <div style={divStyle5}>
                  <i className="mainsp myjs_kundli"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Kundli</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13" style={divStyle4}>
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Dpp">
              <div style={divStyle5}>
                  <i className="mainsp dppHeart"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Desired Partner</div>
          </div>
        </div>






        </div>
      </div>
    );
  }
}
