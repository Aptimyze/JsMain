import React from "react";
import {Link} from "react-router-dom";
import Loader from "../../common/components/Loader";
import MyjsSliderBinding from "../components/MyjsSliderBinding";

var slides1={
  "whiteSpace": "nowrap",
  "marginLeft": "10px",
  "fontSize": "0px",
  "overflowX": "hidden",
  "width": "5783.2px",
  "transitionDuration": "0.5s",
  "transform": "translate3d(0px, 0px, 0px)"
}
var sliderTupleStyle = {'whiteSpace': 'nowrap','marginLeft':'10px','fontSize':'0px','overflowX':'hidden','width':'200%'};
var slides2={
  "width": "329.6px"
}
var slides3={
  "width": "48%"
}

export default class MyjsSlider extends React.Component {
  constructor(props) {
    super(props);
    this.state={
      'sliderBound' :false,
      'sliderStyle' :{'whiteSpace': 'nowrap','marginLeft':'10px','fontSize':'0px','overflowX':'hidden','width':'200%'}

    }
  }

  alterCssStyle(transform,transitionDuration){
        var styleObj = [];
        styleObj['-' + this.props.cssProps.cssPrefix + '-transition-duration'] = transitionDuration + 'ms';
        var propValue = 'translate3d(-' + transform + 'px, 0, 0)';
        styleObj[this.props.cssProps.animProp] =  propValue;
        this.setState({
          'sliderStyle' : {
            ...sliderTupleStyle,
            ...styleObj
          }
         });
  }

  componentDidMount(){  let _this = this;

    if(this.state.sliderBound)return;
    this.obj = new MyjsSliderBinding(document.getElementById("interest_received_tuples"),this.props.listing,this.props,this.alterCssStyle.bind(this),this.props.cssProps);
    this.obj.initTouch();
    this.setState({sliderBound: true});
    //,sliderStyle: {...this.state.sliderStyle,this.obj.cssStyle}
  }

  render(){
    if(!this.props.listing.tuples) {
      return <div></div>;
    }
    return(
      <div>
        <div className="pad1" style = {{marginTop: '15px'}}>
          <div className="fullwid pb10">
            <div className="fl color7"> <span className="f17 fontlig">{this.props.listing.title}</span>&nbsp;<span id='matchAlert_count' className="opa50 f14">{this.props.listing.view_all_count}</span> </div>
            <div className="fr pt5"> <a href="/inbox/7/1" className="f14 color7 opa50 icons1 myjs_arow1">View all </a> </div>
            <div className="clr"></div>
          </div>
        <Loader loaderStyles={{'position': 'relative','margin': '0px auto','display': 'none'}} />

            <div className="swrapper" id="swrapper">
                <div className="wrap-box" id="wrapbox_{this.props.listingName}">
         <div id={this.props.listingName+"_tuples"}   style={this.state.sliderStyle}>
           {this.props.listing.tuples.map( (tuple,index) => (
             <Link to={`/profile/viewprofile.php?profilechecksum=${tuple.profilechecksum}&${this.props.listing.tracking}&total_rec=${this.props.listing.view_all_count}&actual_offset=${index}&contact_id=${this.props.listing.contact_id}`}>

           <div key={index} className="mr10 dispibl ml0 posrel wid300" id="" ><input className="proChecksum" type="hidden" value="{tuple.profilechecksum}"></input><img className="srp_box2 contactLoader posabs dispnone top65" src="/images/jsms/commonImg/loader.gif" />
             <div className="bg4 overXHidden" id="hideOnAction">
               <Link  to={`/profile/viewProfile?profilechecksum=${tuple.profilechecksum}&${this.props.listing.tracking}&total_rec=${this.props.listing.view_all_count}&actual_offset=${index}&contact_id=${this.props.listing.contact_id}`}>
                 <div className="pad16 scrollhid hgt140">
                   <div className="overXHidden fullheight">
                     <div className="whitewid200p overflowWrap">
                       <div className="fl"><img className="tuple_image hgtwid110" src={tuple.photo.url} /> </div>
                       <div className="fl pl_a" style={{'width':'48%'}}>
                         <div className="f14 color7">
                           <div className="username textTru">{tuple.name_of_user ? tuple.name_of_user : tuple.username}</div>
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
               <div className="brdr8 fullwid hgt60">
                 <div className="txtc fullwid fl matchOfDayBtn brdr7 pad2" ><input className="inputProChecksum" type="hidden" value="{elem.profilechecksum}"></input><span className="f15 color2 fontreg">Send Interest</span></div>
                 <div className="clr"></div>
               </div>
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
