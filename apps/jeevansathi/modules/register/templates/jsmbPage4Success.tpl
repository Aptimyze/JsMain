<script>var appPromo=1;</script>
<div style="display:none">
~$errMsg|decodevar`
</div>
<div class="bodyCon">
  <section class="pageHdCont">
    <p class="pageHd">Write About ~if $yourHeading`~$yourHeading`~else`Yourself~/if`</p>
  </section>
  <form id="reg" name="form4" action="/register/jsmbPage4" method="post">
  ~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
		<input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`">
		<input type="hidden" name="groupname" value="~$sf_request->getParameter('groupname')`">
  <section class="wrap proWrap">
      <article class="formRow">
        <li>
			~$form['yourinfo']->renderLabel(null,['class'=>'lblStyl'])`
			~$form['yourinfo']->render(['maxlength'=>'3000','onkeyup'=>'aboutFieldCount()','style'=>'height:200px'])`
			<div class="err_msg">~$form['yourinfo']->renderError()`</div>
		</li>
       <div class="paddnum">
       	<div class="fL">
        	
            	<div class="inst">Number of characters typed</div>
            
        </div>
        <div class="fL">
        	<div class="fL widnum" >
            	<div id="about_yourself_count" name="" value="" placeholder="" class="numcenter redcolor" style="height:15px;border:1px solid #000;width:28px;border-radius:4px;"></div>
            </div>
                   
        </div>
      <div class="cl"></div>
      </div>
	<div>
	
	</div>
       <div style="display:none">
      	<input type="checkbox" value="S" name="service_email" checked> Receive email alerts
        <div class="cl"></div>
        
        <input type="checkbox" value="S" name="promo_email" checked> Receive prmotional mails
        <div class="cl"></div>
        
        <input type="checkbox" value="S" name="service_sms" checked> Receive SMS alerts
        <div class="cl"></div>
        
        <input type="checkbox" value="S" name="service_call" checked> Receive membership calls
        <div class="cl"></div>
      
      </div>
      
      <div class="fl">
                <input type="hidden" value="S" name="memb_mails">
                <input type="hidden" value="S"  name="memb_sms">
                <input type="hidden" value="S" name="memb_ivr">
        </div>

      </article>          
  </section>
  <section class="wrapper">
    <input name="jsmbPage4_submit" type="submit" class="btnM proBtn" value="Create my profile"/>
  </section>
  </form>
</div>

