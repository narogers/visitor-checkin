<?php namespace App\Console\Commands;

use App\OldRegistration;
use App\User;
use App\Services\Aleph;

use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateOldRegistrations extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'registrations:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate existing registrations to new table format';

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
	 * @return mixed
	 */
	public function fire()
	{
		$oldRegistrations = new OldRegistration;
		$archivedRegistrations = $oldRegistrations->getUsers();

		// Use the email address as the unique key such that if
		// duplicates exist they will update existing records.
		// That means this migration can be run multiple times
		// and will just pick up where it left off
		foreach (array_keys($archivedRegistrations) as $patron) {
			echo '[' . $archivedRegistrations[$patron]['id'] . 
			  "] Beginning conversion to new format\r\n";
			$user = User::firstOrNew(['email_address' =>
				$archivedRegistrations[$patron]['email']]);
			$user->name = $patron;

			if ($archivedRegistrations[$patron]['id'] == 719) {
				var_dump($archivedRegistrations[$patron]);
				var_dump($patron);
			}

			// Try to resolve the Aleph ID at this point so that if
			// you do not save the record can be destroyed. Skip to
			// the next record after emitting a warning
			$aleph = new Aleph($user);
			$aleph_id = $aleph->getPatronID();
			if (null == $aleph_id) {
				$this->error('WARNING: Could not resolve an Aleph ID for ' . $patron);
				continue;
			}
			// Now try to resolve the user's role from Aleph using a
			// mapping. If not provided then set the role to 0 which
			// should never happen. These records can be resolved
			// manually via the back end
			$user->role_id = 0;

			// This is not the most efficient way of pulling the
			// signature but it gets the job done well enough. If
			// performance matters it could be refactored
			$patronRegistration = $oldRegistrations->find($archivedRegistrations[$patron]['id']);
			if (!array_key_exists('email', $patronRegistration)) {
				$this->error('WARNING: No valid email found');
				continue;
			}

			$user->email_address = $patronRegistration->email;
			$user->signature = $patronRegistration->signature;
			$user->save();
			echo '[' . $user->id . '] ' . $user->name . " saved\r\n";
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['filename', InputArgument::OPTIONAL, 'File to export to', 'oldRegistrations.csv'] 
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
