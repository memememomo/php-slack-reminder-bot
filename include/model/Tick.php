<?php

require_once 'Base.php';
require_once 'Slack.php';

class Model_Tick extends Model_Base
{
    public function run()
    {
        $Config = $this->c->load_config();

        $Task = $this->c->_m('Task');
        $Slack = new Slack($Config['Slack']['token']);

        $channel = $Config['Slack']['channel'];
        $username = $Config['Slack']['username'];

        $tasks = $Task->fetch_tasks();

        if (count($tasks) == 0) {
            return;
        }

        $texts = array();
        foreach ($tasks as $task) {
            $texts []= $task['text'];
        }

        $text = implode("\n", $texts);
        $text = "@channel\n" . $text;

        $Slack->chat_postMessage($channel, $text, $username);
    }
}
