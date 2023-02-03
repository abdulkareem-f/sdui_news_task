<?php

namespace App\Console\Commands;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Console\Command;

class delete_old_news extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will delete all news older than 14 days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $news = News::where( 'created_at', '<', Carbon::now()->subDays(14));
            if($news->get()->isEmpty()){
                $this->line('No old news to delete');
                return true;
            }
            $newsCount = $news->count();
            if($news->delete()) {
                $this->info("Old news ($newsCount) has been deleted successfully");
            } else {
                $this->error('Old news cannot be deleted');
            }
            return true;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return true;
        }
    }
}
