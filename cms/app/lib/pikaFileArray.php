<?php

/**********************************/
/* Pika CMS (C) 2010 Aaron Worley */
/* http://pikasoftware.com        */
/**********************************/


class pikaFileArray implements ArrayAccess, Iterator
{
	public $array_variable_name = null;
	public $file_location = null;
	
	protected $values;
	protected $position;
	
	protected function __construct()
	{
		$this->position = 0;
		if(file_exists($this->file_location)) 
		{
			$this->values = new stdClass();
			if(is_null($this->array_variable_name) || strlen($this->array_variable_name) < 1)
			{ // Assume (nameless) return method 
				$this->values = include($this->file_location);
			}
			else 
			{ // Assume (named) var = array method
				include($this->file_location);
				if(is_array(${$this->array_variable_name}))
				{
					$this->values = ${$this->array_variable_name};					
				}
			}
		}
	}
	
	// Begin Array Access
	
	public function &__get($name) {
		return $this->values[$name];
	}
	
	public function __set($name,$value) 
	{	
		$this->values[$name] = $value;
	}
	
	public function __isset($name) 
	{
        return isset($this->values[$name]);
    }
	
	public function __unset($name) 
	{
        unset($this->values[$name]);
    }

    public function offsetSet($name, $value) 
    {
        $this->__set($name,$value);
    }
    
    public function offsetExists($name) 
    {
        return $this->__isset($name);
    }
    
    public function offsetUnset($name) 
    {
        $this->__unset($name);
    }
    
    public function offsetGet($name) 
    {
        return $this->__get($name);
    }
    
    // End Array Access
    
    // Begin Iterator
    
    public function rewind()
    {
    	$this->position = 0;
    }
	public function current()
	{
		$keys = array_keys($this->values);
		return $this->values[$keys[$this->position]];
	}
	public function key()
	{
		$keys = array_keys($this->values);
		return $keys[$this->position];
	}
	public function next()
	{
		++$this->position;
	}
	public function valid()
	{
		$keys = array_keys($this->values);
		return isset($keys[$this->position]);
	}
	
    // End Iterator
	
	protected function array2Php($values,$tab_counter = 0) {
		
		$values_string_array = array();
		$tab_level = str_repeat("\t",$tab_counter);
		$values_string = "array(\n";
		foreach ($values as $key => $val) {
			if(is_array($val))
			{	
				$values_string_array[] .= "{$tab_level}'{$key}' => " . $this->array2Php($values[$key],$tab_counter+1);
			}
			else 
			{
				$values_string_array[] = "{$tab_level}'{$key}' => \"{$val}\"";
			}
		}
		$values_string .= implode(",\n",$values_string_array);
		$values_string .= "\n{$tab_level})";
		return $values_string;
	}
	
	public function getValues() 
	{
		return $this->values;
	}
	
	public function isWritable()
	{	
		if(is_writable($this->file_location))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public function save()
	{
		
		if(!is_null($this->array_variable_name) && strlen($this->array_variable_name))
		{ // do the variable method (only for hard coded variables)
			$contents = "<?php\n\${$this->array_variable_name} = ";
		}
		else
		{ // do the return method (to avoid namespace problems)
			$contents = "<?php\nreturn ";
		}
		$contents .= $this->array2Php($this->values);
		$contents .= ";";
		
		if (!file_put_contents($this->file_location,$contents))
		{
			trigger_error("Error: An error occured while saving ({$this->file_location})");
		}
		
		return true;
		
	}
}

?>