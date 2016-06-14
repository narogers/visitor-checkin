<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\ILS\ILSInterface;
use App\Repositories\PatronInterface;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportAlephRecords extends Command {

	protected $name = 'ils:import';
	protected $description = 'Import records from ILS into the local database';

    /**
     * Interfaces to underlying services
     */
    protected $ils;
    protected $patrons;
    
	/**
	 * Create a new command instance. The list of Aleph IDs to resolve
	 * should be passed as a flat text with one value per line. Any valid
	 * IDs will be automatically imported into the system.
	 *
	 * @return void
	 */
	public function __construct(ILSInterface $ils, PatronInterface $patrons)
	{
	  $this->ils = $ils;
      $this->patrons = $patrons;
      parent::__construct();
	}

	public function getArguments() {
	  return [['source', InputArgument::REQUIRED, "List of IDs from ILS"]];
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire() {
	  $ils_ids = file($this->argument('source'), FILE_IGNORE_NEW_LINES);
	  foreach ($ils_ids as $id) {
		$user_information = $ils->getPatronDetails($id);
        $user_information["aleph_id"] = $id;
        $user_information["signature"] = "";
        $user_information["verified_user"] = true;

		if (null == $user["name"]) {
		  $this->error("[ERROR] Could not resolve " . $aleph_id);
		  continue;
		}

        $user = $this->patrons->createOrFindUser($user_information);
        if ($user) {
          // Push the rest of the properties and save again
          $this->patrons->update($user->id, $user_information);
        }        

		$this->info('[SUCCESS] ' . $aleph_id . ' has been imported into the local database');
	  }
   }
}
