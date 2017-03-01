<?php
/*
Plugin Name: Lingxi Form
Plugin URI:
Description: A widget that display Lingxi form fill data.
Version: 1.0
Author: Sardo Ip
Author URI: http://blog.sardo.work
License: GPL
*/

include_once(plugin_dir_path( __FILE__ ) . 'vendor/autoload.php');
include_once(plugin_dir_path( __FILE__ ) . 'lib/lingxi.php');

use Lingxi\Signature\Client;

function add_css_file() {
	wp_enqueue_style('lingxi-form-css', plugins_url('lingxi-form/css/lingxi_form.css'));
}

add_action('wp_enqueue_scripts', 'add_css_file');

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
	public function widget($args, $instance) {
		$api_key = $instance['api_key'];
		$api_secret = $instance['api_secret'];
		$form = $instance['form'];
		$form_summary = $instance['form_summary'];
		$form_article = $instance['form_article'];


		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title'] ). $args['after_title'];
		}

		if(!empty($form)){
			$api_client = new Client($api_key, $api_secret);
			$form_data = get_form($api_client, $form);
			$countersigned = get_form_fill($api_client, $form);
			$qr_code = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $form_article;

			if(!empty($form_data)){
				if(!empty($countersigned)){
					$countersigned_number = count($countersigned);
				}else{
					$countersigned_number = 0;
				}

				echo '<div class="lingxi-form">';
				echo '<div class="lingxi-form-title">' . $form_data['attributes']['title'] . '</div></br>';
				echo '<div class="lingxi-form-summary">' . $form_summary . '</div></br>';
				echo '<hr class="lingxi-form-line"/>';
				echo '<div class="lingxi-countersign">截至目前，已经有' . $countersigned_number . '人参与联署</p> </div>';
				echo '<a href="' .  $form_article . '"class="lingxi-btn">您可以点击此处参与联署</a>';
				echo '<p>也可以扫描下方二维码参与联署:</p>';
				echo '<img src="'. $qr_code . '"/>';
				echo '</div>';
			}
		}

		echo $args['after_widget'];
	}

	/**
	* Save new widget option.
	*
	* @param array $new_instance
	* @param array $old_instance
	*/
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty( $new_instance['title'])) ? strip_tags($new_instance['title']): '';
		$instance['api_key'] = (!empty($new_instance['api_key'])) ? strip_tags($new_instance['api_key']): '';
		$instance['api_secret'] = (!empty($new_instance['api_secret'])) ? strip_tags($new_instance['api_secret']): '';
		$instance['form'] = (!empty($new_instance['form'])) ? strip_tags($new_instance['form']): '';
		$instance['form_summary'] = (!empty($new_instance['form_summary_editor'])) ? $new_instance['form_summary_editor']: '';
		$instance['form_article'] = (!empty($new_instance['form_article'])) ? strip_tags($new_instance['form_article']): '';
		return $instance;
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
		$form_summary = !empty($instance['form_summary'] ) ? $instance['form_summary'] : __('<img class="lingxi-form-image" src="" />', 'lingxi_form_widget' );
		$form_article = !empty($instance['form_article'] ) ? $instance['form_article'] : __('', 'lingxi_form_widget' );

		if(!empty($api_key && $api_secret)){
			$api_client = new Client($api_key, $api_secret);
			$form_options = get_form_list($api_client);
		}

		$settings = array(
			'media_buttons' => true,
			'textarea_rows' => 5,
			'textarea_name' => $this->get_field_name('form_summary_editor')
		);

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
	<input type="hidden" id="<?php echo $this->get_field_id('form_summary_id') ?>" name="<?php echo $this->get_field_name('form_summary_id') ?>" value="<?php echo $this->get_field_id('form_summary') ?>" />
	<p>
		<label for="<?php echo $this->get_field_id('form_summary'); ?>"><?php _e('表单简介：'); ?></label>
		<?php wp_editor($form_summary, $this->get_field_id('form_summary'), $settings); ?>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('form_article'); ?>"><?php _e('表单文章网址：'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('form_article'); ?>" name="<?php echo $this->get_field_name('form_article'); ?>" type="text" value="<?php echo esc_attr($form_article); ?>">
	</p>
	<?php
}
}

add_action('widgets_init',
create_function('', 'return register_widget("Lingxi_Form_Widget");'));
