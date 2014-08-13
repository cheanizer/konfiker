<?php

Class Confiker
{
	private $ci;
	private $_list = array();
	private $_file;
	private $_file_config_name = "config.cfg";
	private $_absolute_path_folder = "[your absolute path folder]";
	private $_type = array(
			'1' => array(
					'name' => 'Text',
					'slug' => 'text'
				),
			'2' => array(
					'name' => 'TextArea',
					'slug' => 'textarea'
				),
			'3' => array(
					'name' => 'Option',
					'slug' => 'option' 
				)
		);
	public function __construct()
	{
		$this->_load();
	}

	public function meta_exist($slug)
	{
		if ( empty ($this->_list)) return false;
		
		foreach ($this->_list as $key => $value)
		{
			if (isset ($this->_list[$key]->slug) && $this->_list[$key]->slug == $slug)
			{
				return true;
			}else
			{
				return false;
			}
		} 

		return false;
	}

	public function set_meta($name,$slug,$type,$option = array(),$value = '')
	{
		$conf = array(
				'name' => $name,
				'slug' => $slug,
				'type' => $type,
				'option' => json_encode($option),
				'value' => $value
			);
		$conf = (object) $conf;

		if (! empty ($this->_list))
		{

			if ($this->meta_exist($slug))
			{
				foreach ($this->_list as $key => $value)
				{
					if ($value->slug == $slug)
					{
						$this->_list[$key] = $conf;
					}
				}	

			}
			else
			{

				array_push($this->_list, $conf);
			}
		}else
		{
			$this->_list[0] = $conf;
		}

		$this->_save();
	}

	public function get_meta($slug = '')
	{
		if ( empty ($this->_list)) return false;

		foreach($this->_list as $key => $value)
		{
			if ($value->slug == $slug) return $value;
		}
		return false;
	}

	public function get_type()
	{
		return $this->_type;
	}

	public function get_list()
	{
		return $this->_list;
	}

	public function get_value($slug,$default = false)
	{
		foreach ($this->_list as $key => $item)
		{
			if ($item->slug == $slug)
			{
				return $item->value;
			}
		}

		return $default;
	}

	public function set_value($slug,$v)
	{
		foreach ($this->_list as $key => $value)
		{
			if ($value->slug == $slug)
			{
				$value->value = $v;
				$this->list[$key] = $value;
			}
		}

		$this->_save();
	}

	public function set_folder($folder)	
	{
		$this->_absolute_path_folder = $folder;
	}

	public function load()
	{
		$this->_load();
	}

	private function _save()
	{
		$con = fopen($this->_absolute_path_folder. "" . $this->_file_config_name , 'w');
		fwrite($con, json_encode($this->_list));
		return true;
	}

	private function _load()
	{
		if (! file_exists($this->_absolute_path_folder . "" . $this->_file_config_name)) $this->_save();
		$string = file_get_contents($this->_absolute_path_folder . "" . $this->_file_config_name);
		$this->_list = json_decode($string);

		return true;
	}

	
}
