<?php

namespace Milax\Cleaner\Commands;

use Illuminate\Console\Command;
use File;
use Carbon\Carbon;

class Cleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleaner:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run garbage cleaner';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->config = config('cleaner');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->config as $config) {
            $seconds = $this->convertToSeconds($config['expires']);
            if (ends_with($config['path'], '*')) {
                foreach (glob($config['path']) as $path) {
                    $this->delete($path, $seconds);
                }
            }
        }
    }
    
    /**
     * Delete file or directory at given path
     * 
     * @param  string $path [File path]
     * @param  int $expires [Expiration in seconds]
     * @return void
     */
    protected function delete($path, $expires)
    {
        if (File::exists($path)) {
            if ((time() - File::lastModified($path)) >= $expires) {
                File::delete($path);
            }
        }
    }
    
    /**
     * Convert config to seconds
     * 
     * @param  array $config  [Config item]
     * @return int
     */
    protected function convertToSeconds($config)
    {
        $seconds = 0;
        
        if (isset($config['seconds'])) {
            $seconds += $config['seconds'];
        }
        
        if (isset($config['minutes'])) {
            $seconds += $config['minutes'] * 60;
        }
        
        if (isset($config['hours'])) {
            $seconds += $config['hours'] * 60 * 60;
        }
        
        if (isset($config['days'])) {
            $seconds += $config['days'] * 24 * 60 * 60;
        }
        
        if (isset($config['weeks'])) {
            $seconds += $config['weeks'] * 7 * 24 * 60 * 60;
        }
        
        if (isset($config['months'])) {
            $seconds += $config['months'] * 30 * 7 * 24 * 60 * 60;
        }
        
        if (isset($config['years'])) {
            $seconds += $config['years'] * 365 * 30 * 7 * 24 * 60 * 60;
        }
        
        return $seconds;
    }
}
