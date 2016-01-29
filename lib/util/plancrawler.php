<?php

require path . 'lib/util/crawler.php';
require path . 'lib/util/simple_html_dom.php';

class PlanCrawler extends Crawler {

    private $output;

    public function crawl() {
        if($this->hasError())
            return;
        if(!$this->isLoggedIn()) {
            $this->setError(true);
            return;
        }
        $this->output = array(array('date' => 'today', 'result' => array()), array('date' => 'tomorrow', 'result' => array()));
        $date = $this->getDate(urlToday . '1.htm');
        if($date == null) {
            $this->setError(true);
            return;
        }
        $this->output[0]['date'] = $date;
        for($page = 1; ; $page++) {
            if(!$this->crawlPage(urlToday . $page . '.htm', $page, 0) || $this->hasError())
                break;
        }

        $date = $this->getDate(urlTomorrow . '1.htm');
        if($date == null) {
            $this->setError(true);
            return;
        }
        $this->output[1]['date'] = $date;
        for($page = 1; ; $page++) {
            if(!$this->crawlPage(urlTomorrow . $page . '.htm', $page, 1) || $this->hasError())
                break;
        }
    }

    public function output() {
        return $this->output;
    }

    public function saveToFile($array) {
        $fp = fopen(path. 'saved/' . $array['date'] . '.json', 'w');
        fwrite($fp, json_encode($array['result']));
        fclose($fp);
    }

    private function crawlPage($url, $page, $array) {
        curl_setopt($this->getCurl(), CURLOPT_URL, $url);

        $response = curl_exec($this->getCurl());

        if(curl_error($this->getCurl())) {
            $this->setError(true);
            return false;
        }

        if(curl_getinfo($this->getCurl(), CURLINFO_HTTP_CODE) == 404) {
            if($page == 1)
               $this->setError(true);
            return false;
        }

        $html = content_get_html($response);

        $changes = $html->find('table', 2);

        if($changes == null)
            $changes = $html->find('table', 1);

        $first = true;

        foreach($changes->find('tr') as $row) {
            if($first) {
               $first = false;
                continue;
            }
            $data = array();
            $data['class'] = $row->find('td', 0)->plaintext;
            $data['hour'] = $row->find('td', 1)->plaintext;
            $data['new_teacher'] = $row->find('td', 2)->plaintext;
            $data['new_subject'] = $row->find('td', 3)->plaintext;
            $data['room'] = $row->find('td', 4)->plaintext;
            $data['status'] = $row->find('td', 5)->plaintext;
            $data['time'] = $row->find('td', 6)->plaintext;
            $data['teacher'] = $row->find('td', 7)->plaintext;
            $data['subject'] = $row->find('td', 8)->plaintext;
            array_push($this->output[$array]['result'], $data);
        }

        return true;

    }

    private function getDate($url) {
        if(!$this->isLoggedIn()) {
            $this->setError(true);
            return;
        }

        curl_setopt($this->getCurl(), CURLOPT_URL, $url);
        curl_setopt($this->getCurl(), CURLOPT_POST, false);
        curl_setopt($this->getCurl(), CURLOPT_POSTFIELDS, "");

        $response = curl_exec($this->getCurl());

        if(curl_error($this->getCurl())) {
            $this->setError(true);
            return;
        }

        if(curl_getinfo($this->getCurl(), CURLINFO_HTTP_CODE) == 404) {
            $this->setError(true);
            return;
        }

        $html = content_get_html($response);

        $info = $html->find('.mon_title', 0);

        $time = explode(" ", $info->plaintext)[0];

        return $time;
    }

}