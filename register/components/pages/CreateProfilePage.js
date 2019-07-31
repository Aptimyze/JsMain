import React from 'react';
import ProfileIcon from '../common/ProfileIcon';
import {withRouter} from 'react-router-dom';
import {setItem, getItem} from "../../services/localStorage";
import PropTypes from 'prop-types';

require('../../style/createProfile.css');

let currentlySelectedIcon = '0';

class CreateProfilePage extends React.Component {


  constructor(props) {
    super(props);
    this.state = {
      currentlySelectedIcon: 0
    };
    this.currentlySelected = this.currentlySelected.bind(this)
  }

  componentDidMount() {
    let loadedData = getItem('UD');
    if (loadedData) {
      if (loadedData.hasOwnProperty('relationship')) {
        currentlySelectedIcon = loadedData.relationship;
        this.setState({
          currentlySelectedIcon: loadedData.relationship
        })
      }
    }
  }

  // set the selected icon
  currentlySelected(item) {
    currentlySelectedIcon = item;
    this.setState({
      currentlySelectedIcon: item
    })
  }

  render() {
    return (
      <div id="relationShip" className="fullheight scrollContent bg4 posabs">
      <div id="relationShipDiv" className="flowauto">
        <div className="fullwid bg4">
          <div className="regbgHead regis_cover">
            <div id="topPnl" className="fullwid hgt50 pad15 bgRegCover">
              <div className="fl">
                <div className="dispbl">
                  <div className="fl white pt4  cursp">
                    <a href="/">
                      <i className="fl mainsp arow2"/>
                      <div className="fl pt2 white">Home</div>
                    </a>
                  </div>
                  <div className="fl padl5 pt5 white fontreg f14"/>
                  <div className="clr"/>
                </div>
              </div>
              <a className="txtc white fontreg f14 fr pt5 padr10 "
                 href="/login">Login</a>
            </div>
            <div className="pad1">
              <div className="rem_pad1">
                <div id="txtPnl" className="fullwid ptreg90">
                  <div className="regis_logo logoPos"/>
                  <div className="txtc white fontreg f12 pt16 padb5 ltSpacing">
                    <p className="dispibl edges edges-right bg4"/>
                    <span>INDIA’S BEST RATED MATRIMONIAL APP</span>
                    <p className="dispibl edges edges-left bg4"/>
                  </div>
                  <div className="txtc">
                    <span className="white fontreg f20">Register Free!
                    </span>

                  </div>
                </div>

                <div className="clr"/>
              </div>
            </div>
          </div>
        </div>
        <div className="f20 fontmed colrReg pt20 txtc">Create profile for </div>
        <div className="f15 fontmed optionsText txtc pt3">Select from the options below</div>
        <div className="pb15 bg4 fw pt5 iconContainer">
          <ProfileIcon iconText="Self"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-416px -145px' backgroundPosAfter='-418px -206px' iconId='1'
                       onIconClick={this.props.onIconClick}/>
          <ProfileIcon iconText="Relative"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-417px -22px' backgroundPosAfter='-417px -79px' iconId='4'
                       onIconClick={this.props.onIconClick}/>
          <ProfileIcon iconText="Brother"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-284px -12px' backgroundPosAfter='-286px -78px' iconId='6'
                       onIconClick={this.props.onIconClick}/>
          <ProfileIcon iconText="Sister"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-481px -20px' backgroundPosAfter='-482px -85px' iconId='6D'
                       onIconClick={this.props.onIconClick}/>
          <ProfileIcon iconText="Son"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-481px -142px' backgroundPosAfter='-481px -202px' iconId='2'
                       onIconClick={this.props.onIconClick}/>
          <ProfileIcon iconText="Daughter"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-289px -136px' backgroundPosAfter='-289px -193px' iconId='2D'
                       onIconClick={this.props.onIconClick}/>
          <ProfileIcon iconText="Friend"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-349px -15px' backgroundPosAfter='-350px -78px' iconId='8'
                       onIconClick={this.props.onIconClick}/>
          <ProfileIcon iconText="Marriage Bureau"
                       currentlySelectedIcon={currentlySelectedIcon}
                       selected={this.currentlySelected}
                       backgroundPosBefore='-352px -141px' backgroundPosAfter='-352px -195px'
                       iconId='5' onIconClick={this.props.onIconClick}/>
        </div>
        </div>
      </div>
    )
  }
}

CreateProfilePage.propTypes = {
  onIconClick: PropTypes.func.isRequired
}

export default withRouter(CreateProfilePage);