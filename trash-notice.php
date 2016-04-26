<?php
/**
 * Plugin Name: Trash Notice
 * Description: Warns user about WP Trash being cleared in 30 days. Helpful for developers with lots of clients and sites with lots of editors.
 * Version: 1.0
 * Author: Saad Ibrahim
 * Author URI: http://saadibrahim.com
 * Text Domain: msi-trash-notice
 */

function msi_get_number_days() {
	if (defined('EMPTY_TRASH_DAYS')) {
    	return EMPTY_TRASH_DAYS;
	} else {
		return "30";
	}
}


// Here we update the message that shows up when user trashes a post or page.
function msi_bulk_post_updated_messages_filter( $bulk_messages, $bulk_counts ) {

	$number_days = msi_get_number_days();

    $bulk_messages['post'] = array(
		'trashed'   => _n( '%s post moved to the Trash. Posts in trash are automatically deleted after '.$number_days.' days.', '%s posts moved to the Trash. Posts in trash are automatically deleted after '.$number_days.' days.', $bulk_counts['trashed'] ),
    );

    $bulk_messages['page'] = array(
		'trashed'   => _n( '%s page moved to the Trash. Pages in trash are automatically deleted after '.$number_days.' days.', '%s pages moved to the Trash. Pages in trash are automatically deleted after '.$number_days.' days.', $bulk_counts['trashed'] ),
    );

    return $bulk_messages;

}

add_filter( 'bulk_post_updated_messages', 'msi_bulk_post_updated_messages_filter', 10, 2 );

// Here we add the notice to page that lists trashed posts or pages in the backend.
function msi_trash_warning() {

	$number_days = msi_get_number_days();

	$current_screen = get_current_screen();

	if($current_screen->parent_base == 'edit' && get_post_status() == 'trash') {
		?>
			<div class="notice-info notice">
				<p><?php _e( 'Note: Posts in trash are automatically deleted after '.$number_days.' days.', 'msi-trash-notice' ); ?></p>
			</div>
		<?php
	}

}

add_action( 'admin_notices', 'msi_trash_warning' );