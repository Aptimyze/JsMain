import React from "react";
import HamMain from "../../Hamburger/containers/HamMain";
import Loader from "../../common/components/Loader";
import {connect} from "react-redux";
import SettingsPage from "./SettingsPage";
require('../../Hamburger/style/ham.css');

 class AboutUsPage extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      showLoader: true
    }

  }

	componentDidUpdate(prevprops) {

	}

	//----start:header HTML
  headerViewSS() {
    let headHTML;
    headHTML = <div className="fullwid bg1 pad1 z80">
      <div className="rem_pad1 clearfix">
        <div className="posrel">
          <div className="posabs ss_hamPos" id="hamburgerIcon">
            <i onClick={() => this.backToHam()} id="backTohamIcon" className="mainsp posabs set_arrowLeft set_pos3"></i>
          </div>
          <div className="txtc color5  fontthin f19">
           About Us
          </div>
        </div>
      </div>
    </div>;

    return headHTML;

  }

  backToHam() {
    let locaToRedirect = localStorage.getItem('CURRENTURLHAM');
    if(locaToRedirect){
      window.location = locaToRedirect;
    }
  }

render() {
	 let  ham = '', innerContent='';
   innerContent = <div><div className='titleTxt fullwid txtc f13 fontreg pt26 pb20  '>INFO EDGE INDIA LTD</div><div className='innerContentTxt lh18Ham txtc pl10 pr10 f13 fontreg ml15 mrHam15'>Info Edge is india’s premier on-line classifieds company in recruitment, matrimony, real-estate and education. The company has a bouquet of websited and associated business in each of these segments</div>
     <div className='fullwid pt15 pb15'><img className='fullwid' src={'/images/jsms/commonImg/logosImg.jpeg'} /></div>
             <div>
               <ul>
              <li className='hamPadl15 nhm_hor_listHam pr10 pt22  f14  fontreg  hgt64'>
                <a  id="privacyPolicyLink" href="/static/page/privacypolicy">
                  <div className='color3 fl wid92p'>Privacy Policy</div>
                  <i className='rightArrIcon'></i></a></li>
               <li className='hamPadl15 nhm_hor_listHam pr10 pt22  f14  fontreg  hgt64'>
                 <a  id="termsLink" href="/static/page/disclaimer">
                   <div className='color3 fl wid92p'>Terms & Conditions</div>
                   <i className='rightArrIcon'></i></a></li>
               <li className='hamPadl15 nhm_hor_listHam pr10 pt22  f14  fontreg  hgt64'>
                 <a id="fraudLink" href="/static/page/fraudalert">
                   <div className='color3 fl wid92p'>Fraud Alert</div>
                   <i className='rightArrIcon'></i></a></li>
            </ul></div></div>

 
    return (
			<div id="AboutUsPage" style={{"backgroundColor": "white",'height':'100vh'}}>
        {/*<div id="mainContent" style={{"backgroundColor": "white"}}>*/}
        <div id="mainContent" >
          {/*{ham}*/}
          {this.headerViewSS()}
          {innerContent}
				</div>
			</div>

		)
 }
}
export default AboutUsPage;
