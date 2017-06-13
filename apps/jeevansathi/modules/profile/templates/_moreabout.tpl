~foreach from=$NameValueArr item=Value key=Label`
<div class="lf p_bl">~if $Label neq "YOURINFO"`<strong>~$Label`</strong>
<div class="sp12"></div>~/if`
<span class="no_b" style="margin-left:3px">~$Value|readmore:$InfoLimit.$Label:$Label` </span>
</div>
<div class="sp8"></div>
~/foreach`

