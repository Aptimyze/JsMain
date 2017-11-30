import React from "react";
import asyncComponent from '../../common/components/asyncComponent';
import Loader from "../../common/components/Loader";
import {getParameterByName} from '../../common/components/UrlDecoder';
import {commonApiCall} from "../../common/components/ApiResponseHandler.js";

const CalComp1 = asyncComponent(() => import('./CalComp1')
    .then(module => module.default), { name: 'calJSMS1' });
const CalComp2 = asyncComponent(() => import('./CalComp2')
        .then(module => module.default), { name: 'calJSMS2' });
const CalComp3 = asyncComponent(() => import('./CalComp3')
    .then(module => module.default), {name: 'calJSMS3'});

export default class calObjectClass extends React.Component
{
  constructor(props) {
    super(props);
//    this.calData = this.props.calData;
    this.calCompArray1  = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','22','27'];

    this.calCompArray2  = ['18','19','20','23','26'];
    this.calCompArray3 = ['24'];
    this.error=false;
    if(!this.props.calData)
    {
      let layerId = getParameterByName(window.location.href,'layerId');
      if(isNaN(parseInt(layerId)))
      {
        this.error = true;
      }
      else
      {
        this.layerId = layerId;
      }
  }
    this.state={
      calData : this.props.calData,
      error : this.error ? true: false,
      layerId : this.layerId ? this.layerId : null
    };

  }
  render() {

      if(!this.state.error)
      {
          if(!this.state.calData)return (<div><Loader show="page"></Loader></div>);
          if(this.calCompArray1.indexOf(this.state.calData.LAYERID) != -1) {
            return(<div><CalComp1 myjsObj={this.props.myjsObj} calData={this.state.calData}/></div>);
          } else if(this.calCompArray2.indexOf(this.state.calData.LAYERID) != -1){
            return(<div><CalComp2 myjsApiHit={this.props.myjsApiHit} myjsObj={this.props.myjsObj} calData={this.state.calData}/></div>);
          }
          else if(this.calCompArray3.indexOf(this.state.calData.LAYERID) != -1){
            return(<div><CalComp3 myjsApiHit={this.props.myjsApiHit} myjsObj={this.props.myjsObj} calData={this.state.calData}/></div>);
          }

      }
      if(typeof this.props.myjsObj == 'function')
        this.props.myjsObj();
      return (<div></div>);
  }

  componentDidMount(){
    let _this=this;
    if(this.state.layerId)
    commonApiCall('/static/getCALData',{layerId:this.state.layerId}).then((response)=>{
      if(response.calObject)
      _this.setState({
        calData : response.calObject
      });
      else {
        _this.setState({
            error : true
        });
      }
    });

  }

}
