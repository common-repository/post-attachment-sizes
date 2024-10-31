<?php

/**
* Post Attachment Sizes
*
* Retrieve all post image attachments and calculate the sizes
*
* @author 	Tyler Bailey
* @version 1.0
* @package post-attachment-sizes
* @subpackage post-attachment-sizes/inc
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('PAS_Attachments')) :

	class PAS_Attachments {
		/*
 		 * Post Meta Key for Byte Size
		 */
		public $bmk;

		/*
 		 * Post Meta Key for Formatted Size
		 */
		public $fmk;

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {

			$this->bmk = 'post_size_bytes';
			$this->fmk = 'post_size_formatted';

			// Make sure the posts have their sizes indexed
			add_action( 'the_post', array($this, 'pas_index_post_size_on_load'));

			// Save post size on save of post
			add_action( 'save_post', array($this, 'pas_index_post_size_on_save'), 10, 3 );

			// Shortcode to display the Bytes & Formatted Size of a post
			add_shortcode( 'pas_display_post_size', array($this, 'pas_display_size_sc') );
    	}

		/**
		* Ensure all post sizes are indexed as post_meta
		*
		* Hooked by `the_posts`
		*
		* @since    1.0.0
		* @param  $post_obj - object - WP Post Object
		* @return	string
		*/
		public function pas_index_post_size_on_load($post) {

			// Make sure this isn't just an autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

			$post_size_bytes = get_post_meta($post->ID, $this->bmk, true);

			if(!$post_size_bytes){
				$sizes = $this->pas_calculate_attachment_sizes($post->ID);

				update_post_meta($post->ID, $this->bmk, $sizes['bytes']);
				update_post_meta($post->ID, $this->fmk, $sizes['formatted']);
			}
		}

		/**
		* Update/Save Post Size to post_meta on save of the post
		*
		* Hooked by `save_post`
		*
		* @since    1.0.0
		* @param  $post_obj - object - WP Post Object
		* @return	string
		*/
		public function pas_index_post_size_on_save($post_id) {

			// Make sure this isn't just an autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

			if(get_post_type($post_id) !== 'post')
				return;

			// Get post size on new save
			$sizes = $this->pas_calculate_attachment_sizes($post_id);

			// Check if we have any previous post size stored
			$post_size_bytes = get_post_meta($post_id, $this->bmk, true);

			// If no size exists OR the new size is different than the old
			if(!$post_size_bytes || $sizes['bytes'] !== $post_size_bytes){
				// Add/update the post meta
				update_post_meta($post_id, $this->bmk, $sizes['bytes']);
				update_post_meta($post_id, $this->fmk, $sizes['formatted']);
			}
		}

		/**
		* Shortcode to display the total file size of a post
		*
		* @since    1.0.0
		* @param  $atts - wp shortcode API attributes
		*                 - post_id : ID of post to calculate total size of || Can be ID of attribute if single is true
		*                 - single : True|False if we are getting size of single attachment
		*                 - return : both | formatted | bytes -- default: both
		* @return	string - html
		*/
		public function pas_display_size_sc($atts) {
			$atts = shortcode_atts(array(
												'post_id' => false,
												'single' => false,
												'return' => 'both',
											), $atts, 'pas_display_post_size');

			if(!$atts['single']) {
				// If post_id is NOT set as a parameter, get it from the global object
				global $post;
				$post_id = (is_numeric($atts['post_id']) ? $atts['post_id'] : $post->ID);

				$sizes['bytes'] = $this->pas_return_byte_size($post_id);
				$sizes['formatted'] = $this->pas_return_formatted_size($post_id);
			} else {
				$file_size = $this->pas_get_attachment_size($atts['post_id']);

				$sizes['bytes'] = $file_size;
				$sizes['formatted'] = size_format($file_size);
			}

			ob_start();

			if(is_array($sizes) && !empty($sizes)) {
				switch($atts['return']) {
					case 'both' :
						echo '<span id="' . $atts['post_id'] . '" class="pas_bytes">' . $sizes['bytes'] . '</span>';
						echo '<span id="' . $atts['post_id'] . '" class="pas_formatted">' . $sizes['formatted'] . '</span>';
					break;
					case 'formatted' :
						echo '<span id="' . $atts['post_id'] . '" class="pas_formatted">' . $sizes['formatted'] . '</span>';
					break;
					case 'bytes' :
						echo '<span id="' . $atts['post_id'] . '" class="pas_bytes">' . $sizes['bytes'] . '</span>';
					break;
				}
			}

			return ob_get_clean();
		}

		/**
		* Returns formatted string of size
		*
		* @since    1.0.0
		* @param  $post_id - int - ID of object to retrieve size for
		* @return	string
		*/
		protected function pas_return_formatted_size($post_id) {
			$size = get_post_meta($post_id, $this->fmk, true);
			return $size;
		}

		/**
		* Returns byte string of size
		*
		* @since    1.0.0
		* @param  $post_id - int - ID of object to retrieve size for
		* @return	string
		*/
		protected function pas_return_byte_size($post_id) {
			$size = get_post_meta($post_id, $this->bmk, true);
			return $size;
		}

		/**
		* Returns an array of total size of all attachments added together
		* Returns size in bytes AND formatted size
		*
		* @since    1.0.0
		* @param  $post_id - int - ID of post to retreive attachments for
		* @return	array
		*/
		private function pas_calculate_attachment_sizes($post_id) {

			// The return Array
			$sizes = array();
			$total_post_size = 0;

			// Get attachments for post
			$attachments = get_attached_media('image', $post_id);

			// Loop through each attachment
			foreach($attachments as $k => $v) {
				if (file_exists(get_attached_file( ($v->ID), $unfiltered = false ))) {
					// Get the attachment filesize
					$size = $this->pas_get_attachment_size($v->ID);

					// Add the filesizes together
					$total_post_size += $size;
				}
			}

			// Put the file sizes in the return array
			$sizes['bytes'] = $total_post_size;
			$sizes['formatted'] = size_format($total_post_size);

			return $sizes;
		}

		/**
		* Return the size of a single attachment
		*
		* @since    1.0.0
		* @param  $att_id - int - ID of the attachment to calculate size
		* @return	string
		*/
		private function pas_get_attachment_size($att_id) {
			return filesize(get_attached_file($att_id));
		}
	}

	new PAS_Attachments();

endif;
