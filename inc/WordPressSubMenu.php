<?php

namespace AcdhchMailman\Inc;

class WordPressSubMenu extends \AcdhchMailman\Inc\WordPressMenu {

	function __construct( $options, \AcdhchMailman\Inc\WordPressMenu $parent ){
		parent::__construct( $options );

		$this->parent_id = $parent->settings_id;
	}

}