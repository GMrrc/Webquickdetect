<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordResetToken;
use Carbon\Carbon;

class DeleteExpiredTokens extends Command
{
    protected $signature = 'tokens:delete-expired';
    protected $description = 'Delete expired password reset tokens';

    public function handle()
    {
        $expirationTime = Carbon::now()->subMinutes(20);
        $deletedRows = PasswordResetToken::where('created_at', '<', $expirationTime)->delete();

        $this->info("Deleted $deletedRows expired tokens.");
    }
}
