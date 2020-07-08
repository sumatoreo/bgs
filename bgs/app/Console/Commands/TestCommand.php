<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mytest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $event = Event::find(1);
//        $p = $event->participants()
//            ->first();
//
//        dd($p->first_name);

        /** @var Participant $p */
        $p = Participant::find(1);
        $p = $p->events()->first();

        dd($p->name);
    }
}
