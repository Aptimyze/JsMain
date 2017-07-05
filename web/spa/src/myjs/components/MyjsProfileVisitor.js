import React from "react";

export class MyjsShowVisitors extends React.Component{
  render(){
    let tupleValues = this.props.listingData.tuples;
    let count = (tupleValues.length<=3)?tupleValues.length: 3;
    let VisitorsListing;
    let totalCount = this.props.listingData.view_all_count;
    if(this.props.listingData.tuples.length>4)
    {
      VisitorsListing = <div className="fl pl_a"><a href="/search/visitors?matchedOrAll=A">
          <div className="bg7 txtc disptbl myjsdim1">
            <div className="dispcell fontlig f18 white lh0 vertmid">+{totalCount-3}</div>
          </div>
        </a></div>

    }
    else {
      VisitorsListing = <div></div>;
    }
    return(
        <div className="fullwid clearfix">
          {tupleValues.slice(0,count).map(function(tuple, index){
            return (
                <div className="fl pl_a" key={tuple.profilechecksum}>
                  <Link  to={`/profile/viewprofile.php?profilechecksum=${tuple.profilechecksum}&${this.props.listingData.tracking}&total_rec=${this.props.listingData.view_all_count}&actual_offset=${index}&contact_id=${this.props.listingData.contact_id}`}>
                    <img className="myjsdim1" src={tuple.photo.url}/>
                  </Link>
                </div>
            )
          })}
          {VisitorsListing}
        </div>
      )


  }

}

export default class ProfileVisitor extends React.Component{
  constructor(props) {
        super();
  }
  render(){
    return(
      <div className="setWidth mt10" id="visitorPresent">
        <div className="pad1 bg4">
          <div className="fullwid pt15 pb10">
            <div className="f17 fontlig color7">Profile Visitors</div>
          </div>
          <div className="myjsp1">
            <div className="fullwid">
              <MyjsShowVisitors listingData={this.props.responseMessage} />
            </div>
          </div>
        </div>
      </div>
    )



  }
}
