<?php

class App {

    public function __construct() {
        if(@$_GET['url'] == null) {
            $response = new Response(400, 'You are wrong here! Check the documentation.');
            $response->build();
            return;
        }

        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        if(!file_exists(path . 'modules/' . $url[0] . '.php')) {
            $response = new Response(404, 'Module ' . $url[0] . ' does not exist! Check the documenation.');
            $response->build();
            return;
        }

        require path . 'modules/' . $url[0] . '.php';

        $module = new $url[0]();
        if($url[1] == null || !method_exists($module, $url[1])) {
            $response = new Response(404, 'You are wrong here. Check the documentation.');
            $response->build();
            return;
        }

        switch(sizeof($url) - 2) {
            case 0:
                $module->$url[1]();
                break;
            case 1:
                $module->$url[1]($url[2]);
                break;
            case 2:
                $module->$url[1]($url[2], $url[3]);
                break;
            case 3:
                $module->$url[1]($url[2], $url[3], $url[4]);
                break;
        }

    }

}