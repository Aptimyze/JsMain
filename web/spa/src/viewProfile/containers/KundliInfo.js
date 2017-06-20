import React from 'react';
import {connect} from "react-redux";
import {commonApiCall} from '../../common/components/ApiResponseHandler.js';
import {getCookie} from '../../common/components/CookieHelper';


class KundliInfo extends React.Component {
	constructor(props) {
        super();
    }
    componentDidMount() {
        if(this.props.show_gunascore && getCookie("AUTHCHECKSUM")){
            this.props.getGuna(this.props.profilechecksum);   
        } 
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
            document.getElementById("gunaScore").innerHTML = htmlStr;
        }
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

    	var rashi;
    	if(this.props.about.more_astro.rashi) {
    		rashi =  <div className="clearfix">
                <div className="fontlig vpro_wordwrap" id="vpro_more_astro_rashi" >
                	{this.props.about.more_astro.rashi}
                </div>
            </div>;
    	}

    	var nakshatra;
    	if(this.props.about.more_astro.nakshatra) {
    		nakshatra =  <div className="clearfix">
                <div className="fontlig vpro_wordwrap" id="vpro_more_astro_nakshatra" >
                	{this.props.about.more_astro.nakshatra}
                </div>
            </div>;
    	}

        var AstroReport;
        if(!this.props.about.NO_ASTRO && this.props.about.sameGender != 1)
        {   
            var classAsign = "";
            if(this.props.about.COMPATIBILITY_SUBSCRIPTION == "N" && this.props.about.paidMem == "Y") {
                classAsign = "js-astroCompMem";
            } else if(this.props.about.COMPATIBILITY_SUBSCRIPTION == "N") {
                classAsign = "js-freeAstroComp";
            } else if(this.props.about.COMPATIBILITY_SUBSCRIPTION != "N") {
                classAsign = "js-astroMem";
            }

            AstroReport = <button className={classAsign + " fontlig lh40 astroBtn1 fr wid48p"}>Get Astro Report</button>
        }
    	var downloadHoroscope;
    	if(this.props.about.othersHoroscope == "Y" && (this.props.about.toShowHoroscope == "Y" || this.props.about.toShowHoroscope == ""))
    	{
            var urlString = "https://www.jeevansathi.com/api/v1/profile/downloadHoroscope?SAMEGENDER=&FILTER=&ERROR_MES=&view_username="+ this.props.about.username + "&SIM_USERNAME="+ this.props.about.username+ "&type=Horoscope&checksum=&otherprofilechecksum="+"ddea8d50a80534cd52d8f3eb72257ce2i8294390"+"&randValue=890&GENDER="+this.props.about.gender;
        
            downloadHoroscope = <div>
    			<a href = {urlString}>
                    <button className="fontlig lh40 astroBtn1 wid49p">Download Horoscope</button>
                </a>
                {AstroReport}
    		</div>
    	}

    	var horoscope;
    	if(this.props.about.sameGender != 1) 
    	{
    		horoscope = <div className="clearfix pb20 pt20">
    		 {downloadHoroscope}
    		</div>;
    	}

    	var horo_match
    	if(this.props.about.more_astro.horo_match) 
    	{
    		horo_match = <div className="clearfix pt10">
                <i className="vpro_sprite vpro_pin"></i>
                <div className="fontlig dispibl padl5 vpro_wordwrap vtop" id="vpro_more_astro_horo_match">{this.props.about.more_astro.horo_match}
                </div>
            </div>
    	}

    	var more_astro;
    	if(this.props.about.more_astro) 
    	{
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
    	
    	return(
    		<div>
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
        getGuna: (profilechecksum) => {
            let call_url = "/api/v1/social/requestPhoto?profilechecksum="+profilechecksum;
            dispatch(commonApiCall(call_url,{},'SHOW_GUNA','GET'));
        }
    }
}

export default connect(mapStateToProps,mapDispatchToProps)(KundliInfo)