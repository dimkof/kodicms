<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

abstract class Datasource_Object_Decorator {
	
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
	 * @var string 
	 */
	public $header = NULL;


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
	 * @var bool 
	 */
	public $caching = TRUE;
	
	/**
	 *
	 * @var integer 
	 */
	public $cache_lifetime = 3600;
	
	/**
	 *
	 * @var bool 
	 */
	public $throw_404 = FALSE;

	public function __construct() {}
	
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
		
		if( 
			$this->caching === TRUE 
		AND 
			! Fragment::load($this->get_cache_id(), $this->cache_lifetime)
		)
		{
			echo $this->_fetch_render($params);
			Fragment::save();
		}
		else if( ! $this->caching )
		{
			Fragment::delete($this->get_cache_id());
			echo $this->_fetch_render($params);
		}
		
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
	
	/**
	 * 
	 * @param array $params
	 * @return View
	 */
	protected function _fetch_render($params)
	{
		$params = Arr::merge($params, $this->template_params);
		$data = $this->fetch_data();
		$data['params'] = $params;
		return View::factory($this->template, $data);
	}

//	abstract public function on_page_load();
	abstract public function fetch_data();
	abstract public function set_values(array $data);
	
	public function set_cache_settings(array $data)
	{
		$this->caching = (bool) Arr::get($data, 'caching', FALSE);
		$this->cache_lifetime = (int) Arr::get($data, 'cache_lifetime');
	}

	/**
	 * 
	 * @return string
	 */
	public function get_cache_id()
	{
		return 'Object::' . $this->ds_type . '::' . $this->type . '::' . $this->id;
	}

	/**
	 * 
	 * @param array $params
	 */
	public function run($params = array()) 
	{
		return $this->render($params);
	}
	
	/**
	 * 
	 * @return bool
	 */
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
		return $this->render($params);
	}
}