<?php

namespace Pinterest;

use Exception;

class BookmarkManager {
    private $bookmark_map = [];

    public function addBookmark($primary, $bookmark, $secondary = null): void {
        if (!isset($this->bookmark_map[$primary])) {
            $this->bookmark_map[$primary] = [];
        }

        if ($secondary !== null) {
            $this->bookmark_map[$primary][$secondary] = $bookmark;
        } else {
            $this->bookmark_map[$primary] = $bookmark;
        }
    }

    public function getBookmark($primary, $secondary = null): ?string {
        try {
            if ($secondary !== null) {
                return $this->bookmark_map[$primary][$secondary];
            } else {
                return $this->bookmark_map[$primary];
            }
        } catch (Exception $e) {
            // Handle the exception if needed.
        }

        return null;
    }

    public function resetBookmark($primary, $secondary = null): void {
        if (isset($this->bookmark_map[$primary])) {
            if ($secondary !== null) {
                unset($this->bookmark_map[$primary][$secondary]);
            } else {
                unset($this->bookmark_map[$primary]);
            }
        }
    }
}