import React from "react";
import {Link} from "react-router-dom";
import Loader from "../../common/components/Loader";
import MyjsSliderBinding from "../components/MyjsSliderBinding";
import ContactEngineButton from "../../contact_engine/containers/contactEngine";
import { connect } from "react-redux";


export class MyjsSlider extends React.Component {

  constructor(props) {
    super(props);
    this.sliderTupleStyle = {'whiteSpace': 'nowrap','marginLeft':'10px','fontSize':'0px','overflowX':'hidden','display': 'inline-block'};
    this.state={
      'sliderBound' :false,
      'sliderStyle' :this.sliderTupleStyle,
      tupleWidth : {'width' : window.innerWidth},
      loaderStyles:[],
      divStyles:[],
      total : props.listingName == 'dailymatches'?props.listing.no_of_results:props.listing.total,
      MyjsThumb:''
    }
  }

componentDidUpdate(){
  this.bindSlider();
  if(this.props.listing.nextpossible=='false' && this.obj)
    this.obj.setIndexElevate(0);

}

componentDidMount(){
  if(this.props.mountFun)this.props.mountFun();
  this.props.restApiFun();
  this.bindSlider();
}
componentWillUnmount() {
  this.props.history.prevUrl = this.props.location.pathname;
}

 componentWillReceiveProps(nextProps){
   if(!nextProps.listing.profiles)return;
    this.setState({
      total : nextProps.listingName == 'dailymatches'?nextProps.listing.profiles.length:nextProps.listing.total
    });
   if(nextProps.listing.profiles.length != this.props.listing.profiles.length)
   {
     this.setState({
       loaderStyles:[],
       divStyles:[]

     });
     this.obj.resetSlider(nextProps.listing.profiles);

   }

}
removeMyjsTuple(index){

  let e = document.getElementById(this.props.listing.infotype+"_"+index);
  let _this=this;
  this.setState((prevState)=>{prevState.divStyles[index] = 'setop0';return prevState; });
  setTimeout(function(){_this.props.spliceIndex(_this.props.listing.infotype,index); }, 1000);

}


bindSlider(){
  if( this.state.sliderBound || !this.props.fetched || !this.props.listing.profiles)return;
  let elem = document.getElementById(this.props.listing.infotype+"_tuples");
  if(!elem)return;
  this.obj = new MyjsSliderBinding(elem,this.props.listing.profiles ? this.props.listing.profiles : this.props.listing.tuples ,{styleFunction:this.alterCssStyle.bind(this)},0,this.props.listing.infotype == 'INTEREST_RECEIVED'? 1:0,
      this.props.apiNextPage);
  this.obj.initTouch();
  this.setState({
    sliderBound: true,
    tupleWidth : {'width' : this.obj.transformX-10}
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

render(){
  if(!this.props.fetched || !this.props.listing.responseStatusCode || !this.props.listing.profiles || !this.props.listing.profiles.length ) {
    return <div></div>;
  }

  return(

      <div>

        <div className="pad1" style = {{marginTop: '15px'}}>
          <div className="fullwid pb10">
            <div className="fl color7">
              <span className="f17 fontlig">{this.props.title}</span>
              <div id="matchAlert_count" className="opa50 f14 dispibl padl5">{this.state.total}</div>
            </div>
            <div className="fr pt5"> <a href={this.props.url} className="f14 color7 opa50 icons1 myjs_arow1">View all </a> </div>
            <div className="clr"></div>
          </div>
          <Loader loaderStyles={{'position': 'relative','margin': '0px auto','display': 'none'}} />
          <div className="swrapper" id="swrapper">
            <div className="wrap-box" id={"wrapbox_"+this.props.listingName}>
              <div id={this.props.listing.infotype+"_tuples"}   style={this.state.sliderStyle}>
              {
                [this.props.listing.profiles.map((tuple,index) => {

                  if(tuple.dontShow)return(<div></div>);
                    if(tuple.profilepic120url==undefined)
                    {

                      if(tuple.photo.url=="null")
                      {
                        if(tuple.gender=="F")
                        {
                         this.state.MyjsThumb = "https://static.jeevansathi.com/images/picture/120x120_f.png?noPhoto";
                        }
                        else
                        {
                         this.state.MyjsThumb = "https://static.jeevansathi.com/images/picture/120x120_m.png?noPhoto";
                        }
                      }
                      else
                      {
                        this.state.MyjsThumb = tuple.photo.url;
                      }
                    }
                    else
                    {

                      if(tuple.profilepic120url=="null")
                      {
                        if(tuple.gender=="F")
                        {
                         this.state.MyjsThumb = "https://static.jeevansathi.com/images/picture/120x120_f.png?noPhoto";
                        }
                        else
                        {
                         this.state.MyjsThumb = "https://static.jeevansathi.com/images/picture/120x120_m.png?noPhoto";
                        }
                      }
                      else
                      {
                        this.state.MyjsThumb = tuple.profilepic120url;
                      }

                    }
                    let profileUrl = '';
                    if ( this.props.listingName != 'dailymatches')
                    {
                      profileUrl = `/profile/viewprofile.php?profilechecksum=${tuple.profilechecksum}&${this.props.listing.tracking}&total_rec=${this.state.totalOffset}&actual_offset=${index}&searchid=${this.props.listing.searchid}&contact_id=${this.props.listing.contact_id}&${tuple.buttonDetails.buttons[0].params}`;
                    }
                    else
                    {
                      profileUrl = `/profile/viewprofile.php?profilechecksum=${tuple.profilechecksum}&${this.props.listing.tracking}&total_rec=${this.state.totalOffset}&actual_offset=${index}&searchid=${this.props.listing.searchid}&contact_id=${this.props.listing.contact_id}&${tuple.buttonDetails.buttons[0].params}&listingName=${this.props.listingName}&hitFromMyjs=${this.props.hitFromMyjs}`;

                    }


                  return (
                <div key={index} className={"mr10 dispibl ml0 posrel rmtuple " + (this.state.divStyles[index] ? this.state.divStyles[index] : '')} style={this.state.tupleWidth} id={this.props.listing.infotype+"_"+index} >
                  <div className="bg4 overXHidden" id="hideOnAction">
                    <Link  to={profileUrl}>
                      <div className="pad16 scrollhid hgt140">
                        <div className="overXHidden fullheight">
                          <div className="whitewid200p overflowWrap">
                            <div className="fl">
                              <img className="tuple_image hgtwid110" src={this.state.MyjsThumb} />
                            </div>
                            <div className="fl pl_a" style={{'width':'48%'}}>
                              <div className="f14 color7">
                                <div className="username textTru">
                                  {tuple.name_of_user ? tuple.name_of_user : tuple.username}
                                </div>
                              </div>
                              <div className="attr">
                                <ul>

                                  <li className="textTru">
                                    <span className="tuple_title">{tuple.occupation}</span>
                                  </li>
                                  <li className="textTru">
                                    <span className="tuple_age">{tuple.age}</span> Years  <span className="tuple_height">{tuple.height}</span>
                                  </li>
                                  <li className="textTru">
                                    <span className="tuple_caste whtSpaceNo">{tuple.caste}</span>
                                  </li>
                                  <li className="textTru">
                                    <span className="tuple_mtongue">{tuple.mtongue}</span>
                                  </li>
                                  <li className="textTru">
                                    <span className="tuple_education">{tuple.edu_level_new}</span>
                                  </li>



                                </ul>
                              </div>
                            </div>
                            <div className="clr"></div>
                          </div>
                        </div>
                      </div>
                    </Link>
                    <div onClick={() => this.setState((prevState)=>{prevState.loaderStyles[index]={};prevState.loaderStyles[index].display='block';return prevState;})}>

                    <ContactEngineButton buttondata={tuple} buttonName={this.props.listingName} callBack={()=>this.removeMyjsTuple(index)} button={tuple.buttonDetails.buttons} profilechecksum={tuple.profilechecksum} pagesrcbtn="myjs" tupleID={this.props.listing.infotype+"_"+index}   />

                    </div>
                </div>
                <img style={this.state.loaderStyles[index] ? this.state.loaderStyles[index] : {} } src='http://static.jeevansathi.com/images/jsms/commonImg/loader.gif' className="posabs setmid dispnone" />

           </div>
         );}),this.props.showLoader=='1' ? (<div key = '-1' className={"mr10 ml0 posrel " + (this.props.listing.nextpossible=='true' ? 'dispibl' :  'dispnone') }  style={this.state.tupleWidth} id="loadingMorePic"><div className="bg4"><div className="row minhgt199"><div className="cell vmid txtc pad17"><i className="mainsp heart"></i><div className="color3 f14 pt5">Loading More Interests</div></div></div></div> </div>) : (<div key='-1' ></div>) ]}
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
     contact: state.contactEngineReducer
    }
}

 const mapDispatchToProps = (dispatch) => {
     return{
       spliceIndex: (infotype,index)=> dispatch({'type': 'SPLICE_MYJS_DATA', payload: {index:index, infotype: infotype}})
     }


 }

export default connect(mapStateToProps,mapDispatchToProps)(MyjsSlider)
