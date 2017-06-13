import React from "react";

require ('../../common/style/common.css');


export class ShowCount extends React.Component{
    constructor(props){
      super();

    }


    render() {
      //if()console.
      if(!this.props.param)  return (<div></div>);
      else if(this.props.param.TOTAL_NEW==0) return (<div></div>);
      return(
          <div className="posabs myjstop1">
            <div className="disptbl oval">
              <div className="dispcell vertmid color6 f11 txtc">{this.props.param.TOTAL_NEW}</div>
            </div>
          </div>
        )
    }
}

export default class MyjsHeadHTML extends React.Component
{

  constructor(props) {
    super(props);
    console.log('in constructor');
    console.log(props);
    this.state={
      dataUpdates: false
    }


  }

  componentWillReceiveProps(nextProps)
  {
      console.log("next",nextProps);
      this.setState({
        dataUpdates: true
      })

  }


 toggleBellView()
 {
   let currentView  = document.getElementById('notificationBellView').style.display;
   let newView = currentView=='block' ? 'none' : 'block';
   document.getElementById('notificationBellView').style.display=newView;
 }



  render(){


      return(
        <div>
          <div className="fullwid bg1 pad1">
              <div className="rem_pad1 clearfix">
                  <div className="fl wid20p">
                    <div id="hamburgerIcon">
                      <i className="loaderSmallIcon dn"></i>
                      <i id="hamburgerIcon" className="dispbl mainsp baricon"></i>
                    </div>
                  </div>
                  <div id="myJsHeadingId" className="fl wid60p txtc color5  fontthin f19">Home</div>
                  <div className="fr">
                    <div className="fullwid">
                      <div className="fl padr15 posrel">
                          <div id="notificationView" className="posrel" onClick ={this.toggleBellView.bind(this)}>
                            <i className="mainsp bellicon" ></i>
                            <ShowCount param={this.props.bellResponse} />
                          </div>
                      </div>
                      <a id="calltopSearch" href="/search/topSearchBand?isMobile=Y&amp;stime=1496993783592">
                        <div className="fl">
                          <i className="mainsp srchicon"></i>
                        </div>
                      </a>
                    </div>
                  </div>
              </div>
          </div>


        </div>
      )
    }


}
