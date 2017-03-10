<?php
/** 
 * Generate custom meta box class
 */

class GenerateCustomMeta {

	public $meta_boxes = array();

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'generate_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 *	Collect custom settings for meta box generator
	 */

	public function add_meta_box($id, $settings) {
		$this->meta_boxes[$id] = $settings;
	}

	/**
	 * Adds the meta box container.
	 */
	public function generate_meta_box( $post_type ) {

		if(empty($this->meta_boxes)) {
			return;
		}

		foreach ($this->meta_boxes as $id => $settings) {

			$settings = (object) $settings;
			
			if(in_array($post_type, $settings->post_type)) {
				add_meta_box(
					$id
					,$settings->title
					,array( $this, 'render_meta_box_content' )
					,$post_type
					,$settings->position
					,'low'
					,$id
				);
			}
		}
            
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['tt_custom_meta_nonce'] ) )
			return $post_id;

		$nonce = $_POST['tt_custom_meta_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'tt_custom_meta' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		foreach ($this->meta_boxes as $id => $settings) {

			if(!empty($_POST[$id]) && is_array($_POST[$id])) {
				// Sanitize the user input.
				$mydata = array();

				foreach ($_POST[$id] as $key => $value) {
					$mydata[$key] = sanitize_text_field($value);
				}

				// Update the meta field.
				update_post_meta( $post_id, $id, $mydata );
			}
		}
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post, $meta_box_id ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'tt_custom_meta', 'tt_custom_meta_nonce' );

		$meta_box_id = !empty($meta_box_id['args']) ? $meta_box_id['args'] : '';

		if(is_array($this->meta_boxes[$meta_box_id]['fields'])) {
			echo '<div class="custom-meta">';
			foreach ($this->meta_boxes[$meta_box_id]['fields'] as $key => $input_type) {
				echo '<div class="input-sep meta-'.$input_type[0].'">';
				$value = get_post_meta( $post->ID, $meta_box_id, true );
				$value = isset($value[$key]) ? $value[$key] : false;
				$this->render_input($input_type[0], $meta_box_id.'['.$key.']', $input_type[1], $value);
				echo '</div>';
			}
			echo '</div>';
		}

	}

	public function render_input($input_type, $key, $title, $value) {

		$label = '<label for="%s" class="input-label">%s</label>';
		$input_text = '<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" %4$s>';

		switch ($input_type) {
			case 'text':
				echo sprintf($label, $key, $title);
				echo sprintf($input_text, 'text', $key, $value, '');
			break;

			case 'date':
				echo sprintf($label, $key, $title);
				echo sprintf($input_text, 'date', $key, $value, '');
			break;

			case 'radio':
				echo sprintf($label, $key, $title);
				echo sprintf($input_text, 'radio', $key, $value, '');
			break;

			case 'checkbox':
				$checked = !empty($value) ? 'checked' : '';
				echo sprintf($label, $key, $title);
				echo sprintf($input_text, 'checkbox', $key, 1, $checked);
			break;

			case 'image':
				$image_path = $value ? wp_get_attachment_image_src($value, 'full', true) : array();
				echo sprintf($label, $key, $title);
				echo '<div class="image-picker">';
				echo sprintf('<img src="%s">', !empty( $image_path[0] ) ?  $image_path[0] : '' );
				if( !empty( $image_path[0] ) ) {
					echo '<span class="add-image tt-hide">Add image</span>';
					echo '<span class="remove-img">Remove image</span>';
				} else {
					echo '<span class="add-image">Add image</span>';
					echo '<span class="remove-img tt-hide">Remove image</span>';
				}
				echo sprintf($input_text, 'hidden', $key, $value, '');
				echo '</div>';
			break;

			case 'icon':
				$remove_button = ((int)$value !== 0) ? sprintf('<span><input type="radio" name="%s" value="0"><i class="icon-%s"></i><small>%s</small></span>', $key, $value, esc_html__('Remove', 'cre8or')) : '';

				echo '<div class="picker-title">'.$title.$remove_button.'</div>';
				echo '<div class="icon-picker">';
				for ($i=1; $i < 550; $i++) { 
					# code...
					$checked = ($i == $value) ? 'checked' : '';
					echo sprintf('<label>%s</label>', '<input type="radio" name="'.$key.'" value="'.$i.'" '.$checked.'><i class="icon-'.$i.'"></i>');
				}
				echo '</div>';
			break;

			case 'color':
				echo sprintf($label, $key, $title);
				echo sprintf('<input type="text" id="%s" name="%s" value="%s" class="tt_color_picker">', $key, $key, $value);
			break;
			
			default:
				# code...
			break;
		}


	}
}