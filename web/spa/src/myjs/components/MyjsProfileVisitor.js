import React from "react";

export class MyjsShowVisitors extends React.Component{
  render(){

    let count = (this.props.tuplesvalues.length<=3)?this.props.tuplesvalues.length: 3;
    let VisitorsListing;
    if(this.props.tuplesvalues.length>4)
    {
      VisitorsListing=    <div className="fl pl_a"><a href="/search/visitors?matchedOrAll=A">
          <div className="bg7 txtc disptbl myjsdim1">
            <div className="dispcell fontlig f18 white lh0 vertmid">+{this.props.totalvisitors-3}</div>
          </div>
        </a></div>

    }
    else {
      VisitorsListing = <div></div>;
    }
    return(
        <div className="fullwid clearfix">
          {this.props.tuplesvalues.slice(0,count).map(function(tuple){
            return (
                <div className="fl pl_a" key={tuple.profilechecksum}>
                  <a href='/profile/viewprofile.php?'>
                    <img className="myjsdim1" src={tuple.photo.url}/>
                  </a>
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
              <MyjsShowVisitors tuplesvalues={this.props.responseMessage.tuples} totalvisitors={this.props.responseMessage.new_count}/>
            </div>
          </div>
        </div>
      </div>
    )



  }
}
