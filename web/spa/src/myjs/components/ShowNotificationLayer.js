import React from "react";




export default class ShowNotificationLayer extends React.Component {
  constructor(props) {
      super(props);
      // this.state={
      //   statusupdate: false
      // }
    }
    // componentWillReceiveProps(nextProps)
    // {
    //   console.log("next",nextProps);
    //   this.setState({
    //     statusupdate: true
    //   })
    // }
    returnBlankIfZero(value){
      if(this.props.layerCount.bellResponse[value]==0) return '';
      return(
        <div className="fr wid8p">
          <div className="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">{this.props.layerCount.bellResponse[value]}</div>
        </div>
      )


    }
  render(){
  	// if(!this.props.layerCount.fetched){
  	// 	return <div></div>;
	  //  }
     return(
        <div className = "bg4 dispnone" id = "notificationBellView">
          <a href="/search/perform?justJoinedMatches=1">
            <div className="fullwid fontthin f14 color3 pad18 brdr1 clearfix">
              <div className="fl wid92p">
                <div className="fullwid txtc">Just Joined Matches</div>
              </div>
              // {this.returnBlankIfZero('NEW_MATCHES')}
            </div>
          </a>
          <a href = "/inbox/4/1" >
            <div className="fullwid fontthin f14 color3 pad18 brdr1 clearfix">
              <div className="fl wid92p">
                <div className="fullwid txtc">Messages</div>
              </div>
                {this.returnBlankIfZero('MESSAGE_NEW')}
            </div>
           </a>
          <a href="/inbox / 9 / 1 ">
            <div className=" fullwid fontthin f14 color3 pad18 brdr1 clearfix ">
              <div className = "fl wid92p" >
                <div className="fullwid txtc">Photo Requests</div>
              </div>
              {this.returnBlankIfZero('PHOTO_REQUEST_NEW')}
            </div>
          </a>
          <a href="/inbox / 1 / 1 ">
            <div className = "fullwid fontthin f14 color3 pad18 brdr1 clearfix" >
              <div className="fl wid92p">
                <div className="fullwid txtc">Interests Received</div>
              </div>
              {this.returnBlankIfZero('AWAITING_RESPONSE_NEW')}
            </div>
          </a>
          <a href="/inbox/2/1">
            <div className="fullwid fontthin f14 color3 pad18 brdr1 clearfix">
              <div className="fl wid92p">
                <div className="fullwid txtc">Members who Accepted me</div>
              </div>
              {this.returnBlankIfZero('ACC_ME_NEW')}
            </div>
          </a>
          <a href = "/inbox/10/1" >
            <div className="fullwid fontthin f14 color3 pad18 brdr1 clearfix">
              <div className="fl wid92p">
                <div className="fullwid txtc">Declined/Cancelled</div>
              </div>
              {this.returnBlankIfZero('DEC_ME_NEW')}
            </div>
          </a>
          <a href="/inbox / 12 / 1 ">
            <div className = "fullwid fontthin f14 color3 pad18 brdr1 clearfix" >
              <div className="fl wid92p">
                <div className="fullwid txtc">Filtered Interests</div>
              </div>
              {this.returnBlankIfZero('FILTERED_NEW')}
            </div>
          </a>
        </div>
    )
  }


}
