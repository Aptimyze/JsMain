import React from 'react';
import {removeClass, $i, $c} from '../../common/components/commonFunctions';
import CALCommonCall from './CommonCALFunctions';
import Loader from "../../common/components/Loader";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import TopError from "../../common/components/TopError"
import { ErrorConstantsMapping } from "../../common/constants/ErrorConstantsMapping";
require ('../style/CALJSMS_css.css');

export default class calComp2 extends React.Component{
constructor(props){
  super(props);
  this.calData = this.props.calData;
  this.state = {
    insertError : false,
    errorMessage : '',
    timeToHide  : 3000,
    showListOcc : false,
    showInputOcc : false,
    showSubmitButton: false

  }

}


componentWillMount(){

  switch(this.calData.LAYERID)
  {
      case '19':
            this.setState({
              calExpiryMnts: Math.floor(this.calData.lightningCALTime/60),
              calExpirySec:'00'
            });
      break;
      case '18':
        this.getOccupationData("/api/v2/static/getFieldData?k=occupation&dataType=json");
        this.setState({
          showListOcc : false,
          selectedOption: 'Select your Occupation'
        });
      break;
      case '20':
      case '23':

        this.getCityStateData("/api/v2/static/getFieldData?l=state_res,city_res_jspc,country_res&dataType=json");
        this.setState({
          showListOcc : false,
          selectedOption: 'Select your State',
          selectedCity: 'Select your City',
        });
      break;
  }





}
componentDidMount(){
  switch(this.props.calData.LAYERID)
  {
      case '19':
        this.showTimerForLightningCal();
      break;
  }
}

componentWillUnmount(){
  switch(this.props.calData.LAYERID)
  {
      case '19':
        clearInterval(this.calTimer);
      break;
  }



}

showError(inputString) {
    let _this = this;
    this.setState ({
            insertError : true,
            errorMessage : inputString
    })
    setTimeout(function(){
        _this.setState ({
            insertError : false,
            errorMessage : ""
        })
    }, this.state.timeToHide+100);
}

render()
{
var toReturn;
switch(this.calData.LAYERID)
{
    case '18':
      toReturn =  this.setOccupCityCalData();
    break;

    case '19':
      toReturn =  this.getLightningCAL();
    break;

    case '20':
    case '23':
      toReturn =  this.setOccupCityCalData();
    break;


}

return (<div>{toReturn}</div>);


}



criticalLayerButtonsAction(url,clickAction,button) {
    if(this.CALButtonClicked===1)
      return;
    this.CALButtonClicked=1;
     switch(this.props.calData.LAYERID)
     {

        case '18':
           if(this.state.showInputOcc)
           {
                    var occupText = $i("occInputDivText").value;
                     if (occupText.trim()=='')
                       {
                       this.showError("Please enter occupation");
                       this.CALButtonClicked=0;
                       return;
                       }
                     else {
                       CALCommonCall(url+'?&occupText='+occupText,clickAction,this.props.myjsObj).then(()=>{this.CALButtonClicked=0;});
                       this.CALButtonClicked=0;
                       return;

                     }
           }
           else {
                   commonApiCall(CONSTANTS.EDIT_SUBMIT+'?editFieldArr[OCCUPATION]='+this.state.slctdOccCode,{},'','POST').then((response) =>
                   {
                       if(response.responseStatusCode==1)
                       {
                       this.showError(response.responseMessage);
                       this.CALButtonClicked=0;
                       return false;
                       }
                       CALCommonCall(url,clickAction,this.props.myjsObj).then(()=>{this.CALButtonClicked=0;});
                       return true;
                  });


           }
        break;

        case '20':

                  var params = `?editFieldArr[COUNTRY_RES]=51&editFieldArr[STATE_RES]=${this.state.slctdStateKey}&editFieldArr[CITY_RES]=${this.state.slctdCityCode}`;
                    commonApiCall(CONSTANTS.EDIT_SUBMIT+params,{},'','POST').then((response) =>
                   {
                       if(response.responseStatusCode==1)
                       {
                       this.showError(response.responseMessage);
                       this.CALButtonClicked=0;
                       return false;
                       }
                       CALCommonCall(url,clickAction,this.props.myjsObj).then(()=>{this.CALButtonClicked=0;});
                       return true;
                  });
        break;

        case '23':
                var params = '?';//`?editFieldArr[COUNTRY_RES]=51&editFieldArr[STATE_RES]=${this.state.slctdStateKey}&editFieldArr[CITY_RES]=${this.state.slctdCityCode}`;

                if(this.state.showInputCity)
                {
                         var cityText = $i("cityInputDivText").value;
                          if (cityText.trim()=='')
                            {
                            this.showError("Please enter city");
                            this.CALButtonClicked=0;
                            return;
                            }
                          else
                            params +='&editFieldArr[ANCESTRAL_ORIGIN]='+cityText;
                }
                else
                  params +='&editFieldArr[ANCESTRAL_ORIGIN]='; // blank
                let countryCode;
                if(this.state.slctdStateKey!='-1')
                {
                    countryCode = '51';
                    params += `&editFieldArr[NATIVE_COUNTRY]=51&editFieldArr[NATIVE_STATE]=${this.state.slctdStateKey}&editFieldArr[NATIVE_CITY]=${this.state.slctdCityCode}`;
                }
                else
                  {
                    countryCode = this.state.slctdCityCode;
                    params += `&editFieldArr[NATIVE_COUNTRY]=${countryCode}`;
                  }
                commonApiCall(CONSTANTS.EDIT_SUBMIT+params,{},'','POST').then((response) =>
                 {
                     if(response.responseStatusCode==1)
                     {
                     this.showError(response.responseMessage);
                     this.CALButtonClicked=0;
                     return;
                     }
                     CALCommonCall(url,clickAction,this.props.myjsObj).then(()=>{this.CALButtonClicked=0;});
                     return;
                });
                return;

        break;
      }
    CALCommonCall(url,clickAction,this.props.myjsObj).then(()=>{this.CALButtonClicked=0;});
    return;

}



setOccupCityCalData(){
  let errorView;
  if(this.state.insertError)
  {
    errorView = (<TopError timeToHide={this.state.errorMessage} message={this.state.errorMessage}></TopError>);
  }

return(
        <div>
          {errorView}
          <div style={{ 'backgroundColor': 'rgb(9, 9, 11)',top: '0',right: '0',bottom: '0',left: '0' }} className="fullheight fullwid posfix">
          <div  style={{'paddingTop':'20%', height:(window.innerHeight - 50)}} className="posrel midDiv white">
              <div className="pb10 fontlig f19 txtc">{this.props.calData.TITLE}</div>
               <div className="pad0840 txtc fontlig f16">{this.props.calData.TEXT}</div>
             <div className="pad0840 txtc fontlig f16">{this.props.calData.SUBTEXT}</div>
                <div id="clickDiv" className="wid90p mar0auto bg4 hgt75 mt30 pad25" onClick={()=>{this.setState({showListOcc:true});}}>
                  <div id="selectDiv" className="dispibl wid90p color11 fontlig f18 vtop textTru">{this.state.selectedOption}</div>
                  <div className="wid8p dispibl"><img className="fr" src={CONSTANTS.API_SERVER + "/images/jsms/commonImg/arrow.png" } /></div>
              </div>
              <div id="cityClickDiv" style={{display:this.state.slctdStateKey ? 'block' : 'none'}} className="wid90p mar0auto bg4 hgt75 mt30 pad25" onClick={()=>{this.setState({showCityList:true});}}>
                <div id="citySelectDiv" className="dispibl wid90p color11 fontlig f18 vtop textTru">{this.state.selectedCity}</div>
                <div className="wid8p dispibl"><img className="fr" src={CONSTANTS.API_SERVER + "/images/jsms/commonImg/arrow.png" } /></div>
            </div>
              <div id="contText" style={{display:this.state.hideSelectText ? 'none' : 'block'}} className="fontlig f15 mt10 txtc">Select to continue</div>
              <div id="occInputDiv" className="mt30 txtc dn" style= {{ display : this.state.showInputOcc ? "block" : 'none' }}>
                  <div className="fontlig f15 white">Enter your occupation to continue</div>
                  <div  className="wid90p mar15auto bg4 hgt75 pad25">
                      <input id="occInputDivText" type="text" className="fullwid fl fontlig f18" placeholder="Enter Occupation"  />
                  </div>
              </div>
              <div id="cityInputDiv" className="mt30 txtc dn" style= {{ display : this.state.showInputCity ? "block" : 'none' }}>
                  <div  className="wid90p mar15auto bg4 hgt75 pad25">
                      <input id="cityInputDivText" type="text" className="fullwid fl fontlig f18" placeholder="Please Specify"  />
                  </div>
              </div>

              </div>
          </div>
      <div id="mainListDiv" className="listDivInner bg4 scrollhid dn"  style= {{ 'WebkitOverflowScrolling': 'touch',display : this.state.showListOcc ? "block" : 'none' }}>
        <div className="hgt70 btmShadow selDiv color11 fontlig f18 fullwid">Select</div>
      {this.getListDataOccCityCAL()}
      </div>

          <div id="cityListDiv" className="listDivInner bg4 scrollhid" style= {{ 'WebkitOverflowScrolling': 'touch', display: this.state.showCityList ? 'block':'none'}}>
          <div className="hgt70 btmShadow selDiv color11 fontlig f18 fullwid">Select</div>
          {this.getCityListData()}
      </div>

      <div id="foot" className="posfix fullwid bg7 btmo">
          <div className="scrollhid posrel">
              <input type="submit" id="calSubmit" style= {{ display : this.state.showSubmitButton ? "block" : 'none' }} className="dispnone fullwid dispbl lh50 txtc f18 white" onClick={() => this.criticalLayerButtonsAction(this.props.calData.BUTTON1_URL_ANDROID,this.props.calData.JSMS_ACTION1,'B1')} value="OK" />
          </div>
      </div>
    </div>);
}

getOccupationData(url){
let a =this;
commonApiCall(url,{},'','POST','',false).then((response) => {
  response = response[0];
  a.setState({
    occData : response
  });
  });
}
getCityStateData(url){

commonApiCall(url,'','','POST','',false).then((response) => {console.log(response);
  var stateTemp = response.state_res[0];
  var cityTemp = response.city_res_jspc;
  var countryTemp = response.country_res[0];

  this.setState({stateList : stateTemp,cityList:cityTemp,countryList:countryTemp});

}) ;
}

getListDataOccCityCAL(){
  let innerDiv = '';
  if(this.props.calData.LAYERID==18)
  {

        let liFunction = (key,data)=>{
            let showOccInput ;
            if(key=='43')
            {
              showOccInput= true;
            }
            else
              showOccInput = false;
              this.setState({showListOcc : false,showInputOcc:showOccInput, showSubmitButton:true,slctdOccCode: key,selectedOption:data,hideSelectText:true});

        }

        if(this.state.occData)
          innerDiv = this.getGenericList(this.state.occData,liFunction);
        else innerDiv = (<div id="ListLoader" className="centerDiv"><Loader show="page"></Loader></div>);

  }
  if(this.props.calData.LAYERID == 20 || this.props.calData.LAYERID == 23)
  {
    let liFunction = (key,data)=>{
          var selectText = key==-1 ? 'Select your Country' : 'Select your City';
          this.setState({showInputCity:false,showSubmitButton:false,slctdStateKey: key,selectedOption:data,slctdCityCode: null,selectedCity:selectText,hideSelectText:false,showListOcc : false});
    }

    if(this.state.stateList)
      innerDiv = this.getGenericList(this.state.stateList,liFunction);
    else innerDiv = (<div id="ListLoader" className="centerDiv"><Loader show="page"></Loader></div>);
  }

return innerDiv;
}

getCityListData(){
  let innerDiv = '';
  if(this.props.calData.LAYERID==20 || this.props.calData.LAYERID == 23)
  {

        let liFunction = (key,data)=>{
          let showInputCity = false;
          if(this.props.calData.LAYERID == 23 && key=='0')
              showInputCity= true;
            this.setState({showInputCity:showInputCity,showSubmitButton:true,slctdCityCode: key,selectedCity:data,hideSelectText:true,showCityList : false});
        }

        if(this.state.cityList)
          innerDiv = this.getCityList(liFunction);
        else innerDiv = (<div id="ListLoader" className="centerDiv"><Loader show="page"></Loader></div>);

  }
  return innerDiv;
}
getGenericList(listData,liFunction){

if(this.props.calData.LAYERID == 18)
{
  let innerDiv = (<ul id="occList" style={{paddingLeft: '40px'}} className="occList color11 fontlig f18">
     {
       Object.keys(listData).map( (value,index) => {
         let temp = listData[value];
         var key = Object.keys(temp)[0];
         var occValue = temp[key];
         if(occValue == 'Others') occValue = "I didn't find my occupation";
         return (
        <li key={index} onClick ={()=>liFunction(key,occValue)}>{occValue}</li>
     );
   })

   }
  </ul>);
return innerDiv;
}
else if(this.props.calData.LAYERID == 20 || this.props.calData.LAYERID == 23)
{
  let otherCityDiv =(<div></div>);
  if(this.props.calData.LAYERID == 23) otherCityDiv = (<li key='-1' onClick ={()=>liFunction(-1,'Outside India')}>Outside India</li>);
  let innerDiv = (<ul id="occList" style={{paddingLeft: '40px'}} className="occList color11 fontlig f18">
    {otherCityDiv}
     {
       listData.map( (value,index) => {
         var stateKey = Object.keys(value)[0]
         var stateName = value[stateKey];
         return (
        <li key={index} onClick ={()=>liFunction(stateKey,stateName)}>{stateName}</li>
     );
   })
   }
  </ul>);
return innerDiv;
}

}

getCityList(liFunction){
if(!this.state.slctdStateKey) return (<div></div>);
var obj;

if(this.state.slctdStateKey !=-1)
{
    obj = this.state.cityList[this.state.slctdStateKey][0];
}
else obj = this.state.countryList;
let innerDiv = (<ul id="occList" style={{paddingLeft: '40px'}} className="occList color11 fontlig f18">
   {
     obj.map( (value,index) => {
       var cityKey = Object.keys(value)[0];
       var cityName = value[cityKey];
       if(cityKey=='51')
        {
          return ('');
        }

       return (
      <li key={index} onClick ={()=>liFunction(cityKey,cityName)}>{cityName}</li>
    );
 })
 }
</ul>);
return innerDiv;


}


////////////LIGHTNING CAL BEGINS///////////////
getLightningCAL(){

return (<div style={{backgroundColor: '#09090b',height: window.innerHeight}}>
<div  className="posrel pad18Incomplete">

<div className="br50p txtc" style={{ height:'80px'}}>
  </div>

</div>

<div className="txtc">
<div className="fontlig white f20 pb20 color16 ">{this.calData.TITLE}</div>
<div className="pad1 lh25 fontlig calf27 calcol1">{this.calData.discountPercentage}</div>
<div className="pad1 lh25 fontlig f20 calcol1 pb20">{this.calData.discountSubtitle}</div>
<div className="white fontlig f16 pb30">
<span className="" >{this.calData.startDate + " "}</span>
<span className="calcol1 lineth" >{this.calData.symbol=='&#8377;' ? '\u20B9' : this.calData.symbol}{this.calData.oldPrice + " "}</span>
<span className="" >{this.calData.symbol=='&#8377;' ? '\u20B9' : this.calData.symbol}{this.calData.newPrice}</span>
</div>
</div>
<div className="white txtc mar0auto pb30" style={{width: '60%'}}>
  <p className="f16 pt20">Hurry! Offer valid for</p>
              <ul className="time">
                <li className="inscol"><span id = "calExpiryMnts">{this.state.calExpiryMnts}</span><span>M</span></li>
                  <li className="pl10"><span id = "calExpirySec">{this.state.calExpirySec}</span><span>S</span></li>
              </ul>
</div>
<div style={{padding: '25px 0 8% 0'}}>
<div id='CALButtonB1' className="bg7 f18 white lh30 fullwid dispbl txtc lh50" onClick={() => this.criticalLayerButtonsAction(this.props.calData.BUTTON2_URL_ANDROID,this.props.calData.JSMS_ACTION2,'B2')}>{this.calData.BUTTON2}</div>
</div>
<div id='CALButtonB2' onClick={() => this.criticalLayerButtonsAction(this.props.calData.BUTTON2_URL_ANDROID,this.props.calData.JSMS_ACTION2,'B2')} style={{color:'#cccccc', paddingTop: '20px'}} className="pdt15 pb10 txtc white f14">{this.calData.BUTTON2}</div>

</div>);
}

updateCalTimer()
{
  var s = this.calTimerTime.getSeconds();
  var m = this.calTimerTime.getMinutes();
  if (!m && !s ) {
     clearInterval(this.calTimer);
     }

    this.calTimerTime.setSeconds(s-1);


    m = this.formatTime(m);
    s = this.formatTime(s);
    this.setState({
      calExpiryMnts:m,
      calExpirySec:s
  });
}

formatTime(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

showTimerForLightningCal() {
this.calTimerTime=new Date(1800); //////just initialise with any time
this.calTimerTime.setMinutes(this.state.calExpiryMnts);
this.calTimerTime.setSeconds(0);
let thisObject= this;
this.calTimer=setInterval(this.updateCalTimer.bind(thisObject),1000);
}


////////////LIGHTNING CAL ENDS///////////////


}
