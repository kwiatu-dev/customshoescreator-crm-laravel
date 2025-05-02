<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

class SendResetPasswordLink extends Command
{
    protected $signature = 'user:send-reset-link {email}';
    protected $description = 'Send a password reset link to the given email address';

    public function handle()
    {
        $email = $this->argument('email');

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->info("Reset link sent to: {$email}");
        } else {
            $this->error("Failed to send reset link. Reason: " . __($status));
        }
    }
}
