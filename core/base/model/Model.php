<?php

namespace core\base\model;

use core\base\controller\Singleton;
use core\base\exceptions\DBException;
use mysqli;

class Model extends ModelMethods
{	
	use Singleton;
	
	protected $db;

/*
|--------------------------------------------------------------------------
|					CONNECT
|--------------------------------------------------------------------------
|		
*/

	protected function __construct()
	{
		$this->db = @new mysqli(HOST, USER, PASS, DB_NAME);

		if($this->db->connect_error)
		{
			throw new DBException('Ошибка подключения к базе данных: ' . $this->db->connect_errno . ' ' . $this->db->connect_error);
		}
		$this->db->query("SET NAMES UTF8");
	}

/*
|--------------------------------------------------------------------------
|					QUERU FUNCTION
|--------------------------------------------------------------------------
|
|  c - CREATE(INSERT),
|  r - READ(SELECT), 
|  u - UPDATE(EDIT),
|  d - DELETE  
|		
*/

	final public function queryFunc($query, $crud = 'r', $return_id = false)
	{
		$result = $this->db->query($query);

		if($this->db->affected_rows === -1)	
		{
			throw new DBException('Ошибка в SLQ запросе: ' . $query . ' - ' . $this->db->errno . ' ' . $this->db->error);
		}
		
		switch ($crud) {

			case 'r':
				if ($result->num_rows) {
					$res = [];

					for ($i=0; $i < $result->num_rows; $i++) {   	# while ($row = $result->fetch_assoc()) {
						$res[] = $result->fetch_assoc();		 	# $res[] = $row; }
					}
					return $res;
				}
				return false;			
				break;

			case 'c':
				if($return_id){
					return $this->db->insert_id;
				}
				return true;
				break;

			default:
				return true;
				break;
		}
	}

/*
|--------------------------------------------------------------------------
|					SELECT
|--------------------------------------------------------------------------
| 
|  string $table      - табоица базы данных
|  array $set         - массив параметров
|  'fields'           => ['id', 'name']
|  'where' 			  => ['id' => '2', 'name' => 'chess']
|  'operand'          => ['=', '<>', 'IN', '%LIKE%', 'NOT IN']
|  'condition'        => ['OR', AND'], 	   default: 'AND'
|  'order'            => ['id', 'name'],
|  'order_direction'  => ['ASC', 'DESC'],  default: 'ASC'
|  'limit'            => '1'
| 
|  "SELECT fields FROM table join where order limit"
*/

	final public function select($table, $set = [])
	{
		$fields = $this->createFields($set, $table);
		$order = $this->createOrder($set, $table);

		 
		$where = $this->createWhere($set, $table);

		if (!$where) $new_where = true;
			else $new_where = false;
		
		$join_arr = $this->createJoin($set, $table, $new_where);

		$fields .= $join_arr['fields'];
		$where .= $join_arr['where'];
		$join = $join_arr['join'];

		$fields = rtrim($fields, ', ');


		$limit = !empty($set['limit']) ? 'LIMIT ' .  $set['limit'] : '';

		$query = "SELECT $fields FROM $table $join $where $order $limit";

 		return $this->queryFunc($query);
	}

/*
|--------------------------------------------------------------------------
|					INSERT
|--------------------------------------------------------------------------
|   
|  string $table - табоица для добавления данных
|  array $set 	- массив параметров
|  fields 		=> [поле => значение]; если не указан, то обрабатывается $_POST[поле => значение]
|  разрешена передача например NOW() в качестве MySQL функции обычной строкой
|  files 		=> [поле => значение]; можно подать массив вида [поле => [массив значение]]
|  except 		=> ['исключение 1', 'исключение 2'] - исключает данные элементы массива из добавления в запрос
|  return_id	=> true|false - возвпащать или нет идентификатор вставленной записи 
|  return mixed 
|   
|  "INSERT INTO table (field, field2) VALUE ('field', 'field2')" 
*/

	final public function insert($table, $set = [])
	{
		$set['fields'] = (is_array($set['fields'])) && !empty($set['fields']) ? $set['fields'] : $_POST;
		$set['files'] = (is_array($set['files'])) && !empty($set['files']) ? $set['files'] : false;	

		if (!$set['fields'] && !$set['files']) return false; 

		$set['except'] = (is_array($set['except'])) && !empty($set['except']) ? $set['except'] : false;
		$set['return_id'] = $set['return_id'] ? true : false;
		
		$insert = $this->createInsert($set['fields'], $set['files'], $set['except']);

		$query = "INSERT INTO $table {$insert['fields']} VALUE {$insert['value']}";

		return $this->queryFunc($query, $crud = 'c', $set['return_id']);
	}
	
}