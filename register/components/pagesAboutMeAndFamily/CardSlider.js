import React from 'react';
import '../../style/slick.css';
import '../../style/slick.theme.css';
import '../../style/aboutme.css'
import classNames from 'classnames';

class CardSlider extends React.Component {
  constructor(props) {
    super(props);
    this.handleCurrentCard = this.handleCurrentCard.bind(this);
  }

  handleCurrentCard() {
    let nextCardNumber = '';
    switch (this.props.cardNumber) {
      case 1:
      case 2:
        nextCardNumber = 4
        break;
      case 3:
        nextCardNumber = 5
        break;
    }
    this.props.handleCurrentCard(nextCardNumber);
  }

  render() {
    return (<div>
      {/* card header start */}
      <div className="f16 fontmed color7 wrapIni vc_gap2 width85p">
                <span id="js-eng-Htext"
                      className={classNames(!this.props.switchChecked ? "" : "dispnone ", "dispibl")}>{this.props.data.headingEng0} &nbsp;</span>
        <span id="js-eng-Htext"
              className={classNames(!this.props.switchChecked ? "" : "dispnone ", "dispibl color2")}>{this.props.data.headingBoldEng} &nbsp; </span>
        <span id="js-eng-Htext"
              className={classNames(!this.props.switchChecked ? "" : "dispnone ", "dispibl")}>{this.props.data.headingEng}</span>
        <span id="js-eng-Htext"
              className={classNames(this.props.switchChecked ? "" : "dispnone ", "dispibl")}> {this.props.data.headingHindi0}&nbsp;</span>
        <span id="js-eng-Htext"
              className={classNames(this.props.switchChecked ? "" : "dispnone ", "dispibl color2")}>{this.props.data.headingBoldHindi} &nbsp;</span>
        <span id="js-eng-Htext"
              className={classNames(this.props.switchChecked ? "" : "dispnone ", "dispibl")}> {this.props.data.headingHindi}</span>
      </div>
      {/* card header end */}

      {/* card start */}
      <div className="fullwid fourSh bg4 vc_hgt1 width85p">
        <div className="pad3 fullheight">
          <div>
            <div className="txtc fontlig color7 f14 wrapIni vc_lh1 preventcopy padlr28 hgt100">
              <div id="js-eng-Htext"
                   className={classNames(!this.props.switchChecked ? "" : "dispnone ", "dispibl")}>
                <div>{this.props.data.subheadingEng1}<strong>{this.props.data.subheadingEngstrong}</strong>{this.props.data.subheadingEng2}
                </div>
                <div className="color2" onClick={e => {
                  this.handleCurrentCard()
                }}>{this.props.data.exampleTextEnglish}</div>
              </div>
              <div id="js-eng-Htext"
                   className={classNames(this.props.switchChecked ? "" : "dispnone ", "dispibl")}>
                <div>{this.props.data.subheadingHindi1}<strong>{this.props.data.subheadingHindistrong}</strong>{this.props.data.subheadingHindi2}
                </div>
                <div className="color2" onClick={e => {
                  this.handleCurrentCard()
                }}>{this.props.data.exampleTextHindi}</div>
              </div>
              {this.props.data.cardbodyDataHindi ? <div
                className="vc_gap3">{this.props.switchChecked ? this.props.data.cardbodyDataHindi : this.props.data.cardbodyDataEnglish}</div> : ''}
            </div>
            {this.props.data.cardBody ? <div className="pt20">
              <div className={this.props.data.cardBody}></div>

            </div> : ''
            }
          </div>
        </div>
      </div>
      {/* card end */}
    </div>)
  }
}

export default CardSlider;