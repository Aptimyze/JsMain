import React from 'react';
require ('../style/deleteProfile_css.css');

export default class otherReasonLayer extends React.Component{
	constructor(props){
	    super(props);
      this.state = {
        specifyReason:'',
        showPasswordLayer:false,
        hideAction:false,
        tupleHeight : {'height': document.documentElement.clientHeight}
      };
	    
	 }

	 render() {
	 	return (<div>
                <div className="fullwid posfix z99 bg4" style={this.state.tupleHeight}>
                    <div className="bg1 txtc pad15" id="delH">
                      <div className="posrel">
                        <div className="fontthin f20 white">Specify Reason</div>
                        <a href="javascript:void(0);" onClick={this.props.closeOtherReasonLayer}>
                          <i className="mainsp posabs set_arow1 set_pos1"></i>
                        </a> 
                        <div className="posabs d1_pos1">
                          <a className="white opa70 f16" onClick={this.props.skipOtherLayer}>
                            Skip
                          </a>
                        </div>
                      </div>
                    </div>             
                  <div>
                    <div className="pad21p">
                      <textarea id="otherReasonID" name="otherReasonID" className="f20 fontthin color11 fullwid txtc" placeholder="Kindly specify your reason"></textarea>
                    </div> 
                  </div>
                  <div id="del_next" className="bg7 white fullwid posfix btm0 txtc lh50" onClick={this.props.proceedToNextStep}>
                      Next
                  </div>
                </div>
          </div>
        );
	 }
}

  