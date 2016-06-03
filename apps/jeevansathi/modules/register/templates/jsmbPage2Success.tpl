<script>
	var appPromo=1;
	var casteAllowed = 1;
	var cityAllowed = 0;
	var countryAllowed = 1;	
	var mtongueVal = "~$mtongue`";
        var genderVal = "~$gender`"
</script>

<div style="display:none">
~$errMsg|decodevar`
</div>
<div class="bodyCon">
  <section class="pageHdCont">
    <p class="pageHd">More about ~if $yourHeading`~$yourHeading`~else`yourself~/if`<span>All Fields are mandatory</span></p>
  </section>
  <form id="reg" name="form2" action="/register/jsmbPage2" method="post">
  ~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
		 <input type="hidden" name="adnetwork" value="~$sf_request->getParameter('adnetwork')`" />
	   	 <input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`"/>
		 <input type="hidden" name="account" value="~$sf_request->getParameter('account')`" />
		 <input type="hidden" name="campaign" value="~$sf_request->getParameter('campaign')`" />
		 <input type="hidden" name="adgroup" value="~$sf_request->getParameter('adgroup')`" />
		 <input type="hidden" name="keyword" value="~$sf_request->getParameter('keyword')`" />
		 <input type="hidden" name="match" value="~$sf_request->getParameter('match')`" />
		 <input type="hidden" name="lmd" value="~$sf_request->getParameter('lmd')`" />
		 <input type="hidden" name="groupname" value="~$sf_request->getParameter('groupname')`">
  <input type="hidden" name="heading" value="~$yourHeading`" >		
  <section class="wrap">
      <article class="formRow">
        <!-- Religion Starts Here -->
		<li>
			~$form['religion']->renderLabel(null, ['class' => 'lblStyl'])`
			~$form['religion']->render(['class'=>"mob"])`
			<div style="clear:both"></div>
			<div class="err_msg">~$form['religion']->renderError()`</div>
			<div style="clear:both"></div>
	    </li>
		
		<!-- Religion Ends Here -->
      </article>
       <article class="formRow" id="caste_section" style="display:none">
		   <li>
		   	~$form['caste']->renderLabel('Caste / Sect',['class'=>'lblStyl'])`
			~$form['caste']->render(['class'=>"mob"])`
			<div style="clear:both"></div>
			<div class="err_msg">~$form['caste']->renderError()`</div>		
			<div style="clear:both"></div>
	        </li>

      </article>
       <article class="formRow">
        <li>
		~$form['mstatus']->renderLabel(null,['class' => 'lblStyl'])`
		~$form['mstatus']->render(['class'=>"mob"])`
		~$form['mstatus']->renderHelp()`
		<div style="clear:both"></div>
		<div class="err_msg">~$form['mstatus']->renderError()`</div>
		<div style="clear:both"></div>
		</li>
      </article>
       <article class="formRow" id="have_child_section" style="display:~if $show_has_child`inline~else`none~/if`">
        ~$form['havechild']->renderLabel(null, ['class' => 'lblStyl'])`
		~$form['havechild']->render(['class'=>"mob"])`
		~$form['havechild']->renderHelp()`
		<div style="clear:both"></div>
		<div class="err_msg">~$form['havechild']->renderError()`</div>
		<div style="clear:both"></div>
      </article>
       <article class="formRow">
        <li>
		~$form['height']->renderLabel(null,['class'=>'lblStyl'])`
		~$form['height']->render(['class'=>"mob"])`
		~$form['height']->renderHelp()`
		<div style="clear:both"></div>
		<div class="err_msg">~$form['height']->renderError()`</div>
		<div style="clear:both"></div>
		</li>
      </article>
       <article class="formRow">
        <li>
		~$form['country_res']->renderLabel(null,['class' => 'lblStyl'])`
		~$form['country_res']->render(['class'=>"mob"])`
		~$form['country_res']->renderHelp()`
		<div style="clear:both"></div>
		<div class="err_msg">~$form['country_res']->renderError()`</div>
		<div style="clear:both"></div>
		</li>
      </article>
  </section>
     <div class="fl">
		<input type="hidden" value="S" name="memb_mails">
		<input type="hidden" value="S"  name="memb_sms">
		<input type="hidden" value="S" name="memb_ivr">
	</div>
  <section class="wrapper">
    <input name="jsmbpage2_submit" type="submit" class="btnM blueBtn" value="Continue"/>
  </section>
  </form>
</div>

