<?php

class Plan extends Module {

    public function crawl() {
        if(!$this->isVerified()) {
            $response = new Response(401, 'You are not verified! Check the documentation.');
            $response->build();
            return;
        }

        require path . 'lib/util/plancrawler.php';

        $crawler = new PlanCrawler();
        $crawler->login();
        $crawler->crawl();
        if($crawler->hasError()) {
            $response = new Response(500, 'Failed Crawling! Please contact the administrator.');
            $response->build();
            return;
        }
        $crawler->saveToFile($crawler->output()[0]);
        $crawler->saveToFile($crawler->output()[1]);

        $response = new Response(200, 'Successfully crawled!');
        $response->build();
    }

    public function get($date=null) {
        if($date == null)
            $date = date('j.n.Y');

        if(file_exists(path . 'saved/' . $date . '.json')) {
            $content = file_get_contents(path. 'saved/' . $date . '.json');
            $response = new Response(200, json_decode($content));
            $response->build();
        } else {
            $response = new Response(404, 'Date ' . $date . ' does not exist.');
            $response->build();
        }
    }

}