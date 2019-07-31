import React from 'react';
import TopError from "../../common/components/TopError";
require ('../style/deleteProfile_css.css');

export default class deleteFlowReasons extends React.Component{
	constructor(props){
	    super(props);
      this.state = {
        showConfirmOverlay:false,
        confirmBody:'',
        button1:'',
        button2:'Continue to Delete',
        redirectPage:'',
        specifyReason:'',
        showPasswordLayer:false,
        showSpecifyTxt:true,
        buttonAction1:'',
        otherReasonLayer:false,
        insertError:false,
        timeToHide:5000,
        errorMessage:'',
        tupleHeight : {'height': document.documentElement.clientHeight}
      };
	 }

   componentDidUpdate(prevProps)
    {
      if(prevProps.specifyReason!=this.props.specifyReason)
        this.props.setParentLayer(this.getOverLayDataDisplay());
    }

   setSpecificReason(deleteReason){
       var specify_reason = '';
       if(deleteReason == 'Other Reasons')
          var specify_reason = document.getElementById("DeleteReasonID").value;
       else{   
         var radio_values = document.getElementsByName("option-group");
          for (var a = 0; a < radio_values.length; a++) {
              if(radio_values[a].checked) {
                  specify_reason = radio_values[a].value;
                  break;
              }
          }
       }
       
       this.setState({specifyReason:specify_reason});
       return specify_reason;    
    }

    showError(inputString,timer=this.state.timeToHide+100) {
          let originalTimeToHide = this.state.timeToHide;
          let _this = this;
          this.setState ({
                  insertError : true,
                  errorMessage : inputString,
                  timeToHide:timer
          })
          setTimeout(function(){
              _this.setState ({
                  insertError : false,
                  errorMessage : "",
                  timeToHide: originalTimeToHide
              })
          }, timer);
      }

   openDeletePreferenceLayer(){
      var deleteReason = this.props.deleteReason;
      var deleteOption = this.props.deleteOption;
      var specifyOption = this.setSpecificReason(deleteReason);
      var layerBody='';
      var redirectUrl= '';
      var buttonText1='';
      var buttonAction1 = '';
      var showPopup = false;

      if(specifyOption == '' && deleteReason != 'Other Reasons'){
        this.showError("Please select a reason.");
        return false;
      }

      if(specifyOption != ''){
        if(deleteReason == "I am unhappy with Jeevansathi services"){
            switch(specifyOption){
              case 'I am getting irrelevant matches':
                  layerBody = 'You can edit your desired partner preferences to get more relevant matches.';
                  buttonText1 = 'Edit Preferences';
                  redirectUrl = "/profile/viewprofile.php?ownview=1#Dpp";
                  showPopup = true;
                  break;
              case 'I am getting repeated matches':
                  layerBody = 'You can choose not to get repeated matches.';
                  buttonText1 = 'Continue Browsing';
                  redirectUrl = "/profile/viewprofile.php?ownview=1#Dpp";
                  buttonAction1 = "RepeatedMatches";
                  showPopup = true;
                  break;
              case 'I am getting very few matches or responses':
                  layerBody = 'You can relax your desired partner preferences to get more matches.';
                  buttonText1 = 'Edit Preferences';
                  redirectUrl = "/profile/viewprofile.php?ownview=1#Dpp";
                  showPopup = true;
                  break;
              case 'Membership plans are expensive':
                  layerBody = 'Get our best discounts planned only for you.';
                  buttonText1 = 'Request a call back';
                  redirectUrl = '/common/requestCallBackJSMS';
                  showPopup = true;
                  break;
              case 'I am facing technical issue':
                  layerBody = 'Apologies for the inconvenience.We will connect with you and rectify it at the earliest.';
                  buttonText1 = 'Request a call back';
                  redirectUrl = '/common/requestCallBackJSMS';
                  showPopup = true;
                  break;
              case 'I am facing privacy issue':
                  layerBody = 'You can edit your profile privacy settings for photo and phone no. visibility.';
                  buttonText1 = 'Edit Preferences';
                  redirectUrl = '/static/privacySettings';
                  showPopup = true;
                  break;
              case 'I am receiving too many app notifications':
                  layerBody = 'You can edit the app notifications preferences.';
                  buttonText1 = 'Edit Preferences';
                  if(typeof Android !== "undefined" && Android !== null){
                    redirectUrl = '/appNotification';
                    showPopup = true;
                  }

                  break;
            }
        }
        else if(deleteReason == "Marry later / will be back later"){
          if(specifyOption == '15 days to 1 month' || specifyOption == '15 days'){
              if(specifyOption == '15 days to 1 month')
                var hideDays = 30;
              else if(specifyOption == '15 days')
                var hideDays = 15;

              layerBody = 'You can instead deactivate your profile temporarily. We will retain your profile details and contacts viewed, and welcome you back after '+hideDays+' days.';
              buttonText1 = 'Deactivate Profile Temporarily';
              buttonAction1 = 'hide';
              redirectUrl = '';
              showPopup = true;
          }
        }   
        
      }
   
      if(specifyOption != '' && showPopup && (deleteReason == 'I am unhappy with Jeevansathi services' || deleteReason == "Marry later / will be back later")){
          this.props.showLayerCommon({showConfirmOverlay:true, confirmBody:layerBody, button1:buttonText1, redirectPage:redirectUrl,buttonAction1:buttonAction1,specifyReason:specifyOption},'showConfirmOverlay');
      }else if(specifyOption == 'Other Reasons')
          this.props.showLayerCommon({otherReasonLayer:true},'otherReasonLayer');
      else
          this.props.showLayerCommon({showPasswordLayer:true,specifyReason:specifyOption},'showPasswordLayer');
  }

