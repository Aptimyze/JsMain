import React from 'react';
import { connect } from "react-redux";
import GA from "../../common/components/GA";

require ('../style/promoLayer_css.css');

export class PromoComp extends React.Component{
  constructor(props){
    super(props);
    this.state = {
      insertError : false
    }
    this.GAObject = new GA();
  }

componentWillMount(){
  this.props.historyObject.push(()    =>{
          this.closePromoLayer();return true;}
      ,'#promo');
}
closePromoLayer(){
  this.props.setPromoShown();
  return true;
}
componentDidMount(){

}

render(){
  let toReturn;
  let errorView;
  if(this.state.insertError){
    errorView = (<TopError timeToHide={this.state.errorMessage} message={this.state.errorMessage}></TopError>);
  }
  toReturn = this.getPromoLayerData();
  return (<div>{errorView}{toReturn}</div>);
}

goToPlayStore(){
  var partLink = '/static/appredirect?type=androidMobFooter';
  window.location.href = partLink;
}
trackEventGA(a, b, c){
  // this.GAObject.trackJsEventGA("jsms","new","1");
  this.GAObject.trackJsEventGA(a, b, c);
}
getPromoLayerData(){
  return(
  <div id="chatPormoMS">
      <div className="fullwid fullheight cpbg1 ">
          <div className="posabs setshare txtc color7 wid94p" id="PLayer"> 
              <p className="fontreg f18">JeevanSathi Chat now on Android!</p>  
              <ul className="txtc fontlig f12 pt15 lh25 listStyled">
                <li>Connect faster with your matches through the Chat feature. </li>
                <li>Get instantly notified about messages.</li>
                <li>Chat with real time online matches and get instant response. </li>                    
              </ul> 
              <p className="fontreg f14 pt10 pb20">All this and much more !!</p>      
              <div className="closeCP pt20" href="" onClick={() => {this.trackEventGA('CHAT PROMOTION', 'Close', 'MS'); this.props.historyObject.pop(true);}}></div>
              <div>
                <img src="/images/chatPromoImg1.png" className="txtc"/>
              </div>  
              <button className="bg7 white fontreg f16" onClick={() => {this.trackEventGA('CHAT PROMOTION', 'Download', 'MS'); this.goToPlayStore();}} >Download APP</button>
            </div>
        </div>
    </div>
    );
}

}

const mapReduxToProps = (state) => {
  return {
    historyObject : state.historyReducer.historyObject
  }
}
const mapReduxDispatcherToProps = (state) => {
  return {

  }
}
export default connect (mapReduxToProps, mapReduxDispatcherToProps)(PromoComp)
