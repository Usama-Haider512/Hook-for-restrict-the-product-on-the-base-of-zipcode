add_action('profile_update', 'send_email_on_approval_status_change', 10, 2);

function send_email_on_approval_status_change($user_id, $old_user_data) {
    // Get the new and old approval statuses
    $old_approval_status = get_user_meta($user_id, 'new_user_approve_status', true);
    $new_approval_status = isset($_POST['new_user_approve_status']) ? sanitize_text_field($_POST['new_user_approve_status']) : '';

    // Check if the approval status has changed
    if ($old_approval_status !== $new_approval_status) {
        // Get the user's email address
        $user_info = get_userdata($user_id);
        $user_email = $user_info ? $user_info->user_email : '';

        // Ensure user email is available
        if (!$user_email) {
            error_log("User email not found for user ID: $user_id");
            return;
        }

        // Prepare email content based on approval status
        $subject = '';
        $message = '';
        if ($new_approval_status === 'approved') {
            $subject = 'Account Approval Notification';
            $message = 'Congratulations, you are now able to purchase bulk orders of our products. You can contact us at the above email or call us directly at (209) 510-6321. Please click on the link below to login on the website ' . PHP_EOL . PHP_EOL . 'https://brothasbakedgoods.com/my-account/';
        } elseif ($new_approval_status === 'denied') {
            $subject = 'Account Denial Notification';
            $message = 'Your account has been denied by the administrator.';
        } else {
            // Invalid or empty approval status
            error_log("Invalid approval status: $new_approval_status");
            return;
        }
        
        // Set headers
        $headers = array(
            'From: Brothas Baked Goods <distributor@brothasbakedgoods.com>',
            'Reply-To: distributor@brothasbakedgoods.com'
        );

        // Send the email to the user if subject and message are set
        if (!empty($subject) && !empty($message)) {
            $mail_sent = wp_mail($user_email, $subject, $message, $headers);
            if (!$mail_sent) {
                error_log("Failed to send email to user ID: $user_id");
            }
        } else {
            // Subject or message is empty
            error_log("Subject or message is empty for user ID: $user_id");
        }
    }
}
