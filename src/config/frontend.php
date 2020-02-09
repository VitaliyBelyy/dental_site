<?php

return [
    // frontend URL
    'url' => env('FRONTEND_URL', 'http://127.0.0.1:8000'),
    // path to email-confirmation frontend page
    'email_verify_url' => env('FRONTEND_EMAIL_VERIFY_URL', '/auth/email-confirmation?queryURL='),
    // path to reset-password frontend page
    'reset_password_url' => env('FRONTEND_RESET_PASSWORD_URL', '/auth/reset-password?token='),
];
