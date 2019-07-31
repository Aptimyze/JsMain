import React from "react";
import classNames from 'classnames';

export default class TopError extends React.Component {
  constructor(props) {
    super();
    this.state = {
      timeToHide: props.timeToHide || 3000
    };
  }

  editClass() {
    let errDiv = document.getElementsByClassName("errClass")[0];
    setTimeout(function () {
      errDiv && errDiv.classList.add("showErr");
    }, 10);

    setTimeout(function () {
      errDiv && errDiv.classList.remove("showErr");
    }, this.state.timeToHide);
    if (this.props.leftAlign) {
      errDiv = document.getElementsByClassName("errClassReg")[0];
      setTimeout(function () {
        errDiv && errDiv.classList.add("showErr");
      }, 10);

      setTimeout(function () {
        errDiv && errDiv.classList.remove("showErr");
      }, this.state.timeToHide);
    }
  }

  componentDidMount() {
    this.editClass();
  }

  componentDidUpdate() {
    this.editClass();
  }

  render() {
    let extendedMessageDiv;
    if (this.props.extendedMessage) {
      extendedMessageDiv = <div className="txtc">
        <div className="pad12_e white f15 op1">{this.props.message}</div>
        <div className="pb10 white f15 op1">{this.props.extendedMessage}</div>
      </div>;
    } else if (this.props.errorObj || this.props.errorArray) {
      let errorArray = [];
      for (let obj in this.props.errorObj) {
        this.props.errorObj[obj] && errorArray.push(this.props.errorObj[obj]);
      }
      if (this.props.errorArray) {
        if (this.props.errorArray.length > 0) {
          for (let i in this.props.errorArray) {
            errorArray.push(this.props.errorArray[i]);
          }
        }
      }
      extendedMessageDiv =
        <div className="pad12_e white f15 regErrorDiv">
          {errorArray.map((err, index) =>
            <div key={index}>
              # {err}
              <br/></div>
          )}
        </div>
    } else if (this.props.errorArray) {
      if (this.props.errorArray.length > 0) {
        extendedMessageDiv =
          <div className="pad12_e white f15 regErrorDiv">
            {this.props.errorArray.map((err, index) =>
              <div key={index}>
                # {err}
                <br/></div>
            )}
          </div>
      }
    } else {
      extendedMessageDiv = <div className="pad12_e white f15 op1">{this.props.message}</div>;
    }

    return (
      <div id="TopError">
        <div
          className={classNames(this.props.topPosition == 55 ? 'top55' : 'topzero', this.props.leftAlign ? "errClassReg" : "errClass", "posfix op1")}
          style={{width: this.props.width ? this.props.width : ''}}>
          {extendedMessageDiv}
        </div>
      </div>
    );
  }
}




















