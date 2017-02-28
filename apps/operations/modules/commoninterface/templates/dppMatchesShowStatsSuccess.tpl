<div> Dpp Matches  (~$totalCount`)</div>
<br><br>
<div>
~foreach from=$usernameArr key=key item=username`
<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?username=~$username`">~$username`</a><br><br>
~/foreach`
</div>