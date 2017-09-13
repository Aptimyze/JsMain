import React from "react";

export default class EditBar extends React.Component {
  constructor(props) {
        super();
        this.state ={};
  }

  render(){
    if(!this.props.fetched) {
      return <div></div>;
    }
    return(
        <EditBarPresent profileInfo = {this.props.profileInfo} cssProps={this.props.cssProps} />
      );

}
}
export class EditBarPresent extends React.Component {
  constructor(props) {
        super();
        this.state ={};
  }


  componentWillMount()
  {
  }

componentDidMount(){
  this.setState({
    pc_temp1 : 0,pc_temp2 : 0,t1 : null,profileCompletionCount : 0,limit:parseInt(this.props.profileInfo.completion), loaderStyle :null, loaderStyle2:null
  });
  var arr = [];
  this.setState({
    loaderStyle : arr,
    loaderStyle2 : arr
  });
  this.profile_completion();
}

componentWillUnmount(){
  clearInterval(this.state.t1);
  clearInterval(this.state.t2);
}

  start1()
  {
  	if (this.state.profileCompletionCount >= this.state.limit) {
  		clearInterval(this.state.t1);
  		return;
  	}
    var arr = [];
    arr[this.props.cssProps.animProp] = "rotate(" + this.state.pc_temp1 + "deg)";
    this.setState((prevState, props) => {
      let profileCompletionCount = prevState.profileCompletionCount + 1, pc_temp1 = prevState.pc_temp1 - 3.6
      var arr = [];
      arr[this.props.cssProps.animProp] = "rotate(" + pc_temp1 + "deg)";
      return ({
        profileCompletionCount: profileCompletionCount,
        pc_temp1: pc_temp1,
        loaderStyle : arr
      })
    });

    if (this.state.profileCompletionCount == 50) {
      let thisObj = this;
  		clearInterval(this.state.t1);
        this.setState({
            t2 : setInterval(this.start2.bind(thisObj), 30)
          });
  	}


  	// document.getElementById("percent").html(this.state.profileCompletionCount + "%");
  	// $(".pie2").css("-o-transform", "rotate(" + pc_temp1 + "deg)").css("-moz-transform", "rotate(" + pc_temp1 + "deg)").css("-webkit-transform", "rotate(" + pc_temp1 + "deg)");
  };


  start2(){
  	if (this.state.profileCompletionCount >= this.state.limit) {
  		clearInterval(this.state.t2);
  		return;
  	}
    this.setState((prevState, props) => {
      let profileCompletionCount = prevState.profileCompletionCount + 1, pc_temp2 = prevState.pc_temp2 - 3.6
      var arr = [];
      arr[this.props.cssProps.animProp] = "rotate(" + pc_temp2 + "deg)";
      return ({
        profileCompletionCount: profileCompletionCount,
        pc_temp2: pc_temp2,
        loaderStyle2 : arr
      })
    });
  }

  profile_completion() {
    var thisObj = this;
  	this.setState({
      t1 : setInterval(this.start1.bind(thisObj), 30)
    });
  };

  render(){
    var editBarView = this.props.profileInfo.incomplete.map((options,index) => {
                       return (
                           <div className="cell brdr6 vtop pad13 editBarStyle4">
            <div className="txtc ">
            <a href={options.url}>
                <div className="editBarStyle5">
                  <i className={"mainsp "+options.cssClass}></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">{options.title}</div>
            </div>
          </div>
                       );
                   }
          ); 

    return(
      <div className="pad1 preload myjsedit1" id="profileDetailSection">
        <div className="row editBarStyle">
        <div className="cell brdr6 editBarStyle1">
          <a href="/profile/viewprofile.php?ownview=1">
          <div className="fullwid pad12" id="jsmsProfilePic">
            <div className="posrel fl">
              <div className="hold hold1">
                <div className="pie pie1" style={this.state.loaderStyle2}></div>
              </div>
              <div className="hold hold2">
                <div className="pie pie2" style={this.state.loaderStyle}></div>
              </div>
              <div className="bg"> </div>
              <img className="image" src={this.props.profileInfo.photo} />
            </div>
            <div className="fl  color7 fontlig padl10 pt16" id="percent">{this.state.profileCompletionCount}%</div>
            <div className="clr"></div>
          </div>
          </a>
        </div>
        {editBarView}
        <div className="cell brdr6 vtop pad13 editBarStyle4">
            <div className="txtc ">
            <a href="/profile/viewprofile.php?ownview=1#Dpp">
                <div className="editBarStyle5">
                  <i className="dppHeart"></i>
              </div>
            </a>
            <div className="f12 color7 fontlig">Desired Partner</div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
