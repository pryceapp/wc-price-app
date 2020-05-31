<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.pryce.app
 * @since      1.0.0
 *
 * @package    Pryceapp
 * @subpackage Pryceapp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pryceapp
 * @subpackage Pryceapp/includes
 * @author     Pryce <contato@pryce.app>
 */
class Pryceapp
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pryceapp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('PRYCEAPP_VERSION')) {
			$this->version = PRYCEAPP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pryceapp';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pryceapp_Loader. Orchestrates the hooks of the plugin.
	 * - Pryceapp_i18n. Defines internationalization functionality.
	 * - Pryceapp_Admin. Defines all hooks for the admin area.
	 * - Pryceapp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pryceapp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pryceapp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-pryceapp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-pryceapp-public.php';

		/**
		 * This class responsible for handle the cookies.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pryceapp-cookie.php';

		/**
		 * This class responsible to call the pryce REST API.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'client/Pryce.php';

		$this->loader = new Pryceapp_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pryceapp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Pryceapp_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Pryceapp_Admin(
			$this->get_plugin_name(),
			$this->get_version(),
			new Pryce(get_option($this->plugin_name, 1))
		);

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		/**
		 * Adds warning on "products" related page to warning user that the plugin is enabled
		 */
		$this->loader->add_action('admin_notices', $plugin_admin, 'add_warning_of_activated_plugin');

		/**
		 * Adds token input on woocommerce settings
		 */
		$this->loader->add_action(
			'woocommerce_general_settings',
			$plugin_admin,
			'add_token_configuration_start_settings'
		);

		/**
		 * Adds menu option under "product" section on admin panel
		 */
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_submenu_page');

		/**
		 * Adds page to see discount on a form
		 */
		$this->loader->add_action('admin_menu', $plugin_admin, 'load_discount_page');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Pryceapp_Public(
			$this->get_plugin_name(),
			$this->get_version(),
			new Pryce(get_option($this->plugin_name, 1))
		);

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('init', $plugin_public, 'set_affiliates_cookie');
		$this->loader->add_action(
			'woocommerce_product_get_price',
			$plugin_public,
			'get_quotation_price',
			10,
			2
		);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pryceapp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
