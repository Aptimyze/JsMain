<div class="pad1 bg4">
  <div class="fullwid pt15 pb10">
    <div class="f17 fontlig color7">~$visitorData.title`</div>
  </div>
  <div class="pad16">
	~if $visitorData.tuples neq ''`
        <div class="fullwid">
	~assign var=counter value=0`
        ~foreach from=$visitorData.tuples item=tupleInfo key=id`
		~if $counter<=2`
			~if $tupleInfo.photo.url`
				~assign var=counter value=$counter+1`
				
					<div class="fl~if $counter eq 1`~else` pl_a~/if`">
                                            <a href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$tupleInfo.profilechecksum`&stype=~SearchTypesEnums::VISITORS_MYJS_JSMS`&actual_offset=~$id+1`&contact_id=~$visitorData.contact_id`&total_rec=~$visitorData.view_all_count`"><img src="~$tupleInfo.photo.url`" height="60" width="60"/></a>
					</div>
				
					
			~/if`
		~/if`
	~/foreach`
	~if $visitorData.new_count>3`
	       <div class="fl pl_a">
                   <a href="~$SITE_URL`/inbox/5/1?matchedOrAll=A">
			<div class="bg7 txtc disptbl" style="width:60px; height:60px;">
				<div class="dispcell fontlig f18 white lh0 vertmid">+~math equation="x-y" x=$visitorData.new_count y=3`</div>
			</div>
                   </a>
		</div>
	~/if`
        <div class="clr"></div>
        </div>
	~else`
	<div class="bg9">
		<div class="pad15 txtc">
		  <div class="fontlig f14 color8">Members Who Visited Your Profile Will Appear Here</div>
		</div>
	</div>
	~/if`
  </div>
</div>



