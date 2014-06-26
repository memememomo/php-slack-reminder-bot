<?php

require_once 'Base.php';

class Model_Task extends Model_Base
{
    public function fetch_tasks()
    {
        $itr = $this->db->search('task', array(
            'remind_at' => array('<=' => SQL_Maker::scalar('NOW()'))
        ));

        $tasks = array();
        while ($task = $itr->next()) {
            $this->db->delete('task', array('id' => $task['id']));
            $tasks []= $task;
        }

        return $tasks;
    }

    public function tasks()
    {
        $itr = $this->db->search('task', array(), array('order_by' => 'remind_at'));

        $tasks = array();
        while ($task = $itr->next()) {
            $tasks []= $task;
        }

        return $tasks;
    }

    public function create($values)
    {
        $this->db->insert('task', $values);
    }

    public function delete($id)
    {
        $this->db->delete('task', array('id' => $id));
    }
}
