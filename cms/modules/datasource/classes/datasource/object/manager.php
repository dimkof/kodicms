<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class Datasource_Object_Manager {
	
	public static function map()
	{
		return Kohana::$config
		   ->load('datasource')
		   ->as_array();
	}
	
	/**
	 * 
	 * @param string $ds_type
	 * @param string $obj_type
	 * @param integer $ds_id
	 * @return \class
	 */
	public static function get_empty_object($ds_type, $obj_type, $ds_id) 
	{
		$class = 'Datasource_Object_' . $ds_type . '_' . $obj_type;

		$object = new $class;

		$object->ds_type = $ds_type;
		$object->type = $obj_type;
		$object->ds_id = (int) $ds_id;

		return $object;
	}

	public static function get_objects($ds_type, $obj_type) 
	{
		$result = array();
		
		$res = DB::select('o.id', 'o.name', 'o.description', 'o.date', 'o.ds_type')
			->select(array(DB::expr('COUNT(:table)')->param( 
					':table', Database::instance()->quote_column( 's.page_id' ) ), 'used'))
			->from(array('objects', 'o'))
			->join(array('siteobjects', 's'), 'left')
				->on('o.id', '=', 's.obj_id')
			->where('o.ds_type', '=', $ds_type)
			->where('o.obj_type', '=', $obj_type)
			->group_by('o.id')
			->group_by('o.name')
			->order_by('o.name')
			->execute();
		
		foreach ( $res as $row )
		{
			$result[$row['id']] = array(
				'name' => $row['name'],
				'description' => $row['description'],
				'published' => $row['used'] > 0,
				'date' => date('j M Y', $row['date'])
			);
		}

		return $result;
	}
	
	public static function get_all_objects()
	{
		$result = array();
		$res = DB::select('id', 'ds_type', 'obj_type', 'name', 'description')
			->from('objects')
			->order_by('ds_type', 'asc')
			->order_by('obj_type', 'asc')
			->order_by('name', 'asc')
			->execute();
		
		foreach ( $res as $row )
		{
			$type = key(Arr::get($row['ds_type'], self::map()));

			$result[$row['id']] = array(
				'ds_type' => $type,
				'name' => $row['name'],
				'description' => $row['description'],
				'obj_type_f' => Arr::path($row['ds_type'].'.'.$type.'.'.$row['obj_type'], self::map()),
				'obj_type'	=>	$row['obj_type'],
			);
		}
	}
	
	public static function create($object)
	{
		if($object->loaded())
		{
			throw new HTTP_Exception_404('Object created');
		}
		
		$data = array(
			'ds_id' => $object->ds_id,
			'ds_type' => $object->ds_type,
			'obj_type' => $object->type,
			'name' => $object->name,
			'description' => $object->description,
			'date' => time(),
			'code' => serialize($object)
		);
		
		$result = DB::insert('objects')
			->columns(array_keys($data))
			->values($data)
			->execute();

		return $result[0];
	}
	
	public static function save($object)
	{
		if( ! $object->loaded() )
		{
			throw new HTTP_Exception_404('Object not loaded');
		}
		
		$old_object = self::load($object->id);

		$data = array(
			'ds_id' => $object->ds_id,
			'ds_type' => $object->ds_type,
			'obj_type' => $object->type,
			'name' => $object->name,
			'tpl' => $object->template,
			'description' => $object->description,
			'code' => serialize($object)
		);
		
		$result = DB::update('objects')
			->set($data)
			->where('id', '=', $object->id)
			->execute();

		return $result[0];
	}
	
	public static function remove( array $ids )
	{
		return DB::delete('objects')
			->where('id', 'in', $ids)
			->execute();
	}
	
	public static function load($id)
	{
		$result = DB::select()->from('objects')
			->where('id', '=', (int) $id)
			->limit(1)
			->execute()
			->current();
		
		if($result === NULL)
		{
			return NULL;
		}

		$object = unserialize($result['code']);
		$object->id = $result['id'];
		
		return $object;
	}
}