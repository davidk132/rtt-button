<?php 
/* First check if the button is enabled on the admin menu */
if( ! get_option( 'rtt_toggle_on') ) {
  return; // if it's off, exit with no further action
}

/* add anchor to the top of the page, if RTT is enabled on both the admin and post pages */
function dkd_add_anchor( $content ) {
  global $post;
  
  /* Exit if not a post, page, or attachment */
  if ( ! is_singular() ) {
    return $content;
  }
  
  if( ! is_admin() ) {
    if ( get_post_meta( $post->ID, 'return_to_top', true) == 'off' ) {
      return $content;
    } else {
      $dkd_rtt_link = '<a href="#" class="rtt_button"><i class="fa fa-chevron-circle-up fa-2x"></i>  ' . esc_attr( get_option( 'rtt_button_text' ) ) . '</a>';
      $content = $content . $dkd_rtt_link;
      return $content;
    }
  } else {
    return $content;
  }
}
add_filter( "the_content", "dkd_add_anchor" );

/* add metabox into posts and pages for toggling rtt button on each page */
function dkd_add_metabox() {
  $screens = array( 'post', 'page' );
  foreach( $screens as $screen ) {
    add_meta_box( 'rtt',
                'Return to Top Button',
                'rtt_metabox_text',
                $screen);
  }
}
add_action( 'add_meta_boxes', 'dkd_add_metabox' );

/* Place text and forms for metabox */
function rtt_metabox_text( $post ) {
  echo( "<p>Include Return to Top Button on this page or post?  ");
  wp_nonce_field( 'rtt_metabox', 'rtt_metabox_the_nonce' );
  $is_rtt_on = get_post_meta( $post->ID, 'return_to_top', true ); ?>
  <?php echo( '</p><p>The current value is: ' . $is_rtt_on . '.</p>' );
if( $is_rtt_on == 'on') {
  $the_on_button = 'checked';
  $the_off_button = '';
} else {
  $the_on_button = '';
  $the_off_button = 'checked'; // This section needs serious refactoring
} ?>
<input type="radio" name="rtt_metabox_radio" value="on" <?php echo( $the_on_button ); ?>  />on<br/>
<input type="radio" name="rtt_metabox_radio" value="off" <?php echo( $the_off_button ); ?> />off<br/>
  <?php
}

/* validate and save data to the wp database */
function dkd_save_rtt_to_meta( $post_id ) {
  /* Thanks, Wordpress Codex! http://codex.wordpress.org/Function_Reference/add_meta_box
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['rtt_metabox_the_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['rtt_metabox_the_nonce'], 'rtt_metabox' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
  if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
  
  /* OK, it's safe for us to save the data now. */	
	// Make sure that it is set in the form.
	if ( ! isset( $_POST['rtt_metabox_radio'] ) ) {
		return;
	}
  // Update the meta field in the database.
	update_post_meta( $post_id, 'return_to_top', $_POST['rtt_metabox_radio'] );
}
add_action( 'save_post', 'dkd_save_rtt_to_meta' );