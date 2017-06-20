import React from "react";

var slides1={
  "whiteSpace": "nowrap",
  "marginLeft": "10px",
  "fontSize": "0px",
  "overflowX": "hidden",
  "width": "5783.2px",
  "transitionDuration": "0.5s",
  "transform": "translate3d(0px, 0px, 0px)"
}

var slides2={
  "width": "329.6px"
}

var slides3={
  "width": "48%"
}

export default class MyjsSlider extends React.Component {
  constructor(props) {
    console.log('Interest Recieved');
    super(props);
  }

  loadPD(){
         // this.props.showPD(true);
  }

  render(){
    if(!this.props.listing.tuples) {
      return <div></div>;
    }
    else{
    return(
      <div>
        <div id="matchalertPresent" className="setWidth sliderc1">
            <div className="pad1">

              <div className="fullwid pb10 clearfix">
                  <div className="fl color7">
                    <span className="f17 fontlig">{this.props.title}</span>&nbsp;<span id="matchAlert_count" className="opa50 f14">{this.props.listing.new_count}</span>
                  </div>
                  <div className="fr pt5">
                    <a href="/inbox/7/1" className="f14 color7 opa50 icons1 myjs_arow1">View all </a>
                  </div>
              </div>
              <div className="swrapper" id="swrapper">
                <div className="wrap-box" id="wrapbox">
                  <div id="match_alert_tuples" style={slides1}>

                      <div className="mr10 dispibl ml0 posrel wid300" style={slides2}>
                        <input className="proChecksum" type="hidden"/>
                        <img className="srp_box2 contactLoader posabs dispnone top65" src="https://static.jeevansathi.com/images/jsms/commonImg/loader.gif"/>

                        <div className="bg4 overXHidden" id="hideOnAction">
                            <div id="detailedProfileRedirect" onClick={()=>this.loadPD()}>
                            // "/profile/viewprofile.php?profilechecksum=35bdf5156711174170753bd3b9aeaaffi16331617&amp;responseTracking=undefined&amp;total_rec=16&amp;actual_offset=1&amp;contact_id=13171207_MATCH_ALERT">
                              <div className="pad16 scrollhid hgt140">
                              <div className="overXHidden fullheight">
                                <div className="whitewid200p overflowWrap">
                                  <div className="fl">
                                    <img className="tuple_image hgtwid110" src="https://mediacdn.jeevansathi.com/5337/3/106743387-1491306352.jpeg"/>
                                  </div>
                                  <div className="fl pl_a" style={slides3}>
                                    <div className="f14 color7">
                                      <div className="username textTru"></div>
                                    </div>
                                    <div className="attr">
                                      <ul>
                                        <li className="textTru">
                                          <span className="tuple_title">Others</span>
                                        </li>
                                        <li className="textTru">
                                          <span className="tuple_age">33</span> Years  <span className="tuple_height"> 5  </span>
                                        </li>
                                        <li className="textTru">
                                          <span className="tuple_caste whtSpaceNo">Hindu: Brahmin Kanyakubj</span>
                                        </li>
                                        <li className="textTru">
                                          <span className="tuple_mtongue">Hindi-MP</span>
                                        </li>
                                        <li className="textTru">
                                          <span className="tuple_education">M.Sc, B.Sc</span>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                  <div className="clr"></div>
                                </div>
                              </div>
                            </div>


                            </div>

                        </div>



                      </div>

                  </div>
                </div>
              </div>





            </div>
        </div>
      </div>
    )
  }
  }
}
