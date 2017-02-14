<body>
    <!--start:header-->
    <div class="cover1">
        <div class="container mainwid pt35 pb48">
            ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
        </div>
    </div>
    <!--end:header-->
    <!--start:middle-->
    <div id="mainContainerID" class="bg-4">
        <div class="container mainwid">
            <!--start:tabbing-->
            <div class="setbg1 fullwid pos-rel">
                <ul class="settingTab clearfix fontlig f15 color11">
                    <li>
                    ~if $page eq visibility`
                    <div>Profile Visibility</div>
                    ~else`
          <div><a class="color11" href="/settings/jspcSettings?visibility=1">Profile Visibility</a></div>
          ~/if`
        </li>
        <li>
          <div><a class="color11" href="/settings/alertManager">Alert Manager</a></div>
        </li>
        <li>
        ~if $page eq hideDelete`
        <div>Hide / Delete Profile</div>
        ~else`
          <div><a class="color11" href="/settings/jspcSettings?hideDelete=1">Hide / Delete Profile</a></div>
          ~/if`
        </li>
        <li>
        ~if $page eq 'changePassword'`
        <div>Change Password</div>
        ~else`
          <div><a class="color11" href="/settings/jspcSettings?changePassword=1">Change Password</a></div>
          ~/if`
        </li>
			~if $page eq visibility`
                    <li class="pos_abs hgt2 bg5" style="width:25%; left:0"></li>
			~else if $page eq hideDelete`
			<li class="pos_abs hgt2 bg5" style="width:25%; left:50%"></li>
			~else if $page eq 'changePassword'`
			<li class="pos_abs hgt2 bg5" style="width:25%; left:75%"></li>
			~/if`
                </ul>
            </div>
            <!--end:tabbing-->
<div class="pt30 pb30"> 
	~if $page eq visibility`
		~include_partial('jspcVisibility',['privacyValue'=>$privacyValue])`
	~elseif $page eq 'changePassword'`
		~include_partial('jspcChangePassword',['emailStr'=>$emailStr])`
	~else if $page eq hideDelete`
	~include_partial('jspcHideDelete',['UNHIDE'=>$UNHIDE,UNHIDE_DATE=>$UNHIDE_DATE,'showOTP'=>$showOTP])`
		~/if`
</div>
        </div>
    </div>
    <!--end:middle-->
    <!--start:footer-->
    ~include_partial('global/JSPC/_jspcCommonFooter')`
    <!--end:footer-->
</body>
<script type="text/javascript">
var profilechecksum = '~$profileID`';
</script>
