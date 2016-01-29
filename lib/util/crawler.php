<?php

class Crawler {

    private $curl;
    private $error;
    private $loggedIn;

    public function __construct() {
        $this->setLoggedIn(false);
        $this->setError(false);
    }

    public function login() {
        if($this->isLoggedIn())
            return;

        curl_setopt($this->getCurl(), CURLOPT_URL, 'https://ulricianum-aurich.de/idesk/');
        curl_setopt($this->getCurl(), CURLOPT_POST, true);
        curl_setopt($this->getCurl(), CURLOPT_POSTFIELDS, 'login_act=' . username . '&login_pwd=' . password);

        curl_exec($this->getCurl());

        if(curl_error($this->getCurl()))
            $this->setError(true);
        else
            $this->loggedIn = true;

        //Not sure if this is necessary
        curl_setopt($this->getCurl(), CURLOPT_POST, false);
        curl_setopt($this->getCurl(), CURLOPT_POSTFIELDS, '');
    }

    public function getCurl() {
        if($this->curl == null)
            $this->initializeCurl();
        return $this->curl;
    }

    private function initializeCurl() {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, path . 'cookie.txt');
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, path. 'cookie.txt');
    }

    public function hasError() {
        return $this->error;
    }

    public function setError($bol) {
        $this->error = $bol;
    }

    public function isLoggedIn() {
        return (useIserv) ? $this->loggedIn : true;
    }

    public function setLoggedIn($bol) {
        $this->loggedIn = $bol;
    }


}