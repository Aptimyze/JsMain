import React from "react";



export class ShowExpiryList extends React.Component {
  constructor(props) {
    super();
  }
  render(){
   let countTuple = (this.props.tuples.length<=3)?this.props.tuples.length: 3;
   let setdim = {width:"60px" , height:"60px" };
   let IntExpListing;
   if(this.props.tuples.length>4)
   {
      IntExpListing=    <div className="mar05 dispibl">
        <div className="row mar05 bg7 brdr50p posrel outerCircleDiv" style={setdim}>
          <div className="cell vmid white fullwid f23 fontlig txtc">
           + {this.props.totalcount-3}
          </div>
        </div>
      </div>
    }
    else
    {
      IntExpListing = <div></div>;
    }
    return(
        <div>
          {this.props.tuples.slice(0,countTuple).map(function(tuple){
            return (
                <div className="mar05 dispibl" key={tuple.profilechecksum}>
                  <div className="row mar05 brdr50p posrel outerCircleDiv">
                    <a href=''>
                      <img src={tuple.photo.url} className="cell vmid brdr50p innerCircleDiv" style={setdim}/></a>
                  </div>
                </div>
            )
          })}
          {IntExpListing}
        </div>
      )
  }

}

export default class InterestExp extends React.Component{
  render(){
    console.log(this.props.int_exp_list);
    return(
      <div className="mt15 bg4">
        <div className="f17 fontlig color7 padd22">Interests Expiring this week</div>
        <div className="pad015">
          <div className="fullwid">
            <ShowExpiryList tuples={this.props.int_exp_list.tuples} totalcount={this.props.int_exp_list.view_all_count} />
          </div>
        </div>

      </div>
    )
  }
}
