<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://gentryx.com/bishalsaha
 * @since      1.0.0
 *
 * @package    Push_Notification_Sender
 * @subpackage Push_Notification_Sender/admin/partials
 */

/**
 * Plugin class that is used to render the list table
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/class-push-notification-sender-list-table.php';

// Create an instance of our package class...
$Push_Notification_Sender_List_Table = new Push_Notification_Sender_List_Table();
//Fetch, prepare, sort, and filter our data...
$Push_Notification_Sender_List_Table->prepare_items();

?>
<div class="wrap">
    <h2><?php _e( 'All Push Notifications' ) ?></h2>
    <p><?php if ( isset( $_GET['notifications'] ) == 'send' ) {
			echo _e( 'Notifications Sent.' );
		} ?> </p>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="push_notification_sender_list_table" action="" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <!-- Now we can render the completed list table -->
		<?php $Push_Notification_Sender_List_Table->display(); ?>
    </form>
</div>
