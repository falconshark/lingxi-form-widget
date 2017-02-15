<?php
/*
Plugin Name: Lingxi Form
Plugin URI:
Description: A widget that display Lingxi form fill data.
Version: 1.0
Author: Sardo Ip
Author URI: http://blog.sardo.work
License: MIT
*/

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require plugin_dir_path( __FILE__ ) . 'lib/lingxi.php';

use Lingxi\Signature\Client;

class Lingxi_Form_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('lingxi_form_widget',
        __('灵析表单', 'lingxi_form_widget' ), // Name
        array('description' => __('显示灵析表单签署人数', 'lingxi_form_widget' )));
    }

    /**
    * Output widget content.
    *
    * @param array $args
    * @param array $instance
    */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title'] ). $args['after_title'];
        }

        echo $args['after_widget'];
    }

    /**
    * Display widget option form.
    *
    * @param array $instance
    */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Lingxi Form', 'lingxi_form_widget' );
        $api_key = !empty($instance['api_key']) ? $instance['api_key'] : __('', 'lingxi_form_widget' );
        $api_secret = !empty($instance['api_secret']) ? $instance['api_secret'] : __('', 'lingxi_form_widget' );
        $form = !empty( $instance['form'] ) ? $instance['form'] : __('', 'lingxi_form_widget' );

        if(!empty($api_key && $api_secret)){
            $api_client = new Client($api_key, $api_secret);
            $form_options = get_form_list($api_client);
        }

        ?>
        <p>
            <i>输入API KEY及API Secret后，才能显示表单列表。</i>
            <br /><br />
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('api_key'); ?>"><?php _e('灵析 API KEY：'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" type="text" value="<?php echo esc_attr($api_key); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('api_secret'); ?>"><?php _e('灵析 API Secret：'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('api_secret'); ?>" name="<?php echo $this->get_field_name('api_secret'); ?>" type="password" value="<?php echo esc_attr($api_secret); ?>">
        </p>

        <?php if (!empty($instance['api_key']) && !empty($instance['api_secret'])): ?>
            <?php if (!empty($form_options)): ?>
                <p>
                    <label for="<?php echo $this->get_field_id('form'); ?>"><?php _e('请选择需要显示的表单:'); ?></label>
                    <select class='widefat' id="<?php echo $this->get_field_id('form'); ?>" name="<?php echo $this->get_field_name('form'); ?>" type="text">
                        <?php foreach($form_options as $option): ?>
                            <option value='<?php echo($option['id']) ?>'<?php echo ($form == $option['id']) ? 'selected':''; ?>>
                                <?php echo $option['attributes']['title']?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            <? else: ?>
            <p>

            </p>
        <?php endif ?>
    <?php endif ?>
    <?php
}

/**
* Save new widget option.
*
* @param array $new_instance
* @param array $old_instance
*/
public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = (!empty( $new_instance['title'])) ? strip_tags($new_instance['title']): '';
    $instance['api_key'] = (!empty($new_instance['api_key'])) ? strip_tags($new_instance['api_key']): '';
    $instance['api_secret'] = (!empty($new_instance['api_secret'])) ? strip_tags($new_instance['api_secret']): '';
    $instance['form'] = (!empty($new_instance['form'])) ? strip_tags($new_instance['form']): '';
    return $instance;
}
}

add_action('widgets_init',
create_function('', 'return register_widget("Lingxi_Form_Widget");')
);
