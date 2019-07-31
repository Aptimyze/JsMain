import React from 'react';
import Loader from "../../common/components/Loader";
import {getParameterByName} from '../../common/components/UrlDecoder';
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";
import { connect } from "react-redux";
import TopError from "../../common/components/TopError";
import {removeCookie} from '../../common/components/CookieHelper';
require ('../style/deleteProfile_css.css');


class deletePasswordLayer extends React.Component{
		constructor(props){
			super(props);
			this.state = {
				tupleHeight : {'height': document.documentElement.clientHeight},
				showOTPLayer : false,
				showResendLayer : false,
				showWrongOTPLayer : false,
				showAttemptOTPLayer : false,
				showoopsDiv: false,
				hideOnTrialsOver:true,
				confirmationLayer:false,
				authStr1:'',
				authStr2:'',
				insertError:false,
				timeToHide:5000,
				errorMessage:'',
				showLoader: false,
				showTrialOver:true,
				offerCheckBoxShow:false
			};
		}

		componentDidMount() {
			if(this.props.deleteOption == '1' || this.props.deleteOption == '2')
				this.setState({offerCheckBoxShow:true});
			

		}


		validatePassword(){
			var password = document.getElementById("passValueID").value.trim();
			var api_url = '/api/v1/common/checkPassword';
			if(typeof Android !== "undefined" && Android !== null)
				var checksum = getParameterByName(this.props.currentUrl,'AUTHCHECKSUM');
			else
				var checksum = this.props.MyProfile.AUTHCHECKSUM;

			var params = JSON.stringify({"pswrd":password,"resetPass":true});

			commonApiCall(api_url,{data:params},'','').then((response)=>{
				if(response.success){
					var hideOption = this.checkForHideProfile();
					if(hideOption != '')
						this.hideProfile(hideOption);
					else
						this.setState({confirmationLayer:true,authStr1:response.authStr1,authStr2:response.authStr2});	
				}
				else
					this.showError("invalid password");

			});
		}

		checkForHideProfile(){
			var hideOption = '';
			if(this.props.deleteReason == 'Marry later / will be back later' && this.props.hideAction && (this.props.specifyReason == '15 days' || this.props.specifyReason == '15 days to 1 month')){
						if(this.props.specifyReason == '15 days')
							hideOption = 2;
						else
							hideOption = 3;
			}
			return hideOption;
		}

		hideProfile(hideOption){
			var api_url = '/api/v1/settings/hideUnhideProfile';
			var params = JSON.stringify({"hide_option":hideOption,"actionHide":1});
			commonApiCall(api_url,{data:params},'','').then((response)=>{
					if(response.success == 1)
					{
						var url = "/static/hideDuration?hide_option="+hideOption;
						window.location.href= url;
					}
					else{
						this.showError("Something went wrong");					
					}

			});
		}

		showError(inputString,timer=this.state.timeToHide+100) {
	          let originalTimeToHide = this.state.timeToHide;
	          this.setState ({
	                  insertError : true,
	                  errorMessage : inputString,
	                  timeToHide:timer
	          })
	          setTimeout(()=>{
	              this.setState ({
	                  insertError : false,
	                  errorMessage : "",
	                  timeToHide: originalTimeToHide
	              })
	          }, timer);
	      }

		profileDeletedSuccesfully() {
	        if(typeof Android !== "undefined" && Android !== null) {
	            Android.profileDeletedSuccesfully();
	        }
	    }


		ajaxDelete(){
			var api = '/api/v1/settings/deleteProfile?'+this.state.authStr1+'&'+this.state.authStr2;
			var offerConsent = (document.getElementById('offerConsentCB') && document.getElementById('offerConsentCB').checked)?'Y':'N';
			var deleteOption = this.props.deleteOption;
			var specifyReason = (this.props.specifyReason)?this.props.specifyReason:'';
			this.setState ({
                showLoader : true
            })
			commonApiCall(api,{deleteReason:deleteOption ,specifyReason:specifyReason,option:'Delete',offerConsent:offerConsent },'','','','','',{'X-Requested-By' : 'jeevansathi'}).then((response)=>{
				if(deleteOption == 1 && response.successStoryMailId){
					window.location.href= "/successStory/layer/?mailid="+response.successStoryMailId;
				}else if(response.output=="Deleted Successfully"){
					if(deleteOption==1 || deleteOption==2)
			          window.location.href= "/static/PostWeddingServices";
			        else{
			           if(typeof Android !== "undefined" && Android !== null){
			           		this.profileDeletedSuccesfully();
			           }else{
			           		removeCookie("AUTHCHECKSUM");
			           		window.location.href = "/login";
			           }
			        }
				}
			});
		}

		closeConfirmationLayer(){
			this.setState({confirmationLayer:false});

		}

		openOTPLayer(){
			this.setState({showOTPLayer:true});
		}

		closeOTPLayer(){
			this.setState({showOTPLayer:false});
		}

