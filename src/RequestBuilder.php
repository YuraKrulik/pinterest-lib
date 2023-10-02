<?php

namespace Pinterest;
class RequestBuilder {
    public function __construct() {
    }

    public function buildPost($options, $source_url = '/', $context = null) {
        return $this->url_encode(array(
            'source_url' => $source_url,
            'data' => json_encode(array(
                'options' => $options,
                'context' => $context
            )),
            '_' => time() * 1000
        ));
    }

    public function buildGet($url, $options, $source_url = '/', $context = null) {
        $data = $this->url_encode(array(
            'source_url' => $source_url,
            'data' => json_encode(array(
                'options' => $options,
                'context' => $context
            )),
            '_' => time() * 1000
        ));

        $url = $url . '?' . $data;
        return $url;
    }

    public function url_encode($query) {
        if (is_string($query)) {
            $query = urlencode($query);
        } else {
            $query = http_build_query($query);
        }
        $query = str_replace('+', '%20', $query);
        return $query;
    }
}