<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExportOldRegistrations extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'registrations:export-old';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Dump details about old registrations to a CSV file';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$conn = DB::Connection('mysql');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$oldRegistrations = new oldRegistrations;

		$archivedRegistrations = $oldRegistrations->getUsers();

		/** 
		 * Now export to the file. The format will be
		 *
	 	 * Name, Email Address, Role, Registrations
	 	 * "Foo Bar", foo@bar.com, Staff, 4
	 	 */ 
		$filename = $this->argument('filename');
		$fh = fopen($filename, "w");
		if ($fh) {
			fputcsv($fh, [
				"Name", 
				"Aleph ID",
				"Email Address", 
				"Role", 
				"Registrations"]
			);
			foreach (array_keys($registrations) as $visitor) {
				fputcsv($fh, [
					$visitor,
					$registrations[$visitor]['aleph_id'],
					$registrations[$visitor]['email'],
					$registrations[$visitor]['role'],
					$registrations[$visitor]['count']
					]
				);
			}
			fclose($fh);
		} else {
			$this->error("WARNING: Could not open " . $filename . " for writing");
		}

		// Summarize what happened
		$this->info("SUMMARY");
		$this->info("Report has been exported to " . $filename);
		$this->info($bad_records . " invalid records skipped");
		$this->info(sizeof($registrations) . " unique registrations found");
		$this->info($total_rows . " total registrations examined");
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
