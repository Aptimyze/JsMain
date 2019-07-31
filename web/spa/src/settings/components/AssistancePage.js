import React from "react";
import HamMain from "../../Hamburger/containers/HamMain";
import Loader from "../../common/components/Loader";
import {connect} from "react-redux";
require('../../Hamburger/style/ham.css');

export class AssistancePage extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      showLoader: true
    }

  }

	componentDidUpdate(prevprops) {
    // console.log("Inside componentDidUpdate");
		if (
			prevprops.location.search.indexOf("ham=1") != -1 &&
			window.location.search.indexOf("ham=1") == -1
		) {
			this.isHamOpen = false;
			this.refs.Hamchild.getWrappedInstance().hideHam();
		}
	}

	//----start:header HTML
  headerViewSS() {
    let headHTML;
    headHTML = <div className="fullwid bg1 pad1 z80">
      <div className="rem_pad1 clearfix">
        <div className="posrel">
          <div className="posabs SS_pos2" id="hamburgerIcon">
            <i onClick={() => this.showHam()} id="hamIcon" className="dispbl mainsp baricon"></i>
          </div>
          <div className="txtc color5  fontthin f19">
            Assistance
          </div>
        </div>
      </div>
    </div>;

    return headHTML;

  }
 showHam() {
    if (window.location.search.indexOf("ham=1") == -1) {
      if (window.location.search.indexOf("?") == -1) {
        this.props.history.push(window.location.pathname + "?ham=1");
      } else {
        this.props.history.push(
          window.location.pathname + window.location.search + "&ham=1"
        );
      }
    }
    this.isHamOpen = true;
    this.refs.Hamchild.getWrappedInstance().openHam();
  }

render() {
	 let  ham = '', innertxt='';

   innertxt = <div> <div><ul>
             <li className='padl15 pr10 pt22 brdr15 f14  fontreg  hgt64'><a href="/help/index"><div className='color3 fl wid92p'>Help</div><i className='rightArrIcon'></i></a></li>
             {/*<li className='pl10 pr10 color7 hgt45 f16 fontreg txtc'><a href="/static/page/privacypolicy"><div>Terms & Conditions</div><i className=''></i></a></li>*/}
             <li className='padl15 pr10 pt22 hgt64 brdr15 color3 f14 fontreg '><a href="/static/page/privacypolicy"><div className='color3 fl wid92p'>Post your Query </div><i className='rightArrIcon'></i></a></li>
            </ul></div> 
            <div className="posrel pad2515 reqAssis">
                        <div className="txtc pad24 color13 f14 colorBrdAssis pt40">Call toll free </div>
                        <div className="txtc pad24 f16 color7 pt5">1-800-419-6299 </div>
                        <div className="txtc pad24 color13 f13 pt5 colorBrdAssisBtm">Daily in between 9AM - 9PM (IST)</div>
                    </div>
                <div className="txtc pad24 color7 f14 pt20 reqCall">Request Callback</div>
                    </div>

    if (this.props.myjsData.apiDataHam != undefined)
      ham =
        <HamMain bellResponse={this.props.myjsData.apiDataHam.hamburgerDetails} ref="Hamchild" page="others"></HamMain>;
		else
      ham = <HamMain ref="Hamchild" page="logout"></HamMain>;
 
    return (
			<div id="AssistancePage">
        <div id="mainContent" style={{"backgroundColor": "white"}}>
          {ham}
          {this.headerViewSS()}
          {innertxt}
				</div>
			</div>

		)
 }
}

