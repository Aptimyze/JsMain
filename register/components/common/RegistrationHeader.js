import React from 'react';
import PropTypes from 'prop-types';

class RegistrationHeader extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      headerData: 'Create profile for'
    };
    this.handleBack = this.handleBack.bind(this);
  }

  handleBack() {
    this.props.onIconClick('previousPage',+this.props.page - 1 + '');
  }

  render() {
    return (
      <div className="pad5">
        <div className="rem_pad1 posrel fullwid ">
          {!this.props.hideBack && <div className="fl wid20p white cursp">
            <i id="backIcon" className="fl dispbl mainsp backicon"
               onClick={this.handleBack}/>
          </div>}

          <div className="white fontthin f19 txtc wid60p" id="totalCountId">
            {this.props.headerData}
          </div>

          {this.props.showSecondaryBtn && <div className="white fontthin f19"
                                               style={this.props.secondaryBtnCss}
                                               onClick={this.props.secondaryBtnFn}>
            {this.props.secondaryBtn}
          </div>}

        </div>
      </div>

    )
  }
}

RegistrationHeader.propTypes = {
  headerData: PropTypes.string.isRequired,
  onIconClick: PropTypes.func
}


export default RegistrationHeader;