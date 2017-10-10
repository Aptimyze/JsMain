import React from "react";
import asyncComponent from '../../common/components/asyncComponent';

const CalComp1 = asyncComponent(() => import('./CalComp1')
    .then(module => module.default), { name: 'calJSMS1' });
const CalComp2 = asyncComponent(() => import('./CalComp2')
        .then(module => module.default), { name: 'calJSMS2' });

export default class calObjectClass extends React.Component
{
  constructor(props) {
    super(props);
    this.calData = this.props.calData;
    this.calCompArray1  = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','22','27'];
    this.calCompArray2  = ['18','19','20','23'];
  }
  render() {
    if(this.calCompArray1.indexOf(this.calData.LAYERID) != -1) {
      return(<div><CalComp1 myjsObj={this.props.myjsObj} calData={this.props.calData}/></div>);
    } else if(this.calCompArray1.indexOf(this.calData.LAYERID) != -1){
      return(<div><CalComp2 myjsApiHit={this.props.myjsApiHit} myjsObj={this.props.myjsObj} calData={this.props.calData}/></div>);
    }
    else this.props.myjsObj();
  }

}
