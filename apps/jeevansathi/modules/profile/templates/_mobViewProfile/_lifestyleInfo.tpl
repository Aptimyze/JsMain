<!--start:lifestyle-->

<div class="pad5 bg4 fontlig color3 clearfix f14">
  ~if isset($arrData.lifestyle)	|| isset($arrData.assets) || isset($arrData.skills_speaks) || isset($arrData.skills_i_cook) || isset($arrData.hobbies) || isset($arrData.interest) || isset($arrData.dress_style) || 
  isset($arrData.fav_tv_show) || isset($arrData.fav_book) || isset($arrData.fav_movies) || isset($arrData.fav_cuisine)`
	  <div class="fl"><i class="vpro_sprite vpro_lstyle"></i></div>
	  <div class="fl color2 f14 vpro_padlTop" id="vpro_lifestyleSection">Lifestyle</div>
	  <div class="clr hgt10"></div>
		~if isset($arrData.lifestyle)`
			<div class="f12 color1">Habits</div>
			<div class="fontlig pb15" id="vpro_lifestyle" >~$arrData.lifestyle`</div>
		~/if`
        ~if isset($arrData.res_status)`
			<div class="f12 color1">Residential Status</div>
			<div class="fontlig pb15" id="vpro_res_status" >~$arrData.res_status`</div>
		~/if`
		~if isset($arrData.assets)`
			<div class="f12 color1">Assets</div>
			<div class="fontlig pb15" id="vpro_assets" >~$arrData.assets`</div>
		~/if`
		~if isset($arrData.skills_speaks) || isset($arrData.skills_i_cook)`
			<div class="f12 color1">Skills</div>  
			<div class="fontlig pb15">
				~if isset($arrData.skills_speaks)`
                <div id="vpro_skills_speaks">~$arrData.skills_speaks`</div>
				~/if`
				~if isset($arrData.skills_i_cook)` 
                <div id="vpro_skills_i_cook">~$arrData.skills_i_cook`</div>    
				~/if`
			</div>
		~/if` 
		~if isset($arrData.hobbies)`
			<div class="f12 color1">Hobbies</div>
			<div class="fontlig pb15" id="vpro_hobbies">~$arrData.hobbies`</div>
		~/if`  
		~if isset($arrData.interest)`
			<div class="f12 color1">Interests</div>
			<div class="fontlig pb15" id="vpro_interest">~$arrData.interest`</div>
		~/if`
		~if isset($arrData.dress_style)`
			<div class="f12 color1">Dress style</div>
			<div class="fontlig pb15" id="vpro_dress_style">~$arrData.dress_style`</div>	
		~/if`	  
		~if isset($arrData.fav_tv_show)`
			<div class="f12 color1">Favorite TV shows</div>
			<div class="fontlig pb15" id="vpro_fav_tv_show">~$arrData.fav_tv_show`</div>
		~/if`
		~if isset($arrData.fav_book)`
			<div class="f12 color1">Favorite books</div>
			<div class="fontlig pb15" id="vpro_fav_book">~$arrData.fav_book`</div>
		~/if`
		~if isset($arrData.fav_movies)`
			<div class="f12 color1">Favorite Movies</div>
			<div class="fontlig pb15" id="vpro_fav_movies">~$arrData.fav_movies`</div>
		~/if` 
		~if isset($arrData.fav_cuisine)`
			<div class="f12 color1">Favorite cuisine</div>
			<div class="fontlig pb15" id="vpro_fav_cuisine">~$arrData.fav_cuisine`</div>
		~/if`
	~/if`
	~if isset($posted_by)`
		<div class="f12 color1 pb20 wordBreak" id="vpro_posted_by">~$posted_by`</div>
	~/if`
</div>
<!--end:lifestyle--> 

