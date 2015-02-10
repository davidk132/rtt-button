<?php // This page needs refactoring!

/* Set up admin menu page and subpages */
function dkd_register_admin_pages() {
  // build the menu
  add_options_page( 'Return to Top', 'Return to Top', 'manage_options', 'return-to-top', 'dkd_plugin_rtt' );
  // register settings so Wordpress can find which settings we're adjusting
  add_action( 'admin_init', 'dkd_register_settings' );
}
add_action( 'admin_menu', 'dkd_register_admin_pages');

function dkd_register_settings() {
  register_setting( 'dkd_settings_group', 'rtt_toggle_on' );
  register_setting( 'dkd_settings_group', 'rtt_button_text' );
  register_setting( 'dkd_settings_group', 'rtt_button_location' );
}

function dkd_plugin_display_admin() {
  echo('<h2>This is the main DKD Plugin Admin Page</h2>');
}

function dkd_plugin_rtt() {
  if( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'You do not have sufficient administrator privileges for this page.');
  }
  echo('<h2>This is the subpage for adjusting the return to top button</h2>');
  echo('<p>Current button text: ');
  if( get_option( 'rtt_button_text' ) ) {
    echo( esc_attr( get_option( 'rtt_button_text' ) ) . '</p>' ); 
  } else {
    echo('There is no button text stored</p>');
  }
  if( get_option( 'rtt_button_location' ) ) {
    echo( '<p>Current button location: ' . esc_attr( get_option( 'rtt_button_location' ) ) . "</p>" );
  } else {
    echo( '<p>There is no location stored</p>' );
  }
  // define form variables
  $current_button_status = get_option( 'rtt_toggle_on' ) ? true : false; // get checbox status stored in db
  $current_button_text = get_option( 'rtt_button_text' ) ? esc_attr( get_option( 'rtt_button_text' ) ) : "Return to Top"; // get stored button text, or add "return to top" if database field is empty
  $location_selection = array( 'bottom-right',
                             'top',
                             'top-right',
                             'center-left',
                             'center',
                             'center-right',
                             'bottom-left',
                             'bottom',
                             'top-left' );
  if ( ! get_option( 'rtt_button_location') ) {
    update_option( 'rtt_button_location', 'bottom-right' );
  }
  $current_location_selection = esc_attr( get_option( 'rtt_button_location' ) ); // get stored location, or add bottom-right if field is empty
  ?>
  <form method="post" id="rtt-form" action="options.php">
    <?php settings_fields( 'dkd_settings_group' ); ?>
    <?php do_settings_sections( 'dkd_settings_group' ); ?>
    <label for="rtt_on">Return to Top button? </label>
    <input type="checkbox" id="rtt_on" name="rtt_toggle_on" <?php echo ( $current_button_status ? "checked" : "" ); ?> /><br/>
    <label for="button_text">Button text (max 20 chars): </label>
    <input type="text" id="button_text" name="rtt_button_text" value="<?php echo( esc_attr( $current_button_text ) ); ?>" /><br/>
    <!-- next, add the select for page location of button -->
    <label for="location">Choose where button will appear on the screen (Current location is <?php echo( $current_location_selection ); ?>): </label>
    <select id="location" name="rtt_button_location">
      <?php foreach ( $location_selection as $location ) {
    echo( '<option value="' . $location . '">' . $location . '</option>' );
  } ?>
    </select><br/>
    <?php submit_button(); ?>
  </form>
<?php
} 
