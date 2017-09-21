import React from "react";

export default class MyjsOcbLayer extends React.Component {
  constructor(){
    super();
    this.state={
      ocExpiryMnts:'00',
      ocbcalExpirySec:'00'
    }

  }


  componentDidMount(){


    this.showTimerForLightningCal();


  }
  getIosVersion(ua) {
	//return false;
	var ua = ua || navigator.userAgent;
	var match= ua.match(/(iPhone);/i);
	//console.log(match);
	var OsVersion=ua.match(/OS\s[0-9.]*/i);
	//console.log(OsVersion);
	if(match==null)
		return false;
	else if(OsVersion==null)
	{
		return false
	}
	else if(OsVersion[0].substring(3,5)>=7)
		return true;
	else
		return false;

}

  showTimerForLightningCal(param) {

    let cT,eT;

    if(this.getIosVersion())
    {
      cT = new Date(this.props.ocb_currentT.replace(/\s+/g, 'T'));
      eT = new Date(this.props.Ocb_data.expiryDate.replace(/\s+/g, 'T'));
    }
    else
    {
      cT = new Date(this.props.ocb_currentT);
      eT = new Date(this.props.Ocb_data.expiryDate);
    }



    let lightningDealExpiryInSec = Math.floor((eT-cT)/1000);

    if(!lightningDealExpiryInSec)
        return;
    let currentTime=new Date();
    let expiryDate=new Date();
    expiryDate.setSeconds(expiryDate.getSeconds() + parseInt(lightningDealExpiryInSec));

    if(expiryDate<currentTime) return;
    let timeDiffInSeconds=(expiryDate-currentTime)/1000;
    if (timeDiffInSeconds>48*60*60) return;


    let temp=timeDiffInSeconds;
    let timerSeconds=temp%60;
    temp=Math.floor(temp/60);
    let timerMinutes=temp%60;
    temp=Math.floor(temp/60);
    let timerHrs=temp;
    this.memTimerExtraDays=Math.floor(timerHrs/24);
    this.memTimerTime=new Date();
    this.memTimerTime.setHours(timerHrs);
    this.memTimerTime.setMinutes(timerMinutes);
    this.memTimerTime.setSeconds(timerSeconds);
    let thisObject= this;
    this.memTimer=setInterval(this.updateMemTimer.bind(thisObject),1000);
  }

  updateMemTimer()
  {
      let h = this.memTimerTime.getHours();
      let s = this.memTimerTime.getSeconds();
      let m = this.memTimerTime.getMinutes();
      if (!m && !s && !h) {
        if(!this.memTimerExtraDays) clearInterval(this.memTimer);
        else this.memTimerExtraDays--;
      }

      this.memTimerTime.setSeconds(s-1);
      h=h+this.memTimerExtraDays*24;

      m = this.formatTime(m);
      s = this.formatTime(s);
      h = this.formatTime(h);

      this.setState({
        ocExpiryMnts:m,
        ocbcalExpirySec:s
      })

  }

  formatTime(i) {
      if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
      return i;
  }

  
  getIosVersion(ua)
 {
  //return false;
  var ua = ua || navigator.userAgent;
  var match= ua.match(/(iPhone);/i);
  //console.log(match);
  var OsVersion=ua.match(/OS\s[0-9.]*/i);
  //console.log(OsVersion);
  if(match==null)
  return false;
  else if(OsVersion==null)
  {
  return false
  }
  else if(OsVersion[0].substring(3,5)>=7)
  return true;
  else
  return false;

}
  
  render(){


    let ocbview;
      if(this.props.Ocb_data.top == "FLASH DEAL")
      {
        ocbview= <div className="posrel fullwid padl30">
                    <div className="posabs white myjsc6 flsh_pos1">
                      <p className="f14 pl38 pb4">Valid for</p>
                        <ul id="rcbflshTimer" className="time">
                          <li className="inscol"><span id="calExpiryMnts" className="f16">{this.state.ocExpiryMnts}</span><span className="f11">M</span></li>
                          <li className="padl5"><span id="calExpirySec" className="f16">{this.state.ocbcalExpirySec}</span><span className="f11">S</span></li>
                        </ul>
                        <div className="txtr pt5">
                          <i className="mainsp flsh_bg1"></i>
                        </div>
                    </div>
                    <div className="clearfix" >
                      <div className="fl fontlig wid88p">
                        <div className="f24 white">{this.props.Ocb_data.top}</div>
                        <div className="f14 white">
                            <div dangerouslySetInnerHTML={{__html: this.props.Ocb_data.extra}} />
                        </div>
                        <div className="f14 white">
                          <div dangerouslySetInnerHTML={{__html: this.props.Ocb_data.bottom}} />
                        </div>
                      </div>
                    </div>
                  </div>
      }
      else {
        ocbview= <div className="posrel fullwid">
  				<div className="clearfix myjsp2">
  					<div className="fl fontlig wid88p">
  						<div className="f24 white">
                  <div dangerouslySetInnerHTML={{__html:this.props.Ocb_data.top}} />
              </div>
              <div className="f14 white">
                          <div dangerouslySetInnerHTML={{__html: this.props.Ocb_data.extra}} />
              </div>
  						<div className="f14 white">
                  <div dangerouslySetInnerHTML={{__html:this.props.Ocb_data.bottom}} />
              </div>
  					</div>
  					<div className="fr wid10p pt16">
  						<i className="mainsp myjsdim2"></i>
  					</div>
  				</div>
  			</div>
      }
      return(
        <a href={this.props.Ocb_data.membership_message_link}>
          <div className="posrel pt20 pb20 newBgBand">

              {ocbview}

          </div>
        </a>
      )

  }


}
