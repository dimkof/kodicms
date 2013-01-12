<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Datasources_Objects extends Controller_System_Datasource {
	
	public function action_index()
	{
		$this->template->set_filename( 'datasource/template' );

		$map = Datasource_Object_Manager::map();

		
		$cur_node = Arr::get($this->request->query(), 'node');
		if($cur_node === NULL)
		{
			$cur_node = key($map) . '.' . key($map[key($map)]);
		}

		$this->template->set_global(array(
			'cur_node' => $cur_node
		));
		
		$section = Arr::path($map, $cur_node);
		$headline = array();

		if($section !== NULL AND !is_array($section))
		{
			$this->breadcrumbs
				->add(__('Objects'), 'datasources/objects')
				->add($section);

			list($ds_type, $obj_type) = explode('.', $cur_node);
			$headline = Datasource_Object_Manager::get_objects($ds_type, $obj_type);
		}
		else 
		{
			$this->breadcrumbs
				->add(__('Objects'));
		}
		
		$this->template->content = View::factory('datasource/object/index', array(
			'title' => $section,
			'headline' => View::factory('datasource/object/headline', array(
				'data' => $headline,
				'fields' => array(
					'Name' => 300,
					'Description' => NULL,
					'Date' => 200
				)
			))
		));
		
		$this->template->menu = View::factory('datasource/object/menu', array(
			'tree' => Datasource_Object_Manager::map(),
		));
		
		$this->styles[] = ADMIN_RESOURCES . 'libs/jquery-treeview/jquery.treeview.css';
		$this->scripts[] = ADMIN_RESOURCES . 'libs/jquery-treeview/jquery.treeview.js';
	}
	
	public function action_create()
	{
		if($this->request->method() === Request::POST)
		{
			return $this->_create();
		}
		
		$node = Arr::get($this->request->query(), 'node');
		$map = Datasource_Object_Manager::map();
		$section = Arr::path($map, $node);
		
		list($ds_type, $obj_type) = explode('.', $node);

		$datasources = Datasource_Data_Manager::get_all($ds_type);
		
		$options = array();
		foreach ($datasources as $value)
		{
			$options[$value['id']] = $value['name'];
		}
		
		$this->breadcrumbs
			->add(__('Objects'), 'datasources/objects')
			->add($section, 'datasources/objects/?node=' . $node)
			->add(__('Create'));
		
		$this->template->content = View::factory('datasource/object/create', array(
			'options' => $options,
			'ds_type' => $ds_type,
			'obj_type' => $obj_type
		));
	}
	
	protected function _create()
	{
		$array = Validation::factory($this->request->post())
			->rules('ds_type', array(
				array('not_empty')
			))
			->rules('obj_type', array(
				array('not_empty')
			))
			->rules('ds_id', array(
				array('not_empty'),
				array('numeric'),
			))
			->rules('name', array(
				array('not_empty')
			))
			->label( 'name', __('Header') );
		
		if(!$array->check())
		{
			Messages::errors($array->errors('validation'));
			$this->go_back();
		}
		
		$object = Datasource_Object_Manager::get_empty_object($array['ds_type'], $array['obj_type'], $array['ds_id']);
		
		$object->name = $array['name'];
		$object->description = Arr::get($array, 'description');
		
		if($result = Datasource_Object_Manager::create($object))
		{
			$this->go('datasources/objects/view/' . $result);
		}
		
		$this->go_back();
	}
	
	public function action_view()
	{
		$id = (int) $this->request->param('id');
		$object = Datasource_Object_Manager::load($id);
		
		if($this->request->method() === Request::POST)
		{
			return $this->_save($object);
		}
		
		$datasources = Datasource_Data_Manager::get_all($object->ds_type);
		
		$options = array();
		foreach ($datasources as $value)
		{
			$options[$value['id']] = $value['name'];
		}
		
		$node = $object->ds_type . '.' . $object->type;
		$path = $object->ds_type . '/' . $object->type;
		
		$section = Arr::path(Datasource_Object_Manager::map(), $node);
		
		$this->breadcrumbs
			->add(__('Objects'), 'datasources/objects')
			->add($section, 'datasources/objects/?node=' . $node)
			->add($object->name);
		
		$this->template->content = View::factory('datasource/object/template/' . $path, array(
			'object' => $object,
			'options' => $options,
			'general' => View::factory('datasource/object/template/general', array(
				'object' => $object
			)),
			'template' => View::factory('datasource/object/template/template', array(
				'object' => $object
			))
		));
	}
	
	protected function _save($object)
	{
		$data = $this->request->post();
		$object->set_cache_settings($data);
		$object->set_values($data);
		Datasource_Object_Manager::save($object);
		$this->go_back();
	}
}