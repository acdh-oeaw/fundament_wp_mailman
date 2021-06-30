<?php

namespace AcdhchMailman\Widget;

class AcdhchNewsletterWidget extends \WP_Widget {

    // The construct part  
    public function __construct() {
        parent::__construct(
                // Base ID of your widget
                'AcdhchNewsletterWidget',
                // Widget name will appear in UI
                __('ACDHCH Newsletter Widget', 'acdhch_nl_widget_domain'),
                // Widget description
                array('description' => __('Sample widget based on WPBeginner Tutorial', 'acdhch_nl_widget_domain'),)
        );
    }

    public function widget($args, $instance) {
        if(!isset($instance['acdhch_nl_header_title'])) {
            $instance['acdhch_nl_header_title'] = "";
        }
        $title = apply_filters('widget_title', $instance['acdhch_nl_header_title']);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (empty($title)) {
            $title = __('Newsletter Subscribe', 'acdhch_nl_widget_domain');
        }

        echo $args['before_title'] . $title . $args['after_title'];
        // This is where you run the code and display the output
        $this->addFormData();
        $this->addResultDiv();
        
        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form($instance) {
        if (isset($instance['acdhch_nl_header_title'])) {
            $title = $instance['acdhch_nl_header_title'];
        } else {
            $title = __('Newsletter Subscribe', 'acdhch_nl_widget_domain');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('acdhch_nl_header_title'); ?>"><?php _e('Heeader Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('acdhch_nl_header_title'); ?>" name="<?php echo $this->get_field_name('acdhch_nl_header_title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['acdhch_nl_header_title'] = (!empty($new_instance['acdhch_nl_header_title']) ) ? strip_tags($new_instance['acdhch_nl_header_title']) : '';
        return $instance;
    }

    private function addResultDiv(): void {
        ?>
        <div id="acdch_nl_result_div" class="acdch_nl_result"></div>
        <?php
    }

    private function addFormData(): void {
        ?>
        <div class="acdch_nl_container">
            <label for="acdch_nl_email"><?php _e('Email address'); ?>:</label>
            <input class="widefat" id="acdhch_nl_email" name="acdhch_nl_email" type="text"  />
            <input type="button" id="acdhch_nl_email_submit" class="acdhch_nl_email_submit_btn" value="<?php _e('Subscribe'); ?>">
        </div>
        <?php
    }

}
