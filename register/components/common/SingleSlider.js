import React from 'react';
import classNames from 'classnames';
import {setItem, getItem} from "../../services/localStorage";
import CircularLoader from './CircularLoader';

class SingleSlider extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      selected: null,
      showLoader: false,
      inputArray: []
    };
    this.hideLoader = this.hideLoader.bind(this);
    this.showLoader = this.showLoader.bind(this);
  }


  componentDidMount() {
    let ud = getItem('UD');
    this.initializeScroller('Single_Slider');
    // this is elemnt id
    setTimeout(() => {
      this.setState({
        inputArray: this.props.inputDataForSlider,
      }, () => {
        // this.showLoader();
        this.bindMoveEventsCaller();
        this.noData = "No item found";
      });
    }, 500);

    if (ud[this.props.localStorageFeildName]) {
      this.setState({
        selected: ud[this.props.localStorageFeildName]
      }, () => {
        setTimeout(() => {
          this.bindMoveEventsCaller();
          this.setIntoView('Single_Slider');
        }, 550);
      });
    }
    setTimeout(() => {
      this.hideLoader()
    }, 500);
    setTimeout(() => {
      this.showLoader();
    }, 50);
  }

  hamState(showRegHamburger) {
    if (!showRegHamburger) {
      setTimeout(() => {
        this.props.hamState(showRegHamburger, this.props.heading);
      }, 100);
    } else {
      this.props.hamState(showRegHamburger, this.props.heading);
    }
  }

  initializeScroller(id) {
    let idElm = document.getElementById('Single_Slider');
    this.bindMoveEventsCaller();
    // let wrapBox = document.createElement("div");
    // wrapBox.className = "wrap-box-reg";
    // wrapBox.setAttribute("id", "wrapboxReg");
    // idElm.parentNode.appendChild(wrapBox);
    // wrapBox.appendChild(document.getElementById(id));
  }

  showLoader() {
    this.setState({showLoader: true});
  }

  hideLoader() {
    this.setState({showLoader: false});
  }

  bindMoveEventsCaller() {
    let idElm = document.getElementById('Single_Slider');
    idElm.style.height = (idElm.children.length + 1) * 50 + 'px';
    if (idElm != null) {
      idElm.parentElement.style.overflow = "auto";
      idElm.parentElement.parentElement.style.height = window.innerHeight - 55 + "px";
      idElm.parentElement.style.height = window.innerHeight - 55 + "px";
    }
  }

  setSelection(val) {
    if (val.code !== -1) {
      let ud = getItem('UD');
      let ud_display = getItem('UD_display');
      if (ud) {
        if (this.props.localStorageFeildName === "employed_in") {
          delete ud.occupation;
          delete ud_display.occupation;
        }
        ud[this.props.localStorageFeildName] = val.code;
        ud_display[this.props.localStorageFeildName] = val.name;
        setItem('UD', ud);
        setItem('UD_display', ud_display);
      }

      this.setState({
        selected: val.code
      }, () => {
        this.hamState(false);
      });
    }
  }

  setIntoView(id) {
    let elm = document.getElementById(id);
    if (elm) {
      for (let node = 0; node <= elm.children.length - 1; node++) {
        if (elm.children[node].className.includes('listItemSelected')) {
          if (node * 50 > window.innerHeight - 55 - 50) {
            try {
              document.getElementById('wrapboxReg').parentElement.scrollTo(0, -((-node * 50) + (window.innerHeight / 2)));
              document.getElementById('wrapboxReg').scrollTo(0, -((-node * 50) + (window.innerHeight / 2)))

            }
            catch (e) {
              console.log('old browser')
            }
          } else {
            try {
              document.getElementById('wrapboxReg').parentElement.scrollTo(0, 0);
            }
            catch (e) {
              console.log("old Browser")
            }
          }
          break;

        }
      }
    }

  }

  render() {
    return (
      <div id="hamMain">
        <div className='white posfix z106 fw fullheight' id="ham"
             style={{marginLeft:this.props.marginLeft}}>
          {/* header: start */}
          <div className="f19  bg1 rem_pad1 txtc fullwid">
            <span className="fl padl10">
              <i id="backIcon" className="mainsp backicon"
                 onClick={e => this.hamState(false, '')}/></span>
            {this.props.header}
          </div>
          {/* header:end */}
          {/* body: start */}
          <div className="sliderDiv">
            {this.state.showLoader
            && <CircularLoader/>}
            <div className="wrap-box-reg" id="wrapboxReg">
            <ul className="ul_date" id="Single_Slider">
              {this.state.inputArray.map((val, index) => (
                  <li key={index}
                      className={classNames(this.state.selected == (val.code)
                        ? 'listItemSelected' : '',
                        val.name == "--More" ? "noselectReg" : "",
                        'fullwid', 'reg_h50')}
                      onClick={() => this.setSelection(val)}>
                  <span className="f17 white textTru listItemInSlider"
                        dangerouslySetInnerHTML={{__html: val.name}}>
                  </span>
                    <input type="radio" name="year"
                           value={index} className="dn" id="ham_year"/>
                  </li>
                )
              )}
            </ul>
            </div>
          </div>
          {/* body: end */}
        </div>
        <div onClick={e => this.hamState(false)} id="hamView"
             className={classNames(this.props.showRegHamburger == true ? '' +
               'backShow z105' : 'dn', 'fw darkView fullheight hamView')}>
        </div>
      </div>

    )
  }
}

export default SingleSlider;