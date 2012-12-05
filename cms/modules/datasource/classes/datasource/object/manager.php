<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class Datasource_Object_Manager {
	
	public static function map()
	{
		return array(
			
		);
	}
	
	public static function get_objects($ds_type, $obj_type) 
	{
		$result = array();
		
		$res = DB::select('o.id', 'o.name', 'o.description', 'o.date')
			->select(array(DB::expr('COUNT(:table)')->param( 
					':table', Database::instance()->quote_column( 's.page_id' ) ), 'used'))
			->from(array('objects', 'o'))
			->join(array('siteobjects', 's'), 'left')
				->on('o.id', '=', 's.obj_id')
			->where('s.obj_id', '=', $ds_type)
			->where('o.obj_type', '=', $obj_type)
			->group_by('o.id')
			->group_by('o.name')
			->order_by('o.name')
			->execute();
		
		foreach ( $res as $row )
		{
			$type = key(Arr::get($row['ds_type'], self::map()));
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
		if(!$object->obj_id)
		{
			throw new HTTP_Exception_404('Object not loaded');
		}
		
		$data = array(
			'ds_id' => $object->ds_id,
			'ds_type' => $object->ds_type,
			'obj_type' => $object->obj_type,
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
}