  skipCurrentLayer(){
    this.props.showLayerCommon({showPasswordLayer:true,specifyReason:''},'showPasswordLayer');
  }
  
  prepareDeleteSpecifyOptions(deleteReason){

    var options = [];
    if(deleteReason == 'I found my match elsewhere')
      options.push(<ul id="optionDel" className="pad5p15p">
                        <li className="clearfix">                    
                            <input type="radio" id="reason1" name="option-group"  value="Family" />
                            <label htmlFor="reason1">Family</label>
                        </li>
                        <li className="clearfix">                    
                            <input type="radio"  id="reason2" name="option-group"  value="Friends" />
                            <label htmlFor="reason2">Friends</label>
                        </li>
                        <li className="clearfix">                    
                            <input type="radio"  id="reason3" name="option-group"  value="Newspaper" />
                            <label htmlFor="reason3">Newspaper</label>
                        </li>
                        <li className="clearfix">                    
                            <input type="radio"  id="reason4" name="option-group"  value="Marriage Centers" />
                            <label htmlFor="reason4">Marriage Centers</label>
                        </li>
                        <li className="clearfix">                    
                            <input type="radio"  id="reason5" name="option-group"  value="Another Website" />
                            <label htmlFor="reason5">Another Website</label>
                        </li>
                        <li className="clearfix">                    
                            <input type="radio"  id="reason6" name="option-group"  value="Other Reasons" />
                            <label htmlFor="reason6">Other Reasons</label>
                        </li> 
                    </ul>);
    else if(deleteReason == 'I am unhappy with Jeevansathi services')
      options.push(<ul id="optionDel" className="pad5p15p">
                  <li className="clearfix">
                      <input type="radio" id="reason1" name="option-group" value="I am getting irrelevant matches" />
											<label htmlFor="reason1">I am getting irrelevant matches</label>
                  </li>
                  <li className="clearfix">                 
                    <input type="radio" id="reason2" name="option-group" value="I am getting repeated matches" />
                    <label htmlFor="reason2">I am getting repeated matches</label>                    
                  </li>
                  <li className="clearfix">                 
                      <input type="radio"  id="reason3" name="option-group" value="I am getting very few matches or responses" />
                      <label htmlFor="reason3">I am getting very few matches or responses</label>                   
                  </li>
                  <li className="clearfix">                 
                      <input type="radio"id="reason4"  name="option-group" value="Membership plans are expensive" />
                      <label htmlFor="reason4">Membership plans are expensive</label>    
                  </li>
                  <li className="clearfix">                 
                      <input type="radio"  id="reason5" name="option-group" value="I am facing technical issue" />
                      <label htmlFor="reason5">I am facing technical issue</label>                   
                  </li> 
                  <li className="clearfix">                 
                      <input type="radio"  id="reason6" name="option-group" value="I am facing privacy issue" />
                      <label htmlFor="reason6">I am facing privacy issue</label>                    
                  </li> 
                  <li className="clearfix">                  
                      <input type="radio" id="reason7" name="option-group" value="I am receiving too many calls and mails" />
                      <label htmlFor="reason7">I am receiving too many calls and mails</label>                   
                  </li>    
                  <li className="clearfix">                  
                      <input type="radio" id="reason8" name="option-group"  value="I am receiving too many app notifications" />
                      <label htmlFor="reason8">I am receiving too many app notifications</label>                    
                  </li> 
                  <li className="clearfix">                    
                      <input type="radio"  id="reason9" name="option-group"  value="Other Reasons"/>
                      <label htmlFor="reason9">Other Reasons</label>
                  </li>     
                </ul>);
      else if(deleteReason == 'Marry later / will be back later')
        options.push(<ul id="optionDel" className="pad5p15p">
                      <li className="clearfix">                    
                          <input type="radio" id="reason1" name="option-group" value="15 days"/>
                          <label htmlFor="reason1">15 days</label>
                      </li>
                      <li className="clearfix">                 
                        <input type="radio" id="reason2" name="option-group"  value="15 days to 1 month"/>
                        <label htmlFor="reason2">15 days to 1 month</label>                
                      </li>
                      <li className="clearfix">                 
                          <input type="radio" id="reason3" name="option-group"  value="1 to 3 months"/>
                          <label htmlFor="reason3">1 to 3 months</label>
                      </li>
                      <li className="clearfix">                 
                          <input type="radio" id="reason4" name="option-group" value="3 to 6 months"/>
                          <label htmlFor="reason4">3 to 6 months</label>
                      </li>
                      <li className="clearfix">                 
                          <input type="radio" id="reason5" name="option-group" value="6 to 12 months"/>
                          <label htmlFor="reason5">6 to 12 months</label>
                      </li> 
                      <li className="clearfix">                 
                          <input type="radio" id="reason6" name="option-group" value="1 year and later"/>
                          <label htmlFor="reason6">1 year and later</label>          
                      </li>
                  </ul>);
      else if(deleteReason == 'Other Reasons')
          options.push(<div className="pad21p">
                    <textarea id="DeleteReasonID" name="DeleteReasonID" className="f20 fontthin color11 fullwid txtc" placeholder="Kindly specify your reason"></textarea>
                    </div>);

    return options;
   }

