<?php

/**
 * Provide a admin settings area for the plugin
 *
 * This file is used to create the settings form of the plugin.
 *
 * @link       https://www.contractorcommerce.com/
 * @since      1.0.0
 *
 * @package    Contractor_Commerce
 * @subpackage Contractor_Commerce/admin/partials
 */

add_action( 'admin_menu', 'concom_add_admin_menu' );
add_action( 'admin_init', 'concom_settings_init' );


function concom_add_admin_menu(  ) { 

	add_options_page( 'Contractor Commerce', 'Contractor Commerce', 'manage_options', 'contractor_commerce', 'concom_options_page' );

}


function concom_settings_init(  ) { 

	register_setting( 'pluginPage', 'concom_settings' );

	add_settings_section(
		'concom_pluginPage_section', 
		null, 
		'concom_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'concom_text_field_0', 
		__( 'Key', 'concom' ), 
		'concom_text_field_0_render', 
		'pluginPage', 
		'concom_pluginPage_section' 
	);

	add_settings_field( 
		'concom_select_field_1', 
		__( 'Page', 'concom' ), 
		'concom_select_field_1_render', 
		'pluginPage', 
		'concom_pluginPage_section' 
	);

	add_settings_field(  
		'Checkbox Element',  
		__( 'Enable SEO Performance', 'concom' ), 
		'concom_seo_checkbox_render',  
		'pluginPage', 
		'concom_pluginPage_section' 
	);

    add_settings_field(
        'not_place_in_head',
        __("Limit script tag to single page (not placed in &lt;head&gt;)", 'concom'),
        'not_place_in_head_render',
        'pluginPage',
        'concom_pluginPage_section',
    );
}

function concom_text_field_0_render(  ) { 

	$options = get_option( 'concom_settings' );
	?>
	<input type='text' name='concom_settings[concom_text_field_0]' value='<?php echo $options['concom_text_field_0']; ?>' class='regular-text'>
	<?php

}


function concom_select_field_1_render(  ) { 

	$options = get_option( 'concom_settings' );
	
	wp_dropdown_pages(array(
		'selected'         => $options['concom_select_field_1'],
		'name'             => 'concom_settings[concom_select_field_1]',
		'show_option_none' => '— Select —',
		'sort_column'      => 'menu_order, post_title',
	));
	?>
	<a href="post-new.php?post_type=page" class="button">Add New Page</a>
	<?php

}

function concom_seo_checkbox_render() {

	$options = get_option( 'concom_settings' );
	
	$checked = isset($options['concom_seo_checkbox']) && $options['concom_seo_checkbox'] ? true : false;

	echo '<input type="checkbox" id="concom_seo_checkbox" name="concom_settings[concom_seo_checkbox]" value="1"' . checked( 1, $checked, false ) . '/>';
}

function not_place_in_head_render() {
    $options = get_option( 'concom_settings' );

    $checked = isset($options['not_place_in_head']) && $options['not_place_in_head'];

    echo '<input type="checkbox" id="not_place_in_head" name="concom_settings[not_place_in_head]" value="1"' . checked( 1, $checked, false ) . '/>';
}

function concom_settings_section_callback(  ) { 

	?>
	<p>Embed Contractor Commerce in your site by adding your Contractor Commerce key and selecting the page that will serve it.</p>
	<?php

}


function concom_options_page(  ) { 

	?>
	<div class="concom-head"></div>
	<div class="wrap">
		<form action='options.php' method='post'>

			<h1>Contractor Commerce Integration</h1>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			flush_rewrite_rules();
			submit_button();
			?>

		</form>
		<p><a href="https://app.contractorcommerce.com/" target="_blank">Additional Contractor Commerce settings</a></p>
	</div>
	<?php

}
