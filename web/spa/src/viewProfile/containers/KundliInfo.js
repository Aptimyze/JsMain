import React from 'react';
import {connect} from "react-redux";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import {getCookie} from '../../common/components/CookieHelper';
import * as CONSTANTS from '../../common/constants/apiConstants';
import axios from "axios";

class KundliInfo extends React.Component {
	constructor(props) {
        super();
        this.state = {
            showAstroLayer:false
        };
    }

    componentDidMount() {
        if(this.props.show_gunascore && getCookie("AUTHCHECKSUM")){
            this.props.getGuna(this.props.profilechecksum,this.props.about.sameGender);
        }
    }

    closeAstroLayer() {
        this.setState({
            showAstroLayer:false
        });
    }

    componentWillReceiveProps(nextProps)
    {
        let htmlStr = "<div class='fl'><i class='vpro_sprite vpro_pin'></i></div>",colorClass,szHisHer;
        if(nextProps.gunaScore.responseMessage == "Successful" && nextProps.gunaScore.SCORE) {
            if(nextProps.gunaScore.SCORE >18) {
                colorClass = "greenText";
            } else {
                colorClass = "redText";
            }
            if(this.props.about.gender = "Female") {
                szHisHer = "her";
            } else {
                szHisHer = "his";
            }
            htmlStr += "<div class='fontlig padl5 fl vpro_wordwrap'> Your guna score with " + szHisHer+ " is        <span class='"+colorClass+"'>" + nextProps.gunaScore.SCORE+ "/36   </span></div>";
            if(document.getElementById("gunaScore")) {
                document.getElementById("gunaScore").innerHTML = htmlStr;
            }
        }
    }

    initAstro(type) {
        this.setState({
            showAstroLayer:true,
            astroType: type
        });
        let call_url = "/api/v1/profile/astroCompatibility?otherProfilechecksum="+this.props.profilechecksum+"&sendMail=1&sampleReport=1&username="+this.props.username;
        if(getCookie('AUTHCHECKSUM')){
           call_url += "&AUTHCHECKSUM="+getCookie('AUTHCHECKSUM');
        }
        axios({
            method: "POST",
            url: CONSTANTS.API_SERVER +call_url,
            data: '',
            headers: {
              'Accept': 'application/json',
              'withCredentials':true
            },
        });
    }

