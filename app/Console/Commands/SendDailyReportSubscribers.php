<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SendDailyReportSubscribers extends Command
{
    protected $signature = 'app:send-daily-report-subscribers';

    protected $description = 'Send daily reports about subscribers';

    public function handle()
    {
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $subscribers = Subscriber::query()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            })
            ->orWhere(function ($query) use ($startDate, $endDate) {
                $query->whereDate('updated_at', '>=', $startDate)
                    ->whereDate('updated_at', '<=', $endDate);
            })
            ->orWhere(function ($query) use ($startDate, $endDate) {
                $query->whereDate('cancelled_at', '>=', $startDate)
                    ->whereDate('cancelled_at', '<=', $endDate);
            })
            ->get();

        dump($subscribers);

        return CommandAlias::SUCCESS;
    }
}
