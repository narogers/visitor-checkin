<?php namespace App\Console\Commands;

use App\OldRegistration;
use App\Role;
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
		// Used for reporting once the script finishes
		$successes = [];
		$failures = [];

		$oldRegistrations = new OldRegistration;
		$archivedRegistrations = $oldRegistrations->getUsers();

		// Use the email address as the unique key such that if
		// duplicates exist they will update existing records.
		// That means this migration can be run multiple times
		// and will just pick up where it left off
		foreach (array_keys($archivedRegistrations) as $patron) {
			$aleph = new Aleph();
			$user = User::firstOrNew(['email_address' =>
				$archivedRegistrations[$patron]['email']]);
			$user->name = $patron;
			
			// Try to resolve the Aleph ID at this point so that if
			// you do not save the record can be destroyed. Skip to
			// the next record after emitting a warning
			$aleph_id = $aleph->getPatronID($user);
			if (null == $aleph_id) {
				$this->error('WARNING: Could not resolve an Aleph ID for ' . $patron);
				array_push($failures, $user->name);
				continue;
			}
			$user->aleph_id = $aleph_id;
			
			// Now try to resolve the user's role from Aleph using a
			// mapping. If not provided then set the role to 0 which
			// should never happen. These records can be resolved
			// manually via the back end
			$role = Role::where(['role' => $archivedRegistrations[$patron]['role']])->first();
			if (null == $role) {
				$user->role_id = 0;
			} else {
				$user->role_id = $role->id;
			}

			// This is not the most efficient way of pulling the
			// signature but it gets the job done well enough. If
			// performance matters it could be refactored
			$patronRegistration = $oldRegistrations->find($archivedRegistrations[$patron]['id']);
			if (!array_key_exists('email', $patronRegistration)) {
				array_push($failures, $user->name);
				continue;
			}

			$user->email_address = $patronRegistration->email;
			$user->signature = $patronRegistration->signature;
			$user->save();
			array_push($successes, $user->name);
		}

		$this->generateReport($successes, $failures);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
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

	/**
	 * Generates a report of which users were migrated and which
	 * need to be handled by hand
	 */
	protected function generateReport($successes = [], 
		$failures = []) {
		// For now write to the console *and* to the log file but
		// one option is to provide a system flag for one or the
		// other
		echo sizeof($successes) . " user(s) migrated\r\n";
		echo "-----\r\n";
		foreach ($successes as $name) {
			echo $name . "\r\n";
		}
		echo "\r\n";
		echo sizeof($failures) . " user(s) skipped\r\n";
		echo "-----\r\n";
		foreach ($failures as $name) {
			echo $name . "\r\n";
		}	
	}

}
