import React from 'react';
import { connect } from "react-redux";
import DeleteFlowReason from '../components/deleteFlowReasons';
import DeletePasswordLayer from '../components/deletePasswordLayer';
import DeleteConfirmationLayer from '../components/deleteConfirmationLayer';
import {getCookie} from '../../common/components/CookieHelper';
import OtherReasonLayer from '../components/otherReasonLayer';
import GA from "../../common/components/GA";
import {REFERRER_SERVER} from "../../common/constants/apiConstants";
require ('../style/deleteProfile_css.css');
let API_SERVER_CONSTANTS = require('../../common/constants/apiServerConstants');

export  class deleteFlow extends React.Component{
	constructor(props){
	    super(props);
      this.layerCount = 0;
      this.GAObject = new GA();
	    this.state = {
		    layerHeading:'Select Reason',
        showDeleteReasonLayer:false,
        showConfirmOverlay:false,
        showPasswordLayer:false,
        otherReasonLayer:false,
        deleteOption:'',
        confirmBody:'',
        button1:'',
        button2:'Continue to Delete',
        buttonAction1:'',
        redirectPage:'',
        deleteReason:'',
        showSpecifyTxt:true,
        specifyReasonHeading:'',
        layerView:'',
        specifyReason:'',
        hideAction:false,
        referrer:document.referrer
		  };
   }

   componentDidMount() {
      if(!this.props.MyProfile.AUTHCHECKSUM && !getCookie('AUTHCHECKSUM')) {
          this.props.history.push('/');
      }

      if(this.state.referrer == '' || this.state.referrer === REFERRER_SERVER +'/static/privacySettings' || this.state.referrer === API_SERVER_CONSTANTS.API_SERVER +'/common/requestCallBackJSMS'){
        this.setState({referrer:REFERRER_SERVER +'/myjs'});
      }
    }

    setLayerView(layer){
      this.setState({layerView:layer});
    }

    showLayerCommon(data,key){
      this.layerCount++;
      this.setState({
        ...data
      },()=>this.setLayerView(this.getOverLayDataDisplay()));
      this.props.historyObject.push(()=>this.hideLayerCommon({[key]:false}),"#layer");
    }

    hideLayerCommon(data){
      if(this.layerCount>0)
        this.layerCount--;
      this.setState({
        ...data
      },()=>this.setLayerView(this.getOverLayDataDisplay()));

      return true;
    }

    getOverLayDataDisplay()
    {

      let layer = [];
      if(this.state.showDeleteReasonLayer){
        layer = (<DeleteFlowReason showLayerCommon={(data,key) => this.showLayerCommon(data,key)} closeReasonLayer={()=>this.props.historyObject.pop(true)} deleteReason={this.state.deleteReason} deleteOption={this.state.deleteOption}showSpecifyTxt={this.state.showSpecifyTxt} specifyReasonHeading={this.state.specifyReasonHeading} setParentLayer={this.setLayerView.bind(this)} />);
      }
      
      if(this.state.showConfirmOverlay && !this.state.showPasswordLayer){
       layer = (<DeleteConfirmationLayer closeConfirmPopup={() => this.historyPop()} deleteOption={this.state.deleteOption} redirectPage={this.state.redirectPage} button1={this.state.button1} button2={this.state.button2} layerBody={this.state.confirmBody} buttonAction1={this.state.buttonAction1} openPasswordPopup={()=>this.openPasswordAfterConfirm(false)} openHidePasswordPopup={()=>this.openPasswordAfterConfirm(true)} GATracking={()=>this.GATrackingOnDeleteFlow('')}/>);
      } 


      if(this.state.otherReasonLayer){ 
        layer = (<OtherReasonLayer showLayerCommon={(data,key) => this.showLayerCommon(data,key)} closeOtherReasonLayer={() => this.historyPop()}  deleteReason={this.state.deleteReason} deleteOption={this.state.deleteOption} specifyReason={this.state.specifyReason} skipOtherLayer={() => this.skipOtherDeleteLayer()} proceedToNextStep={() => this.proceedToPasswordLayer()}/>);
      }

      if(this.state.showPasswordLayer){ 
        layer = (<DeletePasswordLayer closePasswordLayer={() => this.closePasswordLayer()}  deleteReason={this.state.deleteReason} deleteOption={this.state.deleteOption} currentUrl={this.props.location.search} specifyReason={this.state.specifyReason} hideAction={this.state.hideAction}/>);
      }
        return (  <div key="2">{layer}</div>);
    }

