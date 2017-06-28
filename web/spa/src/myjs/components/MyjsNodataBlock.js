import React from "react";

let i=0;

export class SetNodataHtml extends React.Component{
  constructor(){
    super();
  }
  render(){


    i+=1 ;
  
    return(
      <div id={this.props.idname} key={this.props.idname} className="pad1" className={"pad1 " + ((i%2)==0 ? 'bg4' : 'nobg')}>
        <div className="fullwid pt15 pb10">
          <div className="f17 fontlig color7">{this.props.title}</div>
        </div>
        <div className="pb20">
          <div className="bg8">
            <div className="pad14 txtc">
              <div className="fontlig f14 color8">
              {this.props.message}
              </div>
            </div>
          </div>
        </div>
      </div>
    )
  }

}

export default class NodataBlock extends React.Component{
  componentDidMount(){

  }

	render(){
					if(!this.props.fetched)
			 		{
			 			return (<div className="nodatafetch"></div>)
			 		}
					let noDataHtml = '',noDataHtml1 = '', noDataHtml2 = '',noDataHtml3='';
          let browsePrfHtml='';
          if(this.props.data.match_alert.tuples===null){
            browsePrfHtml= <div id="browseMyMatchBand" key="browseprf">
              <div  className="bg7 pad1" >
                <a href="/search/perform?partnermatches=1" className="white">
                  <div className="fullwid myjs_pad1 txtc">
                    <i className="icons1 ppl1"> </i>
                    <div>
                      <div className="white f19 fontthin padl30">Browse Desired Partner Matches</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>;
          }


					if(this.props.data.interest_received.tuples===null){
						noDataHtml1= <SetNodataHtml idname="awaitingResponseAbsent" key="awaitingResponseAbsent" title={this.props.data.interest_received.title} message="Members Who Showed Interest In Your Profile Will Appear Here"   />
				 }
				 if(this.props.data.visitors.tuples===null){
						 noDataHtml2= <SetNodataHtml idname="visitorAbsent" key="novisitor" title={this.props.data.visitors.title} message="  Members Who Visited Your Profile Will Appear Here 1"   />
					}
					if(this.props.data.match_alert.tuples===null){
						noDataHtml3= <SetNodataHtml idname="matchalertAbsent" key="nomatchalert" title={this.props.data.match_alert.title} message="Members Matching Your Desired Partner Profile Will Appear Here"   />
					}

			   noDataHtml =[browsePrfHtml,noDataHtml1,noDataHtml2,noDataHtml3]
			   return (<div>{noDataHtml}</div>);
		}
}
