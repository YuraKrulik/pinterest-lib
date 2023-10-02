<?php

namespace Pinterest;

class PinterestEnum
{
    const HOME_PAGE = "https://www.pinterest.com/";
    const LOGIN_PAGE = "https://www.pinterest.com/login/?referrer=home_page";
    const CREATE_USER_SESSION = "https://www.pinterest.com/resource/UserSessionResource/create/";
    const DELETE_USER_SESSION = "https://www.pinterest.com/resource/UserSessionResource/delete/";
    const USER_RESOURCE = "https://www.pinterest.com/_ngjs/resource/UserResource/get/";
    const BOARD_PICKER_RESOURCE = "https://www.pinterest.com/resource/BoardPickerBoardsResource/get/";
    const BOARDS_RESOURCE = "https://www.pinterest.com/_ngjs/resource/BoardsResource/get/";
    const CREATE_BOARD_RESOURCE = "https://www.pinterest.com/resource/BoardResource/create/";
    const FOLLOW_BOARD_RESOURCE = "https://www.pinterest.com/resource/BoardFollowResource/create/";
    const UNFOLLOW_BOARD_RESOURCE = "https://www.pinterest.com/resource/BoardFollowResource/delete/";
    const FOLLOW_USER_RESOURCE = "https://www.pinterest.com/resource/UserFollowResource/create/";
    const UNFOLLOW_USER_RESOURCE = "https://www.pinterest.com/resource/UserFollowResource/delete/";
    const USER_FOLLOWING_RESOURCE = "https://www.pinterest.com/_ngjs/resource/UserFollowingResource/get/";
    const USER_FOLLOWERS_RESOURCE = "https://www.pinterest.com/resource/UserFollowersResource/get/";
    const PIN_RESOURCE_CREATE = "https://www.pinterest.com/resource/PinResource/create/";
    const REPIN_RESOURCE_CREATE = "https://www.pinterest.com/resource/RepinResource/create/";
    const PIN_LIKE_RESOURCE = "https://www.pinterest.com/resource/PinLikeResource/create/";
    const PIN_UNLIKE_RESOURCE = "https://www.pinterest.com/resource/PinLikeResource/delete/";
    const DELETE_PIN_RESOURCE = "https://www.pinterest.com/resource/PinResource/delete/";
    const PIN_COMMENT_RESOURCE = "https://www.pinterest.com/resource/PinCommentResource/create/";
    const BOARD_INVITE_RESOURCE = "https://www.pinterest.com/_ngjs/resource/BoardInviteResource/create/";
    const BOARD_DELETE_INVITE_RESOURCE = "https://www.pinterest.com/_ngjs/resource/BoardCollaboratorResource/delete/";
    const VISUAL_LIVE_SEARCH_RESOURCE = "https://www.pinterest.com/resource/VisualLiveSearchResource/get/";
    const SEARCH_RESOURCE = "https://www.pinterest.com/resource/SearchResource/get/";
    const TYPE_AHEAD_RESOURCE = "https://www.pinterest.com/resource/AdvancedTypeaheadResource/get/";
    const BOARD_RECOMMEND_RESOURCE = "https://www.pinterest.com/_ngjs/resource/BoardContentRecommendationResource/get/";
    const PINNABLE_IMAGES_RESOURCE = "https://www.pinterest.com/_ngjs/resource/FindPinImagesResource/get/";
    const BOARD_FEED_RESOURCE = "https://www.pinterest.com/resource/BoardFeedResource/get/";
    const USER_HOME_FEED_RESOURCE = "https://www.pinterest.com/_ngjs/resource/UserHomefeedResource/get/";
    const USER_PIN_RESOURCE = "https://www.pinterest.com/resource/UserPinsResource/get/";
    const BASE_SEARCH_RESOURCE = "https://www.pinterest.com/resource/BaseSearchResource/get/";
    const BOARD_INVITES_RESOURCE = "https://www.pinterest.com/_ngjs/resource/BoardInvitesResource/get/";
    const CREATE_COMMENT_RESOURCE = "https://www.pinterest.com/_ngjs/resource/AggregatedCommentResource/create/";
    const GET_PIN_COMMENTS_RESOURCE = "https://www.pinterest.com/_ngjs/resource/AggregatedCommentFeedResource/get/";
    const LOAD_PIN_URL_FORMAT = "https://www.pinterest.com/pin/{}/";
    const DELETE_COMMENT = "https://www.pinterest.com/_ngjs/resource/AggregatedCommentResource/delete/";
    const CONVERSATION_RESOURCE = "https://www.pinterest.com/resource/ConversationsResource/get/";
    const CONVERSATION_RESOURCE_CREATE = "https://www.pinterest.com/resource/ConversationsResource/create/";
    const LOAD_CONVERSATION = "https://www.pinterest.com/resource/ConversationMessagesResource/get/";
    const SEND_MESSAGE = "https://www.pinterest.com/resource/ConversationMessagesResource/create/";
    const BOARD_SECTION_RESOURCE = "https://www.pinterest.com/resource/BoardSectionResource/create/";
    const GET_BOARD_SECTIONS = "https://www.pinterest.com/resource/BoardSectionsResource/get/";
    const BOARD_SECTION_EDIT_RESOURCE = "https://www.pinterest.com/resource/BoardSectionEditResource/delete/";
    const GET_BOARD_SECTION_PINS = "https://www.pinterest.com/resource/BoardSectionPinsResource/get/";
    const UPLOAD_IMAGE = "https://www.pinterest.com/upload-image/";
    const BOARD_FOLLOWERS = "https://pinterest.com/resource/BoardFollowersResource/get/";
    const ADD_PIN_NOTE = "https://www.pinterest.com/resource/ApiResource/create/";
}
