<?php

namespace App\Console\Commands;

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
                
                if (!coin_action($item->user_id, $item->coin,'credit', "Coins Added For Mining Session " . $item->session_id, ['session_id' => $item->session_id])) {
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
