import React from "react";


export default class ShowNotificationLayer extends React.Component {
  constructor(props) {
      super();
    }
    printProp(){
      console.log('printporp');
      console.log(JSON.parse(JSON.stringify(this.props)));
    }

    returnBlankIfZero(value){
      if(!value)return '';
      return (<div className="fr wid8p">
                      <div className="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">{value}</div>
                 </div>)
                 ;

    }
  render(){
  	if(!this.props.fetched){
  		return <div></div>;
	} 
  return(
                <div className = "bg4" id = "notificationBellView" > <a href="/search/perform?justJoinedMatches=1">
                  <div className="fullwid fontthin f14 color3 pad18 brdr1">
                    <div className="fl wid92p">
                      <div className="fullwid txtc">Just Joined Matches</div>
                    </div>
                    {this.returnBlankIfZero(this.props.NEW_MATCHES)}
                    <div className="clr"></div>
                  </div>
                </a>
                <a href = "/inbox/4/1" >
                  <div className="fullwid fontthin f14 color3 pad18 brdr1">
                    <div className="fl wid92p">
                      <div className="fullwid txtc">Messages</div>
                    </div>
                    {this.returnBlankIfZero(this.props.MESSAGE_NEW)}

                  <div className="clr"></div>
                  </div>
               </a>
                      <a href="/inbox / 9 / 1 ">	<div className=" fullwid fontthin f14 color3 pad18 brdr1 ">
                 <div className = "fl wid92p" > <div className="fullwid txtc">Photo Requests</div> < /div>
                 {this.returnBlankIfZero(this.props.PHOTO_REQUEST_NEW)}

                      <div className="clr"></div > </div> < /a>

                      <a href="/inbox / 1 / 1 ">
                 <div className = "fullwid fontthin f14 color3 pad18 brdr1" > <div className="fl wid92p">
                  <div className="fullwid txtc">Interests Received</div>
                </div> <div className = "clr" > </div> < /div>
                      </a > <a href="/inbox/2/1">
                  <div className="fullwid fontthin f14 color3 pad18 brdr1">
                    <div className="fl wid92p">
                      <div className="fullwid txtc">Members who Accepted me</div>
                    </div>
                    <div className="clr"></div>
                  </div>
                </a> < a href = "/inbox/10/1" > <div className="fullwid fontthin f14 color3 pad18 brdr1">
                  <div className="fl wid92p">
                    <div className="fullwid txtc">Declined/Cancelled</div>
                  </div>
                  <div className="fr wid8p">
                    <div className="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">5</div>
                  </div>
                  <div className="clr"></div>
                </div> < /a>

                      <a href="/inbox / 12 / 1 ">
                 <div className = "fullwid fontthin f14 color3 pad18 brdr1" > <div className="fl wid92p">
                  <div className="fullwid txtc">Filtered Interests</div>
                </div> <div className = "clr" > </div> < /div>
                      </a >
                      <button onClick={this.printProp.bind(this)}>print props2</button>
</div>

//</div>
    )
  }


}