	 render() {
    var specifyOptionLayerHtml = this.prepareDeleteSpecifyOptions(this.props.deleteReason);
    let errorView;
    if(this.state.insertError == true)
    {
      errorView = <TopError timeToHide={this.state.timeToHide} message={this.state.errorMessage}></TopError>;
    }

	 	return (<div>

      <div className="wid100p posfix z1 bg4" style={this.state.tupleHeight}><div className="bg1 txtc pad15" id="delH">
      {errorView} 
      <div className="posrel">
        <div className="fontthin f20 white">
          Select Reason
        </div>
        <a href="javascript:void(0);" onClick={this.props.closeReasonLayer}>
          <i className="mainsp posabs set_arow1 set_pos1"></i>
        </a> 
       {this.props.deleteReason == 'Other Reasons' && <div className="posabs d1_pos1">
          <a className="white opa70 f16" onClick={()=>this.skipCurrentLayer()}>
            Skip
          </a>
        </div>}
      </div>
    </div>
    <div className="pad18 bg4 f16 fontlig color13" id="delM">
      {this.props.showSpecifyTxt && <div className="color13 f14">
        {this.props.specifyReasonHeading}
      </div>}
    </div>                
    <div>
      {specifyOptionLayerHtml}
    </div>
    <div id="del_next" className="bg7 white fullwid posfix btm0 txtc lh50" onClick={()=>this.openDeletePreferenceLayer()}>
      Next
    </div>
</div>
</div>
);
	 }
}
