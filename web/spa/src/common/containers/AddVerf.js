import React from 'react';
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';
import { connect } from "react-redux";
import { getParameterByName } from "../../common/components/UrlDecoder";

export class Addverf extends React.Component{

  constructor(props)
  {
    super(props);
    this.layerId = '24';
    this.state = {
      paramSucc: false,
      paramfail: false,
      errMsg:'',
      pageS:''

    }
    this.buttonclicked =0;
  }

  componentDidMount()
  {
    this.getUrlParams(window.location.href)
  }
  getUrlParams(param)
  {
    var errMsg = getParameterByName(param,"error_message");
    var success = getParameterByName(param,"success");
    var pages = getParameterByName(param,"pagesource");
    if(success == "false"){
      this.setState({
        paramfail: true,
        errMsg: errMsg,
        pageS: pages
      })
    }
    else{
      this.setState({
        paramfail: false,
        paramSucc: true,
        pageS: pages
      })   
    }      
  }

  getUrlParamsRetry()
  {
    let param=window.location.href;
    var fromEdit = getParameterByName(param,"fromEdit");
    if(fromEdit == "1#cal"){
      return 1;
    }
    else{
      return 0;
    }
  }
  
  submitAData()
  {
    if(this.buttonclicked) return ;
    this.buttonclicked=1;
  let fromEdit = this.getUrlParamsRetry();
  if(fromEdit){
    fromEdit="JSMSdashboard2";
  }
  else if (this.state.pageS == 'ocbJSMS'){
    fromEdit="JSMSdashboard3"; 
  }
  else{
    fromEdit="JSMSdashboard";
  }
  let pagesource_a = "?fromPage="+fromEdit;
  
  commonApiCall(CONSTANTS.AADH_API+pagesource_a,{},'','POST').then(function(response){
       
      if(response.responseStatusCode=='0')
      {
      window.location.href = response.url+"?request_id="+response.request;
      this.buttonclicked=0;
      }
    });
  }
  redirectA()
  {
    let buttonAction;
    if(this.state.paramSucc)
      buttonAction="B1";
    else
      buttonAction="B2";
    if((!this.state.paramSucc && this.state.pageS == 'myjsJSMS') && !(this.state.pageS == 'ocbJSMS')){
          
          commonApiCall('/api/v1/common/criticalActionLayerTracking?layerR='+this.layerId+'&button='+buttonAction).then(()=>{
              this.redirectAfterClick();
           });            
      }
      else{
        this.redirectAfterClick();
      }

  }
  //Critical layer entry before clicking continue
  layerEntryOnSuccess(){
              var buttonAction="B1";
              commonApiCall('/api/v1/common/criticalActionLayerTracking?layerR='+this.layerId+'&button='+buttonAction);

  }
  //Redirect to myjs or edit based on the parameter from third party
  redirectAfterClick(){
         if(this.state.pageS == 'myjsJSMS' || this.state.pageS == 'ocbJSMS') 
              this.props.history.push('/login');
          else{
            window.location.href= '/profile/viewprofile.php?ownview=1#Details';
          }
  }
     

  render(){
    let midContent = '',button='',skipbtn='';
    if(this.state.paramSucc)
    {
      if(this.state.pageS == 'myjsJSMS'){
      this.layerEntryOnSuccess();
    }
      midContent = <div>
                    <p className="f16 fontmed pb20">Verification Successful</p>
                    <img src="/spa/src/img/Fill411.png"/>
                    <p className="f14 fontreg pt10 wid80p">Thank you for verifying your Aadhaar number. A verification shield will now be shown against your profile in all the listings depicting that your ‘Aadhaar number is verified’</p>
                   </div>;
      button = <div className="pad16">
                <button onClick={() => this.redirectAfterClick()} className="fullwid txtc bg7 lh50 white fontmed border0 f14">
                  CONTINUE
                </button>
              </div>;
    }
    else
    {
      midContent = <div>
                    <p className="f16">Verification Failed</p>
                    <p className="pt20">{this.state.errMsg}</p>
                   </div>;
      skipbtn = <div  id='CALButtonB2' className="txtc">
                  <div className="white opa50" onClick={()=>this.redirectA()}>SKIP</div>
                </div>;
      button = <div id='CALButtonB1' className="pad16">
                <button className="fullwid txtc bg7 lh50 white fontmed border0 f14" onClick={() => this.submitAData()}>
                  TRY AGAIN
                </button>
               </div>
    }

    return(
      <div className="bg14 fullwid" style={{'height': window.innerHeight}} >
        <div className="setmid white posfix wid90p txtc">
          {midContent}
        </div>
        <div className="posfix btmo10 fullwid f16">
          {skipbtn}
          {button}
        </div>
      </div>
    )

  }
}

const mapStateToProps = (state) => {
    return{
       historyObject : state.historyReducer.historyObject
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(Addverf)
