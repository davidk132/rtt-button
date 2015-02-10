<?php 
/* First check if the button is enabled on the admin menu */
if( ! get_option( 'rtt_toggle_on') ) {
  return; // if it's off, exit with no further action
}

/* add anchor to the top of the page */
function dkd_add_anchor( $content ) {
  if( ! is_admin() ) {
  $dkd_rtt_link = '<a href="#" class="rtt_button">' . esc_attr( get_option( 'rtt_button_text' ) ) . '</a>';
  $content = $content . $dkd_rtt_link;
  return $content;
  } else {
    return $content;
  }
}
add_filter( "the_content", "dkd_add_anchor" );

/* add metabox into posts and pages for toggling rtt button on each page */