		sendMatchOtpAjax(hideProfile) {
			var OTP=document.getElementById("matchOtpText").value.trim();
			if(!OTP)
			{
			  this.displayOTPError();return;
			}
			var api_url = '/common/matchOtp';
			commonApiCall(api_url,{'enteredOtp':OTP,'phoneType':'M','resetPass':true},'','').then((response)=>{
				if(response.matched=='true')
	                { 
	                	var hideOption = this.checkForHideProfile();
						if(hideOption != '')
							this.hideProfile(hideOption);
	                   	else
	                   		this.setState({showOTPLayer:false,confirmationLayer:true,authStr1:response.authStr1,authStr2:response.authStr2});
	                   
	                }
	                else if(response.matched=='false')
	                {
	                  if(response.regenerate=='true'){
	                	  this.showError(response.errorMsg);
	                  }
	                	if(response.trialsOver=='N')
	                  {
	                    this.displayOTPError();
	                    this.setState({showoopsDiv:false});    
	                  }
	                  else if(response.trialsOver=='Y') 
	                  {
	                      this.setState({showoopsDiv:true});    
	                      this.showOTPFailedLayer();
	                   }
	                }
			});

		}

		displayOTPError(){
			this.setState({showWrongOTPLayer:true});
		}

		hideDisplayOTPError(){
			this.setState({showWrongOTPLayer:false});
		}

		showOTPFailedLayer(){
			this.setState({showAttemptOTPLayer:true});			
		}
		closeAttemptOTPLayer(){
			this.setState({showAttemptOTPLayer:false});
		}

		showResendOTPLayer(){
			this.setState({showResendLayer:true});
			this.showCommonOtpLayer();
		}

		showCommonOtpLayer(){
			var api_url = '/common/SendOtpSMS';

			commonApiCall(api_url,{'phoneType':'M'},'','').then((response)=>{
				this.setState({showResendLayer:false});
					/*if(showLayer == 1)
			        bringSuccessLayerOnMobile(response);*/
				 	if(response.trialsOver == 'Y')
			      	{
				 		this.showError(response.errorMsg);
				 		this.showOTPFailedLayer();

			      	}else{
			      		this.openOTPLayer();
			      	}

			        if(response.SMSLimitOver =='Y') 
			        {
			        	this.showError(response.errorMsg);
			        	this.setState({showTrialOver:false});
			        }
			});
		}


