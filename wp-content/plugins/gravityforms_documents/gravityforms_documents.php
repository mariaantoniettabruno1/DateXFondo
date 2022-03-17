<?php


	/**
 	 Plugin Name: SP Gravity Forms Documents
	 Plugin URI: http://specialpress.de/plugins/spgfdoc
	 Description: Use Gravity Forms as Front-End to fill Microsoft Word Documents
	 Version: 2.8.0
	 Date: 2021/07/23
	 Author: Ralf Fuhrmann
	 Author URI: http://naranili.de
	 */

	
	
	/**
	 * Changes
	 * -------
	 *
	 * updated to Wordpress 5.8
	 * updated to phpWord 0.1.8.2
	 * fixed a problem with notification-attachements
	 *
	 */
	 
	 
	
	/**
	 * To Do
	 * -----
	 *
	 *
	 */
	 

	
    error_reporting( E_ERROR );
	
	
	require_once( 'class.gravityforms_documents.gfapi.php' );	
	
	
	
	// check if GF is active and include common classes 
	
	if ( class_exists( 'GFForms' ) )
	{


		GFAddOn::register( 'SpGfDocuments' );	

		GFForms::include_feed_addon_framework();



		// check and maybe load the plugin class 

		if( !class_exists( 'SpGfDocuments' ) ) 
			require_once( 'class.gravityforms_documents.php' );
	

	}



?>