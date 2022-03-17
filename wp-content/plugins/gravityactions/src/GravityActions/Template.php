<?php

namespace GravityKit\GravityActions;

use function GravityKit\GravityActions\is_truthy;
use function GravityKit\GravityActions\sort_by_priority;
use GravityKit\GravityActions\Utils\Paths;
use GravityKit\GravityActions\Utils\Arrays;

/**
 * Class Template.
 *
 * @since 1.0
 *
 * @package GravityKit\GravityActions
 */
class Template {
	/**
	 * The folders into which we will look for the template.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $template_folder = [];

	/**
	 * The origin class for the plugin where the template lives.
	 *
	 * @since 1.0
	 *
	 * @var object
	 */
	public $template_origin;

	/**
	 * The local template vars for templates, mutable on every static::template() call.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $template_vars = [];

	/**
	 * The global template vars for this instance of templates.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $template_global_vars = [];

	/**
	 * Used for finding templates for public templates on themes inside of a folder.
	 *
	 * @since 1.0
	 *
	 * @var string[]
	 */
	protected $template_origin_base_folder = [ 'src', 'views' ];

	/**
	 * Allow changing if class will extract data from the local template vars.
	 *
	 * @since 1.0
	 *
	 * @var boolean
	 */
	protected $template_vars_extract = false;

	/**
	 * Current template hook name.
	 *
	 * @since 1.0
	 *
	 * @var string|null
	 */
	protected $template_current_hook_name;

	/**
	 * Base template for where to look for template.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $template_base_path;

	/**
	 * Should we use a lookup into the list of folders to try to find the file.
	 *
	 * @since 1.0
	 *
	 * @var  bool
	 */
	protected $template_folder_lookup = false;

	/**
	 * Create a class variable for the include path, to avoid conflicting with extract.
	 *
	 * @since 1.0
	 *
	 * @var  string
	 */
	protected $template_current_file_path;

	/**
	 * Configures the class origin plugin path
	 *
	 * @since 1.0
	 *
	 * @param object|string $origin The base origin for the templates
	 *
	 * @return self
	 */
	public function set_template_origin( $origin = null ) {
		if ( empty( $origin ) ) {
			$origin = $this->template_origin;
		}

		if ( is_string( $origin ) ) {
			// Origin needs to be a class with a `instance` method
			if ( class_exists( $origin ) && method_exists( $origin, 'instance' ) ) {
				$origin = call_user_func( [ $origin, 'instance' ] );
			}
		}

		if ( ! is_string( $origin ) ) {
			$this->template_origin    = $origin;
			$this->template_base_path = untrailingslashit( $origin::get_base_path() );
		} else {
			$this->template_base_path = untrailingslashit( (array) explode( '/', $origin ) );
		}

		return $this;
	}

	/**
	 * Configures the class with the base folder in relation to the Origin
	 *
	 * @since 1.0
	 *
	 * @param array|string $folder Which folder we are going to look for templates
	 *
	 * @return self
	 */
	public function set_template_folder( $folder = null ) {
		// Allows configuring a already set class
		if ( ! isset( $folder ) ) {
			$folder = $this->template_folder;
		}

		// If Folder is String make it an Array
		if ( is_string( $folder ) ) {
			$folder = (array) explode( '/', $folder );
		}

		// Cast as Array and save
		$this->template_folder = (array) $folder;

		return $this;
	}

	/**
	 * Returns the array for which folder this template instance is looking into.
	 *
	 * @since 1.0
	 *
	 * @return array Current folder we are looking for templates.
	 */
	public function get_template_folder() {
		return $this->template_folder;
	}

	/**
	 * Configures the class with the base folder in relation to the Origin
	 *
	 * @since 1.0
	 *
	 * @param mixed $value Should we look for template files in the list of folders.
	 *
	 * @return self
	 */
	public function set_template_folder_lookup( $value = true ) {
		$this->template_folder_lookup = is_truthy( $value );

		return $this;
	}

	/**
	 * Gets in this instance of the template engine whether we are looking public folders like themes.
	 *
	 * @since 1.0
	 *
	 * @return bool Whether we are looking into theme folders.
	 */
	public function get_template_folder_lookup() {
		return $this->template_folder_lookup;
	}

