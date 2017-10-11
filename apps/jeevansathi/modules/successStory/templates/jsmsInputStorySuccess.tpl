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
    .colorBlack {color:black !important;}
</style>
<body class="bg4">
    <div id="mainContent">
        <div class="loader" id="pageloader"></div>
        <div>
            <!--start:top-->
            <div id="overlayHead" class="bg1 txtc pad15">
                <div class="posrel lh30">
                    <div class="fontthin f20 white dispibl">Your Success Story</div>
                    ~if $fromMailer neq 'true'`
                        <span id="skipSuccess" class="white fr fontthin cursp">Skip</span>
                        <a href="/static/deleteOption"><i class="mainsp posabs set_arow1 set_pos1 cursp"></i></a>
                    ~/if`
                </div>
            </div>
            <!--end:top-->
            <!--start:option-->
            <form id="submit_ss" name="submit_ss" action="/successStory/jsmsSelectImage" method="post" enctype="multipart/form-data" target="_self">
            <div class="bg4 f16 fontlig color13">
                <!--start:input field-->
                <div style="padding:5%">
                    ~if $COMMENTS`
                        <textarea name="successStoryMsg" id="successStory" rows="9" placeholder="Share your success story and get attractive gifts from Jeevansathi" class="f16 fontthin fullwid colorBlack" value="~$COMMENTS`" >~$COMMENTS`</textarea>
                    ~else`
                    <textarea name="successStoryMsg" id="successStory" rows="9" placeholder="Share your success story and get attractive gifts from Jeevansathi" class="f16 fontthin fullwid colorBlack"></textarea>
                    ~/if`
                </div>
                <input type="hidden" name="fromSuccessStoryMailer" value="~$fromMailer`">
                <input type="hidden" name="mailid" value="~$mailId`">
                <!--end:input field-->
                <!--start:submit button-->
                <div id="foot" class="posfix fullwid bg7 btmo cursp">
                    <input type="submit" id="passCheckID" class="fullwid dispbl lh50 txtc f16 white" value="Submit">
                </div>
                <!--end:submit button-->
            </div>
            </form>
            <!--end:option-->
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        var hgt = window.innerHeight - 140;
        $("textarea").css("height",hgt+"px");
        $("#passCheckID").bind('click', function(e){
            e.preventDefault();
            if($("#successStory").val()){
                $("#submit_ss").submit();
            } else {
                setTimeout(function(){
                  ShowTopDownError(["<center>Enter Success Story</center>"]);
                },animationtimer);
            }
        });

        $("#skipSuccess").bind('click', function(e){
            e.preventDefault();
            parent.location.href = "/successStory/jsmsSkipDelete";
        });
    })
</script>