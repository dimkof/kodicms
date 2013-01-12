<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class Datasource_Object_Hybrid_HL extends Datasource_Object_Hybrid {

	public $doc_fields = array();
	
	public $doc_fetched_objects = array();
	
	public $doc_filter = array();
	
	public $doc_order = array();
	
	public $doc_uri = NULL;
	
	public $doc_id = 'item={_id}';

	public $list_offset = 0;
	
	public $list_size = 10;
	
	public $only_published = TRUE;
	
	public $ids = array();
	
	protected $arrays = array();
	
	public function set_values(array $data) 
	{
		$this->header = Arr::get($data, 'header');
		$this->ds_id = (int) Arr::get($data, 'ds_id');
		
		$this->doc_fields = $this->doc_fetched_objects = array();
		foreach(Arr::get($data, 'field', array()) as $f)
		{
			$this->doc_fields[] = (int) $f['id'];
			
			if(isset($f['fetcher']))
				$this->doc_fetched_objects[(int) $f['id']] = (int) $f['fetcher'];
		}
		
		$this->doc_order = Arr::get($data, 'doc_order', array());
		
		$this->list_offset = (int) Arr::get($data, 'list_offset');
		$this->list_size = (int) Arr::get($data, 'list_size');
		
		$this->only_sub = (bool) Arr::get($data, 'only_sub');
		$this->only_published = (bool) Arr::get($data, 'only_published');
		
		$this->doc_uri = Arr::get($data, 'doc_uri', $this->doc_uri);
		$this->doc_id = str_replace('\r\n', '\n', Arr::get($data, 'doc_id', $this->doc_id));
		
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');
		
		$this->sort_by_rand = (bool) Arr::get($data, 'sort_by_rand');
	}

	public function fetch_data()
	{
		$docs = $this->get_documents();
		
		$result = array();
		
		if(empty($docs) AND $this->throw_404)
			Model_Page_Front::not_found ();
		
		$result['docs'] = $docs;
		$result['count'] = count($docs);
		$result['header'] = $this->header;
		
		return $result;
	}
	
	protected function get_documents( $recurse = 3 )
	{
		$result = array();
		
		$agent = $this->get_agent();
		
		if( ! $agent )
		{
			return $result;
		}
		
		$query = $this
			->_get_query();
		
		if($this->caching)
		{
			$query
				->cache_key($this->get_cache_id())
				->cached($this->cache_lifetime);
		}
		else
		{
			Kohana::cache('Database::cache(' . $this->get_cache_id() . ')', NULL, -3600);
		}
		
		$ds_fields = $agent->get_fields();
		$fields = array();
		foreach ($this->doc_fields as $fid)
		{
			if(isset($ds_fields[$fid]))
			{
				$fields[$fid] = $ds_fields[$fid];
			}
		}

		$href_params = $this->_parse_doc_id();
		
		foreach ($query->execute() as $row)
		{
			$result[$row['id']] = array();
			$doc = & $result[$row['id']];
			
			$doc['id'] = $row['id'];
			$doc['header'] = $row['header'];
			
			foreach ($fields as $fid => $field)
			{
				switch($field['type']) {
					case DataSource_Data_Hybrid_Field::TYPE_DATASOURCE:
						$doc[$field['name']] = array(
							'id' => $row[$fid]
						);
						break;
					case DataSource_Data_Hybrid_Field::TYPE_DOCUMENT:
						if($recurse > 0 AND isset($this->doc_fetched_objects[$fid]))
						{
							$doc[$field['name']] = $this->_fetch_related_object($row, $fid, $recurse);
						}
						else
						{
							$doc[$field['name']] = array(
								'id' => $row[$fid], 
								'header' => $row[$fid . 'header']
							);
						}
						break;
					case DataSource_Data_Hybrid_Field::TYPE_ARRAY:
						if($recurse > 0 AND isset($this->doc_fetched_objects[$fid]))
						{
							if(isset($row[$fid]))
							{
								$doc[$field['name']] = $this->_fetch_related_object($row, $fid, $recurse);
							}
							else
							{
								$doc[$field['name']] = array();
							}
						}
						else
						{
							$doc[$field['name']] = $row[$fid];
						}
						break;
//					case DataSource_Data_Hybrid_Field::TYPE_PRIMITIVE:
					default:
						$doc[$field['name']] = $row[$fid];
						
				}
			}
			
			$doc_params = array();
			foreach ($href_params as $url_param => $field)
			{
				if(!isset($doc[$field]))
				{
					continue;
				}
				
				$doc_params[$url_param] = $doc[$field];
			}
			
			$doc['href'] = URL::site($this->doc_uri . URL::query($doc_params, FALSE));
		}
		
		return $result;
	}
	
	protected function _fetch_related_object($row, $fid, $recurse)
	{
		$object_id = $this->doc_fetched_objects[$fid];
		$object = Datasource_Object_Manager::load($object_id);
		if($object === NULL) return array();

		$doc_ids = explode(',', $row[$fid]);
		$object->ids = $doc_ids;
		$docs = $object->get_documents( $recurse - 1);
		return $docs;
	}

	protected function _parse_doc_id()
	{
		$names = $values = array();

		$ids = explode('\n', $this->doc_id);
		$params = array();
		
		foreach ($ids as $string)
		{
			if(($pos = strpos($string, '=')) !== FALSE) 
			{
				$params[substr($string, 0, $pos)] = substr($string, $pos + 2, -1);
			}
		}

		return $params;
	}

	protected function _get_query()
	{
		$agent = $this->get_agent();
		$query = $agent->get_query_props($this->doc_fields, $this->doc_fetched_objects, $this->doc_order, $this->doc_filter);
		
		if(is_array($this->ids) AND count($this->ids) > 0)
		{
			$query->where('d.id', 'in',  $this->ids);
		}
		
		if($this->only_published === TRUE)
		{
			$query->where('d.published', '=',  1);
		}

		$query->limit($this->list_size);
		$query->offset($this->list_offset);
		
		return $query;
	}
}