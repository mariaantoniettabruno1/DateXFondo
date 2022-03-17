<?php



	/**
 	 Class Name: SP Gravity Forms Documents GFAPI
	 Class URI: http://gravity-qr.com/
	 Description: Use Gravity Forms as Front-End to fill Microsoft Word Documents
	 Version: 2.8.0
	 Date: 2021/07/23
	 Author: Ralf Fuhrmann
	 Author URI: http://naranili.de
	 */
	 
	 
	 
	 
	/**
	 *
	 * class: SpGfDocumentsApi
	 *
	 * We can't catch some actions from the GFAPI with the GFFeedAddOn
	 * so we need to add a class before
	 *
	 * @since 2.4.0
	 * 
	 */

	class SpGfDocumentsApi
	{


		/**
		 * @var string URL-friendly identifier used for form settings, add-on settings, text domain localization...
		 */
		protected $_slug = 'documents';
		
		

		/**
		 * construct
		 *
		 * @param
		 * @return
		 * @since	2.4.0 
		 */
		 
		function __construct() 
		{
		
		
			// add GF actions
		
			add_action( 'gform_post_add_entry', array( &$this, 'spgfdocs_gform_post_add_entry' ), 10, 2 );		
			add_action( 'gform_post_update_entry', array( &$this, 'spgfdocs_gform_post_update_entry' ), 10, 2 );		
					
		
		}
		
		
		
		/**
		 * process the feeds if an entry was added with the GFAPI
		 *
		 * @param  object  $entry the current entry
		 * @param  object  $form the current form
		 * @return
		 * @since  2.4.0
		 */

		public function spgfdocs_gform_post_add_entry( $entry, $form )
		{


			// loop thru the fields and process the default values

			foreach( (array)$form[ 'fields' ] as $field )
			{
										
				if( $field[ 'defaultValue' ] )
					$entry[ $field[ 'id' ] ] = GFCommon::replace_variables( $field[ 'defaultValue' ], $form, $entry );											
			
			}	


			// loop thru and process the feeds 

			$feeds = GFAPI::get_feeds( NULL, $entry[ 'form_id' ], $this->_slug );
			foreach( (array)$feeds AS $feed )
			{
			
				$SpGfQRCode = SpGfDocuments::get_instance();
				$SpGfQRCode->process_feed( $feed, $entry, $form );

			}

		}		
		
		
		
		/**
		 * process the feeds after the entry was changed with the GFAPI
		 *
		 * @param  object  $entry the current entry		 
		 * @param  object  $original_entry the entry before the changes
		 * @return
		 * @since  2.4.0
		 */

		public function spgfdocs_gform_post_update_entry( $entry, $original_entry )
		{


			// if the new entry is the same as the old one, there is nothing to do 
			
			if( $entry == $original_entry )
				return;
				

			// loop thru and process the feeds 

			$form = GFAPI::get_form( $entry[ 'form_id' ] );
			$feeds = GFAPI::get_feeds( NULL, $entry[ 'form_id' ], $this->_slug );
			foreach( (array)$feeds AS $feed )
			{
			
				$SpGfQRCode = SpGfDocuments::get_instance();
				$SpGfQRCode->process_feed( $feed, $entry, $form );
			
			}
				
		} 		
		
		
	}
	
	
	/** 
	 * instance the class 
	 */
	
	$SpGfDocumentsApi = new SpGfDocumentsApi();
	
	
?>