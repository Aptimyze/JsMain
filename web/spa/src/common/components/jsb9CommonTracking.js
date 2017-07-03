import * as CONSTANTS from '../../common/constants/CommonConstants';
import { connect } from "react-redux";
import React from "react";

export class jsb9CommonTracking extends React.Component{

  constructor(props){
    super(props);
    console.log('cons common trackRedirectionJsb9');
  }

  setJsb9Key(optionaljsb9Key){
    this.optionaljsb9Key = optionaljsb9Key;
  }

  recordRedirection(time, url){
    if(CONSTANTS.JSB9_UNLOAD_TRACKING)
      console.log('record redirectio');
      console.log(this.props);
    //  this.props.recordJsb9Time({'REDIRECTION' :time,'REFERER_URL' : url});
  }

  recordBundleReceived(time){
    this.props.recordJsb9Time({'BUNDLE_RECEIVED' : time});
  }

  recordServerResponse(apiResponse){
    this.props.recordJsb9Time({'API_RESPONSE_TIME' : apiResponse});
  }

  recordDataReceived(time){
    this.props.recordJsb9Time({'DATA_RECEIVED' : time});
  }

  recordDidMount(time){
      this.props.recordJsb9Time({'VIEW_MOUNTED' : time});
  }

  jsb9TrackApi() {

          var reducerData = this.props.jsb9Reducer;
          var prevTime = reducerData.REDIRECTION ? reducerData.REDIRECTION : "-1" ;
          var refererUrl = reducerData.REFERER_URL ? reducerData.REFERER_URL : "-1" ;
          var jsb9Iframe = document.createElement('div');
          jsb9Iframe.id = 'jsb9Div';
          var style = 'border:0;width:0;height:0;display:none';
          var apiResponseTime = reducerData.API_RESPONSE_TIME;
          var presentUrl = window.location.href;
          var bundleReceivedTime = reducerData.BUNDLE_RECEIVED;
          var dataReceivedTime = reducerData.DATA_RECEIVED;
          var didMountTime = reducerData.VIEW_MOUNTED;
          //Removing | and : from present url and referer url from variable.
          presentUrl=presentUrl.replace(/\|/g,"");
          refererUrl=refererUrl.replace(/\|/g,"");
          presentUrl=presentUrl.replace(/\:/g,"");
          refererUrl=refererUrl.replace(/\:/g,"");

          var data = presentUrl+"|"+refererUrl+"|"+prevTime+"|"+bundleReceivedTime+"|"+dataReceivedTime+"|"+didMountTime+"|"+apiResponseTime;
          if(this.optionaljsb9Key)
              data = data+"|"+this.optionaljsb9Key;
          //remove '#'
          data = data.replace(/#/g,'');
          jsb9Iframe.innerHTML = '<iframe border="0" height=0 width=0 style="visibility: hidden" src="https://track.99acres.com/images/zero.gif?data='+data+'"></iframe>';

          document.getElementsByTagName("HEAD")[0].appendChild(jsb9Iframe);

  }

}
const mapStateToProps = (state) => {
    return{
       jsb9Data: state.jsb9Reducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
      recordJsb9Time: (trackingJson) => {
        dispatch({
          type: 'SET_TIME',
          payload: {}
        });
      }

    }
}

export default connect(mapStateToProps,mapDispatchToProps)(jsb9CommonTracking);
