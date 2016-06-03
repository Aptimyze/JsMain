<div class="hoverlayer">
    <a href="~sfConfig::get('app_site_url')`/static/agentinfo" rel="nofollow" class="pos-abs agentinfo alpha60 verifiedlayer fs18" style="bottom:0;width:100%;">
        <div class="verlogo-band">Verified</div>
    </a>
    <a href="~sfConfig::get('app_site_url')`/static/agentinfo" rel="nofollow" class="agentinfo">
        <div class="verfdisp">
            <div class="pos-abs verfpos1">
                <div class="verf_hover pos-rel verwid2">
                    <div class="pos-abs verficon1"></div>
                    <div class="verfcol1 fs16">
                        Verified by personal visit
                    </div>
                    ~if $detailsArr['VERIFICATION_SEAL'] neq '1'`
                    <div class="pad5top">
                        <table style="width: 100%;">
                            ~foreach  from=$detailsArr['VERIFICATION_SEAL'] item=doc key=seal`
                            <tr class="clearfix verfpadb">
                                <td class="fs12 verfcol2 verwid1">~$seal`</td>
                                <td class="fs12 verfcol2">:</td>
                                <td class="fs12 verfcol2" style="width: 60%;">~$doc`</td>
                            </tr>

                            ~/foreach`
                        </table>
                    </div>
                    ~/if`
                </div>
            </div>
        </div>
    </a>
</div>