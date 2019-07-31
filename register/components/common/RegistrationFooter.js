import React from 'react';

class RegistrationFooter extends React.Component {
  constructor(props) {
    super(props);
    this.props = props;
  }

  render() {
    return (
      <div className="pad5">
        <div className="rem_pad1 posrel fullwid ">
          <div className="white f19 txtc" id="totalCountId">
            {this.props.text}
          </div>
        </div>
      </div>
    )
  }
}

export default RegistrationFooter;
