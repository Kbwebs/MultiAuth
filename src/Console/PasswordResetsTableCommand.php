<?php

namespace Kbwebs\MultiAuth\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PasswordResetsTableCommand extends Command {

	/**
	 * The console command name.
	 * @var string
	 */
	protected $name = 'kbwebs:multi-auth:create-resets-table';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Create a migration for the password resets table';

	/**
	 * The filesystem instance.
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * Create a new resets table command instance.
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @return void
	 */
	public function __construct(Filesystem $files)
	{
		parent::__construct();
		$this->files = $files;
	}

	/**
	 * Execute the console command.
	 * @return void
	 */
	public function fire()
	{
		$fullPath = $this->createBaseMigration();
		$this->files->put($fullPath, $this->getMigrationStub());
		$this->info('Migration created successfully!');
	}

	/**
	 * Create a base migration file for the resets.
	 * @return string
	 */
	protected function createBaseMigration()
	{
		$name = 'create_password_resets_table';
		$path = database_path().'/migrations';
		return $this->laravel['migration.creator']->create($name, $path);
	}

	/**
	 * Get the contents of the reset migration stub.
	 * @return string
	 */
	protected function getMigrationStub()
	{
		$stub = $this->files->get(__DIR__.'/stubs/password_resets.stub');
		return str_replace('password_resets', $this->getTable(), $stub);
	}

	/**
	 * Get the password reset table name.
	 * @return string
	 */
	protected function getTable()
	{
		return $this->laravel['config']->get('auth.password.table');
	}
}