		render(){
			let errorView;
	        if(this.state.insertError == true)
	        {
	          errorView = (<TopError timeToHide={this.state.errorMessage} message={this.state.errorMessage}></TopError>);
	        }

	        let loaderView;
	        if(this.state.showLoader)
	        {
	          loaderView = <Loader show="page"></Loader>;
	        }

	        var deleteAction = 'Delete';
	        var hideOption = this.checkForHideProfile();
			if(hideOption != '')
				deleteAction  = "Deactivate";

			return(<div><div>
						  {loaderView}
						  {errorView}
						  <div id="deleteProfilePasswordPage" className="fullwid posfix z101 bg8" style={this.state.tupleHeight}> 
						    <div id = "showDuringOTP" className='js-NumberedLayer'>
									<div id="overlayHead" className="bg1 txtc pad15">
									      <div className="posrel lh30">
							        			<div className="fontthin f20 white">Your Password</div>
							        			<a onClick={this.props.closePasswordLayer}><i className="mainsp posabs set_arow1 set_pos1"></i></a>
							       		  </div>
							    	</div>
						  				 
								   	<div className="bg4 f16 fontlig color13"> 
								        <div style={{padding:"20%"}}>
								        	<input id="passValueID" type="password" placeholder="Enter Password" className="f20 fontthin color11 fullwid txtc"/>        
								        </div>
							        </div>

							        <div id="foot" className="posfix fullwid bg7 btmo" onClick={()=>this.validatePassword()}>
										<input type="submit" id="passCheckID" className="fullwid dispbl lh50 txtc f16 white" value={deleteAction + " My Profile"}/>
									</div>
							        <div className = "txtc pt10"><a id="otpProfileDeletionJSMS" className="fontlig white f14 pb10 color16 otp_l_color" style={{color:"#d9475c"}} onClick={()=>this.showCommonOtpLayer()}> {deleteAction + " Using One Time Code"}</a></div>
							    
								    {this.state.offerCheckBoxShow && <div id="offerCheckBox" className="disp-none ch_pad">       
									      <div className="fl">
									        <li className="memList"><input id='offerConsentCB' type="checkbox" name="js-offerConsentCheckBox" defaultChecked/></li>
									      </div>
									      <div className="fontlig pl20 pr10 mgl2 f15" >I authorize Jeevansathi to send Emails containing attractive offers related to the wedding</div>
									</div>}
								</div>
								    
								</div>

							</div>
								{this.state.showWrongOTPLayer &&
								<div>
									<div id='otpWrongCodeLayer' className="otplayer ce_z102 "></div>					
										<div className="bg4 fontlig f18  wid85p setshare posfix ce_z103">
									        <div className="txtc pt40">
									            <i className="mainsp otpic1"></i>
									        </div>
									        <p className="color3 txtc pt10">OTP Verification Failed</p>
									        <p className="color4 txtc pt10 pb30">Make sure you entered correct code.</p>
									        <div className="otpbr2 txtc otplh60" >
									            <div id='js-okIncorrectOtp' className="f19 otpcolr2 fontthin" onClick={()=>this.hideDisplayOTPError()}>Ok</div>
									        </div>
								    </div></div>}
								

								{this.state.showResendLayer &&
								<div>
									<div id='otpResendingLayer' className="otplayer ce_z102"></div>
									    <div className="setshare bg4 fontlig f18 wid85p setshare posfix ce_z103">
									        <div className="txtc pt40">
									            <img src="/images/jsms/commonImg/loader_card.gif"/>
									        </div>
									        <p className="color3 txtc pt40">Resending Verification Code</p>
									        <p className="color4 txtc pt15 optp4">Wait for a moment while we send the code.</p>
									        
									    </div>
								</div>}

							    {this.state.showOTPLayer && <div id = "bringSuccessLayerOnMobile" className='js-NumberedLayer otpLayer fullwid posfix ce_z102 bg8' style={this.state.tupleHeight}>
									<div id="overlayHead" className="bg1 txtc pad15">
										<div className="posrel lh30">
											<div className="fontthin f20 white">{deleteAction + " Using OTP"}</div>
											<a onClick={()=>this.closeOTPLayer()}><i className="mainsp posabs set_arow1 set_pos1"></i></a>
										</div>
									</div>    
							          
					               <div id ="putPasswordLayer" className='js-NumberedLayer2'><div className=" txtc f14 fontlig pt30 pb15">
								    	<p>{"Profile "+deleteAction+" code sent"}<span id='isdDiv'></span> <span id='mainPhone'></span></p>
								    	{this.state.showTrialOver && <div id = "hideOnTrialsOver">
								    		<p id='resendSMSDiv' className="pt5">Didn't receive code? <a id='resendTextId'  className="color2" onClick={()=>this.showResendOTPLayer()}>Resend Code</a></p> 
								    	</div>}
							    	</div>

							      	<div className="bg4 otpma">
							            <div className="pt20 pb20 otpwid1">
							            	<input id='matchOtpText' type ="tel" placeholder="Enter Code" autocomplete="off" className="f19 fontlig  fullwid txtc"/>
							            </div>
							        </div>
						   
						            <div id="buttonForCode" className="pt20">
			                                <a id="mainBottomButton2" className=" js-NumberedLayer2 bg7  white lh30 fullwid dispbl txtc lh50 f19 fontlig" onClick={()=>this.sendMatchOtpAjax()}>{deleteAction +" Using OTP"}</a>
			                        </div>
			                    </div>
			                    </div>}
					           
			                     {this.state.showAttemptOTPLayer && <div id ="attemptsOver" className="otpma js-NumberedLayer js-NumberedLayer3 fullwid posfix ce_z102 bg8" style={this.state.tupleHeight}>
				                    	<div id="overlayHead" className="bg1 txtc pad15">
					                        <div className="posrel lh30">
					                        	<div className="fontthin f20 white">{deleteAction +" Using OTP"}</div>
					                        	<a onClick={()=>this.closeAttemptOTPLayer()}><i className="mainsp posabs set_arow1 set_pos1"></i></a>
					                        </div>
				                    	</div>
						                  <div className="txtc optp5 f18 fontlig bg4">
						                      <i className="mainsp otpic1 js-noTrials"></i>
						                      {this.state.showoopsDiv && <p id='oopsDiv' className="otpcolr1 pt20 js-noTrials"><strong>Oops! You have exhausted all your trials !</strong></p>}
						                      <p className="color13 otpp6 lh25">You have reached maximum number of attempts for Verification code.</p>  
						                  </div>
					            </div>}

					            {this.state.confirmationLayer && <div id="deleteConfirmation-Layer" className ="set_bg2 fullwid posfix z101 bg8" style={this.state.tupleHeight}>
								  	<div  className="posrel confirmpd">
										<div className="br50p txtc ht1">				
											</div>				 
									</div>
										 
									<div className="txtc">	 
										<div className="fontlig white f18 pb10 color16">Delete Profile Permanently</div>
										<div className="pad1 lh25 fontlig f14 confirm_color">This will completely delete your profile information, contact history and active paid membership(s), if any. Are you sure about deleting your profile?</div>
								  	</div>
									  <div className="confirmpddiv">
										<div id="deleteYesConfirmation" className="bg7 f18 white lh30 fullwid dispbl txtc lh50" onClick={()=>this.ajaxDelete()}>Yes, Delete Profile Permanently</div>
									  </div>
								 	 <div id="deleteNoConfirmation" className="pdt15 pb10 txtc f14 confirm_color" ><a href="/deleteProfile" className="white">Dismiss</a></div>
								</div>}
					        </div>
			);
		}

}

const mapStateToProps = (state) => {
    return{
         MyProfile: state.LoginReducer.MyProfile,
      }
}

export default connect(mapStateToProps)(deletePasswordLayer)
