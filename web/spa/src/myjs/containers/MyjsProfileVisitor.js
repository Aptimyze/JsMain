import React from "react";


export default class MyjsProfileVisitor extends React.Component{
  constructor(props) {
        console.log('MyjsProfileVisitor');
        console.log(props);
        super();
  }
  render(){
    if(!this.props.fetched) {
      return <div></div>;
    } 
    return(      
      <div className="setWidth mt10" id="visitorPresent">

        <div className="pad1 bg4">

          <div className="fullwid pt15 pb10">
            <div className="f17 fontlig color7">Profile Visitors</div>
          </div>

          <div className="pad16">
            <div className="fullwid">
              <div className="fl">
                <a            href="/profile/viewprofile.php?profilechecksum=7ea4f13261502c4551bdadb4460721f6i13036711&amp;stype=WMV&amp;actual_offset=1&amp;contact_id=13171207_VISITORS&amp;total_rec=5">
                  <img src="https://mediacdn.jeevansathi.com/4682/19/93659447-1485000718.jpeg" height="60" width="60"/>
                </a>
              </div>
              <div className="fl pl_a">
                <a            href="/profile/viewprofile.php?profilechecksum=4bd8161d07d3922d49f30e8d7f36d665i11626133&amp;stype=WMV&amp;actual_offset=2&amp;contact_id=13171207_VISITORS&amp;total_rec=5">
                <img src="https://mediacdn.jeevansathi.com/5315/19/106319463-1491114115.jpeg" height="60" width="60"/>
                </a>
              </div>
              <div className="fl pl_a">
                <a href="/profile/viewprofile.php?profilechecksum=f05374121bc1810c805c9ef98f6005ddi13889575&amp;stype=WMV&amp;actual_offset=3&amp;contact_id=13171207_VISITORS&amp;total_rec=5">
                <img src="https://mediacdn.jeevansathi.com/3718/4/74364025-1472639436.jpeg" height="60" width="60"/></a>
              </div>
              <div className="fl pl_a">
                <a href="/search/visitors?matchedOrAll=A">
                  <div className="bg7 txtc disptbl myjsc1">
                    <div className="dispcell fontlig f18 white lh0 vertmid">+2</div>
                  </div>
                </a>
              </div>
              <div className="clr"></div>
            </div>
          </div>

        </div>
      </div>

    )
  }
}
