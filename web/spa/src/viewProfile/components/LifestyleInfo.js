 import React from 'react';

export default class LifestyleInfo extends React.Component {
	constructor(props) {
        super();
    }
    render() {   

    	var LifestyleSection, handledView;
    	if(this.props.life)
    	{
            var lifestyle,res_status,assets,skills,hobbies,interest,dress_style,fav_tv_show,fav_book,fav_movies,fav_cuisine;

            if(this.props.life.lifestyle)
            {
                lifestyle = <div>
                    <div className="f12 color1">Habits</div>
                    <div className="fontlig pb15" id="vpro_lifestyle" >
                        {this.props.life.lifestyle}
                    </div>
                </div>
            }
            if(this.props.life.res_status)
            {
                res_status = <div>
                    <div className="f12 color1">Residential Status</div>
                    <div className="fontlig pb15" id="vpro_res_status" >
                        {this.props.life.res_status}
                    </div>
                </div>
            }
            if(this.props.life.assets)
            {
                assets = <div>
                    <div className="f12 color1">Assets</div>
                    <div className="fontlig pb15" id="vpro_res_assets" >
                        {this.props.life.assets}
                    </div>
                </div>
            }
            if(this.props.life.i_cook || this.props.life.skills_speaks)
            {
                skills = <div>
                    <div className="f12 color1">Skills</div>  
                    <div className="fontlig pb15">
                        <div id="i_cook">{this.props.life.skills_speaks}</div>
                        <div id="i_cook">{this.props.life.skills_i_cook}</div>
                    </div>
                </div>
            }
            if(this.props.life.hobbies)
            {
                hobbies = <div>
                    <div className="f12 color1">Hobbies</div>
                    <div className="fontlig pb15" id="vpro_hobbies">
                        {this.props.life.hobbies}
                    </div>
                </div>;
            }
            if(this.props.life.interest)
            {
                interest = <div>
                    <div className="f12 color1">Interests</div>
                    <div className="fontlig pb15" id="vpro_interest">
                        {this.props.life.interest}
                    </div>
                </div>;
            }
            if(this.props.life.dress_style)
            {
                dress_style = <div>
                    <div className="f12 color1">Dress style</div>
                    <div className="fontlig pb15" id="vpro_dress_style">
                        {this.props.life.dress_style}
                    </div>
                </div>;
            }
            if(this.props.life.fav_tv_show)
            {
                fav_tv_show = <div>
                    <div className="f12 color1">Favorite TV shows</div>
                    <div className="fontlig pb15" id="vpro_fav_tv_show">
                        {this.props.life.fav_tv_show}
                    </div>
                </div>;
            }    
            if(this.props.life.fav_book)
            {
                fav_book = <div>
                    <div className="f12 color1">Favorite books</div>
                    <div className="fontlig pb15" id="vpro_fav_book">
                        {this.props.life.fav_book}
                    </div>
                </div>;
            }
            if(this.props.life.fav_movies)
            {
                fav_movies = <div>
                    <div className="f12 color1">Favorite Movies</div>
                    <div className="fontlig pb15" id="vpro_fav_movies">
                        {this.props.life.fav_movies}
                    </div>
                </div>;
            }    
            if(this.props.life.fav_cuisine)
            {
                fav_cuisine = <div>
                    <div className="f12 color1">Favorite cuisine</div>
                    <div className="fontlig pb15" id="vpro_fav_cuisine">
                        {this.props.life.fav_cuisine}
                    </div>
                </div>;
            }
            if(this.props.life.lifestyle || this.props.life.assets || this.props.life.skills_speaks || this.props.life.skills_i_cook || this.props.life.hobbies || this.props.life.interest || this.props.life.dress_style || this.props.life.fav_tv_show || this.props.life.fav_book || this.props.life.fav_movies || this.props.life.fav_cuisine){
    		LifestyleSection = <div className="pad5 bg4 fontlig color3 clearfix f14">
    			<div className="fl">
    				<i className="vpro_sprite vpro_lstyle"></i>
    			</div>
	  			<div className="fl color2 f14 vpro_padlTop" id="vpro_lifestyleSection">Lifestyle</div>
	  			<div className="clr hgt10"></div>
	  			{lifestyle}
	  			{res_status}
	  			{assets}
	  			{skills}
	  			{hobbies}
	  			{interest}
	  			{dress_style}
	  			{fav_tv_show}
	  			{fav_book}
	  			{fav_movies}
	  			{fav_cuisine}                
    		</div>;
            }
            if(this.props.about.posted_by)
                handledView = <div className="f12 color1 pb20 wordBreak" id="vpro_posted_by">{this.props.about.posted_by}</div>;
    	}
    	return(
    		<div>
    			{LifestyleSection}
                {handledView}    
    		</div>            
    	);
    }
}