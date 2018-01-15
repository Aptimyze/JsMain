<!--start:career-->
~if isset($arrData.mycareer) || (isset($arrData.work_status) && (isset($arrData.work_status.label) || isset($arrData.work_status.value) || isset($arrData.work_status.company))) || isset($arrData.earning) || isset($arrData.plan_to_work) || isset($arrData.abroad)`
	<div class="pad5 bg4 fontlig color3 clearfix f14">
	  <div class="fl"><i class="vpro_sprite vpro_career"></i></div>
	  <div class="fl color2 f14 vpro_padlTop" id="vpro_careerSection">Career</div>
	  <div class="clr hgt10"></div>
	  ~if isset($arrData.mycareer)`
		<div class="fontlig pb15 wordBreak vpro_lineHeight" id="vpro_mycareer" >~$arrData.mycareer`</div>
	  ~/if`
	  ~if isset($arrData.work_status) && (isset($arrData.work_status.label) || isset($arrData.work_status.value) || isset($arrData.work_status.company))`
		  <div class="f12 color1">~$arrData.work_status.label`</div>
          <div class="fontlig pb15" ><span id="vpro_work_status">~$arrData.work_status.value`</span><br/>
              <span id="vpro_company"> ~$arrData.work_status.company`</span> </div>
	  ~/if`	
	  ~if isset($arrData.earning)`  
		  <div class="f12 color1">Earning</div>
		  <div class="fontlig pb15" id="vpro_earning" >~$arrData.earning`</div>
	  ~/if`
	  <div class="clearfix">
		~if isset($arrData.plan_to_work)`  
			<div class="fl"><i class="vpro_sprite vpro_pin"></i></div>
			<div class="fontlig padl5 fl vpro_wordwrap" id="vpro_plan_to_work" >~$arrData.plan_to_work`</div>
		~/if`	
	  </div>
	  <div class="clearfix">
		~if isset($arrData.abroad)`  
			<div class="fl"><i class="vpro_sprite vpro_pin"></i></div>
			<div class="fontlig padl5 fl vpro_wordwrap" id="vpro_abroad" >~$arrData.abroad`</div>
		~/if`
	  </div>
	</div>
~/if`
<!--end:career--> 
<!--start:education-->
~if isset($arrData.myedu) || isset($arrData.post_grad.deg) || isset($arrData.post_grad.name) || isset($arrData.under_grad.deg) || isset($arrData.under_grad.name) || isset($arrData.school)`
	<div class="pad5 bg4 fontlig color3 clearfix f14">
	  <div class="fl"><i class="vpro_sprite vpro_edu"></i></div>
	  <div class="fl color2 f14 vpro_padlTop" id="vpro_educationSection">Education</div>
	  <div class="clr hgt10"></div>
	  ~if isset($arrData.myedu)`
			<div class="fontlig pb15 wordBreak vpro_lineHeight" id="vpro_myedu" >~$arrData.myedu`</div>
	  ~/if`
	  ~if isset($arrData.post_grad.deg) || isset($arrData.post_grad.name)`
		  <div class="f12 color1">Post Graduation</div>
          <div class="fontlig pb15"><span id="vpro_post_grad_deg">~$arrData.post_grad.deg`</span><br/>
              <span id="vpro_post_grad_name" >	~$arrData.post_grad.name` </span></div>
	  ~/if`
	  ~if isset($arrData.under_grad.deg) || isset($arrData.under_grad.name)`  
		  <div class="f12 color1">Under Graduation</div>
          <div class="fontlig pb15" ><span id="vpro_under_grad_deg">~$arrData.under_grad.deg`</span><br/>
              <span id="vpro_under_grad_name" > ~$arrData.under_grad.name` </span> </div>
	  ~/if`
	  ~if isset($arrData.school)`
		  <div class="f12 color1">School</div>
		  <div class="fontlig pb15" id="vpro_school" > ~$arrData.school`</div>
	  ~/if`
	</div>
~/if`
<!--end:education--> 
