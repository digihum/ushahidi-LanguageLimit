<?php defined('SYSPATH') or die('No direct script access.');

class languagelimit {

	protected $user;

	public function __construct()
	{
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	public function add()
	{
		Event::add('ushahidi_filter.view_pre_render.layout', array($this, 'limit'));
	}

	public function limit()
	{		
	
		$approved = array("en_GB","en_GB_backup","cy_GB","pt_BR");
	
		$this->domDocument = new DOMDocument();
		@$this->domDocument->loadHTML(mb_convert_encoding(Event::$data['header']->kohana_local_data['languages'], 'HTML-ENTITIES', 'UTF-8'));
		$query = "//option"; 
		$xpath = new DOMXPath($this->domDocument); 
		$result = $xpath->query($query); 
		foreach ($result as $node) {
			if(!in_array($node->getAttribute("value"),$approved)){
				$node->parentNode->removeChild($node);
			}
		}
		Event::$data['header']->kohana_local_data['languages'] = $this->domDocument->saveHTML();
	}
	
}
new languagelimit;
