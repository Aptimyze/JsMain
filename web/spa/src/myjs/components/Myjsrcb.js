import React from "react";

export default class RcbLayer extends React.Component {
  constructor() {
    super();
  }

  render(){

    if(this.props.responseMessage.top==="FLASH DEAL"){


    return(
        <div className="posrel pad3 newBgBand clearfix">
          <div className="posabs white myjsc6">
            <p className="f14 pl38 pb4">Valid for</p>
              <ul className="time">
                <li className="inscol"><span id="myjsM" className="f16"></span><span className="f11">M</span></li>
                <li className="padl5"><span id="mysjsS" className="f16"></span><span className="f11">S</span></li>
              </ul>
              <div className="txtr pt5">
                <i className="mainsp myjsc5"></i>
              </div>
          </div>

          <div className="fontlig white">
            <div className="f24">{this.props.responseMessage.top}</div>
            <div className="f14"><div dangerouslySetInnerHTML={{__html: this.props.responseMessage.extra}} /></div>
            <div className="f14"><div dangerouslySetInnerHTML={{__html: this.props.responseMessage.bottom}} /></div>
          </div>
        </div>
      )
    }
    return(
      <a href="/profile/viewprofile.php?ownview=1#Family">
        <div className="posrel pt20 pb20 newBgBand">
          <div className="posrel fullwid">
            <div className="clearfix myjsc2">
              <div className="fl fontlig wid88p">
                <div className="f24 white">Add About Family</div>
                  <div className="f14 white">Get more interests &amp; responses</div>
                </div>
                <div className="fr wid10p">
                  <div className="myjsc4">
                    <i className="mainsp myjsc3"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
    )
  }
}
