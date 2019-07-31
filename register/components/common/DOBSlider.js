import React from 'react';
import classnames from 'classnames';
import RegSliderBinding from './RegSliderBinding';
import {setItem, getItem} from '../../services/localStorage';
import PropTypes from 'prop-types';
import TopError from '../../../common/components/TopError';
import errorStatements from '../../constant/errorStatements';
import {calculateAge} from '../../helpers/dataPreprocessor';

class DOBSlider extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      date: [],
      month: [],
      year: [],
      errorRegData: ''
    }
    this.fakeUp = 0;
    this.ud = getItem('UD');
    this.dob = {'date': [], 'month': [], 'year': []};
    for (let i = 1; i <= 31; i++) {
      this.dob.date.push(i);
    }
    this.dob.month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let currYear = +Date().split(" ")[3];
    if (this.ud.gender == 'F') {
      for (let i = 1948; i <= (currYear-18); i++) {
        this.dob.year.push(i);
      }
    } else {
      for (let i = 1948; i <= (currYear-21); i++) {
        this.dob.year.push(i);
      }
    }
  }

  componentWillMount() {
    this.setState({date: this.dob.date, month: this.dob.month, year: this.dob.year});
  }

  componentDidMount() {
    this.addFakeLi('HAM_OPTION_1');
    this.addFakeLi('HAM_OPTION_2');
    this.addFakeLi('HAM_OPTION_3');
  }

  hamState(showRegHamburger, errorRegData) {
    this.props.hamState(showRegHamburger, this.props.heading, errorRegData);
  }

  bindMoveEvents(options, id) {
    this.obj = new RegSliderBinding(options, id);
    this.obj.init();
  }

  addFakeLi(id) {

    if (document.getElementById(id) != null) {
      let headHeight = 55;
      //height of header
      // clientHeight includes padding also
      let height = window.innerHeight - (headHeight + 55);
      let indh = document.getElementById(id).getElementsByTagName("li")[0]
        .offsetHeight;
      document.getElementById(id).parentElement.style.overflow = "hidden";
      document.getElementById(id).parentElement.style.height = window.innerHeight - 100 + "px";

      let hgt = document.getElementById(id).getElementsByTagName("li")[0]
        .clientHeight; // hgt is the height of one li
      //       let width=document.getElementById(id).children[0].style.width;
      let showP = Math.abs(Math.ceil(height / indh));
      let up, down;
      up = down = Math.floor(showP / 2);

      if (showP % 2 == 0) {
        up = Math.floor(showP / 2);
        down = Math.ceil(showP / 2);
      }
      /*start: so that filterPinkDiv can be in between of screen*/
      up = up - 1;
      down = down - 1;
      /*End*/

      let upArr = [],
        downArr = [];

      for (let i = 0; i < up; i++) {
        upArr[i] = "";
      }
      for (let i = 0; i < down; i++) {
        downArr[i] = "";
      }

      let options = {
        width: "100%",
        height: hgt,
        sliderHeight: indh,
        fakeb: down,
        faket: up,
        startSliderPosition: null,
        sliderType: 'dobSlider',
        startFromMiddle: this.props.startFromMiddle
      };
      this.fakeUp = up;
      let ud = getItem('UD');

      if (id == 'HAM_OPTION_1') {
        let currentDateArr = this.dob.date;
        currentDateArr = [...upArr, ...currentDateArr, ...downArr];
        this.dob.date = currentDateArr;
        options.startSliderPosition = ud.dtofbirth_day ? ud.dtofbirth_day - 1 : null;
        this.setState({
          date: this.dob.date
        }, () => {
          this.bindMoveEvents(options, id)

        });
      } else if (id == 'HAM_OPTION_2') {
        let currentMonthArr = this.dob.month;
        currentMonthArr = [...upArr, ...currentMonthArr, ...downArr];
        this.dob.month = currentMonthArr;
        options.startSliderPosition = ud.dtofbirth_month ? ud.dtofbirth_month - 1 : null;
        this.setState({
          month: this.dob.month
        }, () => {
          this.bindMoveEvents(options, id)

        });
      } else {
        let currentYearArr = this.dob.year;
        currentYearArr = [...upArr, ...currentYearArr, ...downArr];
        this.dob.year = currentYearArr;
        options.startSliderPosition = ud.dtofbirth_year ? ud.dtofbirth_year - 1948 : 47 - this.fakeUp;
        this.setState({
          year: this.dob.year
        }, () => {
          this.bindMoveEvents(options, id)

        });
      }
      let topPos = document.getElementById(id).children[up].offsetTop;
      this.topPos = topPos;
      this.topPos = topPos = indh * up;

      let pinkDiv = document.createElement("div");
      pinkDiv.className = "filterPinkDiv";
      // pinkDiv.style = "top:" + topPos + "px;height:" + indh + "px";
      /*start: For fixing iphone 5C 'attempted to assign to readonly prop' issue */
      let styleVal = "top:" + topPos + "px;height:" + indh + "px";
      pinkDiv.setAttribute('style', styleVal);
      /*End*/

      document.getElementById(id).parentNode.insertBefore(pinkDiv, document.getElementById(id));

      /*wrap ul by this wrapbox element...*/
      let wrapBox = document.createElement("div");
      wrapBox.className = "wrap-box-reg";
      wrapBox.setAttribute("id", "wrapboxReg");
      document.getElementById(id).parentNode.appendChild(wrapBox);
      wrapBox.appendChild(document.getElementById(id));
    }
  }

  doneClick() {
    let date_div = document.getElementById('HAM_OPTION_1');
    let childElement = date_div.children;
    let selectedDate = 16;
    let p;
    let ud = getItem('UD');
    for (let i = 0; i < childElement.length; i++) {
      p = childElement[i].children[1];
      if (childElement[i].children[1].checked) {
        selectedDate = childElement[i].children[1].value - this.fakeUp + 1;
      }
    }
    date_div = document.getElementById('HAM_OPTION_2');
    childElement = date_div.children;
    let selectedMonth = 7;
    for (let i = 0; i < childElement.length; i++) {
      p = childElement[i].children[1];
      if (childElement[i].children[1].checked) {
       // console.log(childElement[i].children[1].value, 123)
        selectedMonth = childElement[i].children[1].value - this.fakeUp + 1;
      }
    }
    date_div = document.getElementById('HAM_OPTION_3');
    childElement = date_div.children;
    let selectedYear = 1973;
    for (let i = 0; i < childElement.length; i++) {
      p = childElement[i].children[1];
      if (childElement[i].children[1].checked) {
        selectedYear = childElement[i].children[1].value - this.fakeUp + 1948;
      }
    }
    if (selectedDate && selectedMonth && selectedYear) {
      let age = calculateAge(`${selectedMonth}/${selectedDate}/${selectedYear}`); // Format: MM/DD/YYYY
      if ((selectedDate == 31 && (selectedMonth == 2
        || selectedMonth == 4 || selectedMonth == 6 ||
        selectedMonth == 9 || selectedMonth == 11)) ||
        (selectedDate == 30 && selectedMonth == 2) ||
        (selectedDate == 29 && selectedMonth == 2 &&
          !(((selectedYear % 4 == 0) && (selectedYear % 100 != 0)) ||
            (selectedYear % 400 == 0)))) {
        //reset date
        delete ud.dtofbirth_day;
        delete ud.dtofbirth_month;
        delete ud.dtofbirth_year;
        setItem('UD', ud);
        this.hamState(false, errorStatements.DOB_ERROR_1);

        // dob is selected after gender selection
      } else if (ud.gender && age < 18 && ud.gender == 'F') {
        delete ud.dtofbirth_day;
        delete ud.dtofbirth_month;
        delete ud.dtofbirth_year;
        setItem('UD', ud);
        this.hamState(false, errorStatements.DOB_ERROR_2);
      } else if (ud.gender && age < 21 && ud.gender == 'M') {
        delete ud.dtofbirth_day;
        delete ud.dtofbirth_month;
        delete ud.dtofbirth_year;
        setItem('UD', ud);
        this.hamState(false, errorStatements.DOB_ERROR_3);
      } else if (!ud.gender && age < 18) {
        delete ud.dtofbirth_day;
        delete ud.dtofbirth_month;
        delete ud.dtofbirth_year;
        setItem('UD', ud);
        this.hamState(false, errorStatements.DOB_ERROR_2);
      }

      else {
        // let ud = getItem('UD');
        let ud_display = getItem('UD_display');
        ud.dtofbirth_day = selectedDate;
        ud.dtofbirth_month = selectedMonth;
        ud.dtofbirth_year = selectedYear;
        setItem('UD', ud);
        this.hamState(false, '');
      }
    }

    //   else {
    //         selectedDate = 16;
    //         selectedMonth = 7;
    //         selectedYear = 1973;
    //     }


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
            {this.props.showHeading}
          </div>
          {/* header:end */}
          {/* body: start */}
          <div className="dobDiv">
            <ul className="ul_date txtc" id="HAM_OPTION_1">

              {this.state.date.map((ele, index) => (
                  <li key={index} className="fullwid">
                    <div className="dob_li">
                      {ele}
                    </div>
                    <input type="radio" name="day" value={index} className="dn" id="ham_day"/>
                  </li>
                )
              )}
            </ul>
          </div>
          <div className="dobDiv">
            <ul className="ul_date txtc" id="HAM_OPTION_2">

              {this.state.month.map((ele, index) => (
                  <li key={index} className="fullwid">
                    <div className="dob_li">
                      {ele}
                    </div>
                    <input type="radio" name="month" value={index} className="dn" id="ham_month"/>
                  </li>
                )
              )}
            </ul>
          </div>
          <div className="dobDiv">
            <ul className="ul_date txtc" id="HAM_OPTION_3">

              {this.state.year.map((ele, index) => (
                  <li key={index} className="fullwid">
                    <div className="dob_li">
                      {ele}
                    </div>
                    <input type="radio" name="year" value={index} className="dn" id="ham_year"/>
                  </li>
                )
              )}
            </ul>
          </div>
          {/* body: end */}
          {/* footer: start */}
          <div className="posfix rem_pad1 btmo white f19 txtc bg7"
               style={{width: '80vw'}}
               id="dob-footer" onClick={e => {
            this.doneClick()
          }}>
            Done
          </div>
          {/* footer: end */}

        </div>
        <div onClick={e => this.hamState(false, '')} id="hamView"
             className={classnames(this.props.showRegHamburger == true ? 'backShow z105' : 'dn', 'fw darkView fullheight hamView')}>
        </div>
      </div>

    )
  }
}

DOBSlider.propTypes = {
  showRegHamburger: PropTypes.bool,
  heading: PropTypes.string.isRequired,
  hamState: PropTypes.func.isRequired
}
export default DOBSlider;