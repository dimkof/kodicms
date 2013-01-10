<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class Datasource_Object_Decorator {
	
	const BLOCK_TYPE_PRE	= 'PRE';
	const BLOCK_TYPE_POST	= 'POST';
	
	/**
	 *
	 * @var integer
	 */
	public $id;

	/**
	 *
	 * @var string 
	 */
	public $type;
	
	/**
	 *
	 * @var integer 
	 */
	public $ds_id;
	
	/**
	 *
	 * @var string
	 */
	public $ds_type;

	/**
	 *
	 * @var string 
	 */
	public $ds_table;

	
	/**
	 *
	 * @var string 
	 */
	public $name;

	/**
	 *
	 * @var string 
	 */
	public $description = '';


	/**
	 *
	 * @var strimg
	 */
	public $template = NULL;
	
	/**
	 *
	 * @var array
	 */
	public $template_params = array();

	/**
	 *
	 * @var string
	 */
	public $block = NULL;
	
	/**
	 * 
	 * @param string $ds_type
	 * @param string $obj_type
	 * @param integer $ds_id
	 */
	public function __construct($ds_type, $obj_type, $ds_id)
	{
		$this->ds_id = (int) $ds_id;
		$this->ds_type = $ds_type;
		$this->type = $obj_type;
	}
	
	/**
	 * 
	 * @param array $params
	 */
	public function render($params = array())
	{
		if(Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Object render', __CLASS__);
		}

		if($this->template === NULL) 
		{
			$this->template = 'datasource/object/default';
		}
		
		$allow_omments = (bool) Arr::get($params, 'comments');
		
		if(!($this->block == self::BLOCK_TYPE_PRE OR $this->block == self::BLOCK_TYPE_POST)) 
		{
			if($allow_omments) 
			{
				echo "<!--\n\n{Object: {$this->name}}\n\n-->";
			}
		}
		
		$params = Arr::merge($params, $this->template_params);
		
		echo View::factory($this->template, array(
			'args' => $params
		));
		
		if(!($this->block == self::BLOCK_TYPE_PRE OR $this->block == self::BLOCK_TYPE_POST)) 
		{
			if($allow_omments) 
			{
				echo "<!--\n\n{/Object: {$this->name}}\n\n-->";
			}
		}
		
		if(isset($benchmark))
		{
			Profiler::stop($benchmark);
		}
	}
	
//	abstract public function init();
//	abstract public function on_page_load();

	/**
	 * 
	 * @param array $params
	 */
	public function run($params = array()) 
	{
		return $this->render($params);
	}
	
	public function loaded()
	{
		return isset($this->id) AND $this->id > 0;
	}

	/**
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->render($params);;
	}

	/**
	 * 
	 * @param string $ds_type
	 * @param string $obj_type
	 * @param integer $ds_id
	 * @return \class
	 */
	public static function factory($ds_type, $obj_type, $ds_id) 
	{
		$class = 'Datasource_Object_' . $ds_type . '_' . $obj_type;
		return new $class($ds_type, $obj_type, $ds_id);
	}
}