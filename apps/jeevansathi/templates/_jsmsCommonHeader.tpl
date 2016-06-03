<!--Fetching revision number from Auth filter in case of jsms and assigning it in script to be accessed in commonExpiration_js.js -->
~assign var=r_num value=$sf_request->getParameter('revisionNumber')`

<script type="text/javascript">
	var r_n_u_m = ~$r_num`;
	function getR_N_U_M(){
    return(r_n_u_m);
}
</script>