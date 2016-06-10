<!--start:header-->
<div class="cover1">
	<div id="top0" class="container mainwid pt35 pb30">
    	<!--start:top horizontal bar-->
            ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
        <!--end:top horizontal bar--> 
    </div>
</div>
<!--end:header-->


<div class="bg-4 padalln pos-rel minhgt470">
    <div class="bg-white scrollhid mainwid pos-abs leftCenter pos-rel">
        <div class="fullhgt wid65p disp_ib rgtGreyBrd minhgt394">
            <!--first page:start-->
            <div id="mainDiv" class="padalln fontlig">
                <div id="searchBack" class="cursp f13 vishid colr2">Back</div>
                <div class="txtc fullwid f21 colr2">How can we help you?</div>
                <div class="txtc mt30">
                    <input type="text" id="searchInput" placeholder="What can we help with?" class="disp_ib wid70p colr2 f14 searchInp"></input>
                    <div id="searchBtn" class="wid100 txtc disp_ib bg_pink lh40 f16 colrw mln5 cursp hoverPink">SEARCH</div>
                </div>
                <div class="mt50"></div>
                <!--section listing:start-->
                <div id="sectionList" class="p4">
                    <div id="catTitle" class="f20 fontrobbold colr2">Categories</div>
                    <table id="catogaryTable" class="mt30 f14 fullwid catogaryTable">

                    </table>
                </div>
                <!--section listing:end-->
                <!--question search listing:start-->
                <div class="p4" id="searchQuesList">
                    <table class="quesTable2">

                    </table>
                </div>
                <!--question search listing:end-->
                <!--no result found div:start-->
                <div class="p4 mt20 disp-none" id="noResultDiv">
                    <div class="fullwid txtc">
                        <img class="disp_ib" src="/images/sad.png" />
                        <div class=" f20 disp_ib alignDiv fontreg colr2">No Result Found</div>
                    </div>
                    <div class="mt56"></div>
                    <div class="fullwid txtc f20 colr2">
                        <div id="postQuery" class="disp_ib cursp fontreg color5">Post your query</div>
                        <div class="disp_ib fontreg">and we will get back to you</div>
                    </div>
                    <table id="queryForm" class="fullwid mt30 p10 f15 formTable disp-none colr2">
                        <tr>
                            <td class="vertM"><span class="color5">*</span> Email</td>
                            <td>
                                <input type="text" class="colr2 f14 searchInp2 email"></input>
                            </td>
                        </tr>
                        <tr>
                            <td class="vertM">Catogary</td>
                            <td>
                                <div id="catDropDown" class="hgt34 wid326 pos-abs colr2 catDropDown"><span class="selectedDrop"> Please Select an option</span> <i class="chosen-container"></i>
                                    <ul class="dropOption disp-none">
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="vertM">Username</td>
                            <td>
                                <input type="text" class="colr2 f14 searchInp2"></input>
                            </td>
                        </tr>
                        <tr>
                            <td class="vertM"><span class="color5">*</span> Question</td>
                            <td>
                                <textarea class="hgt100 colr2 f14 searchInp2 question"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <div id="postBtn" class="wid100 txtc disp_ib bg_pink lh44 f20 fontreg colrw cursp mt10 hoverPink">Post</div>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--no result found div:end-->
            </div>
            <!--first page:start-->
            <!--section question listing:start-->
            <div id="sectionDiv" class="padalln fontlig disp-none">
                <div id="backBtn" class="cursp f13 colr2">Back</div>
                <div class="mt30"></div>
                <div id="sectionHeading" class="fullwid f21 colr2 fontreg padbtm5 bdrBtmGrey"></div>
                <div class="mt30"></div>
                <table class="quesTable">
                </table>
            </div>
            <!--section question listing:end-->
        </div>
        <!--right panel start-->
        <div class="fullhgt wid34p disp_ib vtop">
            <div class="padalln txtc fontlig">
                <div class="f16 colr2">Toll Free number</div>
                <div class="f20 color5">1-800-419-6299</div>
                <div class="f14 colr2">Daily between 7AM-11PM</div>
                <div class="mt70"></div>
                <div class="bg_pink lh40 f16 colrw centerBtn cursp hoverPink"><a class ="colrw fullhgt fullwid pos_rel disp_b" href="/contactus/index?fromSideLink=1">Live Help Chat</a></div>
                <div class="f14 colr2 mt10">Chat with our Customer Care</div>
                <div class="mt70"></div>
                <div class="bg_pink lh40 f16 colrw centerBtn cursp hoverPink js-openRequestCallBack">Request Callback</div>
                <div class="f14 colr2 mt10">Our Customer Care will get back to you</div>
            </div>
        </div>
        <!--right panel end-->
    </div>
</div>
~include_component('common', 'helpWidget',['hideHelpMenu'=>'true'])`
