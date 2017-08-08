import React from "react";

export class AcceptancesCount extends React.Component {
  render(){
    if(this.props.count!=0)
    {
      return(
        <div className="posabs pos3">
          <div className="bg10 txtc wid20 hgt20 brdr50p">
            <div className="white f12 fontlig pt2">
              {this.props.count}
            </div>
          </div>
        </div>
      )
    }
    else {
      return <div></div>;
    }
  }
}

export default class AcceptCount extends React.Component {
  constructor(props) {
      super();
      this.state ={bounceAnimation:""};
  }
componentDidMount(){
this.setState({bounceAnimation:" bounceIn animated "});


}

  render(){
  if(!this.props.fetched) {
      return <div></div>;
    }
    return(
      <div className="bg4 pad1" id="acceptanceCountSection">
        <div className="fullwid pad2 clearfix">

          <a href="/inbox/2/1">
            <div className="fl wid49p txtc">
              <div className={"row bg7 wid75 hgt75 brdr50p posrel "+this.state.bounceAnimation}  id="acceptedMe">
                <div className="cell vmid white fullwid myjs_f30 fontlig">{this.props.apiDataHam ? this.props.apiDataHam.hamburgerDetails.ACCEPTED_MEMBERS : ''}</div>
                <AcceptancesCount count={this.props.apiDataHam ? this.props.apiDataHam.hamburgerDetails.ACC_ME_NEW : ''}/>
              </div>
              <div className="f12 fontlig color7 pt10">
                <p>All</p>
                <p> Acceptances</p>
              </div>
            </div>
          </a>

          <a href="/search/perform?justJoinedMatches=1">
            <div className="fl wid49p txtc">
              <div className={"row bg7 wid75 hgt75 brdr50p posrel "+this.state.bounceAnimation} id="iAccepted">
                <div className="cell vmid white myjs_f30 fontlig">{this.props.apiDataHam ? this.props.apiDataHam.hamburgerDetails.JUST_JOINED_COUNT : ''}</div>
                <AcceptancesCount count={this.props.apiDataHam ? this.props.apiDataHam.hamburgerDetails.JUST_JOINED_NEW :''}/>
              </div>
              <div className="f12 fontlig color7 pt10">
                <p>Just</p>
                <p>Joined Matches</p>
              </div>
            </div>
          </a>



        </div>
      </div>
    )
  }
}
