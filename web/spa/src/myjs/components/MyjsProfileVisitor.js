import React from "react";

import {Link} from "react-router-dom";

export class MyjsShowVisitors extends React.Component{
  render(){
    let tupleValues = this.props.listingData;
    let count = (tupleValues.length<=3)?tupleValues.length: 3;
    let VisitorsListing;
    if(this.props.listingData.length>4)
    {
      VisitorsListing = <div className="fl pl_a"><a href="/search/visitors?matchedOrAll=A">
          <div className="bg7 txtc disptbl myjsdim1">
            <div className="dispcell fontlig f18 white lh0 vertmid">+{this.props.responseMessage.total-3}</div>
          </div>
        </a></div>

    }
    else {
      VisitorsListing = <div></div>;
    }
    return(
        <div className=" fullwid clearfix">
          {tupleValues.slice(0,count).map(function(profiles, index){
            return (
                <div className="fl pl_a" key={profiles.profilechecksum}>
                  <Link  to={`/profile/viewprofile.php?profilechecksum=${profiles.profilechecksum}&${this.props.responseMessage.tracking}&total_rec=${tupleValues.length}&actual_offset=${index}&searchid=${this.props.responseMessage.searchid}&contact_id=nan`}>
                    <img className="myjsdim1" src={profiles.buttonDetailsJSMS.photo.url}/>
                  </Link>
                </div>
            )
          },this)}
          {VisitorsListing}
        </div>
      )


  }

}

export default class ProfileVisitor extends React.Component{
  constructor(props) {
        super();
        this.state={
          mainHeight : 0,
          showNow: 'hidden'

        }

  }
  componentDidMount(){
    if(!this.props.responseMessage.profiles)return;
  }

  componentWillUnmount(){
  clearInterval(this.timer);
  }

  render(){
    return(
      <div style={{}} className=" setWidth mt10" id="visitorPresent">
        <div className="pad1 bg4" style = {{}}>
          <div className="fullwid pt15 pb10">
            <div className="f17 fontlig color7">Profile Visitors</div>
          </div>
          <div className="myjsp1">
            <div className="fullwid">
              <MyjsShowVisitors responseMessage={this.props.responseMessage} listingData={this.props.responseMessage.profiles} />
            </div>
          </div>
        </div>
      </div>
    )



  }


}
