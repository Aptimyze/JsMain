import React from 'react';
import classNames from 'classnames';
import CircularLoader from './CircularLoader';
import {setItem, getItem} from "../../services/localStorage";
import {removeDuplicate} from "../../helpers/dataPreprocessor";
import {
  focusOnCurrentElement, editCssOfContainer,
  removeFocusFromAllElements
} from "../../helpers/screenHandlers";

class SearchSlider extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      selected: null,
      inputArray: [],
      showLoader: false,
    };
    this.noData = "";
    this.hideLoader = this.hideLoader.bind(this);
    this.showLoader = this.showLoader.bind(this);
  }


  componentDidMount() {
    let ud = getItem('UD');
    this.initializeScroller('Single_Slider'); // this is element id
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
        // this.showLoader();
        setTimeout(() => {
          // after keyboard open(pincode then open slider)
          this.bindMoveEventsCaller();
          this.setIntoView('Single_Slider');
        }, 550);
      })
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

  setSelection(val) {
    if (val.code != -1) {

      let ud = getItem('UD');
      let ud_display = getItem('UD_display');
      if (ud) {
        if (this.props.localStorageFeildName === "country_res") {
          delete ud.city_res;
          delete ud.res_status;
          delete ud.pincode;
          delete ud.income;
          delete ud_display.income;
          delete ud_display.city_res;
          delete ud_display.res_status;
          delete ud_display.state_res;
          delete ud.state_res;
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
          if (node * 50 >= window.innerHeight - 35 - 55 - 50) {
            try {
              document.getElementById('wrapboxReg').scrollTo(0, -((-node * 50) + (window.innerHeight / 2)))
              document.getElementById('wrapboxReg').parentElement.scrollTo(0, -((-node * 50) + (window.innerHeight / 2)))

            }
            catch (e) {
              console.log("old Browser")
            }
          } else {
            try {
              elm.scrollTo(0, 0);
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
            if (unique[0] == undefined && unique.length == 1) {
              unique.length = 0;
            }
          }
        }
      })
    } else if (searchText.length == 0) {
      console.log(' else unique',unique);
      unique = this.props.inputDataForSlider
    }
    unique = unique.filter((element) => {
      if(typeof(element)!=="undefined" && element){
          return element
      }
    })
    this.setState({
      inputArray: unique
    }, () => {
      try {
        document.getElementById('wrapboxReg').scrollTo(0, 0);
      }
      catch (e) {
        console.log("old Browser")
      }



      setTimeout(() => {
        let idElm = document.getElementById('Single_Slider');
        idElm.style.height = (idElm.children.length + 1) * 50 + 'px';
        // this.bindMoveEventsCaller();
      }, 200);
    })

  }

  bindMoveEventsCaller() {
    let idElm = document.getElementById('Single_Slider');
    idElm.style.height = (idElm.children.length + 1) * 50 + 'px';
    if (idElm != null) {
      idElm.parentElement.style.overflow = "auto";
      editCssOfContainer();
      document.getElementById('ham').style.height = window.innerHeight + "px";
      document.getElementById('hamView').style.height = window.innerHeight + "px";

      idElm.parentElement.parentElement.style.height = window.innerHeight - 55 - 35 + "px";
      idElm.parentElement.style.height = window.innerHeight - 55 - 35 + "px";
    }
  }


  render() {
    return (
      <div id="hamMain">
        <div className='white posfix z106 fw' id="ham"
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
          <div className="fontlig fullwid  mt10 fl brdr13 posrel newhgtic">
            <input id="sliderSearchBar" className="color14 f17 fontlig newpic  wid85p padl10 setRD"
                   style={{backGround: 'none'}}
                   onFocus={() => {
                     focusOnCurrentElement('sliderSearchBar');
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
                       //  editCssOfContainer();
                     }, 500);
                   }}
                   onChange={this.search.bind(this)}
                   type="text" placeholder="Type to search"/>
            
              <i className="newsrcic" style={{position:'absolute'}}/>
            
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
              ) : <li className="" style={{height: '50px'}}><span
                className="f17 white textTru listItemInSlider">{this.noData}</span>
                <input type="radio" name="year"
                       value={-1} className="dn" id="ham_year"/></li>}
            </ul>
            </div>
          </div>
          {/* body: end */}
        </div>
        <div onClick={e => this.hamState(false)} id="hamView"
             className={classNames(this.props.showRegHamburger == true ? '' +
               'backShow z105' : 'dn', 'fw darkView hamView')}>
        </div>
      </div>

    )
  }
}

export default SearchSlider;