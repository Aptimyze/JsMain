/*This javascript triggers a self executing function that calls getR_N_U_M() which returns the revision number and then
 *matches the condition to check whether lcoalStorage is to be cleared or not
 */
(function(){
	if(typeof getR_N_U_M === "function" && getR_N_U_M()!="0"){
		if(localStorage.getItem("r_N_U_M")==null || localStorage.getItem("r_N_U_M")!=getR_N_U_M()){
			localStorage.clear();
			localStorage.setItem("r_N_U_M",getR_N_U_M());
		}
	}

})();