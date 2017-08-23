<?php

/**
 * Provide a admin settings area view for the plugin
 *
 * This file is used to markup the admin-facing settings of the plugin.
 *
 * @link       http://gentryx.com/bishalsaha
 * @since      1.0.0
 *
 * @package    Push_Notification_Sender
 * @subpackage Push_Notification_Sender/admin/partials
 */

global $wp;
$current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$phpself_url = $_SERVER['PHP_SELF'];

if ( isset( $_POST['is_submitted_general_tab'] ) && $_POST['is_submitted_general_tab'] == 'yes' ) {
	save_general_options();
} else if ( isset( $_POST['is_submitted_ios_tab'] ) && $_POST['is_submitted_ios_tab'] == 'yes' ) {
	save_ios_options();
} else if ( isset( $_POST['is_submitted_android_tab'] ) && $_POST['is_submitted_android_tab'] == 'yes' ) {
	save_android_options();
}

?>
<table>
    <tr>
        <td>
            <h2><?php _e( 'Settings For Push Notifications' ) ?></h2>
        </td>
    </tr>
</table>
<div id="poststuff" class="metabox-holder has-right-sidebar">
    <div class="has-sidebar sm-padded">
        <div class="meta-box-sortabless">
			<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_tab'; ?>
            <h2 class="nav-tab-wrapper">
                <a href="?page=push-notification-sender-settings&tab=general_tab"
                   class="nav-tab <?php echo $active_tab == 'general_tab' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'push-notifications-sender' ); ?></a>
                <a href="?page=push-notification-sender-settings&tab=ios_tab"
                   class="nav-tab <?php echo $active_tab == 'ios_tab' ? 'nav-tab-active' : ''; ?>"><?php _e( 'iOS', 'push-notifications-sender' ); ?></a>
                <a href="?page=push-notification-sender-settings&tab=android_tab"
                   class="nav-tab <?php echo $active_tab == 'android_tab' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Android', 'push-notifications-sender' ); ?></a>
            </h2>
            <form name="pns_push_notification_options_form" action="" id="pns_push_notification_options_form"
                  method="post" enctype="multipart/form-data">
				<?php
				if ( $active_tab == 'general_tab' ) {
					print_general_options_box();
				} else if ( $active_tab == 'ios_tab' ) {
					wp_enqueue_media();
					wp_enqueue_script( 'media-upload' );
					wp_enqueue_script( 'thickbox' );
					wp_enqueue_script( 'my-upload' );
					wp_enqueue_style( 'thickbox' );
					print_ios_options_box();
				} else if ( $active_tab == 'android_tab' ) {
					print_android_options_box();
				}
				?>
                <button type="submit" value="yes" name="pns_save_options_setting_button"
                        class="button button-primary"><?php _e( 'Save Settings' ); ?></button>
            </form>
        </div>
    </div>
</div>

<?php
function print_general_options_box() {
	//General Option
	$pns_on_new_post_publish  = get_option( 'pns_on_new_post_publish' );
	$pns_on_new_page_save     = get_option( 'pns_on_new_page_save' );
	$pns_on_new_user_register = get_option( 'pns_on_new_user_register' );
	$pns_on_new_comment_post  = get_option( 'pns_on_new_comment_post' );
	wp_nonce_field( 'pns_general_options', 'pns_general_options' );
	?>

    <div class="postbox">
        <h3 class="hndle">
            <span><?php _e( 'Send Push Notifications When', 'push-notifications-sender' ); ?></span>
        </h3>
        <div class="inside">
            <p>
            <table width="100%">
                <tr valign="top">
                    <td>
                        <input type="hidden" name="is_submitted_general_tab" value="yes"/>
                        <input type="checkbox" name="pns_on_new_post_publish"
                               value="yes" <?php echo( $pns_on_new_post_publish == 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'A new post is published' ); ?>
                        <br>
                        <input type="checkbox" name="pns_on_new_page_save"
                               value="yes" <?php echo( $pns_on_new_page_save == 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'A new page is published' ); ?>
                        <br>
                        <input type="checkbox" name="pns_on_new_user_register"
                               value="yes" <?php echo( $pns_on_new_user_register === 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'A new user register' ); ?>
                        <br>
                        <input type="checkbox" name="pns_on_new_comment_post"
                               value="yes" <?php echo( $pns_on_new_comment_post == 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'A new comment post' ); ?>
                    </td>
                </tr>
            </table>
            </p>
        </div>
    </div>

	<?php
}

