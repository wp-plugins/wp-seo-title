<?php
/*
Plugin Name: WP SEO Title
Plugin URI: http://wpseotitle.com/
Description: Used by millions, WP SEO Title is the best way in the world to <strong>keyword research</strong>. It keeps your site High level. <a href="http://wpseotitle.com/get/?return=true">Sign up for an WP SEO Title API key</a>, and Go to your <a href="admin.php?page=wpst_options">WP SEO Title configuration</a> page, and save your API key.
Version: 1.0.2
Author: wpseotitle
Author URI: http://wpseotitle.com/
*/

if ('wp-seo-title.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

class wp_seo_title_suggestions
{
	function __construct()
	{
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'wp_ajax_wpst_title_suggestions', array( &$this, 'ajax_title_suggestions' ) );
		
	}
	
	static function activation()
	{
		add_option( 'wpst_country_selected', 'us' );
		$countries = array('br' => 'Brazil','de' => 'Germany','es' => 'Spain','fr' => 'France','it' => 'Italy', 'ru' => 'Russian','uk' => 'United Kingdom','us' => 'United States');
		add_option( 'wpst_countries', $countries );
		add_option( 'wpst_value', 'Volume');
		add_option( 'wpst_order', 'DESC');
		add_option( 'wpst_api_key', '595f44fec1e92a71d3e9e77456ba80d2'); /* BETA */
	}
	
	static function deactivation()
	{
		delete_option( 'wpst_country_selected' );
		delete_option( 'wpst_countries' );
		delete_option( 'wpst_value' );
		delete_option( 'wpst_order' );
		delete_option( 'wpst_api_key' );
	}
	
	function admin_init()
	{
		if ( is_admin() && ( strpos( $_SERVER['SCRIPT_NAME'], 'post-new.php' ) || strpos( $_SERVER['SCRIPT_NAME'], 'post.php' ) ) !== false ) :
			wp_enqueue_script( 'wp-seo-title_suggestions', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/js/wp-seo-title.js' );
			
			wp_enqueue_script( 'wp-seo-title-gcomplete', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/js/jquery.gcomplete.0.1.2.js' );

			$wpst_country_selected = get_option( 'wpst_country_selected' );
			$countries_list = '<input type="hidden" id="wpst_country_selected" name="wpst_country_selected" value="'.$wpst_country_selected.'" /><select id="wpst_country_selected_ul" name="wpst_country_selected_ul" style="min-width:200px;">';
			$countries = get_option( 'wpst_countries' );

			foreach ($countries as $code => $name)
			{
				$countries_list .= '<option value="' . $code . '" data-imagesrc="'._WPST_PATH_.'/images/flags/' . $code . '.png" data-description="' . $name . '"';
				if ( $wpst_country_selected == $code ) $countries_list .= ' selected="selected"';
				$countries_list .= '>' . strtoupper($code) . '</option>';
			}

			wp_localize_script( 'wp-seo-title_suggestions', 'objectL10nWPST', array(
				'countries_list'  => $countries_list,
				'getsuggestions' => __('Get Suggestions', _PLUGIN_NAME_),
				'suggestionstitles' => __('Suggestions for titles', _PLUGIN_NAME_),
				'placekeyword' => __('Put Keyword', _PLUGIN_NAME_),
				'suggestionsfor' => __('Suggestions for', _PLUGIN_NAME_),
				'enterkeyword' => __('Please enter a keyword in title or keyword input and try again.', _PLUGIN_NAME_),
			) );
			wp_enqueue_script( 'jquery_ddslick', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/js/jquery.ddslick.min.js');
			wp_enqueue_style( 'wp-seo-title_suggestions', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/css/wp-seo-title.css' );
			wp_enqueue_style( 'wp-seo-title-gcomplete', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/css/jquery.gcomplete.default-themes.css' );
		endif;
		if ( is_admin() && ( isset( $_GET['page'] ) && $_GET['page'] == 'wpst_options' ) ) :
			wp_enqueue_script( 'wp-seo-title_suggestions', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/js/jquery.ddslick.min.js');
			wp_enqueue_style( 'wp-seo-title_suggestions', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/css/wp-seo-title.css' );
		endif;
	}
	
	function admin_menu()
	{
		add_menu_page( __('WP SEO Title Options', _PLUGIN_NAME_), 'WP SEO Title', 'manage_options', 'wpst_options', array( &$this, 'admin_options' ), get_option('siteurl') . '/wp-content/plugins/' . basename( dirname( __FILE__ ) ) . '/images/icon16.png' );
	}

	function admin_options()
	{
		include 'inc/wpst-admin.php';
	}

	function ajax_title_suggestions()
	{
		require_once('inc/functions.php');
		require_once('inc/keywords-list.php');
		
		global $blog_id;
		$wpst_api = new wpst_api();

		if( ! empty($_POST['wpst_sortfield']))
			$wpst_sortfield = sanitize_text_field($_POST['wpst_sortfield']);
		else
			$wpst_sortfield = get_option( 'wpst_sortfield' );

		if( ! empty($_POST['wpst_sorttype']))
			$wpst_sorttype = sanitize_text_field($_POST['wpst_sorttype']);
		else
			$wpst_sorttype = get_option( 'wpst_sorttype' );

		$wpst_api_key = get_option( 'wpst_api_key' );
		$url_blog = get_option('siteurl');

		$request_json = $wpst_api->request( sanitize_text_field($_POST['wpst_keyword']), $wpst_api_key, sanitize_text_field($_POST['wpst_country_selected']), $wpst_sortfield, $wpst_sorttype, $url_blog);
		
		$request_array = json_decode($request_json);

		/* credits remaining */
		$credits_remaining = $request_array->credits;

		/* show suggestions */
		$keywords_list = new keywords_list();
		echo $keywords_list->show_suggestions($request_array->suggestions, $wpst_sortfield, $wpst_sorttype, $credits_remaining);

		/* not return 0 */
		die();
	}
}
/* comentario */
define('_PLUGIN_NAME_', 'wp-seo-title'); //THIS LINE WILL BE CHANGED WITH THE USER SETTINGS
define( '_WPST_PATH_', plugins_url( '', __FILE__ ) );
load_plugin_textdomain( _PLUGIN_NAME_, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );	

register_activation_hook( __FILE__, array( 'wp_seo_title_suggestions', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'wp_seo_title_suggestions', 'deactivation' ) );

$auto_title_suggestions = new wp_seo_title_suggestions();

?>