	/**
	 * Configures the class global template vars
	 *
	 * @since 1.0
	 *
	 * @param array $template_vars Default global template vars
	 *
	 * @return self
	 */
	public function add_template_globals( $template_vars = [] ) {
		// Cast as Array merge and save
		$this->template_global_vars = wp_parse_args( (array) $template_vars, $this->template_global_vars );

		return $this;
	}

	/**
	 * Configures if the class will extract template vars for template
	 *
	 * @since 1.0
	 *
	 * @param bool $value Should we extract template vars for templates
	 *
	 * @return self
	 */
	public function set_template_vars_extract( $value = false ) {
		// Cast as bool and save
		$this->template_vars_extract = is_truthy( $value );

		return $this;
	}

	/**
	 * Set the current hook name for the template include.
	 *
	 * @since 1.0
	 *
	 * @param string $value Which value will be saved as the current hook name.
	 *
	 * @return self  Allow daisy-chaining.
	 */
	public function set_template_current_hook_name( $value ) {
		$this->template_current_hook_name = (string) $value;

		return $this;
	}

	/**
	 * Gets the hook name for the current template setup.
	 *
	 * @since 1.0
	 *
	 * @return string Hook name currently set on the class.
	 */
	public function get_template_current_hook_name() {
		return $this->template_current_hook_name;
	}

	/**
	 * Sets an Index inside of the global or local template vars.
	 * Final to prevent extending the class when the `get` already exists on the child class.
	 *
	 * @see    Arrays::set()
	 *
	 * @since 1.0
	 *
	 * @param array|string $index    Specify each nested index in order.
	 *                               Example: [ 'lvl1', 'lvl2' ];
	 * @param mixed        $default  Default value if the search finds nothing.
	 * @param boolean      $is_local Use the Local or Global template vars.
	 *
	 * @return mixed The value of the specified index or the default if not found.
	 */
	final public function get( $index, $default = null, $is_local = true ) {
		$template_vars = $this->get_template_global_vars();

		if ( true === $is_local ) {
			$template_vars = $this->get_template_local_vars();
		}

		/**
		 * Allows filtering the the getting of template vars variables, also short circuiting.
		 * Following the same structure as WP Core.
		 *
		 * @since 1.0
		 *
		 * @param mixed        $value    The value that will be filtered.
		 * @param array|string $index    Specify each nested index in order.
		 *                               Example: [ 'lvl1', 'lvl2' ];
		 * @param mixed        $default  Default value if the search finds nothing.
		 * @param boolean      $is_local Use the Local or Global template vars.
		 * @param self         $template Current instance of the Template.
		 */
		$value = apply_filters( 'gk/gravityactions/template_vars_get', null, $index, $default, $is_local, $this );

		if ( null !== $value ) {
			return $value;
		}

		return Arrays::get( $template_vars, $index, $default );
	}

	/**
	 * Sets a Index inside of the global or local template vars.
	 * Final to prevent extending the class when the `set` already exists on the child class.
	 *
	 * @see    Arrays::set
	 *
	 * @since 1.0
	 *
	 * @param string|array $index       To set a key nested multiple levels deep pass an array
	 *                                  specifying each key in order as a value.
	 *                                  Example: [ 'lvl1', 'lvl2', 'lvl3' ];
	 * @param mixed        $value       The value.
	 * @param boolean      $is_local    Use the Local or Global template vars
	 *
	 * @return array Full array with the key set to the specified value.
	 */
	final public function set( $index, $value = null, $is_local = true ) {
		if ( true === $is_local ) {
			$this->template_vars = Arrays::set( $this->template_vars, $index, $value );

			return $this->template_vars;
		}

		$this->template_global_vars = Arrays::set( $this->template_global_vars, $index, $value );

		return $this->template_global_vars;
	}

