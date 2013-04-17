<?php

class Twonet {

    private $DEBUG = false;

    private $endpoint;
    private $auth;

    public function __construct($endpoint, $key, $secret) {
        $this->endpoint = $endpoint;
        $this->auth = base64_encode($key . ':' . $secret);
        if ($this->DEBUG) echo "DEBUG: INIT ${endpoint}\n";
    }

    protected function twonet_headers() {
        return "Authorization: Basic " . $this->auth . "\r\n" .
            "Accept: application/json\r\n" .
            "Content-type: application/json\r\n";
    }

    protected function twonet_get($uri) {
        $opts = array('http' => array(
            'method' => 'GET',
            'header' => $this->twonet_headers()));
        $context = stream_context_create($opts);
        $content = file_get_contents($this->endpoint . $uri, false, $context);
        if ($this->DEBUG) echo "DEBUG: GET ${uri} => ${content}\n";
        return json_decode($content, true);
    }

    protected function twonet_delete($uri) {
        $opts = array('http' => array(
            'method' => 'DELETE',
            'header' => $this->twonet_headers()));
        $context = stream_context_create($opts);
        $content = file_get_contents($this->endpoint . $uri, false, $context);
        if ($this->DEBUG) echo "DEBUG: DELETE ${uri} => ${content}\n";
        return json_decode($content, true);
    }

    protected function twonet_post($uri, $body) {
    	
        $opts = array('http' => array(
            'method' => 'POST',
            'header' => $this->twonet_headers(),
            'content' => json_encode($body)));
        $context = stream_context_create($opts);
        $content = file_get_contents($this->endpoint . $uri, false, $context);
        if ($this->DEBUG) echo "DEBUG: POST ${uri} + " . json_encode($body) . " => ${content}\n";
        return json_decode($content, true);
    }
}

?>
