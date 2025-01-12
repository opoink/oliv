<?php
namespace Opoink\Oliv\Console\Commands;

use Illuminate\Console\Command;
use Opoink\Oliv\Lib\Plugin\UpdatePlugin;
 
class PluginsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'oliv:plugins-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all installed plugins';
 
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
		$updatePlugin = new UpdatePlugin($this);
		$updatePlugin->executeUpdate();
    }
}