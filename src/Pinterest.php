<?php

namespace Pinterest;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;
use GuzzleHttp\Cookie\SetCookie;

class Pinterest {
    private $email;
    private $username;
    private $password;
    private $reqBuilder;
    private $bookmarkManager;
    private $http;
    private $proxies;
    private $user_agent;
    private $registry;

    const AGENT_STRING = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';

    public function __construct($password = '', $proxies = null, $username = '', $email = '', $cred_root = 'data', $user_agent = null) {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->reqBuilder = new RequestBuilder();
        $this->bookmarkManager = new BookmarkManager();
        $this->http = new Client();
        $this->proxies = $proxies;
        $this->user_agent = $user_agent;

        $this->registry = new Registry($cred_root, $email);

        $cookies = $this->registry->getAll();
        foreach ($cookies as $key => $value) {
            $cookieJar = new SessionCookieJar($this->email);
            $cookieJar->setCookie(new SetCookie([
                'Name' => $key,
                'Value' => $value,
            ]));
            $this->http = new Client(['cookies' => $cookieJar]);
        }

        if ($this->user_agent === null) {
            $this->user_agent = self::AGENT_STRING;
        }
    }

    public function request($method, $url, $data = null, $files = null, $extra_headers = null) {
        $headers = [
            'Referer' => PinterestEnum::HOME_PAGE,
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent' => $this->user_agent,
        ];

        $csrftoken = $this->http->getDefaultOption('cookies')->getCookieByName('csrftoken');
        if ($csrftoken) {
            $headers['X-CSRFToken'] = $csrftoken->getValue();
        }

        if ($extra_headers !== null) {
            $headers = array_merge($headers, $extra_headers);
        }

        $options = [
            'headers' => $headers,
            'proxy' => $this->proxies,
        ];

        if ($data !== null) {
            $options['form_params'] = $data;
        }

        if ($files !== null) {
            foreach ($files as $name => $file) {
                $options['multipart'][] = [
                    'name' => $name,
                    'contents' => fopen($file, 'rb'),
                ];
            }
        }

        $response = $this->http->request($method, $url, $options);
        $response->getBody()->rewind();

        return $response;
    }

    public function get($url) {
        return $this->request('GET', $url);
    }

    public function post($url, $data = null, $files = null, $headers = null) {
        return $this->request('POST', $url, $data, $files, $headers);
    }

    function get_board_followers($board_id, $page_size = 50, $source_url = null) {
        $next_bookmark = $this->bookmarkManager->getBookmark(
            "board_followers", $board_id
        );
        if ($next_bookmark === "-end-") {
            return [];
        }

        $options = [
            "isPrefetch" => false,
            "board_id" => $board_id,
            "page_size" => $page_size,
            "no_fetch_context_on_resource" => false,
        ];
        $url = $this->reqBuilder->buildGet(
            PinterestEnum::BOARD_FOLLOWERS, $options, $source_url
        );

        $response = json_decode($this->get($url)->getBody(), true);

        $bookmark = $response["resource"]["options"]["bookmarks"][0];
        $this->bookmarkManager->addBookmark(
            "board_followers", $board_id, $bookmark
        );

        return $response["resource_response"]["data"];
    }

    function getUserOverview($username = null) {
        if ($username === null) {
            $username = $this->username;
        }

        $options = [
            "isPrefetch" => "false",
            "username" => $username,
            "fieldSetKey" => "profile",
        ];
        $url = $this->reqBuilder->buildGet(PinterestEnum::USER_RESOURCE, $options);

        $result = json_decode($this->get($url)->getBody(), true);

        return $result["resourceResponse"]["data"];
    }
    function boards($username = null, $pageSize = 50, $resetBookmark = false) {
        if ($username === null) {
            $username = $this->username;
        }

        $nextBookmark = $this->bookmarkManager->getBookmark(
            "boards",
            $username
        );

        if ($nextBookmark === "-end-") {
            if ($resetBookmark) {
                $this->bookmarkManager->resetBookmark(
                    "boards",
                    $username
                );
            }
            return [];
        }

        $options = [
            "page_size" => $pageSize,
            "privacy_filter" => "all",
            "sort" => "custom",
            "username" => $username,
            "isPrefetch" => false,
            "include_archived" => true,
            "field_set_key" => "profile_grid_item",
            "group_by" => "visibility",
            "redux_normalize_feed" => true,
            "bookmarks" => [$nextBookmark],
        ];
        $sourceUrl = "/$username/boards/";
        $url = $this->reqBuilder->buildGet(
            PinterestEnum::BOARDS_RESOURCE,
            $options,
            $sourceUrl
        );

        $result = json_decode($this->get($url)->getBody(), true);

        $bookmark = $result["resource"]["options"]["bookmarks"][0];

        $this->bookmarkManager->addBookmark(
            "boards",
            $username,
            $bookmark
        );
        return $result["resourceResponse"]["data"];
    }

