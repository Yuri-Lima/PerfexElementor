<?php
/**
 * Plugin Name: Perfex API[Elementor Version]
 * Plugin URI: http://www.
 * Description: Integre o seu Elementor com o Mautic DE GRAÇA.
 * Version: 1.0
 * Author: Gabriel Carvalho Gama
 * Author URI: http://www.gabcarvalhogama.com.br
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