function save_general_options() {
	$pns_on_new_post_publish  = isset( $_POST['pns_on_new_post_publish'] ) ? $_POST['pns_on_new_post_publish'] : "no";
	$pns_on_new_page_save     = isset( $_POST['pns_on_new_page_save'] ) ? $_POST['pns_on_new_page_save'] : "no";
	$pns_on_new_user_register = isset( $_POST['pns_on_new_user_register'] ) ? $_POST['pns_on_new_user_register'] : "no";
	$pns_on_new_comment_post  = isset( $_POST['pns_on_new_comment_post'] ) ? $_POST['pns_on_new_comment_post'] : "no";

	if ( ( $pns_on_new_comment_post == "no" ) && ( $pns_on_new_user_register == "no" ) && ( $pns_on_new_page_save == "no" ) && ( $pns_on_new_post_publish == "no" ) ) {
		$pns_message_er = __( 'Please select at least one checkbox box to send the push notification.' );
		printf( "<p class='error'>%s</p>", $pns_message_er );
	}

	update_option( 'pns_on_new_post_publish', $pns_on_new_post_publish );
	update_option( 'pns_on_new_page_save', $pns_on_new_page_save );
	update_option( 'pns_on_new_user_register', $pns_on_new_user_register );
	update_option( 'pns_on_new_comment_post', $pns_on_new_comment_post );
}

?>
<?php
function print_ios_options_box() {
	//General Option
	$pns_send_to_ios         = get_option( 'pns_send_to_ios' );
	$pns_send_via_production = get_option( 'pns_send_via_production' );
	$pns_send_via_sandbox    = get_option( 'pns_send_via_sandbox' );
	$pns_ios_certi_path      = get_option( 'pns_ios_certi_path' );
	$pns_ios_certi_name      = get_option( 'pns_ios_certi_name' );

	wp_nonce_field( 'pns_ios_options', 'pns_ios_options' );
	?>

    <div class="postbox">

        <div class="inside">
            <p>
            <table width="100%">
                <tr valign="top">
                    <td>
                        <input type="hidden" name="is_submitted_ios_tab" value="yes"/>
                        <input type="checkbox" name="pns_send_to_ios"
                               value="yes" <?php echo( $pns_send_to_ios == 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'Send Push notifications to iOS devices' ); ?>
                        <hr>
                        <input type="radio" name="pns_push_to_ios"
                               value="send_via_sandbox" <?php echo( $pns_send_via_sandbox == 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'Send Push notifications to iOS devices using sandbox environment' ); ?>
                        <br>
                        <input type="radio" name="pns_push_to_ios"
                               value="send_via_production" <?php echo( $pns_send_via_production == 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'Send Push notifications to iOS devices using Production environment' ); ?>
                        <hr>
                        <label for="upload_ios_certi">
                            <input id="pns_upload_ios_certi" type="text" size="36" name="pns_upload_ios_certi"
                                   value="<?php echo $pns_ios_certi_path; ?>"/>
                            <input id="pns_upload_image_button" name="pns_upload_image_button" type="button"
                                   value="<?php _e( 'Upload  Certificate' ) ?>" data-uploader_title='Select .PEM File'
                                   data-uploader_button_text='Select'/>
                            <input type="hidden" name="pns_upload_ios_certi_name" id="pns_upload_ios_certi_name"
                                   value="<?php echo $pns_ios_certi_name; ?>">
                        </label>
                    </td>
                </tr>
            </table>
            </p>
        </div>
    </div>

	<?php
}

