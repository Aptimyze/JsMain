<?php
interface NotificationEngine 
{
  public function sendNotification($registrationIds, $details, $profileid);
  public function checkAppleErrorResponse($object, $registrationId);

}
