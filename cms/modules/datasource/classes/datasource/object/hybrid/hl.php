<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class Datasource_Object_Hybrid_HL extends Datasource_Object_Decorator {
	
	const NID_PLAIN = 0;
	const NID_OR = 1;
	const NID_AND = 2;
	
	public $doc_fields = array();
	
	public $doc_filter = array();
	
	public $doc_order = array();
	
	public $doc_id = 'item={_id}';

	public $list_offset = 0;
	
	public $list_size = 10;

	public $sort_by_rand = FALSE;
	
	public $only_sub = FALSE;
	
	public $ids = array();
	
	public $throw_404 = FALSE;
	
	protected $agent;
	
	public function __construct() 
	{
		parent::__construct();
	}

	public function fetch_data()
	{
		
	}
}