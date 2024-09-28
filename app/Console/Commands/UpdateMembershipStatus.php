<?php

namespace App\Console\Commands;

use App\Models\MembersTrack;
use Illuminate\Console\Command;
use App\Models\MemberTrack;
use Carbon\Carbon;

class UpdateMembershipStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:update-status';
    protected $description = 'Update status membership based on end_membership date';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil data membership yang sudah expired
        $expiredMemberships = MembersTrack::where('end_membership', '<', Carbon::now())
            ->where('status', 'active')
            ->get();

        foreach ($expiredMemberships as $membership) {
            $membership->status = 'expired';
            $membership->save();

            // Output ke console atau log jika ada perubahan
            $this->info("Membership ID {$membership->id} has been set to expired.");
        }

        $this->info('Membership status updated successfully.');
    }
}
