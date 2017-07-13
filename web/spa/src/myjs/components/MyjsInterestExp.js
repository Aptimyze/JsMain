import React from "react";
import {Link} from "react-router-dom";



export class ShowExpiryList extends React.Component {
  constructor(props) {
    super();
  }
  render(){
   let tupleArray = this.props.listingData.profiles;
   let countTuple = (tupleArray.length<=3)?tupleArray.length: 3;
   let setdim = {width:"60px" , height:"60px" };
   let IntExpListing;
   if(tupleArray.length>4)
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
          {this.props.listingData.profiles.slice(0,countTuple).map((profiles,index) => (
                <div className="mar05 dispibl" key={profiles.profilechecksum}>
                  <div className="row mar05 brdr50p posrel outerCircleDiv">
                    <Link to={`/profile/viewprofile.php?profilechecksum=${profiles.profilechecksum}&${this.props.listingData.tracking}&total_rec=${this.props.listingData.total}&actual_offset=${index}&contact_id=${this.props.listingData.contact_id}`}>
                      <img src={profiles.photo.url} className="cell vmid brdr50p innerCircleDiv" style={setdim}/>
                    </Link>
                  </div>
                </div>
          ))}
          {IntExpListing}
        </div>
      )
  }

}

export default class InterestExp extends React.Component{
  render(){
    return(
      <div className="mt15 bg4">
        <div className="f17 fontlig color7 padd22">Interests Expiring this week</div>
        <div className="pad015">
          <div className="fullwid">
            <ShowExpiryList listingData={this.props.int_exp_list} totalcount={this.props.int_exp_list.total} />
          </div>
        </div>

      </div>
    )
  }
}
