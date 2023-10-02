<?php

namespace Pinterest;

use Exception;

class Registry {
    private $path = null;
    private array $cookies = [];

    public function __construct(private $root, private $username) {
        $this->root = $root;
        $this->username = $username;

        $credFilePath = $this->getCredFilePath();
        if (is_dir($credFilePath)) {
            $this->removeDirectory($credFilePath);
        }

        if (!file_exists($root)) {
            mkdir($root, 0777, true);
        }

        try {
            $content = file_get_contents($credFilePath);
            $this->cookies = json_decode($content, true);
        } catch (Exception $e) {
            echo "No credentials stored " . $e->getMessage();
        }
    }

    public function get($cookie_name) {
        return isset($this->cookies[$cookie_name]) ? $this->cookies[$cookie_name] : null;
    }

    public function getAll() {
        return $this->cookies;
    }

    public function updateAll($cookie_dict) {
        $this->cookies = $cookie_dict;
        $this->persist();
    }

    public function set($key, $value) {
        $this->cookies[$key] = $value;
        $this->persist();
    }

    private function persist() {
        $credFilePath = $this->getCredFilePath();
        echo "Reading credential from " . $credFilePath;
        file_put_contents($credFilePath, json_encode($this->cookies));
    }

    private function getCredFilePath() {
        return $this->root . DIRECTORY_SEPARATOR . $this->username;
    }

    private function removeDirectory($path) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
    }
}