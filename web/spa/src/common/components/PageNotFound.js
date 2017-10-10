import React from "react";

export default class PageNotFound extends React.Component {
    constructor(props) {
    super();
  }
  componentDidMount(){
    console.log(document.getElementsByClassName("sreen404"));
    document.getElementById("Pnf").style.height = window.innerHeight+"px";
    document.getElementById("Pnf").style.width = window.innerWidth+"px";
  }

  render() {

      return (
        <div className="mainContent">
          <div className="perspective" id="perspective">
            <div id="pcontainer">
              <div className="bg7 sreen404"  id="Pnf">
                <div className="pad19">
                  <div className="hamicon1">
                    <i id="hamburgerIcon" className="mainsp baricon "></i>
                  </div>
                  <div className="disptbl">
                    <div className="dispcell vertmid">
                      <div className="posrel">
                        <img src="../../img/js-error-img-1.png"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      );
  }
}
