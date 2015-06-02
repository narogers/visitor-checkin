<?php namespace App\Console\Commands;

use App\Services\AlephClient;
use App\User;

use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportAlephRecords extends Command {

	protected $name = 'aleph:import';
	protected $description = 'Import records from Aleph into the local database';

	/**
	 * Create a new command instance. The list of Aleph IDs to resolve
	 * should be passed as a flat text with one value per line. Any valid
	 * IDs will be automatically imported into the system.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function getArguments() {
		return [['source', InputArgument::REQUIRED, 
								"List of Aleph IDs"]];
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
			$client = new AlephClient;

			$aleph_ids = file($this->argument('source'), 
				FILE_IGNORE_NEW_LINES);
			foreach ($aleph_ids as $aleph_id) {
				$user = new User;
				$user->importPatronDetails($aleph_id);

        # If no record was found skip over this entry
				if (null == $user->name) {
					$this->error("[ERROR] Could not resolve " . $aleph_id);
					continue;
				}

				$is_existing_record = (0 < User::where('email_address', $user->email_address)->count());
				if ($is_existing_record) {
					$user_record = User::where("email_address", $user->email_address)->first();
					$user_record->name = $user->name;
					$user_record->aleph_id = $user->aleph_id;
					$user = $user_record;
				}

				# Otherwise we've got nothing to do because this is a new
				# record and we can assume that the person is already valid
				# since they have an Aleph ID
				$user->verified_user = true;
				$user->signature = '';
				$user->save();

				$this->info('[SUCCESS] ' . $aleph_id . ' has been imported into the local database');
			}
	}

}
