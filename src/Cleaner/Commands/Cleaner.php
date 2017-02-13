<?php

namespace MisterPaladin\Cleaner\Commands;

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
        foreach ($this->config as $file) {
            $seconds = $this->convertToSeconds($file['expires']);
            if (ends_with($file['path'], '*')) {
                foreach (glob($file['path']) as $path) {
                    $this->delete($path, $seconds);
                }
            } else {
                $this->delete($file, $seconds);
            }
        }
    }
    
    /**
     * Delete file or directory at given path
     * 
     * @param  array $file [Config item]
     * @param  int $expires [Expiration in seconds]
     * @return void
     */
    protected function delete($file, $expires)
    {
        if (File::exists($file['path'])) {
            if (!empty($file['before']) && is_callable($file['before'])) {
                $file['before']($file['path']);
            }
            if ((time() - File::lastModified($file['path'])) >= $expires) {
                if (File::isDirectory($file['path'])) {
                    File::deleteDirectory($file['path']);
                } else {
                    File::delete($file['path']);
                }
            }
            if (!empty($file['after']) && is_callable($file['after'])) {
                $file['after']($file['path']);
            }
        }
    }
    
    /**
     * Convert config to seconds
     * 
     * @param  array $file  [Config item]
     * @return int
     */
    protected function convertToSeconds($file)
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
