import React from 'react';
import classNames from 'classnames';
import RegSliderBinding from './RegSliderBinding';
import CircularLoader from './CircularLoader';
import {getItem} from "../../services/localStorage";
import {
  editCssOfContainer,
} from "../../helpers/screenHandlers";

class FamilyBasedSlider extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      selected: null,
      inputArray: this.props.inputDataForSlider,
      showLoader: true
    };
    this.indh = null;
    this.hideLoader = this.hideLoader.bind(this);
  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.inputDataForSlider.length > 0) {
      this.setState({
        inputArray: nextProps.inputDataForSlider
      }, () => {
        if (nextProps.prevState === 2) {
          setTimeout(() => {
            this.bindMoveEventsCaller();
            this.setIntoView('Single_Slider');
          }, 100)
        }
      });
      if (nextProps.prevState === 2) {
        if (document.getElementById('inputBox')) document.getElementById('inputBox').value = '';
        let ud = getItem('UD');
        if (ud[this.props.localStorageFeildName2]) {
          this.setState({
            selected: ud[this.props.localStorageFeildName2]
          }, () => {
            this.setIntoView('Single_Slider');
          });
        }
      }
    }

  }

  componentDidMount() {
    let ud = getItem('UD');
    this.initializeScroller('Single_Slider'); // this is elemnt id
    if (ud[this.props.localStorageFeildName]) {
      this.setState({
        selected: ud[this.props.localStorageFeildName]
      }, () => {
        setTimeout(() => {
          this.bindMoveEventsCaller();
          this.setIntoView('Single_Slider');
        }, 250);
      });
    }
    else {
      setTimeout(() => {
        this.bindMoveEventsCaller();
      }, 250);
    }
    setTimeout(() => {
      this.hideLoader()
    }, 450);
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

  bindMoveEvents(options, id) {
    this.obj = new RegSliderBinding(options, id);
    this.obj.init();
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

  setSelection(val) {
    if (val.code != -1) {
      this.setState({
        selected: val.code
      });

      this.props.setFamilyData(val);
      if (this.props.prevState === 2) {
        //this.hamState(false)
      }

    }
  }

  setIntoView(id) {
    let elm = document.getElementById(id);
    if (elm) {
      for (let node = 0; node <= elm.children.length - 1; node++) {
        if (elm.children[node].className.includes('listItemSelected')) {
          let height_slider_container = window.innerHeight - 55 - 50;
          if (node * this.indh > height_slider_container) {
            try{
              document.getElementById('wrapboxReg').scrollTo(0, -((-node * 50) + (window.innerHeight / 2)));
              document.getElementById('wrapboxReg').parentElement.scrollTo(0, -((-node * 50) + (window.innerHeight / 2)))

            }
            catch (e) {
              console.log('old Browser')
            }
          } else {
            try {
              document.getElementById('wrapboxReg').scrollTo(0, 0);
              document.getElementById('wrapboxReg').parentElement.scrollTo(0, 0);
            }
            catch (e) {
              console.log('old Browser')
            }
          }
          break;

        }
      }
    }

  }


  bindMoveEventsCaller() {
    let idElm = document.getElementById('Single_Slider');
    if (idElm != null) {
      let indh = idElm.getElementsByTagName("li")[0]
        .offsetHeight;
      this.indh = indh;
      idElm.parentElement.style.overflow = "auto";
      idElm.parentElement.style.height = window.innerHeight - 60 + "px";
      idElm.parentElement.parentElement.style.height = window.innerHeight - 60 + "px";
      editCssOfContainer();
      document.getElementById('ham').style.height = window.innerHeight + "px";
      document.getElementById('hamView').style.height = window.innerHeight + "px";
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

          <div className="sliderDiv">
            {this.state.showLoader
            && <CircularLoader/>}
            <div className="wrap-box-reg" id="wrapboxReg">
            <ul className="ul_date" id="Single_Slider">
              {this.state.inputArray.length >= 1 ? this.state.inputArray.map((val, index) => (
                  <li key={index} style={{height: '50px'}}
                      className={classNames(this.state.selected == (val.code)
                        ? 'listItemSelected' : '',
                        val.code == "-1" ? "noselectReg" : "",
                        'fullwid')}
                      onClick={() => this.setSelection(val)}>
                  <span className="f17 white textTru listItemInSlider"
                        dangerouslySetInnerHTML={{__html: val.name}}>
                  </span>
                    <input type="radio" name="year"
                           value={index} className="dn" id="ham_year"/>
                  </li>
                )
              ) : <li className="" style={{height: '50px'}}><span className="f17 white textTru
              listItemInSlider">No item found</span><input type="radio" name="year"
                                                           value={-1} className="dn" id="ham_year"/></li>}
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

export default FamilyBasedSlider;