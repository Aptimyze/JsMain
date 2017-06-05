<script>
	var appPromo=1;
	var casteAllowed = 0;
	var cityAllowed = 1;
	var countryAllowed = 0;
	var country = '~$country`';
</script>
<div style="display:none">
~$errMsg|decodevar`
</div>
<div class="bodyCon">
  <section class="pageHdCont">
    <p class="pageHd">About ~if $yourHeading`~$yourHeading`'s~else`your~/if` education &amp; work</p>
  </section>
  <form id="reg" name="form3" action="/register/jsmbPage3" method="post">
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
        <li>
		  	~$form['edu_level_new']->renderLabel(null,['class'=>'lblStyl'])`
			~$form['edu_level_new']->render(['class'=>"mob"])`
			<div style="clear:both"></div>
			<div class="err_msg">~$form['edu_level_new']->renderError()`</div>
			<div style="clear:both"></div>
		</li>
      </article>
       <article class="formRow">
        <li>
			~$form['occupation']->renderLabel('Occupation',['class'=>'lblStyl'])`
			~$form['occupation']->render(['class'=>"mob"])`
			<div style="clear:both"></div>
			<div class="err_msg">~$form['occupation']->renderError()`</div>
			<div style="clear:both"></div>
		</li>
      </article>
       <article class="formRow">
        <li>
		  	~$form['income']->renderLabel(null,['class'=>'lblStyl'])`
			~$form['income']->render(['class'=>"mob"])`
			<div style="clear:both"></div>
			<div class="err_msg">~$form['income']->renderError()`</div>
			<div style="clear:both"></div>
		</li>
      </article>
	~if $country eq '51'`
       <article class="formRow">
       <li>
		  	~$form['city_res']->renderLabel('City Living In',['class'=>'lblStyl'])`
			~$form['city_res']->render(['class'=>"mob"])`
			<div style="clear:both"></div>
			<div class="err_msg">~$form['city_res']->renderError()`</div>
			<div style="clear:both"></div>
		</li>
      </article> 
	<article class="formRow">
       <li style="display:none">
                        ~$form['pincode']->renderLabel('Pincode',['class'=>'lblStyl'])`
                        ~$form['pincode']->render(['class'=>"mob"])`
                        <div style="clear:both"></div>
                        <div class="err_msg">~$form['pincode']->renderError()`</div>
                        <div style="clear:both"></div>
                </li>
      </article>
	~/if`      
  </section>
  <section class="wrapper">
    <input name="jsmbpage3_submit" type="submit" class="btnM blueBtn" value="Continue"/>
  </section>
  </form>
</div>

