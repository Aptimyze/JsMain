~if $AGENT eq 'Y'`
	<var>{{AGENT_ADDRESS:profileid=~$profileid`}}</var>, <var>{{AGENT_NAME:profileid=~$profileid`}}</var>-<var>{{AGENT_CONTACT:profileid=~$profileid`}}</var>
~else`
	<a href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d; font-size:11px;">Click here</a><span style="font-size:11px;"> to view our 60+ offices across India</span>
~/if`
