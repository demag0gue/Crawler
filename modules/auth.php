<?php

class Auth extends Module {

    public function verify($token) {
        if($token == null) {
            $response = new Response(400, 'No Token found! Check the documentation.');
            $response->build();
            return;
        }

        $stmt = $this->getDatabase()->prepare('SELECT COUNT(*) FROM verify WHERE code = ? LIMIT 1');
        $stmt->bindParam(1, $token);
        $stmt->execute();

        if($stmt->fetchColumn()) {
            session_start();
            $_SESSION['time'] = time();
            $response = new Response(200, 'Token is valid. Session is alive for 2 minutes.');
            $response->build();
        } else {
            $response = new Response(403, 'Token is invalid.');
            $response->build();
        }
    }

    public function keepAlive() {
        if(!$this->isVerified()) {
            $response = new Response(401, 'You are not verified! Check the documentation.');
            $response->build();
            return;
        }
        $_SESSION['time'] = time();
        $response = new Response(200, 'Session is keeped alive for 2 minutes.');
        $response->build();

    }

}