import React from "react";

export default class NodataBlock extends React.Component{
	render(){
					if(!this.props.fetched)
			 		{
			 			return (<div className="nodatafetch"></div>)
			 		}
					let noDataHtml = '',noDataHtml1 = '', noDataHtml2 = '',noDataHtml3='';
          let browsePrfHtml='';

          if(this.props.data.match_alert.tuples===null){
            browsePrfHtml= <div id="browseMyMatchBand" className="mt10" key="browseprf">
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
						noDataHtml1= <span id="awaitingResponseAbsent" key="int_rec" >
												 <div className="pad1">
													 <div className="fullwid pt15 pb10">
														 <div className="f17 fontlig color7">{this.props.data.interest_received.title}</div>
													 </div>
													 <div className="pb20" id="eoiAbsent">
														 <div className="bg8">
															 <div className="pad14 txtc">
																 <div className="fontlig f14 color8">
																	 Members Who Showed Interest In Your Profile Will Appear Here
																 </div>
															 </div>
														 </div>
													 </div>
												 </div>
											 </span>
				 }
				 if(this.props.data.visitors.tuples===null){
						 noDataHtml2= <span id="visitorAbsent" key="novisitor">
						 							<div className="pad1">
														<div className="fullwid pt15 pb10">
															<div className="f17 fontlig color7">{this.props.data.visitors.title}</div>
														</div>
														<div className="pb20" id="eoiAbsent">
															<div className="bg8">
																<div className="pad14 txtc">
																	<div className="fontlig f14 color8">
																		Members Who Visited Your Profile Will Appear Here
																	</div>
																</div>
															</div>
														</div>
													</div>
												</span>
					}
					if(this.props.data.match_alert.tuples===null){
						noDataHtml3= <span id="matchalertAbsent" key="nomatchalert">
												 <div className="pad1">
													 <div className="fullwid pt15 pb10">
														 <div className="f17 fontlig color7">{this.props.data.match_alert.title}</div>
													 </div>
													 <div className="pb20" id="eoiAbsent">
														 <div className="bg8">
															 <div className="pad14 txtc">
																 <div className="fontlig f14 color8">
																	Members Matching Your Desired Partner Profile Will Appear Here
																 </div>
															 </div>
														 </div>
													 </div>
												 </div>
											 </span>
					}

			   noDataHtml =[browsePrfHtml,noDataHtml1,noDataHtml2,noDataHtml3]
			   return (<div>{noDataHtml}</div>);
		}
}
