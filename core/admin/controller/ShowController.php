<?php 

namespace core\admin\controller;

use core\base\settings\Settings;

class ShowController extends AdminController
{
    protected function inputData()
    {
        if(!$this->userId) $this->parent_inputData(); # parent::inputData() не вызываем из-за плагина 

        $this->createTableData();

        $this->createData();  

        return $this->extension();

    }

# -------------------- CREATE DATA -----------------------------------------------
    
    protected function createData($arr = [])
    {
        $fields = [];
        $order = [];    
        $order_direction = [];

        if (!$this->columns['primary_key'])
            return $this->data = [];
    
        $fields[] = $this->columns['primary_key'] . ' as id';

        if (!empty($this->columns['name']))
            $fields['name'] = 'name';

        if (!empty($this->columns['image']))
            $fields['image'] = 'image';

        if (count($fields) < 3)
            foreach ($this->columns as $key => $value) {
                if (!isset($fields['name']) && strpos($key, 'name') !== false) {
                    $fields['name'] = $key . ' as name';
                }
                if (!isset($fields['image']) && strpos($key, 'image') === 0) {
                    $fields['image'] = $key . ' as image';
                }
            }

        /** fields ************************/

        if (isset($arr['fields'])) {
            if (is_array($arr['fields'])) {
                $fields = Settings::instance()->arrayMergeRecursive($fields, $arr['fields']);
            } else {
                $fields[] = $arr['fields'];
            }
        }
        
        /** parent_id *********************/

        if (!empty($this->columns['parent_id'])) {
            if (!in_array('parent_id', $fields))
                $fields[] = 'parent_id';
            $order[] = 'parent_id';
        }

        /** menu position *****************/ 

        if (!empty($this->columns['menu_position'])) {
            $order[] = 'menu_position';
        } elseif (!empty($this->columns['date'])) {
            if ($order)
                $order_direction = ['ASC', 'DESC'];
            else
                $order_direction[] = 'DESK';

            $order[] = 'date';
        }

        /** order *************************/

        if (isset($arr['order'])) {
            if (is_array($arr['order'])) {
                $order = Settings::instance()->arrayMergeRecursive($order, $arr['order']);
            } else {
                $order[] = $arr['order'];
            }
        }

        /** order direction ***************/

        if (!empty($arr['order_direction'])) {
            if (is_array($arr['order_direction'])) {
                $order_direction = Settings::instance()->arrayMergeRecursive($order_direction, $arr['order_direction']);
            } else {
                $order_direction[] = $arr['order_direction'];
            }
        }

        $this->data = $this->model->select($this->table, [
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction
        ]);
    }
}


?>