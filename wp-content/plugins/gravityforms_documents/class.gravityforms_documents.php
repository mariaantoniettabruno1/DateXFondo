<?php



	/**
 	 Class Name: SP Gravity Forms Documents
	 Class URI: http://specialpress.de/plugins/spgfdoc
	 Description: Use Gravity Forms as Front-End to fill Microsoft Word Documents
	 Version: 2.8.0
	 Date: 2021/07/23
	 Author: Ralf Fuhrmann
	 Author URI: http://naranili.de
	 */



	class SpGfDocuments extends GFFeedAddOn 
	{

	
		private static $_instance = null;


        /**
         * @var string Version number of the Add-On
         */
		protected $_version = '2.8.0';

        /**
         * @var string Gravity Forms minimum version requirement
         */
		protected $_min_gravityforms_version = '2.5.2.2';

        /**
         * @var string URL-friendly identifier used for form settings, add-on settings, text domain localization...
         */
		protected $_slug = 'documents';

        /**
    	 * @var string Relative path to the plugin from the plugins folder. Example "gravityforms/gravityforms.php"
         */
		protected $_path = 'gravityforms_documents/gravityforms_documents.php';

        /**
         * @var string Full path the the plugin. Example: __FILE__
         */
		protected $_full_path = __FILE__;

        /**
         * @var string URL to the Gravity Forms website. Example: 'http://www.gravityforms.com' OR affiliate link.
         */
        protected $_url;

        /**
         * @var string Title of the plugin to be used on the settings page, form settings and plugins page. Example: 'Gravity Forms MailChimp Add-On'
         */
		protected $_title = 'Gravity Forms Documents';

        /**
         * @var string Short version of the plugin title to be used on menus and other places where a less verbose string is useful. Example: 'MailChimp'
         */
		protected $_short_title = 'GF Documents';

        /**
         * @var array Members plugin integration. List of capabilities to add to roles.
         */
        protected $_capabilities = array();


        // ------------ Permissions -----------

        /**
         * @var string|array A string or an array of capabilities or roles that have access to the settings page
         */
        protected $_capabilities_settings_page = array( 'gravityforms_edit_settings' );

        /**
         * @var string|array A string or an array of capabilities or roles that have access to the form settings
         */
        protected $_capabilities_form_settings = array( 'gravityforms_edit_forms' );

        /**
         * @var string|array A string or an array of capabilities or roles that have access to the plugin page
         */
        protected $_capabilities_plugin_page = array( 'gravityforms_edit_settings' );

        /**
         * @var string|array A string or an array of capabilities or roles that have access to the app menu
         */
        protected $_capabilities_app_menu = array( 'gravityforms_edit_settings' );

        /**
         * @var string|array A string or an array of capabilities or roles that have access to the app settings page
         */
        protected $_capabilities_app_settings = array( 'gravityforms_edit_settings' );

        /**
         * @var string|array A string or an array of capabilities or roles that can uninstall the plugin
         */
        protected $_capabilities_uninstall = array( 'gravityforms_uninstall' );



		/**
		 * get an instance of this class
		 *
		 * @param
		 * @return GFSimpleAddOn
		 * @since  2.0.0
		 */

		public static function get_instance() 
		{
		
			
			if ( self::$_instance == null ) 
				self::$_instance = new SpGfDocuments();
		

			return( self::$_instance );


		}	



		/**
		 * plugin starting point
		 * handles hooks, loading of language files
		 *
		 * @param
		 * @return void
		 * @since  2.0.0
		 */

		public function init() 
		{

		
			parent::init();


			// load the textdomain 

			if( function_exists('load_plugin_textdomain') )
				load_plugin_textdomain( 'spgfdocs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');


			// add GF actions 

            add_action( 'gform_admin_pre_render', array( &$this, 'spgfdocs_gform_admin_pre_render' ), 10 , 1 );
			add_action( 'gform_before_delete_form', array( &$this, 'spgfdocs_gform_before_delete_form' ), 10, 1 );
			add_action( 'gform_after_update_entry', array( &$this, 'spgfdocs_gform_after_update_entry' ), 10, 3 );	
			add_action( 'gform_delete_entry', array( &$this, 'spgfdocs_gform_delete_entry' ), 10, 1 );
			add_action( 'gform_delete_entries', array( &$this, 'spgfdocs_gform_delete_entries' ), 10, 2 );
			add_action( 'gform_entry_info', array( &$this, 'spgfdocs_gform_entry_info' ), 10, 2 );
		
		
			// add GF filters 

			add_filter( 'gform_replace_merge_tags', array( &$this, 'spgfdocs_gform_replace_merge_tags' ), 10, 7 );
			add_filter( 'gform_notification_settings_fields', array( &$this, 'spgfdocs_gform_notification_settings_fields' ), 10, 3);	
			add_filter( 'gform_notification', array( &$this, 'spgfdocs_gform_notification' ), 10, 3 );
            add_filter( 'gform_include_thousands_sep_pre_format_number', '__return_true' );


			// add GravityView actions 
			
			add_action( 'gravityview/edit_entry/after_update', array( &$this, 'spgfdocs_gravityview_edit_entry_after_update' ), 10, 3 );     
			       

		}



		/**
		 * enable feed duplication
		 * 
		 * @param  int $feed_id  the ID of the feed to be duplicated or the feed object when duplicating a form
		 * @return bool
		 * @since  2.0.0
		 */
		 
        public function can_duplicate_feed( $feed_id ) 
        {


			self::log_debug( __METHOD__ );		
		
            return( true );

		
        }



		/**
		 * process the feed 
		 *
		 * @param  array $feed  the feed object to be processed
		 * @param  array $entry  the entry object currently being processed
		 * @param  array $form  the form object currently being processed
		 * @return bool|void
		 * @since  2.0.0
		 */

		public function process_feed( $feed, $entry, $form ) 
		{


			self::log_debug( __METHOD__ );
			
			
			// return if there is no template file

			self::log_debug( __METHOD__ . ' | Checking template : ' . $feed[ 'meta' ][ 'templateFile' ] );
			
			if( empty( $feed[ 'meta' ][ 'templateFile' ] ) )
				return;
			
			if( !is_file( $feed[ 'meta' ][ 'templateFile' ] ) )
				return;
					
					
		
			// include the libs
		
			self::log_debug( __METHOD__ . ' | Loading lib : ' . realpath( __DIR__ . '/vendor' ) . '/autoload.php' );
			
			require_once ( realpath( __DIR__ . '/vendor' ) . '/autoload.php' );
			

			// load the template file 
			
			self::log_debug( __METHOD__ . ' | Loading template : ' . $feed[ 'meta' ][ 'templateFile' ] );
	
			$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor( $feed[ 'meta' ][ 'templateFile' ] );
			

			// process the used datafields 
			
			self::log_debug( __METHOD__ . ' | Processing fields' );

			foreach( (array)$feed[ 'meta' ][ 'templateFields' ] AS $field ) 
			{


				$entry_field = RGFormsModel::get_field( $form, $field[ 'value' ] );
				
				$entry_value = $this->get_field_value( $form, $entry, $field[ 'value' ] );
				

				// get some special values 
				
				switch( $entry_field[ 'type' ] ) 
				{
				
					// standard fields 
				
					case 'text':
                    case 'textarea':
                    case 'number':
                        $entry_value = GFCommon::get_lead_field_display( $entry_field, $entry_value, $entry[ 'currency' ] );
                        break;


					// advanced fields 
                    
                    case 'date':
                    case 'time':
                        $entry_value = GFCommon::get_lead_field_display( $entry_field, $entry_value, $entry[ 'currency' ] );
                        break;
                        
                        
                    // post fields 

                    case 'post_title':
                    case 'post_excerpt':
                    case 'post_content':
                        $entry_value = GFCommon::get_lead_field_display( $entry_field, $entry_value, $entry[ 'currency' ] );
                        break;

                    case 'post_custom_field':
                    
						
						// this field could have an input type of some other field 
						
						switch( $entry_field[ 'inputType' ] )
						{
						
						
							case 'text':
							case 'textarea':
							case 'number':
								$entry_value = GFCommon::get_lead_field_display( $entry_field, $entry_value, $entry[ 'currency' ] );
								break;						
								
								
						}
						break;
                                        
                    
                                            
					// pricing fields 
                    
                    case 'total':
                    case 'option':
                    case 'shipping':
                        $entry_value = GFCommon::get_lead_field_display( $entry_field, $entry_value, $entry[ 'currency' ] );
                        break;
                        
				}				


				// strip all HTML tags, excluding <br> 

                $entry_value = strip_tags( $entry_value, "<br>" );
                

				// change somes special HTML characters to XML 
                
				$entry_value = str_replace( "<br />", "<w:br />", $entry_value );
				$entry_value = str_replace( "<br/>", "<w:br />", $entry_value );
				$entry_value = str_replace( "<br>", "<w:br />", $entry_value );

				
                // replace the template field 
				
				$templateProcessor->setValue( $field[ 'custom_key' ], $entry_value );


			}


			// check if the entry already has metadata for this DOCX document 
				
			$fileurlDOCX = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_url' );		
			$filenameDOCX = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_file' );		
				
			if( !$filenameDOCX || !is_file( $filenameDOCX ) )
			{
        
        
				// check the template file 

				$uploadDir = wp_upload_dir();
			
				if( !is_dir( trailingslashit( $uploadDir[ 'path' ] ) . 'documents' ) )
				{
				
					if( !mkdir( trailingslashit( $uploadDir[ 'path' ] ) . 'documents' ) )
						self::log_debug( __METHOD__ . ' | ERROR creating directory: ' .  trailingslashit( $uploadDir[ 'path' ] ) . 'documents' );
					else
						self::log_debug( __METHOD__ . ' | Created directory: ' .  trailingslashit( $uploadDir[ 'path' ] ) . 'documents' );
                
                }


				// create the output file 
            
				$documentFile = $feed[ 'meta' ][ 'documentFile' ];
				if( empty( $documentFile ) )
					$documentFile = 'document_' . sha1( $entry[ 'id' ] . '-' . $feed[ 'id' ] );
				else
					$documentFile = GFCommon::replace_variables( $documentFile, $form, $entry );
                
                
                $fileurlDOCX = trailingslashit( $uploadDir[ 'url' ] ) . 'documents/' . $documentFile . '.docx';
				$filenameDOCX = trailingslashit( $uploadDir[ 'path' ] ) . 'documents/' . $documentFile . '.docx';
			
			}
			
			
			
			// update the entry meta 
				
			gform_update_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_url', $fileurlDOCX );
			gform_update_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_file', $filenameDOCX );
			
                
			// save the templated file as DOCX 
			
			self::log_debug( __METHOD__ . ' | Try to save DOCX file : ' . $filenameDOCX );
		
			$templateProcessor->saveAs( $filenameDOCX );

			
			if( !is_file( $filenameDOCX ) )
				self::log_debug( __METHOD__ . ' | ERROR creating file: ' .  $filenameDOCX );
			
			

			// check if we also need a HTML document 
   
			
			if( $feed[ 'meta' ][ 'generateHTML' ] == 1 )
			{


				// check if the entry already has metadata for this DOCX document 
				
				$fileurlHTML = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_url' );		
				$filenameHTML = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_file' );		
				
				if( !$filenameHTML || !is_file( $filenameHTML ) )
				{
        
        
					// check the template file 

					$uploadDir = wp_upload_dir();
			
					if( !is_dir( trailingslashit( $uploadDir[ 'path' ] ) . 'documents' ) )
						mkdir( trailingslashit( $uploadDir[ 'path' ] ) . 'documents' );
                

					// create the output file 
            
					$documentFile = $feed[ 'meta' ][ 'documentFile' ];
					if( empty( $documentFile ) )
						$documentFile = 'document_' . sha1( $entry[ 'id' ] . '-' . $feed[ 'id' ] );
					else
						$documentFile = GFCommon::replace_variables( $documentFile, $form, $entry );
                
					$fileurlHTML = trailingslashit( $uploadDir[ 'url' ] ) . 'documents/' . $documentFile . '.html';
					$filenameHTML = trailingslashit( $uploadDir[ 'path' ] ) . 'documents/' . $documentFile . '.html'; 
					               
				
				}
				
				
				// update the entry meta 
				
				gform_update_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_url', $fileurlHTML );
				gform_update_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_file', $filenameHTML );				
				
				
				// save the templated file as HTML 
				
				self::log_debug( __METHOD__ . ' | Try to save HTML file : ' . $filenameHTML );				

				$templateProcessor = \PhpOffice\PhpWord\IOFactory::load( $filenameDOCX );
				$templateProcessorHTML = \PhpOffice\PhpWord\IOFactory::createWriter( $templateProcessor, 'HTML' );
				$templateProcessorHTML->save( $filenameHTML );
				
				if( !is_file( $filenameHTML ) )
					self::log_debug( __METHOD__ . ' | ERROR creating file: ' .  $filenameHTML );			
						
				
			}
			
			
			self::log_debug( __METHOD__ . ' | End feed processing' );
            

		}




		/**
		 * --------------------------------------------------------------------------------
		 * filters and actions to extend the GF functions
		 * --------------------------------------------------------------------------------
		 */




		/**
		 * return an array of the columns to display
		 *
		 * @param
		 * @return array
		 * @since  2.0.0
		 */

		public function feed_list_columns() 
		{


			self::log_debug( __METHOD__ );		
    
		
			return( array(
				'feedName' => esc_html__( 'Feed Name', 'spgfdocs' ),
				'templateFile' => esc_html__( 'Template File', 'spgfdocs' )
			) );


		}



		/**
		 * configures the settings which should be rendered on the feed edit page in the form settings
		 *
		 * @param
		 * @return array
		 * @since  2.0.0
		 */

		public function feed_settings_fields() 
		{


			self::log_debug( __METHOD__ );		


			$settingFields = array();


			$settingFields[ 'default' ] = array(
				
				'title'  => esc_html__( 'Default Document Fields', 'spgfdocs' ),
				'description' => '',
				'fields' => array(

						array(
							'label'   	=> esc_html__( 'Feed Name', 'spgfdocs' ),
							'type'   	=> 'text',
							'name'    	=> 'feedName',
							'class'		=> 'medium',
							'tooltip' 	=> esc_html__( 'Enter a name for the feed', 'spgfdocs' ),
							'required'	=> true,
						),

						array(
							'label'     => esc_html__( 'Template Filename', 'spgfdocs' ),
							'name'      => 'templateFile',
							'tooltip'	=> esc_html__( 'Enter the name and path of your uploaded Template file', 'spgfdocs' ),
							'type'    	=> 'feed_file_upload',
							'class'   	=> 'large',
							'save_callback' => array( &$this, 'handle_file_upload_save' )
						),
						
						array(
							'label'     => esc_html__( 'Document Filename', 'spgfdocs' ),
							'name'      => 'documentFile',
							'tooltip'	=> esc_html__( "Enter the name of the output Document file without the '.docx'. You can use Merge-Tags. Leave the field empty to use an SHA1-Coded String", 'spgfdocs' ),
							'type'    	=> 'text',
							'class'   	=> 'large',
						),						
						
						array(
							'label'     => esc_html__( 'Generate Files', 'spgfdocs' ),
							'name'      => 'generateFiles',
							'tooltip'	=> esc_html__( "Please select wich files should be generated", 'spgfdocs' ),
							'type'    	=> 'checkbox',
							'choices' 	=> array(
												array(
													'label'         => esc_html__( 'Generate DOCX File', 'spgfdocs' ),
													'name'          => 'generateDOCX',
													'default_value' => 1,
 
													),
												array(
													'label'         => esc_html__( 'Generate HTML File', 'spgfdocs' ),
													'name'          => 'generateHTML',
													'default_value' => 0,
 
													),													
												),
							),
												
                        array(
                            'name'           	=> 'feedCondition',
                            'label'         	 => esc_html__( 'Feed Condition', 'gravityforms' ),
                            'type'           	=> 'feed_condition',
                            'checkbox_label' 	=> esc_html__( 'Enable Conditional Logic', 'gravityforms' ),
                            'instructions'   	=> esc_html__( 'Process this feed if', 'gravityforms' ),
                        ),
					
					),

				);


			$settingFields[ 'mapping' ] = array(

				'title'  => esc_html__( 'Template Fields', 'spgfdocs' ),
				'fields' => array(

					array(
						'name'                => 'templateFields',
						'label'               => esc_html__( 'Template Fields', 'spgfdocs' ),
						'type'                => 'dynamic_field_map',
						'tooltip'             => esc_html__( 'Add the Template Fields you need to map to your Form Fields', 'spgfdocs' ),

					),
    
				),

			);
				
			return( array_values( $settingFields ) );


		}



		/**
		 * display a file upload field
		 *
		 * @param array $field  the field object currently being processed
		 * @return bool|void
		 * @since 2.0.0
		 */

		public function settings_plugin_file_upload( $field )
		{


			self::log_debug( __METHOD__ );		
    

			$attributes = $this->get_field_attributes( $field );
			$default_value = rgar( $field, 'value' ) ? rgar( $field, 'value' ) : rgar( $field, 'default_value' );
			$value = $this->get_setting( $field[ 'name' ], $default_value );


			if( isset( $_POST[ '_gform_setting_' . $field[ 'name' ] ] ) )
				$value = $_POST[ '_gform_setting_' . $field['name' ] ];


			?>
			<input type="text" name="_gform_setting_<?php echo $field[ 'name' ]; ?>" value="<?php echo $value; ?>" <?php echo implode( ' ', $attributes ); ?> readonly />
			<input type="file" name="_gform_setting_<?php echo $field[ 'name' ]; ?>_upload" id="<?php echo $field[ 'name' ]; ?>" class="<?php echo $field[ 'class' ]; ?>" />
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('input[name=_gform_setting_<?php echo $field[ 'name']; ?>_upload]').closest('form').attr('enctype', 'multipart/form-data');
				});
			</script>
			<?php
  

		}



		/**
		 * display a file upload field
		 *
		 * @param array $field  the field object currently being processed
		 * @return bool|void
		 * @since 2.0.0
		 */

		public function settings_feed_file_upload( $field )
		{


			self::log_debug( __METHOD__ );		


			$attributes = $this->get_field_attributes( $field );
			$default_value = rgar( $field, 'value' ) ? rgar( $field, 'value' ) : rgar( $field, 'default_value' );
			$value = $this->get_setting( $field[ 'name' ], $default_value );


			if( isset( $_POST[ '_gform_setting_' . $field[ 'name' ] ] ) )
				$value = $_POST[ '_gform_setting_' . $field[ 'name' ] ];


			?>
			<input type="text" name="_gform_setting_<?php echo $field[ 'name' ]; ?>" value="<?php echo $value; ?>" <?php echo implode( ' ', $attributes ); ?> readonly />
			<input type="file" name="_gform_setting_<?php echo $field[ 'name' ]; ?>_upload" <?php echo implode( ' ', $attributes ); ?> />
			<?php echo rgar( $field, 'after_input' ); ?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('input[name=_gform_setting_<?php echo $field['name']; ?>_upload]').closest('form').attr('enctype', 'multipart/form-data');
				});
			</script>
			<?php
  

		}



		/**
		 * save the field value for a file upload field
		 *
		 * @param array $field  the field object currently being processed
		 * @param array $field_setting  the settings of the currently processed field
		 * @return string 
		 * @since 2.0.0
		 */

		public function handle_file_upload_save( $field, $field_setting )
		{


			self::log_debug( __METHOD__ );		
    
    
			// check if the function is already there 
			
			if( !function_exists( 'wp_handle_upload' ) )
				require_once( ABSPATH . 'wp-admin/includes/file.php' );			


			// upload the file 

			$upload = wp_handle_upload( $_FILES[ '_gform_setting_' . $field[ 'name' ] . '_upload' ], array( 'test_form' => false ) );

			if( isset( $upload[ 'file' ] ) )
				$_POST[ '_gform_setting_' . $field[ 'name' ] ] = $upload[ 'file' ];
			else
				$_POST[ '_gform_setting_' . $field[ 'name' ] ] = $field_setting;


			return( $_POST[ '_gform_setting_' . $field[ 'name' ] ]  );
  

		}



		/**
		 * --------------------------------------------------------------------------------
		 * filters and actions to extend the GF functions
		 * --------------------------------------------------------------------------------
		 */



		/**
		 * add some nice and new merge tags for each feed to be replaced with the needed data
		 *
		 * @param  object $form  the form
		 * @return object
		 * @since  2.0.0
		 */
		 
        function spgfdocs_gform_admin_pre_render( $form )
        {
        
        
			self::log_debug( __METHOD__ );		
        
        
			?>
			<script type="text/javascript">
				gform.addFilter('gform_merge_tags', 'add_merge_tags');
				function add_merge_tags(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option){
					<?php


					// get the feeds for this form 

					$feeds = GFAPI::get_feeds( NULL, $form[ 'id' ], $this->_slug );		
					
					
					// add a merge tag for each feed 
					
					foreach( (array)$feeds AS $feed )
					{
					
						if( $feed[ 'meta' ][ 'generateDOCX' ] == 1 )
						{
							?>
							mergeTags["custom"].tags.push({ tag: '{gfdocs-docx-url-<?php echo $feed[ 'id' ]; ?>}', label: '<?php echo __( 'DOCX|URL: ', 'spgfdocs' ) . $feed[ 'meta' ][ 'feedName' ]; ?>' });
							mergeTags["custom"].tags.push({ tag: '{gfdocs-docx-file-<?php echo $feed[ 'id' ]; ?>}', label: '<?php echo __( 'DOCX|FILE: ', 'spgfdocs' ) . $feed[ 'meta' ][ 'feedName' ]; ?>' });
							<?php
						}
						
						if( $feed[ 'meta' ][ 'generateHTML' ] == 1 )
						{
							?>					
							mergeTags["custom"].tags.push({ tag: '{gfdocs-html-url-<?php echo $feed[ 'id' ]; ?>}', label: '<?php echo __( 'HTML|URL: ', 'spgfdocs' ) . $feed[ 'meta' ][ 'feedName' ]; ?>' });
							mergeTags["custom"].tags.push({ tag: '{gfdocs-html-file-<?php echo $feed[ 'id' ]; ?>}', label: '<?php echo __( 'HTML|FILE: ', 'spgfdocs' ) . $feed[ 'meta' ][ 'feedName' ]; ?>' });
							<?php
						
						}
					}
					?>
					return mergeTags;
				}
			</script>
			<?php
	
	
			// return the form object from the php hook 
			
			return( $form );
            
        
        }



		/**
		 * replace the placeholder at a field with the right value
		 *
		 * @param  mixed $value  the field value
		 * @param  object $lead  the current entry
		 * @param  object $field  the current field
		 * @param  object $form  the current form
		 * @param  string $input_id  the id of the input
		 * @return array
		 * @since  2.0.0
		 */

		function spgfdocs_gform_replace_merge_tags( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format )
		{


			self::log_debug( __METHOD__ );		
			
			
			// check if we have an entry and a form 
			
			if( empty( $entry ) || empty( $form ) )
				return( $text );


			// check if we have a qr-code merge tag 
			
			if( (    strpos( $text, '{gfdocs-docx-url-' ) === false ) 
				&& ( strpos( $text, '{gfdocs-docx-file-' ) === false ) 
				&& ( strpos( $text, '{gfdocs-html-url-' ) === false )  
				&& ( strpos( $text, '{gfdocs-html-file-' ) === false ) )
				return( $text );


			// get the needed feeds for this form und return if we got an error object 

			$feeds = GFAPI::get_feeds( NULL, $form[ 'id' ], $this->_slug );
			if( is_object( $feeds ) )			
				return;
				
			
			// loop thru the feeds 
			
			foreach( (array)$feeds AS $feed )
			{
			
				
				// replace the DOCX tags 

				$fileurl = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_url' );
				$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ]. '_docx_file' );
				
				
				// only if there is a file 
				
				if( is_file( $filename ) )
				{
				
					$text = str_replace( '{gfdocs-docx-url-' . $feed[ 'id' ] . '}', $fileurl, $text );					
					$text = str_replace( '{gfdocs-docx-file-' . $feed[ 'id' ] . '}', $filename, $text );					
					
					
				}
								
					
				// replace the HTML tags 

				$fileurl = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_url' );
				$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ]. '_html_file' );
				
				
				// only if there is a file 
				
				if( is_file( $filename ) )
				{
				
					$text = str_replace( '{gfdocs-html-url-' . $feed[ 'id' ] . '}', $fileurl, $text );					
					$text = str_replace( '{gfdocs-html-file-' . $feed[ 'id' ] . '}', $filename, $text );					
					
					
				}
					
			}

			
			return( $text );			
			
			
		}



		/**
		 * extend the notification settings to attach the DOCX or HTML document at the notification eMail
		 *
		 * @param  array $fields  array of all fields of the form
		 * @param  array $notification  the notification array
		 * @param  object $form  the form object
		 * @return array
		 * @since  2.7.0
		 */

		function spgfdocs_gform_notification_settings_fields( $fields, $notification, $form )
		{


			self::log_debug( __METHOD__ );		

	
			// get the feeds for this form 

			$feeds = GFAPI::get_feeds( NULL, $form[ 'id' ], $this->_slug );


			// return if we got an error object 

			if( is_object( $feeds ) )
				return( $fields );
				
				
            // loop thru the feeds to extend the DOCX settings


			foreach( (array)$feeds AS $feed ) 
			{ 
			
			
				$choices = array();
				
				if( $feed[ 'meta' ][ 'generateDOCX' ] == 1 )
				{			
				
					$choices[] = array(
							
									'name'  => 'spgfdocs_notification_docx_feed_' . $feed[ 'id' ],
									'label' => esc_html__( 'Add a DOCX Document to the Notification', 'spgfdocs' ),
							
								);
				}
				
				if( $feed[ 'meta' ][ 'generateHTML' ] == 1 )
				{			
				
					$choices[] = array(
							
									'name'  => 'spgfdocs_notification_html_feed_' . $feed[ 'id' ],
									'label' => esc_html__( 'Add a HTML Document to the Notification', 'spgfdocs' ),
							
								);
				}				

				$fields[] = array(
			
					'title'  => esc_html__( 'Gravity Forms Documents', 'spgfdocs' ),
					'fields' => array(
				
						array(
					
							'name'    => 'spgfdocs_notification_feed_' . $feed[ 'id' ],
							'label'   => $feed[ 'meta' ][ 'feedName' ],
							'tooltip' => esc_html__( 'Add Documents to the Notification', 'spgfdocs' ),
							'type'    => 'checkbox',
							'choices' => $choices,							
						
						),

					),
			
				);
				
			}
			
			
			return( $fields );		

	
		}



		/**
		 * process the notification and add the qrcode as an attachement
		 *
		 * @param  array $notification  the notification array
		 * @param  object $form  the form object
		 * @param  object $entry  the entry object
		 * @return array
		 * @since  2.0.0
		 */

		function spgfdocs_gform_notification( $notification, $form, $entry ) 
		{


			self::log_debug( __METHOD__ );		


			// get the feeds for this form 

			$feeds = GFAPI::get_feeds( NULL, $form[ 'id' ], $this->_slug );


			// return if we got an error object 

			if( is_object( $feeds ) )
				return( $notification );


			// loop thru the feeds 

			foreach( (array)$feeds AS $feed ) 
			{

			
				// check if there is a DOCX file to attach 
				
				if( isset( $notification[ 'spgfdocs_notification_docx_feed_' . $feed[ 'id' ] ] ) && $notification[ 'spgfdocs_notification_docx_feed_' . $feed[ 'id' ] ] == '1' ) 
				{
				
					$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_file' );
			
					if( is_file( $filename ) )
						$notification[ 'attachments' ][] = $filename;
						
				}
				
				
				// check if there is a HTML file to attach 
				
				if( isset( $notification[ 'spgfdocs_notification_html_feed_' . $feed[ 'id' ] ] ) && $notification[ 'spgfdocs_notification_html_feed_' . $feed[ 'id' ] ] == '1' ) 
				{
				
					$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_file' );
			
					if( is_file( $filename ) )
						$notification[ 'attachments' ][] = $filename;
						
				}				


			}


			return( $notification );
			
			
		}	
		


		/**
		 * add custom entry information to the Info area on the Entry detail page
		 *
		 * @param	string $form  The ID of the form from which the entry was submitted
		 * @param	objec $entry  The current entry.
		 * @return  void
		 * @since	2.4.0
		 */

		function spgfdocs_gform_entry_info( $form_id, $entry )
		{


			self::log_debug( __METHOD__ );		
			
			
			// get the entry 

			$form = GFAPI::get_form( $form_id );
			
			
			// get the feeds for this form 

			$feeds = GFAPI::get_feeds( NULL, $form_id, $this->_slug );

			
			// return if we got an error object 

			if( is_object( $feeds ) )
				return;			
			
			
			// loop thru and process the feeds 
			
			foreach( (array)$feeds AS $feed )
			{
			
				
				// get the stored DOCX filename and display the file link 
		
				$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_file' );
				
				if( is_file( $filename ) )
				{
				
					$fileurl = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_url' );				
					?><br /><a href="<?php echo esc_url( $fileurl ) ?>" target="_blank"><?php echo esc_html( $feed[ 'meta' ][ 'feedName' ] ) ?> DOCX</a><?php
								
				}


				// get the stored HTML filename and display the file link 
		
				$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_file' );
				
				if( is_file( $filename ) )
				{
				
					$fileurl = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_url' );				
					?><br /><a href="<?php echo esc_url( $fileurl ) ?>" target="_blank"><?php echo esc_html( $feed[ 'meta' ][ 'feedName' ] ) ?> HTML</a><?php
								
				}

			}
			
				
		} 

		

		/**
		 * process the feeds after the entry was changed from the backend
		 *
		 * @param	object $form  the current form
		 * @param	int	$entry_id  the entry id
		 * @param	object $original_entry  the entry before the changes
		 * @return	void
		 * @since	2.4.0
		 */

		function spgfdocs_gform_after_update_entry( $form, $entry_id, $original_entry )
		{


			self::log_debug( __METHOD__ );		
			
			
			// get the entry 

			$entry = GFAPI::get_entry( $entry_id );
			
			
			// if the new entry is the same as the old one, there is nothing to do 
			
			if( $entry == $original_entry )
				return;


			// get the feeds for this form 

			$feeds = GFAPI::get_feeds( NULL, $entry[ 'form_id' ], $this->_slug );

			
			// return if we got an error object 

			if( is_object( $feeds ) )
				return;			
			
			
			// loop thru and process the feeds 
			
			foreach( (array)$feeds AS $feed )
				self::process_feed( $feed, $entry, $form );
			
				
		} 



		/**
		 * delete all qr-codes for the deleted entry
		 *
		 * @param  int $entry_id  the ID of the entry that is about to be deleted.
		 * @return void
		 * @since  2.4.0
		 */

		public function spgfdocs_gform_delete_entry( $entry_id )
		{


			self::log_debug( __METHOD__ );		


			// get the entry 

			$entry = GFAPI::get_entry( $entry_id );


			// loop thru and process the feeds 

			$feeds = GFAPI::get_feeds( NULL, $entry[ 'form_id' ], $this->_slug );
			
			
			// return if we got an error object 

			if( is_object( $feeds ) )
				return( $notification );
				
							
			// loop thru the feeds 
						
			foreach( (array)$feeds AS $feed )
			{


				// get the stored DOCX filename and delete the file 
		
				$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_file' );
				
				if( is_file( $filename ) )
					unlink( $filename );


				// get the stored HTML filename and delete the file 
		
				$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_file' );
				
				if( is_file( $filename ) )
					unlink( $filename );					
					
			}

				
		}



		/**
		 * empty the trash and delete the qr-codes for all trashed entries
		 *
		 * @param  int $form_id  the form id
		 * @param  string $status  the delete status
		 * @return void
		 * @since  2.4.0
		 */

		public function spgfdocs_gform_delete_entries( $form_id, $status )
		{


			self::log_debug( __METHOD__ );		


			// only if we empty the trash 
			 
			if( $status == 'trash' ) 
			{
						
						
				// get the feeds for this form 

				$feeds = GFAPI::get_feeds( NULL, $form_id, $this->_slug );


				// return if we got an error object 

				if( is_object( $feeds ) )
					return( $notification );
							
				
				// retrieve all assigned entries from the database 
				
				$search_criteria[ 'status' ] = 'trash';
				
				GFAPI::get_entries( $form_id, $search_criteria );
				
			
				// loop thru the entries 

				foreach( (array)$entries AS $entry ) 
				{


					// loop thru the feeds 
			
					foreach( (array)$feeds AS $feed )
					{
					
					
						// get the stored DOCX filename and delete the file 
		
						$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_file' );
						
						if( is_file( $filename ) )
							unlink( $filename );
					
					
						// get the stored DOCX filename and delete the file 
		
						$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_file' );
						
						if( is_file( $filename ) )
							unlink( $filename );		
										
					
					}
					
				}
						
			}
					
		}
		
		
		
		/**
		 * delete all documents if the form will be deleted
		 *
		 * @param  int $form_id  the form id
		 * @return void
		 * @since  2.5.0
		 */		
		
		public function spgfdocs_gform_before_delete_form( $form_id )
		{


			self::log_debug( __METHOD__ );		
		
		
			// get the form data 
			
			$form = GFAPI::get_form( $form_id );
			
			
			// get the feeds for this form 

			$feeds = GFAPI::get_feeds( NULL, $form_id, $this->_slug );


			// return if we got an error object 

			if( is_object( $feeds ) )
				return;
				

			// retrieve all assigned entries from the database 
				
			GFAPI::get_entries( $form_id, $search_criteria );
				
			
			// loop thru the entries 

			foreach( (array)$entries AS $entry ) 
			{


				// loop thru the feeds 
			
				foreach( (array)$feeds AS $feed )
				{
					
					
					// get the stored DOCX filename and delete the file 
		
					$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_docx_file' );
						
					if( is_file( $filename ) )
						unlink( $filename );
						
					
					// get the stored HTML filename and delete the file 
		
					$filename = gform_get_meta( $entry[ 'id' ], $this->_slug . '_feed_' . $feed[ 'id' ] . '_html_file' );
						
					if( is_file( $filename ) )
						unlink( $filename );						
					
				
				}
					
			}
			
		
		}




		/**
		 * --------------------------------------------------------------------------------
		 * filters and actions to support some other plugins
		 * --------------------------------------------------------------------------------
		 */



		
		/**
		 *
		 * GravityView doesn't trigger the `gform_after_submission` action when editing entries. This does that.
		 * 
		 * @param  array $form
		 * @param  int $entry_id ID of the entry being updated
		 * @param  GravityView_Edit_Entry_Render $object
		 * @return void
		 * @since  2.4.0
		 */

		public function spgfdocsgravityview_edit_entry_after_update( $form = array(), $entry_id = array(), $object = array() )
		{


			self::log_debug( __METHOD__ );		
		
			gf_do_action( array( 'gform_after_submission', $form['id'] ), $object->entry, $form );
			
		
		}


	}
