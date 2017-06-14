import React from 'react';

export default class KundliInfo extends React.Component {
	constructor(props) {
        super();
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
            	<div className="clearfix vpro_dn" id="gunaScore">
                </div>
            	</div>
    		</div>;
    	}

    	var muslim_m;
    	if(this.props.about.muslim_m) 
    	{
    		muslim_m = "?????";
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
     			<div className="fontlig pb15" id="vpro_more_sikh">
     				{this.props.about.sikh_m}
     			</div>
     			<div className="fontlig pb15" id="vpro_more_christian">
     				{this.props.about.christian_m}
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
    	
    	return(
    		<div>
    			{kundliSection}
  				{Religious}
    		</div>
    	);
    }
}