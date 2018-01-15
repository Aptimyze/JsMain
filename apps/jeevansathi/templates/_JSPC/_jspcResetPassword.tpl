<div id="ResetPasswordJspc" class="changePwdContent">
<p class="pt30 pb30 txtc fontlig color11 f15">Reset your Password</p>
            	<div class="setwid5 mauto pb30">                	
                    <div class="fullwid bg-white">
                    	<form action="/common/resetPassword?submitPassword=1" method="POST">
						<input type="hidden" name="emailStr" id="emailStr" value="~$emailStr`">
						<input type="hidden" name="d" id="emailStr" value="~$d`">
						<input type="hidden" name="h" id="emailStr" value="~$h`">
                        	<div class="setp2 fontlig">
                            	<!--start:field 1-->
                                <p id="topError" class="color5 f12 txtc vishid sethgt1">~$passwordInvalid`</p>
                                <div class="setbdr1">
                                	<input id="password1" name="password1" type="password" class="hgt30IE color12 fullwid brdr-0 outwhi lh40 pl20 wid90p f15 fontlig" placeholder="New Password">
                                </div>                                
                                <!--end:field 1-->
                                <!--start:field 2-->
                                <div class="setbdr1 mt30">
                                	<input id="cnewPwd" name="cnewPwd" type="password" class=" hgt30IE color12 fullwid brdr-0 outwhi lh40 pl20 wid90p f15 fontlig" placeholder="Re -Enter New Password">
                                </div>                                
                                <!--end:field 2-->
                            </div>
                            <div id="saveBtn" class="cursp applied1 brdr-0 fullwid lh50 txtc colrw f15 fontlig">Save New Password</div>
                        </form>
                    </div>                
                </div>               
            </div>
