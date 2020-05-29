<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.pryce.app
 * @since      1.0.0
 *
 * @package    Pryceapp
 * @subpackage Pryceapp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pryceapp
 * @subpackage Pryceapp/public
 * @author     Pryce <contato@pryce.app>
 */
class Pryceapp_Public
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
	 * The Pryce client class
	 * 
	 * @var Client
	 */
	private $client;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $client)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->client = $client;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pryceapp-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pryceapp-public.js', array('jquery'), $this->version, false);
	}

	public function set_affiliates_cookie()
	{
		$key = Pryceapp_Cookie::DEFAULT_AFFILIATE_IDENTIFIER;
		if (!isset($_REQUEST[$key]) || empty($_REQUEST[$key])) {
			return '';
		}

		$utmSource = strip_tags((string) wp_unslash($_REQUEST[$key]));
		if (!empty($utmSource)) {
			$cookieHandler = Pryceapp_Cookie::getInstance();
			$cookieHandler->set($utmSource, strtotime('+20 minutes'));
		}
	}

	public function get_quotation_price($price, $product)
	{
		/**
		 * Some products do not show prices on the page (related products section)
		 * in this case we need to return the empty string injected on this function.
		 */
		if (empty($price)) {
			return $price;
		}

		$affiliate = Pryceapp_Cookie::getInstance()->get();

		$productCategories = get_terms([
			'taxonomy' 	=> 'product_cat',
			'include'	=> $product->get_category_ids()
		]);

		$data = [
			'sku' 			=> $product->get_sku(),
			'price' 		=> $price,
			'categories' 	=> wp_list_pluck($productCategories, 'name'),
			'affiliate' 	=> !$affiliate ? "" : $affiliate
		];

		$sellingPrice = $price;
		try {
			$quotation = $this->client->quotation()->get($data);
			$sellingPrice = $quotation[0]->selling_price;
		} catch (Exception $ex) {
			$sellingPrice = $price;
		}

		return $sellingPrice;
	}
}
