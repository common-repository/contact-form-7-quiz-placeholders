<?php
/*
Plugin Name: Contact Form 7 Quiz Placeholders
Plugin URI: http://thebyob.com/contact-form-7-quiz-placeholders
Description: Automatically converts Contact Form 7 quiz labels into HTML5 placeholders (like the "Watermark" feature in CF7, which is missing for quizzes). Includes Modernizr to add placeholder support to Internet Explorer.
Version: 1.1
Author: Josh Davis
Author URI: http://josh.dvvvvvvvv.com/
*/

/*  Copyright 2012  Josh Davis

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function cf7qp_load_scripts() {
	wp_enqueue_script('jquery');
	wp_register_script('modernizr', 'http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js', array('jquery'));
	wp_enqueue_script('modernizr');
}    
add_action('wp_enqueue_scripts', 'cf7qp_load_scripts');

function register_cf7qp_settings() {
	$setting_vars = array(
		'cf7qp_class',
		);
	foreach ( $setting_vars as $setting_var ){
		register_setting( 'cf7qp_mystery', $setting_var );
	}
}
add_action( 'admin_init', 'register_cf7qp_settings' );

function cf7qp_menu() {
	add_options_page( 'Contact Form 7 Quiz Placeholders Settings', 'Contact Form 7 Quiz Placeholders', 'manage_options', 'cf7qp_uid', 'cf7qp_options' );
}

function cf7qp_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap"><h2>Contact Form 7 Quiz Placeholders Settings</h2><form method="post" action="options.php">';
	settings_fields('cf7qp_mystery');
?>

<style>.wrap form td span{color:#888;} .wrap legend{font-size:13px; font-weight:bold; margin-left:-5px;} .wrap fieldset{margin:10px 0px; padding:15px; padding-top:0px; border:1px solid #ccc;}</style>
<fieldset>
	<legend>Convert quiz labels to placeholders:</legend>
	<table class="form-table">
		<tr><td><input type="checkbox" name="cf7qp_class" value="1" <?php checked( '1', get_option( 'cf7qp_class' ) ); ?> /> Only on quizzes with the class <b><i>.cf7_placeholder</b></i> <span>- By default, leaving this unchecked will apply the effect to all Contact Form 7 quizzes</span></td></tr>
	</table>
</fieldset>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

<?php
	echo '</form></div>';
}
add_action( 'admin_menu', 'cf7qp_menu' );

function cf7qp_script() { ?>

<script>
// Start allowance of jQuery to $ shortcut
jQuery(document).ready(function($){

	// Contact Form Quiz Placeholder
	$('<?php if (get_option('cf7qp_class')) echo '.cf7_placeholder'; else echo '.wpcf7-quiz' ?>').each(function(){
		$(this).attr('placeholder', $(this).parent('.wpcf7-form-control-wrap').children('.wpcf7-quiz-label').text());
		$(this).parent('.wpcf7-form-control-wrap').html($(this).parent('.wpcf7-form-control-wrap').children('input'));
	});

	// Use modernizr to add placeholders for IE
	if(!Modernizr.input.placeholder){$("input,textarea").each(function(){if($(this).val()=="" && $(this).attr("placeholder")!=""){$(this).val($(this).attr("placeholder"));$(this).focus(function(){if($(this).val()==$(this).attr("placeholder")) $(this).val("");});$(this).blur(function(){if($(this).val()=="") $(this).val($(this).attr("placeholder"));});}});}

// Ends allowance of jQuery to $ shortcut
});
</script>

<?php

}

add_action('wp_head', 'cf7qp_script');

?>
