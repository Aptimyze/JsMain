~if $apiData['about']['documents_provided'] neq '' && $apiData['about']['documents_provided'] neq '1'`
<div class="prfbg3 prfwid12 fontlig mb15">
<div class="bg-white prfwid12 fontlig">
                	<div class="disp-tbl prfbr2 color11 fontlig fullwid pb20 pt49">
                    	<div class="f17 disp-cell vbtm pl20">  Documents Provided</div>
                    </div>
                    <div class="prfp12 f14">
                        
                    	<ul class="listn documents">
                        ~if $apiData['about']['documents_provided']['VERIFICATION_SEAL']['Qualification'] neq ""`
                            <li>
                                <p class="color12">Education</p>
                                <p class="color11">~$apiData['about']['documents_provided']['VERIFICATION_SEAL']['Qualification']`</p>
                            </li>
                        ~/if`
                        ~if $apiData['about']['documents_provided']['address'] neq ""`
                            <li>
                                <p class="color12">Address</p>
                                <p class="color11">~$apiData['about']['documents_provided']['address']`</p>
                            </li>
                        ~/if`
                         
                        ~if $apiData['about']['documents_provided']['VERIFICATION_SEAL']['Date_of_Birth'] neq ""`
                            <li>
                                <p class="color12">Age</p>
                                <p class="color11">~$apiData['about']['documents_provided']['VERIFICATION_SEAL']['Date_of_Birth']`</p>
                            </li>
                        ~/if`
                        ~if $apiData['about']['documents_provided']['VERIFICATION_SEAL']['Income'] neq ""`
                            <li>
                                <p class="color12">Income</p>
                                <p class="color11">~$apiData['about']['documents_provided']['VERIFICATION_SEAL']['Income']`</p>
                            </li>
                        ~/if`
                        ~if $apiData['about']['documents_provided']['VERIFICATION_SEAL']['Divorce'] neq ""`
                            <li>
                                <p class="color12">Marital Status</p>
                                <p class="color11">~$apiData['about']['documents_provided']['VERIFICATION_SEAL']['Divorce']`</p>
                            </li>
                        ~/if`
                        </ul>
                        </div>                    
                    </div>                
                </div>                
~/if`
