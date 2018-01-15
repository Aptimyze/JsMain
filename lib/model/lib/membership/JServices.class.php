<?php
class JServices extends Services
{
    public function activateLoggedServicesForOnline($servicesStr) {
        $servicesObj = new billing_SERVICES();
        $servicesObj->deActivateShowOnlineForServices();
        $servicesObj->activateShowOnlineForServices($servicesStr);
    }
}
?>
