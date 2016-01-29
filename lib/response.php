<?php

class Response {

    private $status, $content;

    public function __construct($status, $content) {
        $this->status = $status;
        $this->content = $content;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getContent() {
        return $this->content;
    }

    public function build() {
        header('Content-type: application/json');
        echo json_encode(array('response' => array('status' => $this->status, 'message' => $this->content), 'timestamp' => time()));
    }

}