<?php

/**
 * Hatena-Haiku Blog Public Timeline ViewModel.
 * 
 * @package app
 * @extends ViewModel
 */
class View_Timeline_Private extends ViewModel
{

	/**
	 * Get Hatena-Haiku Public Timeline.
	 * 
	 * Data cache to make Access-control, 
	 * and get data from cache in a time.
	 */
	public function view()
	{
		$this->set('title', 'Private Timeline');
	}
}

