import React from 'react';
import {setItem, getItem} from "../../services/localStorage";
import PropTypes from 'prop-types';
import {calculateAge} from "../../helpers/dataPreprocessor";
import classNames from "classnames";

class MProfileIcon extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      backgroundPos: this.props.backgroundPosBefore,
      textColor: "#8A9DB0",
      boxShadow: "",
    };
  }


  componentDidMount() {
    let loadedData = getItem('UD');
    if (loadedData) {
      if (loadedData.hasOwnProperty('relationship')) {
        if (this.props.iconId == loadedData.relationship) {
          let backgroundPos = this.props.backgroundPosAfter;
          this.setState({backgroundPos, textColor: "#14202B",
            boxShadow: "0 3px 8px 0 rgba(9, 50, 131, 0.2)"});
        }
      }
    }

  }

  handleIconClick() {
    let ud = getItem('UD');
    let ud_display = getItem('UD_display');
    let backgroundPos = this.props.backgroundPosAfter;
    this.setState({backgroundPos, textColor: "#14202B",
      boxShadow: "0 3px 8px 0 rgba(9, 50, 131, 0.2)"});
    this.props.selected(this.props.iconId);
    //
    // "1" => "Self",
    //   "2" => "Son",
    //   "2D" => "Daughter",
    //   "4" => "Relative/Friend",
    //   "6D" => "Sister",
    //   "6" => "Brother",
    //   "5" => "Client-Marriage Bureau",
    // lisiting for relationship

    if (ud !== null && Object.keys(ud).length > 0) {
      if (this.props.currentlySelectedIcon !== this.props.iconId) {
        if (ud.hasOwnProperty('dtofbirth_day')) {
          let age = calculateAge(`${ud.dtofbirth_month}/${ud.dtofbirth_day}/${ud.dtofbirth_year}`); // Format: MM/DD/YYYY
          if ((age < 21) && (this.props.iconId == "6" || this.props.iconId == "2")) {
            delete ud.dtofbirth_day;
            delete ud.dtofbirth_month;
            delete ud.dtofbirth_year;
          }
        }
      }

    } else {
      ud = {};
      ud_display = {};
      setItem('UD_display', ud_display);
    }

    if ((this.props.iconId == "6D" || this.props.iconId == "2D") && ud && ud.mstatus == 'M') {
      delete ud.mstatus;
      delete ud.havechild;
      delete ud_display.mstatus;
      delete ud_display.havechild;
    }
    ud['relationship'] = this.props.iconId == 8 ? '4' : this.props.iconId;
    if (this.props.iconId == "2" || this.props.iconId == "6") {
      ud['gender'] = 'M'
    } else if (this.props.iconId == "6D" || this.props.iconId == "2D") {
      ud['gender'] = 'F'
    } else {
      delete ud.gender
    }
    setItem('UD', ud);
    setItem('UD_display', ud_display);
    setTimeout(() => {
      this.props.onIconClick('nextPage', 1);
    }, 500);


  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.iconId != nextProps.currentlySelectedIcon) {
      let backgroundPos = this.props.backgroundPosBefore;
      this.setState({backgroundPos, textColor: "#8A9DB0",boxShadow:""});
    }
  }

  render() {
    return (
      <div className={classNames(this.props.cClass ? 'cClass cursp' : 'wid24p cursp')}>
        <div className="txtc pad16 MProfileIcon"
             style={{boxShadow:this.state.boxShadow}}
             onClick={this.handleIconClick.bind(this)}>
          <i className={classNames(this.props.cClass ? 'cursp regis_sp tranScale selficon_sel' : 'cursp regis_sp selficon_sel')}
             style={{backgroundPosition: this.state.backgroundPos}}/>
          <div className={classNames(this.props.cClass ? 'f16 fontlig color11' : 'f16 fontlig color11')}>{this.props.iconText}</div>
        </div>
      </div>

    )
  }
}

MProfileIcon.propTypes = {
  onIconClick: PropTypes.func.isRequired,
  iconText: PropTypes.string.isRequired,
  currentlySelectedIcon: PropTypes.string.isRequired,
  selected: PropTypes.func.isRequired,
  backgroundPosBefore: PropTypes.string.isRequired,
  backgroundPosAfter: PropTypes.string.isRequired,
  iconId: PropTypes.string.isRequired
};

export default MProfileIcon;