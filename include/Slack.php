<?php

require_once dirname(__FILE__).'/Snoopy.class.php';

class Slack
{
    public $snoopy = null;

    private $root_url = 'https://slack.com/api';

    public $curl_path = '/usr/bin/curl';

    public $api_token = '';

    public function __construct($token, $curl_path = null)
    {
        $this->api_token = $token;

        $this->check_curl($curl_path);
        $this->snoopy = new Snoopy();
        $this->snoopy->curl_path = $this->curl_path;
    }

    public function check_curl($curl_path = null)
    {
        if (is_null($curl_path))
        {
            $curl_path = $this->curl_path;
        }

        if (!is_executable($curl_path)) {
            throw new Exception("{$curl_path} is not available");
        }

        $this->curl_path = $curl_path;
    }

    public function user_list()
    {
        return $this->_submit(
            $this->_url('users.list'),
            $this->_params()
        );
    }

    public function channels_join($name)
    {
        return $this->_submit(
            $this->_url('channels.join'),
            $this->_params(array('name' => $name))
        );
    }

    public function channels_leave($channel)
    {
        return $this->_submit(
            $this->_url('channels.leave'),
            $this->_params(array('channel' => $channel))
        );
    }

    public function channels_history($channel, $latest = null, $oldest = null, $count = null)
    {
        return $this->_submit(
            $this->_url('channels.history'),
            $this->_params(array(
                'channel' => $channel,
                'latest' => $latest,
                'oldest' => $oldest,
                'count' => $count,
            ))
        );
    }

    public function channels_mark($channel, $ts)
    {
        return $this->_submit(
            $this->_url('channels.mark'),
            $this->_params(array(
                'channel' => $channel,
                'ts' => $ts,
            ))
        );
    }

    public function files_upload()
    {
        // TODO
    }

    public function files_list($user = null, $ts_from = null, $ts_to = null, $types = null, $count = null, $page = null)
    {
        return $this->_submit(
            $this->_url('files.list'),
            $this->_params(array(
                'user' => $user,
                'ts_from' => $ts_from,
                'ts_to' => $ts_to,
                'types' => $types,
                'count' => $count,
                'page'  => $page,
            ))
        );
    }

    public function files_info($file, $count = null, $page = null)
    {
        return $this->_submit(
            $this->_url('files.info'),
            $this->_params(array(
                'file' => $file,
                'count' => $count,
                'page' => $page,
            ))
        );
    }

    public function im_history($channel, $latest = null, $oldest = null, $count = null)
    {
        return $this->_submit(
            $this->_url('im.history'),
            $this->_params(array(
                'channel' => $channel,
                'latest' => $latest,
                'oldest' => $oldest,
                'count' => $count,
            ))
        );
    }

    public function im_list()
    {
        return $this->_submit(
            $this->_url('im.list'),
            $this->_params()
        );
    }

    public function groups_history($channel, $latest = null, $oldest = null, $count = null)
    {
        return $this->_submit(
            $this->_url('groups.history'),
            $this->_params(array(
                'channel' => $channel,
                'latest' => $latest,
                'oldest' => $oldest,
                'count' => $count,
            ))
        );
    }

    public function groups_list($exclude_archived = null)
    {
        return $this->_submit(
            $this->_url('groups.list'),
            $this->_params(array(
                'exclude_archived' => $exclude_archived,
            ))
        );
    }

    public function search_all($query, $sort = null, $sort_dir = null, $highlight = null, $count = null, $page = null)
    {
        return $this->_submit(
            $this->_url('search.all'),
            $this->_params(array(
                'query' => $query,
                'sort' => $sort,
                'sort_dir' => $sort_dir,
                'highlight' => $highlight,
                'count' => $count,
                'page' => $page,
            ))
        );
    }

    public function search_files($query, $sort = null, $sort_dir = null, $highlight = null, $count = null, $page = null)
    {
        return $this->_submit(
            $this->_url('search.files'),
            $this->_params(array(
                'query' => $query,
                'sort'  => $sort,
                'sort_dir' => $sort_dir,
                'highlight' => $highlight,
                'count' => $count,
                'page' => $page,
            ))
        );
    }

    public function search_messages($query, $sort = null, $sort_dir = null, $highlight = null, $count = null, $page = null)
    {
        return $this->_submit(
            $this->_url('search.messages'),
            $this->_params(array(
                'query' => $query,
                'sort'  => $sort,
                'sort_dir' => $sort_dir,
                'highlight' => $highlight,
                'count' => $count,
                'page' => $page,
            ))
        );
    }

    public function chat_postMessage($channel, $text, $username = null, $parse = null, $link_names = null, $attachments = null, $unfurl_links = null, $icon_url = null, $icon_emoji = null)
    {
        return $this->_submit(
            $this->_url('chat.postMessage'),
            $this->_params(array(
                'channel' => $channel,
                'text' => $text,
                'username' => $username,
                'parse' => $parse,
                'link_names' => $link_names,
                'attachments' => $attachments,
                'unfurl_links' => $unfurl_links,
                'icon_url' => $icon_url,
                'icon_emoji' => $icon_emoji,
            ))
        );
    }

    public function stars($user = null, $count = null, $page = null)
    {
        return $this->_submit(
            $this->_url('stars.list'),
            $this->_params(array(
                'user' => $user,
                'count' => $count,
                'page' => $page,
            ))
        );
    }

    public function auth_test()
    {
        return $this->_submit(
            $this->_url('auth.test'),
            $this->_params()
        );
    }

    public function _submit($url, $params)
    {
        $this->snoopy->submit($url, $params);
        return json_decode($this->snoopy->results);
    }

    public function _params($p = array())
    {
        $params = array('token' => $this->api_token);
        return array_merge($params, $p);
    }

    public function _url($cmd)
    {
        return $this->root_url . '/' . $cmd;
    }
}
