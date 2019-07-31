import React from 'react';
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";
require ('../style/deleteProfile_css.css');


export default class deleteConfirmationLayer extends React.Component{
		constructor(props){
			super(props);
			this.state = {
			};
		}

		redirectPageUrl() {
			if(typeof Android !== "undefined" && Android !== null){
				this.props.GATracking();
				setTimeout(() => {
					Android.redirectPageUrl(this.props.redirectPage);
			    }, 100);
			}else{
				this.props.closeConfirmPopup();
				this.props.GATracking();
				setTimeout(() => {
					  window.location.href = this.props.redirectPage; 
			    }, 100);
			}
		}

		stopRepeatedMatches(){
			var api_url = '/settings/repeatedMatchesCriteria';
			commonApiCall(api_url,{},'','').then((response)=>{					
				if(response){
					this.props.GATracking();
					if(typeof Android !== "undefined" && Android !== null){
						setTimeout(() => {
							Android.redirectPageUrl('/myjs');
					 	}, 100);
					}else{
						setTimeout(() => {
							window.location.href= '/myjs';
						}, 100);					
					}
				}else
					window.location.reload();
			});
		}

		confirmationAction(){
			var html = [];
			if(this.props.buttonAction1 == 'hide'){
				html.push(<a className='color2' onClick={this.props.openHidePasswordPopup}>{this.props.button1}</a>);
			}else if(this.props.buttonAction1 == 'RepeatedMatches'){
				html.push(<a className='color2' onClick={()=>this.stopRepeatedMatches()} >{this.props.button1}</a>);
			}else{
				html.push(<a className='color2' onClick={()=>this.redirectPageUrl()} >{this.props.button1}</a>);
			}

			return html;
			
		}

		confirmOverlayLayer(){
			var confirmHtml = [];
			confirmHtml.push(<div id="overlayDel">
			            <div className="tapoverlay posfix" onClick={this.props.closeConfirmPopup}></div>
			            <div className="setshare bg4 posfix wid94p del_z1">
			                <div className="txtc pad3 f14 color3">
			                    <p className="pt5 pb20 fontlig">{this.props.layerBody}</p>
			                </div>
			                <div className="del_brdr3">
			                    <div className="fullwid clearfix ">
			                        <div className="fl txtc pad2 wid49p f13 brdr2" onClick={this.props.openPasswordPopup}>
			                            <div className="fontlig">{this.props.button2}</div>
			                         </div>
			                        {this.props.button1 && <div className="fl txtc pad2 wid49p ">
			                            <div className="white f13 fontlig">
			                                <div className="fontreg">{this.confirmationAction()}</div>
			                            </div>
			                        </div>}
			                     </div>
			                </div>        
			            </div>
			        </div>);

			return confirmHtml;
		}

		render(){
			var confirmLayerHtml  = this.confirmOverlayLayer();
			return(<div>
				{confirmLayerHtml}
				</div>
			);
		}

}
