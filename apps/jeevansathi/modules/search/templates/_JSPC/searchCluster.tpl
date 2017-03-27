<!-- main cluster div-->
<div id="indClusterStructure">
<div class="mt1">
	<div class="srpbg1 color11 clusterView cursp">
		<div class="showedu srppad8 disp-tbl">
			<div class="disp-cell vmid"><i class="fl sprite2 srpimg1 js-up cursp" id="{clusterName}"></i></div>
			<div class="disp-cell vmid f13 pl15 uppercase bold srpwid7 clusterName">{clusterName}</div>
		</div>
	</div>

	<div class="srpbg2 suboption sideClusterHS sideClusters eduopt" id="js-clusterHead{clusterTopLevelId}">
		<ul>
                {clustersOptions}               
		</ul>
		<div class="txtr sprpb1 f11 fontreg colr2 cursp moreCluster" id="moreid{clusterTopLevelId}" style="width: 70px; margin-left: 160px;"> {clustersMoreCount} </div>
	</div>
</div>
</div>
<!-- main cluster div-->

<!-- cluster multiple options -->
<div id="clusterOptionsStructure">
  <li class="{js-clusterOptionsShowHide}" js-cluster="1">
   <span class="custom-checkbox {tickClass}">
   <input class="js-cluster" {ifclusterchoosen} type="checkbox" name="appCluster{clusterTopLevelId}[]" value="{clusterArrName}">
   </span>
   <a href="#" class="js-clusterCheckboxSelector">{clusterOptName}</a>
   <span class="js-clusterCheckboxSelector">{clusterOptCount}</span> 
  </li>
</div>
<!-- cluster multiple options -->

<!-- cluster slider structure -->
<div id="sliderClusterStructure">
    <div class="mt1">
	<!--start:slider box-->
        <div class="srpbg1 color11 clusterView cursp">
			<div class="showedu srppad8 disp-tbl">
				<div class="disp-cell vmid"><i id="{clusterName}" class="fl sprite2 srpimg1"></i></div>
				<div class="disp-cell vmid f13 uppercase bold pl15 srpwid6">{clusterName}</div>
			</div>
        </div>
        <div class="srpbg2 srppad9 sideClusterHS  fontlig">
			<div class="pt20">              	                
			<!--start:slider loop-->
            {sliderSubTypesStructure}
			<!--end:slider loop-->             
			</div>
        </div>
      <!--end:slider box-->
    </div>
</div> 
<!-- cluster slider structure -->

<!-- slider bar structure -->
<div id="sliderBarStructure" >
	<div id="{sliderTypeName}">  
		<div class="clearfix ">	
			<div class="fl f13 allcaps pt5">{sliderTypeName}</div>				
			<div class="fr opa50">
			  <input type="text" value="" disabled class="filinp1 brdrinp1 f9 txtc" id="{sliderTypeNameId}minfield"/>
			  -
			  <input type="text" value="" disabled class="filinp1 brdrinp1 f9 txtc" id="{sliderTypeNameId}maxfield"/>
			  <input type="hidden" value={sliderMinValue} class="filinp1 brdrinp1 f9 txtc" id="{sliderTypeNameId}Hiddenminfield"/>
			  <input type="hidden" value={sliderMaxValue} class="filinp1 brdrinp1 f9 txtc" id="{sliderTypeNameId}Hiddenmaxfield"/>
			  <input type="hidden" value={clusterID} class="filinp1 brdrinp1 f9 txtc" id="{sliderTypeNameId}HiddenID"/>
			</div>
		</div>
		<div class="pt30 sideClusters sideCluster{onlyClusterID}">
			<div class="fullwid pos-rel srpght11">
			  <input type="text" id="{sliderTypeNameId}slider" value="" name="range" />
			</div>
		</div>                  
	</div>
	<div class="pt30"></div>
</div>
<!-- slider bar structure -->

<!--start:no Cluster section main div-->
<div id="noClusterSection">	
</div>
<!--end:no Cluster section main div-->

<!--start:no Cluster section basic div-->
<div id="noClusterBasicStructure" class="disp-none">
	<div class="srpbg2 mt13">
		<div class="srpboxp1">
			<div id="srpNoClusterHeading" class="f17 fontlig color11 txtc pb15">{heading}</div>
			<div class="srpbdim1 mauto bg5 srpbrad1 txtc {js-hideCircle}">
				<div id="srpNoClusterCount" class="disp-cell vmid fontthin f50 colrw">{totalCount}</div>
			</div>
			<div id="srpNoClusterMessage" class="pt20 txtc fontlig color2 f15 lh22">
				{message}
			</div>
		</div>
	</div> 
</div>
<!--end:no Cluster section basic div-->
