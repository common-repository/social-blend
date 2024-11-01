<?php

// $screen = get_current_screen(); 
// if ( $screen->id != 'settings_page_plugin_settings' ) { return; }

class SocialBlendSettings {

    private $options;

    public function __construct() {
    	// hook into the admin menu
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        // render settings page
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }
    
    public function admin_menu() {
        // add the menu item and page
        add_menu_page(
			'Social Blend',
            'Social Blend',
            'manage_options',
            'socialblend_settings',
            array( $this, 'settings_page_content' ),
    	    'dashicons-admin-plugins',
    	    100
        );
    }

    public function setup_sections() {

        add_settings_section( 
            'socialblend_overview', 
            'Getting Started', 
            array( $this, 'overview_section_callback' ),
            'socialblend_fields'
        );

        add_settings_section( 
            'socialblend_usage', 
            'Usage', 
            array( $this, 'usage_section_callback' ),
            'socialblend_fields'
        );
        
        add_settings_section( 'socialblend_settings', 
                              'Settings', 
                              array( $this, 'section_callback' ),
                              'socialblend_fields' );

        add_settings_section( 
        'socialblend_support', 
        'Support', 
        array( $this, 'support_section_callback' ),
        'socialblend_fields'
    );
    }

    public function section_callback( $arguments ) {
        // $section = $arguments['id'];
    }

    public function setup_fields() {

        $fields = array(
            array(
        		'uid' => 'socialblend_fields_poweredby',
        		'label' => 'Footer',
        		'section' => 'socialblend_settings',
        		'type' => 'checkbox',
        		'options' => array(
        			'true' => 'Show powered by socialblend.com'
                ),
                'default' => array()
            )    
        );
        foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'socialblend_fields', $field['section'], $field );
            register_setting( 'socialblend_fields', $field['uid'] );
		}
     }

    /**
     * Register and add settings
     */
    public function admin_init()
    {
        self::setup_sections();
        self::setup_fields();
    }

    public function settings_page_content() { 
        // get the options for plugin
        $this->options = get_option( 'socialblend_fields' );
        ?>
        <style>
            .form-table th, .form-table td { padding: 0 }
            .sb-section { margin-bottom: 25px; }
		</style>
    	<div class="wrap">
    		<h2>Social Blend</h2>
    		<form method="POST" action="options.php">
                <?php
                    settings_fields( 'socialblend_fields' );
                    do_settings_sections( 'socialblend_fields' );
                    submit_button();
                ?>
    		</form>
    	</div> <?php
    }

    public function overview_section_callback() {
        $content = '<div class="sb-section">';
        $content .= '<ol>';
        $content .= '<li>Signup at <a href="https://socialblend.com" target="_blank">socialblend.com</a></li>';
        $content .= '<li>Create a feed</li>';
        $content .= '<li>Add sources to the feed</li>';
        $content .= '<li>Adjust settings, display & branding to suit</li>';
        $content .= '<li>Share feed to copy the embed shortcode or PHP code</li>';
        $content .= '</ol>';
        $content .= '</div>';
        echo $content;
    }

    public function usage_section_callback() {
        $content = '<div class="sb-section">';
        $content .= '<ul>';
        $content .= '<li><strong>Using shortcode</strong>';
        $content .= '<p>Edit a post/page and paste the shortcode.</li>';
        $content .= '<li><strong>Using PHP</strong>';
        $content .= '<p>A feed can also be placed into your theme template by inserting the PHP code</p></li>';
        $content .= '</ul>';
        $content .= '</div>';
        echo $content;
    }

    public function support_section_callback() {
        $content = '<div class="sb-section">';
        $content .= '<p>Please reach out to us at <a href="https://socialblend.com" target="_blank">socialblend.com</a>.';
        $content .= '</p>';
        $content .= '</div>';
        echo $content;
    }

    public function field_callback( $arguments ) {

		$value = get_option( $arguments['uid'] );
        if( ! $value ) {
            $value = $arguments['default'];
        }

        switch( $arguments['type'] ){
            case 'text':
            case 'password':
            case 'number':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" style="width:450px" autocomplete="no" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'textarea':
                printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
                break;
            case 'select':
			case 'multiselect':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, in_array( $key, $value ) ? "selected='selected'" : "", $label );
                    }
                    if( $arguments['type'] === 'multiselect' ){
                        $attributes = ' multiple="multiple" ';
                    }
                    printf( '<select name="%1$s[]" id="%1$s" %2$s style="width:250px">%3$s</select>', $arguments['uid'], $attributes, $options_markup );
                }
                break;
            case 'radio':
            case 'checkbox':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    $iterator = 0;
                    foreach( $arguments['options'] as $key => $label ){
                        $iterator++;
                        $options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', 
                                                    $arguments['uid'], 
                                                    $arguments['type'], 
                                                    $key, 
                                                    in_array($key, $value) ? "checked" : "", 
                                                    $label, 
                                                    $iterator );
                    }
                    printf( '<fieldset>%s</fieldset>', $options_markup );
                }
                break;
        }

	}

}