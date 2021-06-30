<?php

namespace AcdhchMailman\Inc;

class WordPressMenuTab {

    public $slug;
    public $title;
    public $menu;

    function __construct($options, \AcdhchMailman\Inc\WordPressMenu $menu) {

        $this->slug = $options['slug'];
        $this->title = $options['title'];
        $this->menu = $menu;

        $this->menu->add_tab($options);
    }

    /**
     * Add field to this tab
     * @param [type] $array [description]
     */
    public function add_field($array) {

        $this->menu->add_field($array, $this->slug);
    }

}
