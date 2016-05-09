<style type="text/css">
    .set_arow1 {
        background-position: -221px -179px;
        width: 17px;
        height: 24px;
    }
    .set_pos1 {
        left:0;
        top:2px;
    }
</style>
<body class="bg4">
    <div id="mainContent">
        <div class="loader" id="pageloader"></div>
        <div>
            <!--start:top-->
            <div id="overlayHead" class="bg1 txtc pad15">
                <div class="posrel lh30">
                    <div class="fontthin f20 white">Delete Profile</div>
                    <a href="/static/deleteOption"><i class="mainsp posabs set_arow1 set_pos1 cursp"></i></a>
                </div>
            </div>
            <!--end:top-->
            <!--start:option-->
            <div class="bg4 f16 fontlig color13 abs_c">
                <!--start:input field-->
                <div style="padding:10%">
                    <div id="successStory" class="f16 fontthin ncolr1 fullwid txtc">Clicking on 'Submit' will delete your profile. You can also send your success story to feedback@jeevansathi.com in your free time.</div>
                </div>
                <!--end:input field-->
                <!--start:submit button-->
                <div id="foot" class="posfix fullwid bg7 btmo cursp">
                    <input type="submit" id="deleteProfile" class="fullwid dispbl lh50 txtc f16 white" value="Submit">
                </div>
                <!--end:submit button-->
            </div>
            <!--end:option-->
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $("#deleteProfile").bind('click', function(e){
            ajaxDelete('','1'); 
        });
    })
</script>