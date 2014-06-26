<?php

require_once 'Base.php';

class Model_TaskDaily extends Model_Base
{
    public function tasks()
    {
        $itr = $this->db->search('task_daily', array(), array('order_by' => 'remind_hour, remind_min'));

        $tasks = array();
        while ($task = $itr->next()) {
            $tasks []= $task;
        }

        return $tasks;
    }

    public function create($values)
    {
        $this->db->insert('task_daily', $values);
    }

    public function delete($id)
    {
        $this->db->delete('task_daily', array('id' => $id));
    }
}