	/**
	 * Merges local and global template vars, and saves it locally.
	 *
	 * @since 1.0
	 *
	 * @param array  $template_vars Local template vars array of data.
	 * @param string $file          Complete path to include the PHP File.
	 * @param array  $name          Template name.
	 *
	 * @return array
	 */
	public function template_merge_vars( $template_vars = [], $file = null, $name = null ) {
		// Allow for simple null usage as well as array() for nothing
		if ( is_null( $template_vars ) ) {
			$template_vars = [];
		}

		// Applies new local template vars on top of Global + Previous local.
		$template_vars = wp_parse_args( (array) $template_vars, $this->get_template_vars() );

		/**
		 * Allows filtering the Local template vars
		 *
		 * @since 1.0
		 *
		 * @param array  $template_vars Local template vars array of data
		 * @param string $file          Complete path to include the PHP File
		 * @param array  $name          Template name
		 * @param self   $template      Current instance of the Template
		 */
		$this->template_vars = apply_filters( 'gk/gravityactions/template_vars', $template_vars, $file, $name, $this );

		return $this->template_vars;
	}

	/**
	 * Fetches the path for locating files in the Plugin Folder
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	protected function get_template_plugin_path() {
		// Craft the plugin Path.
		$path = array_merge( (array) $this->template_base_path, $this->template_folder );

		// Implode to avoid Window Problems.
		$path = implode( DIRECTORY_SEPARATOR, $path );

		/**
		 * Allows filtering of the base path for templates.
		 *
		 * @since 1.0
		 *
		 * @param string $path     Complete path to include the base plugin folder.
		 * @param self   $template Current instance of the Template.
		 */
		return apply_filters( 'gk/gravityactions/template_plugin_path', $path, $this );
	}

	/**
	 * Fetches the Namespace for the public paths, normally folders to look for
	 * in the theme's directory.
	 *
	 * @since 1.0
	 *
	 * @param string $plugin_namespace Overwrite the origin namespace with a given one.
	 *
	 * @return array Namespace where we to look for templates.
	 */
	protected function get_template_public_namespace( $plugin_namespace ) {
		$namespace = [
			'gravityview/bulk-actions',
		];

		if ( ! empty( $plugin_namespace ) ) {
			$namespace[] = $plugin_namespace;
		}

		/**
		 * Allows filtering of the base path for templates
		 *
		 * @since 1.0
		 *
		 * @param array $namespace Which is the namespace we will look for files in the theme
		 * @param self  $template  Current instance of the Template
		 */
		return apply_filters( 'gk/gravityactions/template_public_namespace', $namespace, $this );
	}

	/**
	 * Fetches which base folder we look for templates in the origin plugin.
	 *
	 * @since 1.0
	 *
	 * @return array The base folders we look for templates in the origin plugin.
	 */
	public function get_template_origin_base_folder() {
		/**
		 * Allows filtering of the base path for templates.
		 *
		 * @since 1.0
		 *
		 * @param array $namespace Which is the base folder we will look for files in the plugin.
		 * @param self  $template  Current instance of the Template.
		 */
		return apply_filters( 'gk/gravityactions/template_origin_base_folder', $this->template_origin_base_folder, $this );
	}

	/**
	 * Fetches the path for locating files given a base folder normally theme related.
	 *
	 * @since 1.0
	 *
	 * @param mixed  $base      Base path to look into.
	 * @param string $namespace Adds the plugin namespace to the path returned.
	 *
	 * @return string  The public path for a given base.˙˙
	 */
	protected function get_template_public_path( $base, $namespace ) {

		// Craft the plugin Path
		$path = array_merge( (array) $base, (array) $this->get_template_public_namespace( $namespace ) );

		// Pick up if the folder needs to be added to the public template path.
		$folder = array_diff( $this->template_folder, $this->get_template_origin_base_folder() );

		if ( ! empty( $folder ) ) {
			$path = array_merge( $path, $folder );
		}

		// Implode to avoid Window Problems
		$path = implode( DIRECTORY_SEPARATOR, $path );

		/**
		 * Allows filtering of the base path for templates.
		 *
		 * @since 1.0
		 *
		 * @param string $path     Complete path to include the base public folder.
		 * @param self   $template Current instance of the Template.
		 */
		return apply_filters( 'gk/gravityactions/template_public_path', $path, $this );
	}

	/**
	 * Fetches the folders in which we will look for a given file
	 *
	 * @since 1.0
	 *
	 * @return array<string,array> A list of possible locations for the template file.
	 */
	protected function get_template_path_list() {
		$folders = [];

		$folders['plugin'] = [
			'id'       => 'plugin',
			'priority' => 20,
			'path'     => $this->get_template_plugin_path(),
		];

		/**
		 * Allows filtering of the list of folders in which we will look for the
		 * template given.
		 *
		 * @since 1.0
		 *
		 * @param array $folders  Complete path to include the base public folder
		 * @param self  $template Current instance of the Template
		 */
		$folders = (array) apply_filters( 'gk/gravityactions/template_path_list', $folders, $this );

		uasort( $folders, '\GravityKit\GravityActions\sort_by_priority' );

		return $folders;
	}

	/**
	 * Get the list of theme related folders we will look up for the template.
	 *
	 * @since 1.0
	 *
	 * @param string $namespace Which plugin namespace we are looking for.
	 *
	 * @return array
	 */
	protected function get_template_theme_path_list( $namespace ) {
		$folders = [];

		$folders['child-theme']  = [
			'id'       => 'child-theme',
			'priority' => 10,
			'path'     => $this->get_template_public_path( STYLESHEETPATH, $namespace ),
		];
		$folders['parent-theme'] = [
			'id'       => 'parent-theme',
			'priority' => 15,
			'path'     => $this->get_template_public_path( TEMPLATEPATH, $namespace ),
		];

		/**
		 * Allows filtering of the list of theme folders in which we will look for the template.
		 *
		 * @since 1.0
		 *
		 * @param array  $folders   Complete path to include the base public folder.
		 * @param string $namespace Loads the files from a specified folder from the themes.
		 * @param self   $template  Current instance of the Template.
		 */
		$folders = (array) apply_filters( 'gk/gravityactions/template_theme_path_list', $folders, $namespace, $this );

		uasort( $folders, '\GravityKit\GravityActions\sort_by_priority' );

		return $folders;
	}

	/**
	 * Tries to locate the correct file we want to load based on the Template class
	 * configuration and it's list of folders
	 *
	 * @since 1.0
	 *
	 * @param mixed $name File name we are looking for
	 *
	 * @return string
	 */
	public function get_template_file( $name ) {
		// If name is String make it an Array
		if ( is_string( $name ) ) {
			$name = (array) explode( '/', $name );
		}

		$folders    = $this->get_template_path_list();
		$found_file = false;
		$namespace  = false;

		foreach ( $folders as $folder ) {
			if ( empty( $folder['path'] ) ) {
				continue;
			}

			// Build the File Path
			$file = Paths::merge( $folder['path'], $name );

			// Append the Extension to the file path
			$file .= '.php';

			// Skip non-existent files
			if ( file_exists( $file ) ) {
				$found_file = $file;
				$namespace  = ! empty( $folder['namespace'] ) ? $folder['namespace'] : false;
				break;
			}
		}

		if ( $this->get_template_folder_lookup() ) {
			$theme_folders = $this->get_template_theme_path_list( $namespace );

			foreach ( $theme_folders as $folder ) {
				if ( empty( $folder['path'] ) ) {
					continue;
				}

				// Build the File Path
				$file = implode( DIRECTORY_SEPARATOR, array_merge( (array) $folder['path'], $name ) );

				// Append the Extension to the file path
				$file .= '.php';

				// Skip non-existent files
				if ( file_exists( $file ) ) {
					$found_file = $file;
					break;
				}
			}
		}

		if ( $found_file ) {
			/**
			 * A more Specific Filter that will include the template name
			 *
			 * @since 1.0
			 *
			 * @param string $file     Complete path to include the PHP File
			 * @param array  $name     Template name
			 * @param self   $template Current instance of the Template
			 */
			return apply_filters( 'gk/gravityactions/template_file', $found_file, $name, $this );
		}

		// Couldn't find a template on the Stack
		return false;
	}

	/**
	 * A very simple method to include a Template, allowing filtering and additions using hooks.
	 *
	 * @since 1.0
	 *
	 * @param string|array $name          Which file we are talking about including.
	 *                                    If an array, each item will add a directory separator to get to the single template.
	 * @param array        $template_vars Any template vars data you need to expose to this file
	 * @param boolean      $echo          If we should also print the Template
	 *
	 * @return string|false Either the final content HTML or `false` if no template could be found.
	 */
	public function render( $name, $template_vars = [], $echo = true ) {
		static $file_exists = [];
		static $files = [];
		static $template_names = [];

		/**
		 * Allow users to disable templates before rendering it by returning empty string.
		 *
		 * @since 1.0
		 *
		 * @param string  null     Whether to continue displaying the template or not.
		 * @param array   $name          Template name.
		 * @param array   $template_vars Any template vars data you need to expose to this file.
		 * @param boolean $echo          If we should also print the Template.
		 */
		$done = apply_filters( 'gk/gravityactions/template_done', null, $name, $template_vars, $echo );

		if ( null !== $done ) {
			return false;
		}

		// Key we'll use for in-memory caching of expensive operations.
		$cache_name_key = is_array( $name ) ? implode( '/', $name ) : $name;

		// Cache template name massaging so we don't have to repeat these actions.
		if ( ! isset( $template_names[ $cache_name_key ] ) ) {
			// If name is String make it an Array
			if ( is_string( $name ) ) {
				$name = (array) explode( '/', $name );
			}

			// Clean this Variable
			$name = array_map( 'sanitize_title_with_dashes', $name );

			$template_names[ $cache_name_key ] = $name;
		}

		// Cache file location and existence.
		if (
			! isset( $file_exists[ $cache_name_key ] )
			|| ! isset( $files[ $cache_name_key ] )
		) {
			// Check if the file exists
			$files[ $cache_name_key ] = $file = $this->get_template_file( $name );

			// Check if it's a valid variable
			if ( ! $file ) {
				return $file_exists[ $cache_name_key ] = false;
			}

			// Before we load the file we check if it exists
			if ( ! file_exists( $file ) ) {
				return $file_exists[ $cache_name_key ] = false;
			}

			$file_exists[ $cache_name_key ] = true;
		}

		// If the file doesn't exist, bail.
		if ( ! $file_exists[ $cache_name_key ] ) {
			return false;
		}

		// Use filename stored in cache.
		$file                   = $files[ $cache_name_key ];
		$name                   = $template_names[ $cache_name_key ];
		$origin_folder_appendix = array_diff( $this->template_folder, $this->template_origin_base_folder );
		$namespace              = array_merge( $origin_folder_appendix, $name );

		// Setup the Hook name.
		$hook_name      = implode( '/', $namespace );
		$prev_hook_name = $this->get_template_current_hook_name();

		// Store the current hook name for the purposes of third-party integration.
		$this->set_template_current_hook_name( $hook_name );

		/**
		 * Allow users to filter the HTML before rendering.
		 *
		 * @since 1.0
		 *
		 * @param string $html     The initial HTML
		 * @param string $file     Complete path to include the PHP File
		 * @param array  $name     Template name
		 * @param self   $template Current instance of the Template
		 */
		$pre_html = apply_filters( 'gk/gravityactions/template_pre_html', null, $file, $name, $this );

		/**
		 * Allow users to filter the HTML by the name before rendering.
		 *
		 * E.g.:
		 *    `gk-gravityactions/template_pre_html:form/details/text`
		 *    `gk-gravityactions/template_pre_html:form/entry`
		 *
		 * @since 1.0
		 *
		 * @param string $html     The initial HTML
		 * @param string $file     Complete path to include the PHP File
		 * @param array  $name     Template name
		 * @param self   $template Current instance of the Template
		 */
		$pre_html = apply_filters( "gk/gravityactions/template_pre_html:{$hook_name}", $pre_html, $file, $name, $this );

		if ( null !== $pre_html ) {
			return $pre_html;
		}

		// Merges the local data passed to template to the global scope
		$this->template_merge_vars( $template_vars, $file, $name );

		$before_include_html = $this->actions_before_template( $file, $name, $hook_name );
		$before_include_html = $this->filter_template_before_include_html( $before_include_html, $file, $name, $hook_name );

		$include_html = $this->template_safe_include( $file );
		$include_html = $this->filter_template_include_html( $include_html, $file, $name, $hook_name );

		$after_include_html = $this->actions_after_template( $file, $name, $hook_name );
		$after_include_html = $this->filter_template_after_include_html( $after_include_html, $file, $name, $hook_name );

		// Only fetch the contents after the action
		$html = $before_include_html . $include_html . $after_include_html;

		$html = $this->filter_template_html( $html, $file, $name, $hook_name );

		if ( $echo ) {
			echo $html;
		}

		// Revert the current hook name.
		$this->set_template_current_hook_name( $prev_hook_name );

		return $html;
	}

	/**
	 * Includes a give PHP inside of a safe template vars.
	 *
	 * This method is required to prevent template files messing with local variables used inside of the
	 * `self::template` method. Also shelters the template loading from any possible variables that could
	 * be overwritten by the template vars.
	 *
	 * @since 1.0
	 *
	 * @param string $file Which file will be included with safe template vars.
	 *
	 * @return string Contents of the included file.
	 */
	public function template_safe_include( $file ) {
		ob_start();
		// We use this instance variable to prevent collisions.
		$this->template_current_file_path = $file;
		unset( $file );

		// Only do this if really needed (by default it won't).
		if ( true === $this->template_vars_extract && ! empty( $this->template_vars ) ) {
			// Make any provided variables available in the template variable scope.
			extract( $this->template_vars ); // @phpcs:ignore
		}

		include $this->template_current_file_path;

		// After the include we reset the variable.
		unset( $this->template_current_file_path );

		return ob_get_clean();
	}

	/**
	 * Sets a number of values at the same time.
	 *
	 * @see   Template::set()
	 * @since 1.0
	 *
	 * @param bool  $is_local Whether to set the values as global or local; defaults to local as the `set` method does.
	 *
	 * @param array $values   An associative key/value array of the values to set.
	 */
	public function set_template_vars( array $values = [], $is_local = true ) {
		foreach ( $values as $key => $value ) {
			$this->set( $key, $value, $is_local );
		}
	}

	/**
	 * Returns the Template global template vars.
	 *
	 * @since 1.0
	 *
	 * @return array An associative key/value array of the Template global template vars.
	 */
	public function get_template_global_vars() {
		return $this->template_global_vars;
	}

	/**
	 * Returns the Template local template vars.
	 *
	 * @since 1.0
	 *
	 * @return array An associative key/value array of the Template local template vars.
	 */
	public function get_template_local_vars() {
		return $this->template_vars;
	}

	/**
	 * Returns the Template global and local template vars values.
	 *
	 * Local values will override the template global template vars values.
	 *
	 * @since 1.0
	 *
	 * @return array An associative key/value array of the Template global and local template vars.
	 */
	public function get_template_vars() {
		return array_merge( $this->get_template_global_vars(), $this->get_template_local_vars() );
	}

	/**
	 * Filters the full HTML for the template.
	 *
	 * @since 1.0
	 *
	 * @param string $html      The final HTML.
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param string $hook_name The hook used to create the filter by name.
	 *
	 * @return string HTML after filtering.
	 */
	protected function filter_template_html( $html, $file, $name, $hook_name ) {
		/**
		 * Allow users to filter the final HTML.
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( 'gk/gravityactions/template_html', $html, $file, $name, $this );

		/**
		 * Allow users to filter the final HTML by the name.
		 *
		 * E.g.:
		 *    `gk-gravityactions/template_html:form/details/text`
		 *    `gk-gravityactions/template_html:form/embed`
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( "gk/gravityactions/template_html:{$hook_name}", $html, $file, $name, $this );

		return $html;
	}

	/**
	 * Filters the HTML for the Before include actions.
	 *
	 * @since 1.0
	 *
	 * @param string $html      The final HTML.
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param string $hook_name The hook used to create the filter by name.
	 *
	 * @return string HTML after filtering.
	 */
	protected function filter_template_before_include_html( $html, $file, $name, $hook_name ) {
		/**
		 * Allow users to filter the Before include actions.
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( 'gk/gravityactions/template_before_include_html', $html, $file, $name, $this );

		/**
		 * Allow users to filter the Before include actions by name.
		 *
		 * E.g.:
		 *    `gk-gravityactions/template_before_include_html:form/details/text`
		 *    `gk-gravityactions/template_before_include_html:form/embed`
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( "gk/gravityactions/template_before_include_html:{$hook_name}", $html, $file, $name, $this );

		return $html;
	}

	/**
	 * Filters the HTML for the PHP safe include.
	 *
	 * @since 1.0
	 *
	 * @param string $html      The final HTML.
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param string $hook_name The hook used to create the filter by name.
	 *
	 * @return string HTML after filtering.
	 */
	protected function filter_template_include_html( $html, $file, $name, $hook_name ) {
		/**
		 * Allow users to filter the PHP template include actions.
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( 'gk/gravityactions/template_include_html', $html, $file, $name, $this );

		/**
		 * Allow users to filter the PHP template include actions by name.
		 *
		 * E.g.:
		 *    `gk-gravityactions/template_include_html:form/details/text`
		 *    `gk-gravityactions/template_include_html:form/embed`
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( "gk/gravityactions/template_include_html:{$hook_name}", $html, $file, $name, $this );

		return $html;
	}

	/**
	 * Filters the HTML for the after include actions.
	 *
	 * @since 1.0
	 *
	 * @param string $html      The final HTML.
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param string $hook_name The hook used to create the filter by name.
	 *
	 * @return string HTML after filtering.
	 */
	protected function filter_template_after_include_html( $html, $file, $name, $hook_name ) {
		/**
		 * Allow users to filter the after include actions.
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( 'gk/gravityactions/template_after_include_html', $html, $file, $name, $this );

		/**
		 * Allow users to filter the after include actions by name.
		 *
		 * E.g.:
		 *    `gk-gravityactions/template_after_include_html:form/details/text`
		 *    `gk-gravityactions/template_after_include_html:form/embed`
		 *
		 * @since 1.0
		 *
		 * @param string $html     The final HTML.
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		$html = apply_filters( "gk/gravityactions/template_after_include_html:{$hook_name}", $html, $file, $name, $this );

		return $html;
	}

	/**
	 * Fires of actions before including the template.
	 *
	 * @since 1.0
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param string $hook_name The hook used to create the filter by name.
	 *
	 * @return string HTML printed by the before actions.
	 */
	protected function actions_before_template( $file, $name, $hook_name ) {
		ob_start();

		/**
		 * Fires an Action before including the template file
		 *
		 * @since 1.0
		 *
		 * @param string $file     Complete path to include the PHP File
		 * @param array  $name     Template name
		 * @param self   $template Current instance of the Template
		 */
		do_action( 'gk/gravityactions/template_before_include', $file, $name, $this );

		/**
		 * Fires an Action for a given template name before including the template file,
		 *
		 * E.g.:
		 *    `gk-gravityactions/template_before_include:form/details/text`
		 *    `gk-gravityactions/template_before_include:form/embed`
		 *
		 * @since 1.0
		 *
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		do_action( "gk/gravityactions/template_before_include:{$hook_name}", $file, $name, $this );

		return ob_get_clean();
	}

	/**
	 * Fires of actions after including the template.
	 *
	 * @since 1.0
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param string $hook_name The hook used to create the filter by name.
	 *
	 * @return string HTML printed by the after actions.
	 */
	protected function actions_after_template( $file, $name, $hook_name ) {
		ob_start();
		/**
		 * Fires an Action after including the template file.
		 *
		 * @since 1.0
		 *
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		do_action( 'gk/gravityactions/template_after_include', $file, $name, $this );

		/**
		 * Fires an Action for a given template name after including the template file.
		 *
		 * E.g.:
		 *    `gk-gravityactions/template_after_include:form/details/text`
		 *    `gk-gravityactions/template_after_include:form/embed`
		 *
		 * @since 1.0
		 *
		 * @param string $file     Complete path to include the PHP File.
		 * @param array  $name     Template name.
		 * @param self   $template Current instance of the Template.
		 */
		do_action( "gk/gravityactions/template_after_include:{$hook_name}", $file, $name, $this );

		return ob_get_clean();
	}
}
