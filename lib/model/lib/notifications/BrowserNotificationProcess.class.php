<?php

/*
 * Browser Notification Process Object class
 */
class BrowserNotificationProcess {
    private $method;
    private $subMethod;
    private $profiles;
    private $profileId;
    private $agentId;
    private $notificationKey;
    private $message;
    private $title;
    private $icon;
    private $tag;
    private $channel;
    private $landingUrl;
    private $landingId;
    private $selfUserId;
    private $otherUserId;
            
    function getmethod() {
        return $this->method;
    }

    function getsubMethod() {
        return $this->subMethod;
    }

    function getprofiles() {
        return $this->profiles;
    }

    function getprofileId() {
        return $this->profileId;
    }

    function getselfUserId() {
        return $this->selfUserId;
    }

    function getotherUserId() {
        return $this->otherUserId;
    }
    
    function getagentId() {
        return $this->agentId;
    }

    function getmessage() {
        return $this->message;
    }

    function gettitle() {
        return $this->title;
    }

    function geticon() {
        return $this->icon;
    }

    function gettag() {
        return $this->tag;
    }

    function getchannel() {
        return $this->channel;
    }

    function getlandingUrl() {
        return $this->landingUrl;
    }
    
    function getnotificationKey() {
        return $this->notificationKey;
    }

    function getlandingId() {
        return $this->landingId;
    }

    function setmethod($method) {
        $this->method = $method;
    }

    function setsubMethod($subMethod) {
        $this->subMethod = $subMethod;
    }

    function setprofiles($profiles) {
        $this->profiles = $profiles;
    }

    function setprofileId($profileId) {
        $this->profileId = $profileId;
    }

    function setagentId($agentId) {
        $this->agentId = $agentId;
    }

    function setmessage($message) {
        $this->message = $message;
    }

    function settitle($title) {
        $this->title = $title;
    }

    function seticon($icon) {
        $this->icon = $icon;
    }

    function settag($tag) {
        $this->tag = $tag;
    }

    function setchannel($channel) {
        $this->channel = $channel;
    }

    function setlandingUrl($landingUrl) {
        $this->landingUrl = $landingUrl;
    }
    
    function setnotificationKey($notificationKey) {
        $this->notificationKey = $notificationKey;
    }

    function setlandingId($landingId) {
        $this->landingId = $landingId;
    }

    function setselfUserId($selfUserId) {
        $this->selfUserId = $selfUserId;
    }

    function setotherUserId($otherUserId) {
        $this->otherUserId = $otherUserId;
    }

    function setDetails($paramsArr)
    {
        foreach ($paramsArr as $key => $value) {
            $this->{set.$key}($value);
        }
    }

}