    openPasswordAfterConfirm(hideAction){
      history.back();
      setTimeout(() => {
          if(hideAction)
            this.GATrackingOnDeleteFlow('');
          else
            this.GATrackingOnDeleteFlow('delete');

          this.showLayerCommon({showPasswordLayer:true,showConfirmOverlay:false,hideAction:hideAction},'showPasswordLayer');
      }, 100);
    }

    bindAction(layer,index,deleteOption,deleteReason)
    {
      switch(layer)
      {
        case 'passwordLayer':
          this.showLayerCommon({showPasswordLayer:true,deleteOption : deleteOption, deleteReason:deleteReason},'showPasswordLayer');
        break;
        
        case 'reasonLayer':
          var showSpecifyTxt = true;
          var specifyHeading = '';
          if(deleteReason == 'Other Reasons')
               showSpecifyTxt = false;
          else if(deleteReason == 'I found my match elsewhere')
               specifyHeading = 'Kindly specify the source';
          else if(deleteReason == 'I am unhappy with Jeevansathi services')
               specifyHeading = 'What is your reason of unhappiness?';
          else if(deleteReason == 'Marry later / will be back later')
               specifyHeading = 'You will be back in?';
          this.showLayerCommon({deleteOption : deleteOption,showDeleteReasonLayer:true,deleteReason:deleteReason,specifyReasonHeading:specifyHeading,showSpecifyTxt:showSpecifyTxt},'showDeleteReasonLayer');
        break;

        case 'confirmLayer':
          if(deleteReason == 'I have to do some changes in my profile'){
              var layerBody = 'You can add or edit your profile details in your current profile.';
              var buttonText1 = 'Edit Profile';
              var buttonText2 = 'Continue to Delete';
              var redirectUrl = '/profile/viewprofile.php?ownview=1';
          }else if(deleteReason == 'Privacy Issue'){
              var layerBody = 'You can edit your profile privacy settings for photo and phone no. visibility.';
              var buttonText1 = 'Edit Preferences';
              var buttonText2 = 'Continue to Delete';
              var redirectUrl = '/static/privacySettings';
          }

          this.showLayerCommon({deleteOption : deleteOption, showConfirmOverlay:true, deleteReason:deleteReason, confirmBody:layerBody, button1:buttonText1, button2:buttonText2, redirectPage:redirectUrl},'showConfirmOverlay');
        break;

      }
    }

    closePasswordLayer(){
      this.setState({hideAction:false});
      this.historyPop();
    }

    historyPop(){
      //this.props.historyObject.pop(true)
      history.back();
    }

    doubleHistoryBack(){
       history.back();
       history.back();
    }

    skipOtherDeleteLayer(){
      this.showLayerCommon({showPasswordLayer:true,specifyReason:'Other Reason'},'showPasswordLayer');
    }  

    proceedToPasswordLayer(){
      var otherReason = document.getElementById("otherReasonID").value.trim();
      if(otherReason == ''){
        otherReason = 'Other Reason';
      }
      this.showLayerCommon({specifyReason:otherReason,showPasswordLayer:true},'showPasswordLayer');
    }

    backRedirectHandling(){
      if(typeof Android !== "undefined" && Android !== null){
        this.backToMyJs();
      }else{
        window.location = this.state.referrer;
      }
    }

    backToMyJs() {
        if(typeof Android !== "undefined" && Android !== null) {
            Android.backToMyJs();
        }
    }