function save_ios_options() {

	if ( ! wp_verify_nonce( $_POST['pns_ios_options'], 'pns_ios_options' ) ) {

		print 'Sorry, your nonce did not verify.';

	} else {

		$pns_send_to_ios    = isset( $_POST['pns_send_to_ios'] ) ? $_POST['pns_send_to_ios'] : 'no';
		$pns_ios_certi_path = $_POST['pns_upload_ios_certi'];
		$pns_ios_certi_name = $_POST['pns_upload_ios_certi_name'];
		$pns_push_to_ios    = $_POST['pns_push_to_ios'];

		if ( $pns_push_to_ios == 'send_via_production' ) {
			$pns_send_via_production = "yes";
			$pns_send_via_sandbox    = "no";
		} else {
			$pns_send_via_production = "no";
			$pns_send_via_sandbox    = "yes";
		}

		update_option( 'pns_send_to_ios', $pns_send_to_ios );
		update_option( 'pns_ios_certi_path', $pns_ios_certi_path );
		update_option( 'pns_ios_certi_name', $pns_ios_certi_name );
		update_option( 'pns_send_via_production', $pns_send_via_production );
		update_option( 'pns_send_via_sandbox', $pns_send_via_sandbox );

	}
}

?>
<?php
function print_android_options_box() {
	//General Option
	$pns_send_to_android     = get_option( 'pns_send_to_android' );
	$pns_google_api_key      = get_option( 'pns_google_api_key' );
	$pns_send_to_android_via = get_option( 'pns_send_to_android_via' );

	wp_nonce_field( 'pns_android_options', 'pns_android_options' );
	?>
    <script>
        jQuery(document).ready(function () {
            // validate signup form on keyup and submit
            jQuery("#pns_push_notification_options_form").validate({
                rules: {
                    pns_google_api_key: "required",
                    pns_send_to_android_via: "required",
                    pns_send_to_android: "required"
                },
                messages: {
                    pns__google_api_key: "Please Enter Google Api Key",
                    pns_send_to_android_via: "Please Select option",
                    pns_send_to_android: "Please Select option for send notifications to android devices",

                },
                errorPlacement: function (error, element) {
                    jQuery(element).closest('tr').next().find('.error_label').html(error);
                }
            });
        });
    </script>
    <div class="postbox">
        <h3 class="hndle">
            <span><?php _e( 'Send Push Notifications When', 'push-notifications-sender' ); ?></span>
        </h3>
        <div class="inside">
            <p>
            <table width="100%">
                <tr valign="top">
                    <td valign="top">
                        <input type="checkbox" name="pns_send_to_android"
                               value="yes" <?php echo( $pns_send_to_android == 'yes' ? 'checked' : '' ); ?>>
						<?php _e( 'Send Push notifications to Android devices' ); ?>
                        <hr>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <input type="hidden" name="is_submitted_android_tab" value="yes"/>
                        <input type="radio" name="pns_send_to_android_via" value="gcm"
							<?php if ( isset( $pns_send_to_android_via ) && ( $pns_send_to_android_via == "gcm" ) ) {
								echo "checked";
							} ?> >
						<?php _e( 'Send Push notifications via Google Cloud Messaging (GCM)' ); ?>
                        <br>
                        <input type="radio" name="pns_send_to_android_via" value="fcm"
							<?php if ( isset( $pns_send_to_android_via ) && ( $pns_send_to_android_via == "fcm" ) ) {
								echo "checked";
							} ?> >
						<?php _e( 'Send Push notifications via Firebase Cloud Messaging (FCM)' ); ?>
                        <hr>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <h4> <?php _e( 'Google API Key:' ); ?> </h4>
                        <input type="text" maxlength="255" style="width:500px;"
                               value="<?php echo $pns_google_api_key; ?>" id="pns_google_api_key"
                               name="pns_google_api_key" class="textfield" <?php if ( $pns_send_to_android == 'yes' ) {
							echo 'required';
						} ?> >
                    </td>
                </tr>
            </table>
            </p>
        </div>
    </div>

	<?php
}

function save_android_options() {
	if ( ! wp_verify_nonce( $_POST['pns_android_options'], 'pns_android_options' ) ) {
		print 'Sorry, your nonce did not verify.';
	} else {
		$pns_send_to_android     = isset( $_POST['pns_send_to_android'] ) ? $_POST['pns_send_to_android'] : 'no';
		$pns_google_api_key      = $_POST['pns_google_api_key'];
		$pns_send_to_android_via = $_POST['pns_send_to_android_via'];

		update_option( 'pns_send_to_android', $pns_send_to_android );
		update_option( 'pns_google_api_key', $pns_google_api_key );
		update_option( 'pns_send_to_android_via', $pns_send_to_android_via );
	}
}

?>
