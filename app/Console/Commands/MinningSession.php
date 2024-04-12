<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;

use Illuminate\Console\Command;
use App\Models\MiningSession;
use Illuminate\Support\Facades\DB;

class MinningSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:minning-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $now  = Carbon::now();
            $pending = MiningSession::where('status', 'running')->where('end_time', '<=', $now)->orderBy('end_time')->take(100)->get();

            foreach ($pending as $item) {
                DB::beginTransaction();
                $update = MiningSession::findOrFail($item->id);
                $update->status = 'closed';
                $update->save();
                sendpush($item->user->country_code . $item->user->phone_number, 'Hey There ! Your Mining Session Has Ended 😨 Come Back And Start Mining Again 💰💸');
                //push refer bondus
                if ($item->user->referred_by != null || $item->user->referred_by != "skiped") {
                    $ref_user = User::where('refer_code', $item->user->referred_by)->first();
                    coin_action($ref_user->id, $item->coin, 'credit', "Commission Received From Your Referral User ".$item->user->name );
                }
                if (!coin_action($item->user_id, $item->coin, 'credit', "Coins Added For Mining Session " . $item->session_id, ['session_id' => $item->session_id])) {
                    DB::rollBack();
                } else {
                    DB::commit();
                }
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
        echo "success";
    }
}
