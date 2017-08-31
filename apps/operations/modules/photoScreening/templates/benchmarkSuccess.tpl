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
</style>
~include_partial('global/header')`
 <br>

 
 
    <div id='row_1' class="disp-none">
        <form id="form_row_1" method="post" onsubmit="formSubmit(this); return false;">
            ~foreach from = $arrPic key=imgType item=imgSrc`
            <div class="gallery">
                <a target="_blank" href="~$imgSrc`">
                <img src="~$imgSrc`" >
                </a>
                <!-- Pic Description -->
                <div class="desc"> ~$imgType` </div>
            </div>
            ~/foreach`
            <br>
            <input type="radio" name="undefined" value="Approve" checked="checked">
            <label> Approve </label>
            
            <input type="radio" name="undefined" value="edit" checked="checked">
            <label> Edit </label>
            
            <input type="submit" id="row_1"/>
        </form>
    </div>
 
 
 

~include_partial('global/footer')`
 </body>
