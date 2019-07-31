import React from 'react';
import classNames from 'classnames';
import RegSliderBinding from './RegSliderBinding';
import CircularLoader from './CircularLoader';
import {getItem} from "../../services/localStorage";
import {removeDuplicate} from "../../helpers/dataPreprocessor";
import {
  focusOnCurrentElement, editCssOfContainer,
  removeFocusFromAllElements
} from "../../helpers/screenHandlers";

class DoubleSlider extends React.Component {
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
          // setTimeout(() => {
          // this.bindMoveEventsCaller();
          this.setIntoView('Single_Slider');
          // }, 100)
        }
      });
      if (nextProps.prevState === 2) {
        if (this.props.header === "Brother(s)" || this.props.header === "Sister(s)") {
          this.setState({
            selected: null
          });
        }
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
        }, 495);
      });
    }
    else {
      setTimeout(() => {
        this.bindMoveEventsCaller();
      }, 250);
    }
    setTimeout(() => {
      this.hideLoader()
    }, 500);
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

      this.props.setLocalData(val);
      if (this.props.prevState === 2) {
        this.hamState(false)
      }

    }
  }

  setIntoView(id) {
    let elm = document.getElementById(id);
    if (elm) {
      for (let node = 0; node <= elm.children.length - 1; node++) {
        if (elm.children[node].className.includes('listItemSelected')) {
          let height_slider_container;
          if (this.props.showSearch) {
            height_slider_container = window.innerHeight - 55 - 35 - 50;
          } else {
            height_slider_container = window.innerHeight - 55 - 50;
          }
          if (node * this.indh > height_slider_container) {
           try {
             document.getElementById('wrapboxReg').scrollTo(0, -((-node * 50) + (window.innerHeight / 2)));
             document.getElementById('wrapboxReg').parentElement.scrollTo(0, -((-node * 50) + (window.innerHeight / 2)))
           }
           catch (e) {
             console.log("old Browser")
           }


          } else {
            try {
              document.getElementById('wrapboxReg').scrollTo(0, 0);
              document.getElementById('wrapboxReg').parentElement.scrollTo(0, 0);
            }
            catch (e) {
              console.log("old Browser")
            }
          }
          break;

        } else {
         try {
           document.getElementById('wrapboxReg').scrollTo(0, 0);
           document.getElementById('wrapboxReg').parentElement.scrollTo(0, 0);
         }
         catch (e) {
           console.log("old Browser")
         }
        }
      }
    }

  }

  search(e) {
    let searchText = e.target.value;
    let output = [];
    let unique = [];
    if (searchText.length >= 1) {
      let elm = document.getElementById('Single_Slider');
      elm.style.transform = "translate3d(0,0,0)";
      searchText = searchText.toLowerCase();
      this.props.inputDataForSlider.map(item => {
        if (item.name.toLowerCase().includes(searchText)) {
          output.push(item);
          if (output.length >= 1) {
            unique = removeDuplicate(output);
            if (unique[0] === undefined && unique.length === 1) {
              unique.length = 0;
            }
          }
        }
      })
    } else if (searchText.length === 0) {
      unique = this.props.inputDataForSlider
    }

    this.setState({
      inputArray: unique
    }, () => {
      try {
        document.getElementById('wrapboxReg').scrollTo(0, 0);
      }
      catch (e) {
        console.log('old Browser')
      }
      setTimeout(() => {
        // this.bindMoveEventsCaller();
        let idElm = document.getElementById('Single_Slider');
        idElm.style.height = (idElm.children.length + 1) * 50 + 'px';
      }, 200);
    })
  }

  bindMoveEventsCaller() {
    let idElm = document.getElementById('Single_Slider');
    if (idElm != null) {
      let indh = idElm.getElementsByTagName("li")[0]
        .offsetHeight;
      this.indh = indh;
      idElm.parentElement.style.overflow = "auto";
      if (this.props.showSearch) {
        idElm.parentElement.style.height = window.innerHeight - 55 - 35 + "px";
        idElm.parentElement.parentElement.style.height = window.innerHeight - 60 + "px";
      } else {
        idElm.parentElement.style.height = window.innerHeight - 55 - 35 + "px";
        idElm.parentElement.parentElement.style.height = window.innerHeight - 60 + "px";
      }
      editCssOfContainer()
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
          {/* header:end */}
          {/* body: start */}

          {this.props.showSearch && <div className="opa50 fontlig fullwid  mt10 fl brdr13 reg_h35 posrel">
            <input className="color14 f17 fontlig  wid85p searchBar padl10 setRD" id="inputBox"
                   style={{backGround: 'none'}}
                   onChange={this.search.bind(this)}
                   onFocus={() => {
                     focusOnCurrentElement('inputBox');
                     setTimeout(() => {
                       //  this.bindMoveEventsCaller();
                     }, 200)
                   }}
                   onBlur={() => {
                     removeFocusFromAllElements();
                     setTimeout(() => {
                       //  this.bindMoveEventsCaller();
                     }, 200);
                     setTimeout(() => {
                       //  this.editCssOfContainer();
                     }, 500);
                   }}
                   type="text" placeholder="Type to search"/>
              <i className="newsrcic" style={{position:'absolute'}}/>
          </div>}
          <div className="sliderDiv">
            {(this.state.showLoader || this.props.showLoader)
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
              ) : <li className="" style={{height: '50px'}}><span className="f17 white textTru listItemInSlider">No item found</span><input
                type="radio" name="year"
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

export default DoubleSlider;