    function boardsAll($username = null): array
    {
        $boards = [];
        $boardBatch = $this->boards($username);

        while (count($boardBatch) > 0) {
            $boards = array_merge($boards, $boardBatch);
            $boardBatch = $this->boards($username);
        }

        return $boards;
    }

    function getUserPins($username = null, $pageSize = 250, $resetBookmark = false) {
        if ($username === null) {
            $username = $this->username;
            $ownProfile = true;
        } else {
            $ownProfile = false;
        }

        $nextBookmark = $this->bookmarkManager->getBookmark(
            "pins",
            $username
        );

        if ($nextBookmark === "-end-") {
            if ($resetBookmark) {
                $this->bookmarkManager->resetBookmark(
                    "pins",
                    $username
                );
            }
            return [];
        }

        $options = [
            "username" => $username,
            "is_own_profile_pins" => $ownProfile,
            "field_set_key" => "grid_item",
            "pin_filter" => null,
            "bookmarks" => [$nextBookmark],
            "page_size" => $pageSize,
        ];

        $url = $this->reqBuilder->buildGet(
            PinterestEnum::USER_PIN_RESOURCE,
            $options
        );

        $response = json_decode($this->get($url)->getBody(), true);
        $bookmark = $response["resource"]["options"]["bookmarks"][0];

        $this->bookmarkManager->addBookmark(
            "pins",
            $username,
            $bookmark
        );

        return $response["resource_response"]["data"];
    }

    function createBoard(
        $name,
        $description = "",
        $category = "other",
        $privacy = "public",
        $layout = "default"
    ) {
        $options = [
            "name" => $name,
            "description" => $description,
            "category" => $category,
            "privacy" => $privacy,
            "layout" => $layout,
            "collab_board_email" => "true",
            "collaborator_invites_enabled" => "true",
        ];

        $sourceUrl = "/{$this->email}/boards/";
        $data = $this->reqBuilder->buildPost(["options" => $options, "source_url" => $sourceUrl]);

        return $this->post(PinterestEnum::CREATE_BOARD_RESOURCE, $data);
    }

    function pin(
        $boardId,
        $imageUrl,
        $description = "",
        $link = "",
        $title = "",
        $altText = "",
        $sectionId = null
    ) {
        $options = [
            "board_id" => $boardId,
            "image_url" => $imageUrl,
            "description" => $description,
            "link" => $link ? $link : $imageUrl,
            "scrape_metric" => ["source" => "www_url_scrape"],
            "method" => "uploaded",
            "title" => $title,
            "alt_text" => $altText,
            "section" => $sectionId,
        ];
        $sourceUrl = "/pin/find/?url=" . $this->reqBuilder->url_encode($imageUrl);
        $data = $this->reqBuilder->buildPost($options, $sourceUrl);
        $url = PinterestEnum::PIN_RESOURCE_CREATE;

        return $this->post($url, $data);
    }

//    function upload_pin($board_id, $image_file, $description = "", $link = "", $title = "", $alt_text = "", $section_id = null) {
//        $image_url = $this->_upload_image($image_file)->json()["image_url"];
//        return $this->pin(
//            $board_id,
//            $image_url,
//            $description,
//            $link,
//            $title,
//            $alt_text,
//            $section_id
//        );
//    }

    function repin($board_id, $pin_id, $section_id = null) {
        $options = [
            "board_id" => $board_id,
            "pin_id" => $pin_id,
            "section" => $section_id,
            "is_buyable_pin" => false,
        ];
        $source_url = "/pin/{$pin_id}/";
        $data = $this->reqBuilder->buildPost($options, $source_url);
        return $this->post(PinterestEnum::REPIN_RESOURCE_CREATE, $data);
    }

    function boardFeed($board_id = "", $page_size = 250, $reset_bookmark = false) {
        $next_bookmark = $this->bookmarkManager->getBookmark(
            "board_feed",
            $board_id
        );

        if ($next_bookmark === "-end-") {
            if ($reset_bookmark) {
                $this->bookmarkManager->resetBookmark(
                    "board_feed",
                    $board_id
                );
            }
            return [];
        }

        $options = [
            "isPrefetch" => false,
            "board_id" => $board_id,
            "field_set_key" => "partner_react_grid_pin",
            "filter_section_pins" => true,
            "layout" => "default",
            "page_size" => $page_size,
            "redux_normalize_feed" => true,
            "bookmarks" => [$next_bookmark]
        ];

        $url = $this->reqBuilder->buildGet(PinterestEnum::BOARD_FEED_RESOURCE, $options);
        $response = $this->get($url);
        $data = json_decode($response->getBody(), true);

        $bookmark = $data["resource"]["options"]["bookmarks"][0];
        $this->bookmarkManager->addBookmark(
            "board_feed",
            $board_id,
            $bookmark
        );

        return $data["resource_response"]["data"];
    }
    // The rest of the Pinterest class methods remain the same...
}