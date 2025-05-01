<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:test-email {--email=filip@example.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to a given email address or the default email address if not provided.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->option('email');

        try {
            Mail::raw('This is a test email sent from Laravel.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email');
            });

            $this->info("Test email has been sent to {$email}.");
        } catch (\Exception $e) {
            $this->error('Error sending email: ' . $e->getMessage());
        }
    }
}
