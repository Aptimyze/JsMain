import React from "react";
import {Link} from "react-router-dom";
import Loader from "../../common/components/Loader";
import MyjsSliderBinding from "../components/MyjsSliderBinding";
import { connect } from "react-redux";
import { commonApiCall } from "../../common/components/ApiResponseHandler";
import * as CONSTANTS from '../../common/constants/apiConstants';


export class MyjsSlider extends React.Component {
  constructor(props) {
    super(props);
    this.sliderTupleStyle = {'whiteSpace': 'nowrap','marginLeft':'10px','fontSize':'0px','overflowX':'hidden','display': 'inline-block'};

    this.state={
      'sliderBound' :false,
      'sliderStyle' :this.sliderTupleStyle,
      tupleWidth : {'width' : window.innerWidth}

    }
    console.log("mm",props.listing.infotype)
  
  }

componentDidUpdate(){
  this.bindSlider();
}

componentDidMount(){ // console.log('did mount myjs');console.log(this.props.listing.tuples[0].profilechecksum);
  this.bindSlider();
}

componentWillReceiveProps(nextProps){
  if(nextProps.contact.contactDone) {
    console.log('interest sent');
  }    
}

bindSlider(){
  if( this.state.sliderBound || !this.props.fetched || !this.props.listing.profiles)return;
  let elem = document.getElementById(this.props.listing.infotype+"_tuples");
  if(!elem)return;
  this.obj = new MyjsSliderBinding(elem,this.props.listing.profiles ? this.props.listing.profiles : this.props.listing.tuples  ,this.alterCssStyle.bind(this));
  this.obj.initTouch();
  this.setState({
    sliderBound: true,
    upleWidth : {'width' : this.obj.transformX-10}
  });
}

alterCssStyle(duration, transform){
  this.setState((prevState)=>{
    var styleArr = Object.assign({}, prevState.sliderStyle);
    styleArr[this.props.cssProps.cssPrefix + 'TransitionDuration'] = duration + 'ms';
    var propValue = 'translate3d(' + transform + 'px, 0, 0)';
    styleArr[this.props.cssProps.animProp] =  propValue;
    prevState.sliderStyle =styleArr;
    return prevState;
  });
}
getButtonView(tuple) {
  if(this.props.listing.infotype == "INTEREST_RECEIVED_FILTER") {
    console.log("yess",tuple)
    return (<div className="brdr8 fl wid90p hgt60">
                  <div className="txtc wid49p fl eoiAcceptBtn brdr7 pad2">
                    <input className="inputProChecksum" type="hidden" value={tuple.profilechecksum} />
                      <a className="f15 color2 fontreg">Accept</a>
                  </div>
                  <div className="txtc wid49p fl f15 pad2 eoiDeclineBtn">
                    <input className="inputProChecksum" type="hidden" value={tuple.profilechecksum} />
                    <a className="f15 color2 fontlig">Decline</a>
                  </div>
                  <div className="clr"></div>
                </div>);
      } 
      else {
          return "";
      }

}

render(){
  var buttonView;
  console.log()


   /* buttonView = <div className="brdr8 fullwid hgt60">
                      <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" onClick={() => this.props.contactApi(tuple.profilechecksum)}>
                        <input className="inputProChecksum" type="hidden" value="{tuple.profilechecksum}"></input>
                        <span className="f15 color2 fontreg">Send Interest</span>
                      </div>
                      <div className="clr"></div>
                    </div>;*/
  if(!this.props.fetched || !this.props.listing.profiles) {
    return <div></div>;
  }
  return(
      <div>
        <div className="pad1" style = {{marginTop: '15px'}}>
          <div className="fullwid pb10">
            <div className="fl color7">
              <span className="f17 fontlig">{this.props.title}</span>
              &nbsp;
              <span id='matchAlert_count' className="opa50 f14">{this.props.listing.total}</span>
            </div>
            <div className="fr pt5"> <a href="/inbox/7/1" className="f14 color7 opa50 icons1 myjs_arow1">View all </a> </div>
            <div className="clr"></div>
          </div>
          <Loader loaderStyles={{'position': 'relative','margin': '0px auto','display': 'none'}} />
          <div className="swrapper" id="swrapper">
            <div className="wrap-box" id={"wrapbox_"+this.props.listingName}>
              <div id={this.props.listing.infotype+"_tuples"}   style={this.state.sliderStyle}>
              {this.props.listing.profiles.map((tuple,index) => (
                <div key={index} className="mr10 dispibl ml0 posrel" style={this.state.tupleWidth} id="" >
                  <input className="proChecksum" type="hidden" value={tuple.profilechecksum}></input>
                  <img className="srp_box2 contactLoader posabs dispnone top65" src="/images/jsms/commonImg/loader.gif" />
                  <div className="bg4 overXHidden" id="hideOnAction">
                    <Link  to={`/profile/viewprofile.php?profilechecksum=${tuple.profilechecksum}&${this.props.listing.tracking}&total_rec=${this.props.listing.total}&actual_offset=${index}&searchid=${this.props.listing.searchid}&contact_id=${this.props.listing.contact_id}`}>
                      <div className="pad16 scrollhid hgt140">
                        <div className="overXHidden fullheight">
                          <div className="whitewid200p overflowWrap">
                            <div className="fl">
                              <img className="tuple_image hgtwid110" src={tuple.photo.url} />
                            </div>
                            <div className="fl pl_a" style={{'width':'48%'}}>
                              <div className="f14 color7">
                                <div className="username textTru">
                                  {tuple.name_of_user ? tuple.name_of_user : tuple.username}
                                </div>
                              </div>
                              <div className="attr">
                                <ul>
                                   <li className="textTru"><span className="tuple_title">{tuple.tuple_title_field}</span> </li>
                                   <li className="textTru"><span className="tuple_age">{tuple.age}</span> Years <span className="tuple_height"> {tuple.height} </span> </li>
                                   <li className="textTru"><span className="tuple_caste whtSpaceNo">{tuple.caste}</span></li>
                                   <li className="textTru"><span className="tuple_mtongue">{tuple.mtongue}</span></li>
                                   <li className="textTru"><span className="tuple_income">{tuple.income}</span></li>
                                </ul>
                              </div>
                            </div>
                            <div className="clr"></div>
                          </div>
                        </div>
                      </div>
                    </Link>
                    {this.getButtonView(tuple)}
                </div>
           </div>
         ))}
         <div className="clr"></div>
         </div>
       </div>
       </div>
     </div>
   </div>);

  }
}

const mapStateToProps = (state) => {
    return{
     contact: state.MyjsReducer
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        contactApi: (profilechecksum) => {
          var url = '&stype=15&myjs=1&profilechecksum='+profilechecksum;
          return commonApiCall(CONSTANTS.CONTACT_ENGINE_URL,url,'CONTACT_ACTION','POST',dispatch,true);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(MyjsSlider)

