
~if $type eq MSTATUS` ~$type = SPECIAL_CASES`~/if`
<div class="frm-container">

		<div class="row07" style="padding-left:5px;">
		~foreach from=$SEO_FOOTER[$type] key=k item=v name=seoFoot`
			<div class="search-tag">
				<a ~if $value eq $v[$page][1]`~/if` title="~$v[$page][1]` ~if $page eq 'N'`Matrimony~else if $page eq 'B'`brides Matrimony~else`grooms Matrimony~/if`" href="~$SITE_URL`~$v[$page][0]`">~$v[$page][1]`</a>
			</div>
		~/foreach`
		</div>
	</div>
