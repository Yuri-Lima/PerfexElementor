<?php
/**
 * Plugin Name: Perfex API[Elementor Version]
 * Plugin URI: https://github.com/Yuri-Lima/PerfexElementor
 * Description: Integre o seu Elementor com o Perfex.
 * Version: 0.0.1
 * Author: Yuri Matos de Lima
 * Author URI: https://resume.yurilima.com.br
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'elementor_pro/init', function() {
	// Here its safe to include our action class file
	require "Perfex_API_Form_Action.php";


	// Instantiate the action class
	$perfex_api = new Perfex_API_Form_Action();

	// Register the action with form widget
	\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $perfex_api->get_name(), $perfex_api );
});