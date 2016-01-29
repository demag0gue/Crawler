<?php

require path . 'lib/util/crawler.php';
require path . 'vendor/autoload.php';

class TeacherCrawler extends Crawler {

    private $output;

    public function crawl() {
        if($this->hasError())
            return;
        if(!$this->isLoggedIn()) {
            $this->setError(true);
            return;
        }

        if(!$this->downloadList())
            return;

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(path . 'saved/teacher/teacher.pdf');

        $content = explode(PHP_EOL, $pdf->getText());
        array_splice($content, 0, 1);

        $output = array();

        foreach($content as $line) {
            $parts = explode(' ', $line);
            $teacher = '';
            $list = array();
            foreach($parts as $part) {
                if(ctype_space($part) || $part == '')
                    continue;
                if(strlen($part) > 3 || ctype_lower($part) || strpos($part, '.') !== false) {
                    $teacher .= $part . ' ';
                    continue;
                }

                array_push($list, $part);
            }
            $teacher = rtrim($teacher);
            $output[$teacher] = $list;
        }

        $this->output = $output;
    }

    public function output() {
        return $this->output;
    }

    public function saveToFile($array) {
        $fp = fopen(path. 'saved/teacher/teacher.json', 'w');
        fwrite($fp, json_encode($array));
        fclose($fp);
    }

    private function downloadList() {
        curl_setopt($this->getCurl(), CURLOPT_URL, urlTeacher);
        $response = curl_exec($this->getCurl());
        $fp = fopen(path . 'saved/teacher/teacher.pdf', 'w');
        fwrite($fp, $response);

        if(curl_error($this->getCurl())) {
            $this->setError(true);
            return false;
        }

        return true;
    }

}