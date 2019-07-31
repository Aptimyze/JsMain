import React from 'react';
import MProfileIcon from '../common/MprofileIcon';
import {withRouter} from 'react-router-dom';
import {getItem} from "../../services/localStorage";
import PropTypes from 'prop-types';

require('../../style/createProfile.css');

let currentlySelectedIcon = '0';

class MarketingProfileFor extends React.Component {


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
          <div className="fw bg1">
            <div className="pad5">
              <div className="rem_pad1 posrel fullwid ">
                <div className="fl wid20p white cursp">
                  <i id="backIcon" onClick={() => {

                    window.location.href = localStorage.getItem('pageM') ?
                      localStorage.getItem('pageM') : '/';
                  }} className="fl dispbl mainsp backicon"/></div>
                <div className="white fontthin f19 txtc wid60p">Create profile for</div>
              </div>
            </div>
          </div>
          <div className="ici1">
            <div className="f15 fontmed
            optionsText txtc pt10">Select from the options below</div>
            <div className="bg4 fw pt5  iconContainer">
              <MProfileIcon iconText="Self"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-418px -206px' backgroundPosAfter='-418px -206px'
                            iconId='1'
                            onIconClick={this.props.onIconClick}/>
              <MProfileIcon iconText="Relative"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-417px -79px' backgroundPosAfter='-417px -79px' iconId='4'
                            onIconClick={this.props.onIconClick}/>
              <MProfileIcon iconText="Brother"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-286px -78px' backgroundPosAfter='-286px -78px' iconId='6'
                            onIconClick={this.props.onIconClick}/>
              <MProfileIcon iconText="Sister"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-482px -85px' backgroundPosAfter='-482px -85px' iconId='6D'
                            onIconClick={this.props.onIconClick}/>
              <MProfileIcon iconText="Son"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-481px -202px' backgroundPosAfter='-481px -202px' iconId='2'
                            onIconClick={this.props.onIconClick}/>
              <MProfileIcon iconText="Daughter"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-289px -193px' backgroundPosAfter='-289px -193px' iconId='2D'
                            onIconClick={this.props.onIconClick}/>
              <MProfileIcon iconText="Friend"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-350px -78px' backgroundPosAfter='-350px -78px' iconId='8'
                            onIconClick={this.props.onIconClick}/>
              <MProfileIcon iconText="Marriage Bureau"
                            cClass="cClass"
                            currentlySelectedIcon={currentlySelectedIcon}
                            selected={this.currentlySelected}
                            backgroundPosBefore='-352px -195px' backgroundPosAfter='-352px -195px'
                            iconId='5' onIconClick={this.props.onIconClick}/>
            </div>
          </div>

        </div>
      </div>
    )
  }
}

MarketingProfileFor.propTypes = {
  onIconClick: PropTypes.func.isRequired
}

export default withRouter(MarketingProfileFor);