<div class="mt15 bg4">
	<div class="f17 fontlig color7 padd22">~$expiringData.title`</div>
	<div class="pad015">
		~if $expiringData.tuples neq ''`
    	    <div class="fullwid">
			~assign var=counter value=0`
    		~foreach from=$expiringData.tuples item=tupleInfo key=id`
				~if $counter<=2`
					~if $tupleInfo.photo.url`
						~assign var=counter value=$counter+1`
						<div class="mar05 dispibl">
							<div class="row mar05 bg7 brdr50p posrel outerCircleDiv">
		                    <a href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$tupleInfo.profilechecksum`&~$expiringData.tracking`&actual_offset=~$id+1`&contact_id=~$expiringData.contact_id`&tupleId=~$id`&searchid=23&responseTracking=~JSTrackingPageType::INTEREST_EXPIRING_JSMS`&total_rec=~$expiringData.view_all_count`"><img src="~$tupleInfo.photo.url`" class="cell vmid brdr50p innerCircleDiv" style="margin-top:1px;" /></a>
							</div>
						</div>
					~/if`
				~/if`
			~/foreach`
			~if $expiringData.view_all_count>3`
				<div class="mar05 dispibl">
				<a href="~$SITE_URL`/profile/contacts_made_received.php?page=eeoi&filter=R">
					<div class="row mar05 bg7 brdr50p posrel outerCircleDiv">
	            	<div class="cell vmid white fullwid f23 fontlig txtc">+~math equation="x-y" x=$expiringData.view_all_count y=3`
	            	</div>
	    			</div>
	    		</a>
	    		</div>
	    	~/if`
		        <div class="clr"></div>
		        </div>
		~else`
			<div class="bg9">
			<div class="pad15 txtc">
				<div class="fontlig f14 color8">Interests which expire in next 7 days will appear here. Respond to them immediately after they appear here
				</div>
			</div>
			</div>
		~/if`
	</div>
</div>