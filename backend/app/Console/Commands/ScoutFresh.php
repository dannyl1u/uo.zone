<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Professor\Professor;
use App\Models\Subject;
use Illuminate\Console\Command;

class ScoutFresh extends Command
{
    private const MODELS = [
        Course::class,
        Subject::class,
        Professor::class,
    ];

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'scout:fresh';

    /**
     * The console command description.
     */
    protected $description = 'Flush and import all models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (static::MODELS as $model) {
            $indexName = (new $model)->getTable();
            $this->call('scout:delete-index', ['name' => $indexName]);
        }

        $this->call('scout:sync-index-settings');

        foreach (static::MODELS as $model) {
            $this->call('scout:import', ['model' => $model]);
        }
    }
}
