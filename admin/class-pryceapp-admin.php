<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.pryce.app
 * @since      1.0.0
 *
 * @package    Pryceapp
 * @subpackage Pryceapp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pryceapp
 * @subpackage Pryceapp/admin
 * @author     Pryce <contato@pryce.app>
 */
class Pryceapp_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Client class to use Pryce REST API
	 * 
	 * @var Client
	 */
	private $client;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $client)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->client = $client;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pryceapp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pryceapp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pryceapp-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pryceapp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pryceapp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pryceapp-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * This function adds a warning to remind the user that the plugin
	 * is enabled.
	 * 
	 * The reminder is necessary because the price on the product admin page
	 * will be not equal to the price that is showed on his store.
	 */
	public function add_warning_of_activated_plugin()
	{
		global $post_type;
		if ($post_type == 'product') {
			$title = __('💬 Seu plugin Pryce.app esta ativo e gerando descontos para seus produtos, não esqueça!');
			$div = '<div class="notice notice-info is-dismissible"><p>%s</p></div>';
			echo sprintf($div, $title);
		}
	}

	/**
	 * This function adds a input on the settings page.
	 * 
	 * The input is used to place the user api TOKEN
	 */
	public function add_token_configuration_start_settings($settings)
	{
		$updated_settings = [];

		foreach ($settings as $section) {
			if (
				isset($section['id']) && 'general_options' == $section['id'] &&
				isset($section['type']) && 'sectionend' == $section['type']
			) {
				$updated_settings[] = [
					'name'     => __('Token para pryce.app', 'wc_seq_order_numbers'),
					'desc_tip' => __('Token de requisição encontrado no painel de controle (pagina de perfil)', 'wc_seq_order_numbers'),
					'id'       => $this->plugin_name,
					'type'     => 'text',
					'css'      => 'min-width:300px;',
					'std'      => '',  // WC < 2.0
					'default'  => '',  // WC >= 2.0
					'desc'     => __('Exemplo: b4e58140b61cf086c82153f6c371668684f6ca71'),
				];
			}

			$updated_settings[] = $section;
		}

		return $updated_settings;
	}

	/**
	 * This function adds a submenu under "Products" menu
	 * on admin panel.
	 */
	public function add_submenu_page()
	{
		add_submenu_page(
			'edit.php?post_type=product',
			__('Discounts'),
			__('Discounts'),
			'manage_woocommerce',
			$this->plugin_name,
			array($this, 'display_options_page')
		);
	}

	/**
	 * This function display all available discounts on a table.
	 */
	public function display_options_page()
	{
		$discounts = $this->client->discounts()->getAll();

		require_once 'partials/pryceapp-admin-display.php';
	}

	/**
	 * This function display a page to edit a discount
	 */
	public function load_discount_page()
	{
		add_submenu_page(
			null,
			__('Edit discount', $this->plugin_name),
			__('Edit discount', $this->plugin_name),
			'manage_woocommerce',
			$this->plugin_name . '_edit_discount',
			array($this, 'display_option_edit_discount_page')
		);
	}

	public function display_option_edit_discount_page()
	{
		$code = $_GET['code'];  // This is not right !!!

		$discount = $this->client->discounts()->getByCode($code);

		require_once 'partials/pryceapp-admin-edit.php';
	}
}
