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
        height: 400px;
    }

    div.desc {
        padding: 15px;
        float: right;
    }


    .marLeft35Per {
        text-align: center;
    }
</style>
~include_partial('global/header')`
<br>


<div id ="container">
    <div id='content' >
        <form id="form" method="post" onsubmit="formSubmit(this); return false;">
            <input type=hidden name="cid" value="~$cid`">
            <input type=hidden name="name" value="~$name`">
            <input type=hidden name="pid" value="~$details.Id`">
            <div>
                <div class="gallery">
                    <a target="_blank" href="~$details.imgs`">
                        <img src="~$details.imgs`" >
                    </a>
                    <!-- Pic Description -->
                    <div class="desc">
                        <div style="float: left"> Details
                            <table border="1">
                                <tr><td>Face Count</td><td>~$details.facecount`</td></tr>
                                <tr><td>Adult</td><td>~$details.adult`</td></tr>
                                <tr><td>Spoof</td><td>~$details.spoof`</td></tr>
                                <tr><td>Violence</td><td>~$details.violence`</td></tr>
                            </table>
                            ~if $details.facecount gt 0`
                            ~foreach from=$details.faces item=photo key=k`
                            Face ~$k+1`
                            <table border="1">
                                <tr><td>Blur</td><td>~$photo.BLUR`</td></tr>
                                <tr><td>Pan Angle</td><td>~$photo.PAN_ANGLE`</td></tr>
                                <tr><td>Roll Angle</td><td>~$photo.ROLL_ANGLE`</td></tr>
                                <tr><td>Tilt Angle</td><td>~$photo.TILT_ANGLE`</td></tr>
                                <tr><td>Under Exposed</td><td>~$photo.UNDEREXPOSED`</td></tr>

                            </table>
                            ~/foreach`
                            ~/if`
                        </div>
                        <div style="float: right; display: none;" id="deletereason">
                            <table>
                                <tr class="deleteReasonShow" id="deleteReasonsArea" style="display: table-row;">
                                    <td>
                                        <table>
                                            <tbody><tr> <td colspan="2">Please select reason for picture deletion - <br></td>
                                            </tr>
                                            <fieldset id="checkArray">
                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons0" value="0" type="checkbox" tabindex="1"></td><td> The photo is not clear.<br></td>
                                            </tr>

                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons1" value="1" type="checkbox" tabindex="2"></td><td> We find that the photo you have submitted is inappropriate.<br></td>
                                            </tr>

                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons2" value="2" type="checkbox" tabindex="3"></td><td> The photo is of a well known personality. If it is yours, submit an identity.<br></td>
                                            </tr>


                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons4" value="4" type="checkbox" tabindex="5"></td><td> Group photo.<br></td>
                                            </tr>

                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons6" value="6" type="checkbox" tabindex="7"></td><td> Obscene photo.<br></td>
                                            </tr>

                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons7" value="7" type="checkbox" tabindex="8"></td><td> Side face.<br></td>
                                            </tr>

                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons8" value="8" type="checkbox" tabindex="9"></td><td> Attachment error.<br></td>
                                            </tr>

                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons9" value="9" type="checkbox" tabindex="10"></td><td> Small size / size is not proper.<br></td>
                                            </tr>



                                            <tr class="deleteReasonShow" style="">
                                                <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons11" value="11" type="checkbox" tabindex="12"></td><td> Edited/Morphed photo.<br></td>
                                            </tr>

                                            <tr class="deleteReasonShow" style="">
                                                    <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons12" value="12" type="checkbox" tabindex="13"></td><td> Watermarked photo.<br></td>
                                            </tr>
                                            </fieldset>

                                            <tr>
                                                <td colspan="2">
                                                    <span style="color: red;">*Alteast 1 reason is required</span>
                                                </td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                    <div style="clear:both"></div>
                    <div class="marLeft35Per">
                        <input type="radio" name="edit" value="approve" checked>

                        <label> Approve </label>

                        <input type="radio" name="edit" value="delete" >
                        <label> Delete </label>
                    </div>
                    <div  class="marLeft35Per">
                        <input type="submit" id="benchmark-form"/>
                    </div>
        </form>
    </div>
</div>




<script>
    var cid = "~$cid`";
    var name = "~$name`";


</script>
~include_partial('global/footer')`
</body>
