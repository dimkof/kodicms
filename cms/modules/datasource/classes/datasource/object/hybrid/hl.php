<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class Datasource_Object_Hybrid_HL extends Datasource_Object_Hybrid {
	
	const NID_PLAIN = 0;
	const NID_OR = 1;
	const NID_AND = 2;
	
	public $doc_fields = array();
	
	public $doc_filter = array();
	
	public $doc_order = array();
	
	public $doc_uri = NULL;
	
	public $doc_id = 'item={_id}';

	public $list_offset = 0;
	
	public $list_size = 10;
	
	public $only_published = TRUE;
	
	public $ids = array();
	
	public $node_id = NULL;
	public $node_type = 0;
	
	public static function types()
	{
		return array(
			self::NID_PLAIN		=> __('Single node'),
			self::NID_OR		=> __('Multiple node (OR)'),
			self::NID_AND		=> __('Multiple node (AND)')
		);
	}
	
	public function set_values(array $data) 
	{
		$this->header = Arr::get($data, 'header');
		$this->ds_id = (int) Arr::get($data, 'ds_id');
		$this->doc_fields = array_values(Arr::get($data, 'field', array()));
		
		$this->list_offset = (int) Arr::get($data, 'list_offset');
		$this->list_size = (int) Arr::get($data, 'list_size');
		
		$this->only_sub = (bool) Arr::get($data, 'only_sub');
		$this->only_published = (bool) Arr::get($data, 'only_published');
		
		$this->doc_uri = Arr::get($data, 'doc_uri', $this->doc_uri);
		$this->doc_id = str_replace('\r\n', '\n', Arr::get($data, 'doc_id', $this->doc_id));
		
		$this->node_id = Arr::get($data, 'node_id');
		$this->node_type = (int) Arr::get($data, 'node_type');
		
		$this->throw_404 = (bool) Arr::get($data, 'throw_404');
		
		$this->sort_by_rand = (bool) Arr::get($data, 'sort_by_rand');
	}

	public function fetch_data()
	{
		$docs = $this->_get_documents();
		
		$result = array();
		
		if(empty($docs) AND $this->throw_404)
			Model_Page_Front::not_found ();
		
		$result['docs'] = $docs;
		$result['count'] = count($docs);
		$result['header'] = $this->header;
		
		return $result;
	}
	
	protected function _get_documents( $recurse = 3 )
	{
		$result = array();
		
		$agent = $this->get_agent();
		
		if( ! $agent )
		{
			return $result;
		}
		
		$query = $this
			->_get_query()
			->execute();
		
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
		
		foreach ($query as $row)
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
						$doc[$field['name']] = array(
							'id' => $row[$fid], 
							'header' => $$row[$fid . 'header']
						);
						break;
					case DataSource_Data_Hybrid_Field::TYPE_ARRAY:
						$doc[$field['name']] = $row[$fid];
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
		$query = $agent->get_query_props($this->doc_fields, $this->doc_order, $this->doc_filter);
		
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