<script>    
    $('.loader').addClass('simple').addClass('dark').addClass('image');
</script>
<input type="hidden" name="channel" id="channel" value=~$channel`>
<div ng-app="regApp" ng-element-ready="">
	<div ng-view class=" slide {{slideDir}} "></div>
</div>
<img class="dn" src="~sfConfig::get('app_img_url')`/images/jsms/commonImg/loader.gif"/>
<img  class="dn" src="~sfConfig::get('app_img_url')`/images/jsms/registerImg/regis_sp.png"/>
<script>
var appPromo = 1;
var view = "~$szLandOnView`";	
var screenValue = parseInt(window.location.hash.split('s')[1]);

~if isset($isLogin) && !$isLogin`//if not login
if( screenValue > 5 &&  view.length == 0)
{
  window.location.hash = '#/s1';  
}
~/if`
~if isset($szLandOnView)`
	window.location.hash = "~$szLandOnView`";
~/if`
var myTmpStorage = new SessionStorage('country_res,city_res,height,mtongue,reg_caste_,edu_level_new,occupation,income,reg_mstatus,religion,isd,degree_grouping_reg');
myTmpStorage.storeUserData('trackServerParams',JSON.stringify(~$track|decodevar`));

~if isset($familyJsonData)`
myTmpStorage.storeUserData('_familyData',JSON.stringify(~$familyJsonData|decodevar`));
~/if`
~if isset($familyIncomeDep)`
myTmpStorage.storeUserData('familyIncomeDep',JSON.stringify(~$familyIncomeDep|decodevar`));
~/if`
delete myTmpStorage;
~if $sourcename && $groupname`
	~include_partial("global/gtm",['groupname'=>$groupname,'sourcename'=>$sourcename,'age'=>'','mtongue'=>'','city'=>''])`
~/if`
</script>
