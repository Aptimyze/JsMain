<style>


disp-none {
    display: none
}

div.gallery {
    margin: 5px;
    border: 1px solid #ccc;
    float: left;
    
}

div.gallery:hover {
    border: 1px solid #777;
}

div.gallery img {
    width: 100%;
    height: auto;
}

div.desc {
    padding: 15px;
    text-align: center;
}

.marLeft15Per {
    margin-left: 15%
}

.marLeft35Per {
    margin-left: 35%
}
</style>
~include_partial('global/header')`
 <br>

 
 <div id ="container">
     <div id='content' class="marLeft15Per">
        <form id="form" method="post" onsubmit="formSubmit(this); return false;">
            <div>
            ~foreach from = $arrPic key=imgType item=imgSrc`
            <div class="gallery">
                <a target="_blank" href="~$imgSrc`">
                <img src="~$imgSrc`" >
                </a>
                <!-- Pic Description -->
                <div class="desc"> ~$imgType` </div>
            </div>
            ~/foreach`
            </div>
            <div style="clear:both"></div>
            <div class="marLeft35Per">
                <input type="radio" value="approve" checked="checked">
                <label> Approve </label>

                <input type="radio" value="edit" >
                <label> Edit </label>
            </div>
            <div  class="marLeft35Per">
                <input type="submit" id="~$picId`"/>
            </div>
        </form>
    </div>
 </div>
    
 
 
 
 <script></script>
~include_partial('global/footer')`
 </body>