    GATrackingOnDeleteFlow(action){
      var GAReason = this.state.specifyReason;
      var GA_keyword ='';
      if(GAReason == '')
        GAReason = this.state.deleteReason;
      
      if(GAReason == 'I am getting irrelevant matches')
          GA_keyword= (action=='delete')?'Irrelevant delete':'Irrelevant edit';
      else if(GAReason == 'I am getting repeated matches')
          GA_keyword= (action=='delete')?'Repeated delete':'Repeated continue browsing';
      else if(GAReason == 'I am getting very few matches or responses')
          GA_keyword= (action=='delete')?'Few delete':'Few edit';
      else if(GAReason == 'Membership plans are expensive')
          GA_keyword= (action=='delete')?'Membership delete':'Membership rcb';
      else if(GAReason == 'I am facing technical issue')
          GA_keyword= (action=='delete')?'Technical delete':'Technical rcb';
      else if(GAReason == 'I am facing privacy issue' || GAReason == "Privacy Issue")
          GA_keyword= (action=='delete')?'Privacy delete':'Privacy edit';
      else if(GAReason == 'I am receiving too many calls and mails')
          GA_keyword= (action=='delete')?'Calls delete':'Calls edit';
      else if(GAReason == '15 days to 1 month' || GAReason == '15 days')
          GA_keyword= (action=='delete')?'Marry later delete':'Marry later hide';
      else if(GAReason == "I have to do some changes in my profile")
          GA_keyword= (action=='delete')?'Profile delete':'Profile edit';

      this.GAObject.trackJsEventGA("Delete-jsms",GA_keyword,this.GAObject.getGenderForGA());
    }

   render() {
    return (
            <div id="mainContent">
              {this.state.layerView}
              <div className="bg1 txtc pad15">
                <div className="posrel">
                  <div className="fontthin f20 white">Select Reason</div>
                  <a onClick={() => this.backRedirectHandling()}><i className="mainsp posabs set_arow1 set_pos1"></i></a> </div>
              </div>
            
            <div className="pad18 bg4 f16 fontlig color13"> 
                <div>This will delete your profile permanently. Let us know why you wish to delete your profile.</div>
                <div className="clearfix pad12" onClick={() => this.bindAction('passwordLayer',0,'1','I found my match on Jeevansathi')}>
                  <div className="fl wid94p"><a className="color13">I found my match on Jeevansathi</a></div>
                  <div className="fr pt2"><a><i className="mainsp set_arow2"></i></a></div>
                </div>
                
                <div className="clearfix pad12" onClick={() => this.bindAction('reasonLayer',0,'2','I found my match elsewhere')}>
                  <div className="fl wid94p"><a className="color13" >I found my match elsewhere</a></div>
                  <div className="fr pt2"><a><i className="mainsp set_arow2"></i></a></div>
                </div>
               
                <div className="clearfix pad12" onClick={() => this.bindAction('reasonLayer',0,'3','I am unhappy with Jeevansathi services')}>
                  <div className="fl wid94p"><a className="color13">I am unhappy with Jeevansathi services</a></div>
                  <div className="fr pt2"><a><i className="mainsp set_arow2"></i></a></div>
                </div>
                
                <div className="clearfix pad12" onClick={() => this.bindAction('reasonLayer',0,'4','Marry later / will be back later')}>
                  <div className="fl wid94p"><a className="color13">Marry later / Create profile later</a></div>
                  <div className="fr pt2"><a><i className="mainsp set_arow2"></i></a></div>
                </div>
                <div className="clearfix pad12" onClick={() => this.bindAction('confirmLayer',0,'5','I have to do some changes in my profile')}>
                  <div className="fl wid94p"><a className="color13">I have to do some changes in my profile</a></div>
                  <div className="fr pt2"><a><i className="mainsp set_arow2"></i></a></div>
                </div>
                <div className="clearfix pad12" onClick={() => this.bindAction('confirmLayer',0,'6','Privacy Issue')}>
                  <div className="fl wid94p"><a className="color13">Privacy Issue</a></div>
                  <div className="fr pt2"><a><i className="mainsp set_arow2"></i></a></div>
                </div>
               
                <div className="clearfix pad12" onClick={() => this.bindAction('reasonLayer',0,'7','Other Reasons')}>
                  <div className="fl wid94p"><a className="color13">Other Reasons</a></div>
                  <div className="fr pt2"><a><i className="mainsp set_arow2"></i></a></div>
                </div>            
            </div>   
        </div>);
   }
}

const mapStateToProps = (state) => {
    return{
         MyProfile: state.LoginReducer.MyProfile,
         historyObject : state.historyReducer.historyObject
      }
}

export default connect(mapStateToProps)(deleteFlow)
