<?php

class Teacher extends Module {

    public function crawl() {
        if(!$this->isVerified()) {
            $response = new Response(401, 'You are not verified! Check the documentation.');
            $response->build();
            return;
        }

        require path . 'lib/util/teachercrawler.php';

        $crawler = new TeacherCrawler();
        $crawler->login();
        $crawler->crawl();
        if($crawler->hasError()) {
            $response = new Response(500, 'Failed Crawling! Please contact the administrator.');
            $response->build();
            return;
        }

        $crawler->saveToFile($crawler->output());

        $response = new Response(200, 'Successfully crawled!');
        $response->build();

    }

    public function get() {
        $content = file_get_contents(path. 'saved/teacher/teacher.json');
        $response = new Response(200, json_decode($content));
        $response->build();
    }

}