// Hook into user profile update
add_action('profile_update', 'send_email_on_profile_update', 10, 2);

function send_email_on_profile_update($user_id, $old_user_data) {
    // Check if the user has selected "Yes"
    $selected_option = get_user_meta($user_id, 'status', true);
    $email_sent_flag = get_user_meta($user_id, 'email_sent_flag', true);

    if ($selected_option === 'Yes' && !$email_sent_flag) {
        // Get the user's email address
        $user_email = get_userdata($user_id)->user_email;

        // Prepare email content
        $subject = 'Approval of Registration';
		$message = 'Please click on the link below to review and complete distributor agreements. Upon approval of your distributor agreements, you will receive access to place your first order.' . PHP_EOL . PHP_EOL . 'https://brothasbakedgoods.com/upload-agreements/?email=' . $user_email;

        
        // Set headers
        $headers = array(
            'From: Brothas Baked Goods <distributor@brothasbakedgoods.com>',
            'Reply-To: distributor@brothasbakedgoods.com'
        );

        // Send the email to the user
        $mail_sent = wp_mail($user_email, $subject, $message, $headers);

        // Update the email sent flag to prevent sending multiple times
        if ($mail_sent) {
            update_user_meta($user_id, 'email_sent_flag', true);
        }
    }
}
