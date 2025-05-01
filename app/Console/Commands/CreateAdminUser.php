<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin 
                            {--first_name=Filip} 
                            {--last_name=Kwiatkowski} 
                            {--email=filip@example.com}';

    protected $description = 'Tworzy administratora';

    public function handle()
    {
        if (!app()->environment('local')) {
            $this->error("Ta komenda może być uruchomiona tylko lokalnie.");
            return;
        }
        
        $email = $this->option('email');

        if (User::where('email', $email)->exists()) {
            $this->info("Użytkownik o adresie $email już istnieje.");
            return;
        }

        $user = User::factory()
            ->verified()
            ->create([
                'first_name' => $this->option('first_name'),
                'last_name' => $this->option('last_name'),
                'email' => $email,
                'is_admin' => true,
            ]);

        $this->info("Utworzono użytkownika: {$user->email}");
    }
}
