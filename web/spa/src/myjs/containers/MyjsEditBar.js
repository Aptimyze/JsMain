import React from "react";


export default class EditBar extends React.Component {
  constructor(props) {
        console.log('EditBar');
        console.log(props);
        super();
  }
  render(){
    if(!this.props.fetched) {
      return <div></div>;
    }
    return(
      <div className="pad1 preload myjsedit1" id="profileDetailSection">
        <div className="row editBarStyle">


        <div className="cell brdr6 editBarStyle1">

          <div className="fullwid pad12" id="jsmsProfilePic">
            <div className="posrel fl">
              <div className="hold hold1">
                <div className="pie pie1 editBarStyle2"></div>
              </div>
              <div className="hold hold2">
                <div className="pie pie2 editBarStyle3"></div>
              </div>
              <div className="bg"> </div>
              <img className="image" />
            </div>
            <div className="fl  color7 fontlig padl10 pt16" id="percent">87%</div>
            <div className="clr"></div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
            <a href="/profile/viewprofile.php?ownview=1#Family">
              <div className="editBarStyle5">
                <i className="mainsp myjs_family"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Family</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
            <a href="/profile/viewprofile.php?ownview=1#Details">
              <div className="editBarStyle5">
                  <i className="mainsp basicdetail"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Basic</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Lifestyle">
              <div className="editBarStyle5">
                  <i className="mainsp lifestyle_2"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Lifestyle</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Album">
                <div className="editBarStyle5">
                  <i className="mainsp camera"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Photos</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Education">
                <div className="editBarStyle5">
                  <i className="mainsp myjs_edu"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Education</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Career">
                <div className="editBarStyle5">
                  <i className="mainsp myjs_career"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Career</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Kundli">
              <div className="editBarStyle5">
                  <i className="mainsp myjs_kundli"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Kundli</div>
          </div>
        </div>

        <div className="cell brdr6 vtop pad13 editBarStyle4">
          <div className="txtc ">
              <a href="/profile/viewprofile.php?ownview=1#Dpp">
                <div className="editBarStyle5">
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