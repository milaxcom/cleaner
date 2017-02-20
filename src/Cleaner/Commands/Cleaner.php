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
            
            if (!is_array($file['path'])) {
                $file['path'] = [$file['path']];
            }
            
            foreach ($file['path'] as $key => $filepath) {
                if (ends_with($filepath, '*')) {
                    $glob = glob($filepath);
                    unset($file['path'][$key]);
                    $file['path'] = array_merge($file['path'], $glob);
                }
            }
            
            $this->delete($file, $seconds);
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
        if (!is_array($file['path'])) {
            $file['path'] = [$file['path']];
        }
        
        foreach ($file['path'] as $path) {
            if (File::exists($path)) {
                if (!empty($file['before']) && is_callable($file['before'])) {
                    $file['before']($path);
                }
                if ((time() - File::lastModified($path)) >= $expires) {
                    if (File::isDirectory($path)) {
                        File::deleteDirectory($path);
                    } else {
                        File::delete($path);
                    }
                }
                if (!empty($file['after']) && is_callable($file['after'])) {
                    $file['after']($path);
                }
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
