<?php 
error_reporting(0);
class cURL {
    var $img;
        var $json;

    function get($url) {
        $process = curl_init($url); 
        curl_setopt($process, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($process, CURLOPT_TIMEOUT, 60); 
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 2);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }

    function post($url, $data) {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($process, CURLOPT_TIMEOUT, 60);
        curl_setopt($process, CURLOPT_POST, TRUE);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 2);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }

    function poster() {
        $json = $this->json;
        $this->img = $json['img'];
        return $this->img;
    }
     function loadApi($url) {
        $ch = curl_init();
        $timeout = 20;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);

        $xix = [];
        $internalErrors = libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        $xpath = new DOMXPath($dom);
        $nlist = $xpath->query("//script");
        $fileurl = $nlist[0]->nodeValue;
        $diix = explode('var VIDEO_CONFIG = ', $fileurl);

        $ress = json_decode($diix[1], true);
        $xix['links'] = $ress['streams'];
        $xix['img'] = $ress['thumbnail'];
        $this->json = $xix;  
        return $this->json;
    }
}
?>