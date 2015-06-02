<?php namespace App\Console\Commands;

use App\OldRegistration;
use App\Role;
use App\User;
use App\Services\AlephClient;

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
		$aleph_interface = new AlephClient();
		foreach (array_keys($archivedRegistrations) as $patron) {
			$user = User::firstOrNew(['email_address' =>
				$archivedRegistrations[$patron]['email']]);
			$user->name = $patron;
			
			$this->info('[Migration] Migrating ' . $patron);

			// Try to resolve the Aleph ID at this point so that if
			// you do not save the record can be destroyed. Skip to
			// the next record after emitting a warning
			$aleph_id = $aleph_interface->validatePatronID($user);
			if (null == $aleph_id) {
				$this->error('WARNING: Could not resolve an Aleph ID for ' . $patron);
				array_push($failures, $user->name);
				continue;
			}
			if (Role::ofType($archivedRegistrations[$patron]['role'])->count() > 0) {
			  $user->role_id = Role::ofType($archivedRegistrations[$patron]['role'])->first()->id;
			}
			$user->importPatronDetails($aleph_id);

			/**
			 * This is a kludge to deal with situations where the
			 * registration table uses one email address but the
			 * Aleph response has another
			 */
			if (0 < User::where(["email_address" => $user->email_address])
				->count()) {
				$this->info('[Migration] Import into existing user record');
				$this->replaceUser($user);
			}

			# Set the verified flag to true and assume all existing registrations
			# have been validated already
			$user->verified_user = true;
			
			// This is not the most efficient way of pulling the
			// signature but it gets the job done well enough. If
			// performance matters it could be refactored
			$patronRegistration = $oldRegistrations->find($archivedRegistrations[$patron]['id']);
			if (!array_key_exists('email', $patronRegistration)) {
				array_push($failures, $user->name);
				continue;
			}

			$user->signature = $patronRegistration->signature;
			$user->save();
			array_push($successes, $user->name);
		}

		$this->generateReport($successes, $failures);
	}

	/**
	 * Given a reference to a temporary user's details locates the
	 * existing record by email address and copies over the name,
	 * barcode, and Aleph ID.
	 *
	 * Returns the existing user object with updated information
	 */
	public function replaceUser(&$new_user) {
		$existing_user = User::where('email_address', $new_user->email_address)->first();
		$existing_user->name = $new_user->name;
		$existing_user->aleph_id = $new_user->aleph_id;

		# Now swap out the old stub for the existing record so that
		# going forward it references the one already in the database
		#
		# No need to return because we are using a reference to directly
		# manipulate the object
		$new_user = $existing_user;
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
