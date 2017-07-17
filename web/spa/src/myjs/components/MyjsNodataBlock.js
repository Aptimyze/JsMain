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

	render(){
    console.log('no data block');
    console.log(this.props);
			  let noDataHtml = '',noDataHtml1 = '', noDataHtml2 = '',noDataHtml3='';
        let browsePrfHtml='';

        if(this.props.data.apiDataDR.no_of_results=="0"){
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
        if(!this.props.data.apiDataIR.total){
          noDataHtml1= <SetNodataHtml idname="IR_null" key="IR_null" title="Interests Received" message="Members Who Showed Interest In Your Profile Will Appear Here"   />
       }
       if(!this.props.data.apiDataVA.total){
           noDataHtml2= <SetNodataHtml idname="PF_null" key="PF_null" title="Profile Visitors" message="Members Who Visited Your Profile Will Appear Here"   />
        }
        if(!this.props.data.apiDataDR.no_of_results){
          noDataHtml3= <SetNodataHtml idname="MA_null" key="MA_null" title="Daily Recommendations" message="Members Matching Your Desired Partner Profile Will Appear Here"   />
        }


        noDataHtml =[browsePrfHtml,noDataHtml1,noDataHtml2,noDataHtml3]
        return (<div>{noDataHtml}</div>);
		}
}
