<?php

/**
* Post Attachment Sizes Administration
*
* Sets up the custom sortable size columns in wp-admin
*
* @author 	Tyler Bailey
* @version 1.0
* @package post-attachment-sizes
* @subpackage post-attachment-sizes/inc/admin
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('PAS_Admin')) :

	class PAS_Admin extends PAS_Attachments {

		/**
		* Executed on class istantiation.
		*
		* @since    1.0.0
		*/
		public function __construct() {
			if(!is_admin())
				exit("You must be an administrator.");

			parent::__construct();

			// Init WP API
			$this->pas_init_wp_filters();
    	}

		/**
		* Hooks into WP API to create the custom size column.
		*
		* @since    1.0.0
		*/
		private function pas_init_wp_filters() {
			// Create the Post Size column in wp-admin post list
			add_filter('manage_posts_columns', array($this, 'pas_size_column_head'));
			// Populate the Post Size Column
			add_action( 'manage_posts_custom_column', array($this, 'pas_size_column_content'), 10, 2 );
			// Make Post Size column sortable
			add_filter( 'manage_edit-post_sortable_columns', array($this, 'pas_size_column_sortable'));
			// Process the order by request
			add_filter( 'pre_get_posts', array($this, 'pas_size_column_sortable_orderby') );
		}

		/**
		* Creates the header for the Size column
		*
		* @since    1.0.0
		* @param  $columns - array
		* @return	array
		*/
		public function pas_size_column_head($columns) {
			$columns['size'] = __('Post Size', PAS_SLUG);
			return $columns;
		}

		/**
		* Populates the 'Size' column
		*
		* @since    1.0.0
		* @param  $column_name - string
		* @param  $post_id - int
		* @return	string
		*/
		public function pas_size_column_content($column_name, $post_id) {
			if('size' != $column_name)
				return;

			$size = $this->pas_return_formatted_size($post_id);

			echo $size;
		}

		/**
		* Adds the Post Size column to the sortable array
		*
		* @since    1.0.0
		* @param  $columns - array
		* @return	array
		*/
		public function pas_size_column_sortable($columns) {
			$columns['size'] = $this->bmk;
			return $columns;
		}

		/**
		* Custom sorting function for the Post Size column
		*
		* @since    1.0.0
		* @param  $query - string
		* @return	string
		*/
		public function pas_size_column_sortable_orderby($query) {

			if(!is_admin())
				return;

			$orderby = $query->get('orderby');

			if($this->bmk == $orderby) {
				$query->set('meta_key', $this->bmk);
				$query->set('orderby', 'meta_value_num meta_value');
			}
		}

	}

	new PAS_Admin();

endif;
