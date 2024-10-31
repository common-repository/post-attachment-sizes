<?php

/**
 * Post Attachment Sizes Plugin Bootstrap File
 *
 * @author 	Tyler Bailey
 * @version 1.0
 * @package post-attachment-sizes
 * @subpackage post-attachment-sizes/inc
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('PAS')) :

	class PAS {

		/**
		 * Plugin initialization functions
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->set_locale();
			$this->load_dependencies();
		}


		/**
		 * Loads all required plugin files and istantiates classes
		 *
		 * @since   1.0.0
		 */
		private function load_dependencies() {
			require_once(PAS_GLOBAL_DIR . 'inc/class-pas-attachments.php');

			if(is_admin())
				require_once(PAS_GLOBAL_DIR . 'inc/admin/class-pas-admin.php');

		}

		/**
		 * Loads the plugin text-domain for internationalization
		 *
		 * @since   1.0.0
		 */
		private function set_locale() {
			load_plugin_textdomain( PAS_SLUG, false, PAS_GLOBAL_DIR . 'language' );
	    }
		
	}

endif;