    render() {
    	var city_country;
    	if(this.props.about.city_country)
    	{
    		city_country = <div>
    			<div className="f12 color1">City, Country of Birth</div>
            	<div className="fontlig pb15" id="vpro_city_country" >
            		{this.props.about.city_country}
            	</div>
    		</div>;
    	}
    	var date_time;
    	if(this.props.about.date_time)
    	{
    		date_time = <div>
    			<div className="f12 color1">Date &amp; Time of Birth</div>
            	<div className="fontlig pb15" id="vpro_date_time" >
            		{this.props.about.date_time}
            	</div>
    		</div>;
    	}

        var AstroReport;
        if(!this.props.about.NO_ASTRO && this.props.about.sameGender != 1)
        {
            var classAsign = "";
            if(this.props.about.COMPATIBILITY_SUBSCRIPTION == "N" && this.props.about.paidMem == "Y") {
                classAsign = "astroCompMem";
            } else if(this.props.about.COMPATIBILITY_SUBSCRIPTION == "N") {
                classAsign = "freeAstroComp";
            } else if(this.props.about.COMPATIBILITY_SUBSCRIPTION != "N") {
                classAsign = "astroMem";
            }

            AstroReport = <button id="getAstro" onClick={() => this.initAstro(classAsign)} className={classAsign + " fontlig lh40 astroBtn1 fr wid48p"}>Get Astro Report</button>
        }
    	var downloadHoroscope;
    	if(this.props.about.othersHoroscope == "Y" && (this.props.about.toShowHoroscope == "Y" || this.props.about.toShowHoroscope == ""))
    	{
               var urlString = "/api/v1/profile/downloadHoroscope?SAMEGENDER=&FILTER=&ERROR_MES=&view_username="+ this.props.about.username + "&SIM_USERNAME="+ this.props.about.username+ "&type=Horoscope&checksum=&otherprofilechecksum="+this.props.profilechecksum+"&randValue=890&GENDER="+this.props.about.gender;

            downloadHoroscope = <div>
    			<a href = {urlString}>
                    <button id="downloadHoroscope" className="fontlig lh40 astroBtn1 wid49p">Download Horoscope</button>
                </a>
                {AstroReport}
    		</div>
    	}

    	var horoscope;
    	if(this.props.about.sameGender != 1 && this.props.about.othersHoroscope == "Y" && (this.props.about.toShowHoroscope == "Y" || this.props.about.toShowHoroscope == ""))
    	{
    		horoscope = <div className="clearfix pb20 pt20">
    		 {downloadHoroscope}
    		</div>;
    	}

    	var more_astro;
    	if(this.props.about.more_astro)
    	{
            var rashi,nakshatra,horo_match;
            if(this.props.about.more_astro.rashi) {
                rashi =  <div className="clearfix">
                    <div className="fontlig vpro_wordwrap" id="vpro_more_astro_rashi" >
                        {this.props.about.more_astro.rashi}
                    </div>
                </div>;
            }
            if(this.props.about.more_astro.nakshatra) {
                nakshatra =  <div className="clearfix">
                    <div className="fontlig vpro_wordwrap" id="vpro_more_astro_nakshatra" >
                        {this.props.about.more_astro.nakshatra}
                    </div>
                </div>;
            }
            if(this.props.about.more_astro.horo_match)
            {
                horo_match = <div className="clearfix pt10">
                    <i className="vpro_sprite vpro_pin"></i>
                    <div className="fontlig dispibl padl5 vpro_wordwrap vtop" id="vpro_more_astro_horo_match">{this.props.about.more_astro.horo_match}
                    </div>
                </div>
            }

    		more_astro = <div>
    			<div className="f12 color1">More</div>
            	<div className="fontlig pb15">
            	{rashi}
            	{nakshatra}
            	{horoscope}
            	{horo_match}
            	<div className="clearfix" id="gunaScore">
                </div>
            	</div>
    		</div>;
    	}

    	var kundliSection;
    	if(this.props.about.city_country || this.props.about.date_time || this.props.about.more_astro)
    	{
    		kundliSection = <div className="pad5 bg4 fontlig color3 clearfix f14">
    			<div className="fl">
    				<i className="vpro_sprite vpro_kund"></i>
    			</div>
      			<div className="fl color2 f14 vpro_padlTop" id="vpro_astroSection">Kundali & Astro</div>
      			<div className="clr hgt10"></div>
      			{city_country}
      			{date_time}
      			{more_astro}
    		</div>;
    	}

        var muslim_m;
        if(this.props.about.muslim_m)
        {
            let htmlStr = "<div id='vpro_muslim_m'>", muslimData = this.props.about.muslim_m, keyArray = Object.keys(muslimData);
            for(var i=0; i<keyArray.length; i++) {
                htmlStr += '<div class="f12 color1">'+keyArray[i]+'</div>';
                htmlStr += '<div class="fontlig pb15">'+muslimData[keyArray[i]]+'</div>';
            }
            htmlStr += "</div>";
            muslim_m = <div dangerouslySetInnerHTML={{__html: htmlStr}} />
        }

        var sikh_m;
        if(this.props.about.sikh_m) {
            sikh_m = <div className="fontlig pb15" id="vpro_more_sikh">
                    {this.props.about.sikh_m}
                </div>;
        }

        var christian_m;
        if(this.props.about.christian_m) {
            christian_m = <div className="fontlig pb15" id="vpro_more_christian">
                    {this.props.about.christian_m}
                </div>
        }

        var Religious;
        if(this.props.about.muslim_m || this.props.about.sikh_m || this.props.about.christian_m)
        {
            Religious = <div className="pad5 bg4 fontlig color3 clearfix f14">
                <div className="fl">
                    <i className="vpro_sprite vpro_kund"></i>
                </div>
                <div className="fl color2 f14 vpro_padlTop">Religious Beliefs</div>
                <div className="clr hgt10"></div>
                {muslim_m}
                {sikh_m}
                {christian_m}
            </div>;
        }

        var astroLayer,astroButton,astroText;
        if(this.state.showAstroLayer == true) {
            if(this.state.astroType == "astroCompMem") {
                astroButton = <div>
                    <a id="astroButton" className="f18 fontlig astrob2 js-buttonAstro dispbl txtc" href = "https://www.jeevansathi.com/profile/mem_comparison.php">
                        Upgrade Membership
                    </a>
                </div>;
                astroText = <div className="astrob1 js-textAstro">
                    A sample astro compatibility report has been sent to your Email ID. Upgrade to a Paid membership and buy Astro Compatibility add-on to access these reports for your matches.
                </div>;
            } else if(this.state.astroType == "freeAstroComp") {
                astroButton = <div>
                    <a id="astroButton" className="f18 fontlig astrob2 js-buttonAstro dispbl txtc" href = "https://www.jeevansathi.com/profile/mem_comparison.php">
                        Upgrade Membership
                    </a>
                </div>;
                astroText = <div className="astrob1 js-textAstro">
                    A sample astro compatibility report has been sent to your Email ID. Upgrade to a Paid membership and buy Astro Compatibility add-on to access these reports for your matches.
                </div>;
            } else {
                astroButton = <div id="astroButton" className = "js-textAstro txtc lh50"  onClick={() => this.closeAstroLayer()}>
                    OK
                </div>;
                astroText = <div className="astrob1 js-textAstro">
                    Astro compatibility report with this member has been sent to your registered Email ID.
                </div>;
            }
            astroLayer = <div>
                <div id="astroReportLayer" onClick={() => this.closeAstroLayer()}className="overlayAstro js-astroReportLayer">
                </div>
                <div className="setcenter fontlig f18 js-astroTextButton">
                    {astroText}
                    {astroButton}
                </div>
            </div>
        }

    	return(
    		<div>
                {astroLayer}
    			{kundliSection}
  				{Religious}
    		</div>
    	);
    }
}

const mapStateToProps = (state) => {
    return{
       gunaScore: state.ProfileReducer.gunaScore,
    }
}

const mapDispatchToProps = (dispatch) => {
    return{
        getGuna: (profilechecksum,sameGender) => {
            let call_url = "/api/v1/profile/gunascore?oprofile="+profilechecksum+"&sameGender="+sameGender;
            commonApiCall(call_url,{},'SHOW_GUNA','GET',dispatch,false);
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(KundliInfo)
