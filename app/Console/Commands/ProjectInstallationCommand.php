<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProjectInstallationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project-installation {name=softexpert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate the project installation process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectName = $this->argument('name');
        $this->info("Starting installation for: {$projectName}");

        $bar = $this->output->createProgressBar(4);
        $bar->start();

        $this->runComposerInstall($bar);
        $this->generateKey($bar);

        // JWT secret
        Artisan::call('jwt:secret');
        $this->info("JWT secret generated");
        $bar->advance();
        $this->newLine();

        // Migrate and seed
        Artisan::call('migrate', ['--seed' => true]);
        $this->info("Database migrated and seeded");
        $bar->advance();
        $this->newLine();
        
        $bar->finish();
        $this->info("\n Project {$projectName} installed successfully!");
    }

    private function runComposerInstall($bar)
    {
        $this->line("Installing PHP dependencies with Composer...");
        exec('composer install', $output, $result);

        if ($result === 0) {
            $this->info("Composer dependencies installed");
        } else {
            $this->error("Composer install failed. Check your composer.json or internet connection.");
            $this->line(implode("\n", $output));
        }
        $this->newLine();
        $bar->advance();
    }

    private function generateKey($bar)
    {
        $this->line("Generating application key...");
        Artisan::call('key:generate');
        $this->info("Application key generated");
        $this->newLine();
        $bar->advance();
    }
}
