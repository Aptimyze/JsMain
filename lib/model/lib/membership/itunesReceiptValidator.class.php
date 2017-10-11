<?php
class itunesReceiptValidator {

    function __construct($endpoint, $receipt = NULL) {
        $this->setEndPoint($endpoint);

        if ($receipt) {
            $this->setReceipt($receipt);
        }
    }

    function getReceipt() {
        return $this->receipt;
    }

    function setReceipt($receipt) {
        // if (strpos($receipt, '{') !== false) {
        //     $this->receipt = base64_encode($receipt);
        // } else {
            $this->receipt = $receipt;
        //}
    }

    function getEndpoint() {
        return $this->endpoint;
    }

    function setEndPoint($endpoint) {
        $this->endpoint = $endpoint;
    }

    function validateReceipt() {
        $response = $this->makeRequest();

        $decoded_response = $this->decodeResponse($response);

        if (!isset($decoded_response->status) || $decoded_response->status != 0) {
            return $decoded_response->status;
        }

        if (!is_object($decoded_response)) {
            return false;
        }

		return $decoded_response->receipt;
    }

    private function encodeRequest() {
        return json_encode(array('receipt-data' => $this->getReceipt()));
    }

    private function decodeResponse($response) {
        return json_decode($response);
    }

    private function makeRequest() {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->encodeRequest());

        $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT,"JsInternal");    

        $response = curl_exec($ch);

        // remove header from curl Response 
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $response = substr($response, $header_size);

        $errno    = curl_errno($ch);
        $errmsg   = curl_error($ch);
        curl_close($ch);

        if ($errno != 0) {
            throw new jsException('',$errmsg, $errno);
        }

        return $response;
    }
}
