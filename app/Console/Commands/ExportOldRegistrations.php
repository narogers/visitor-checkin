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
		// Since we are using the same table space there's no need to define a 
		// new schema
		$total_rows = DB::table('registrations_old')->count();;

		// Need to iterate through results 50 at a time so we don't blow up the
		// stack
		$offset = 0;
		$rows_per_page = 50;
		$bad_records = 0;
		$registrations = [];

		while (($offset < $total_rows)) {
			$patrons = DB::table('registrations_old')->skip($offset)->take($rows_per_page)->get();

			foreach ($patrons as $p) {
				$offset++;
				$data = json_decode($p->regJSON);
				if (null == $data) {
					$this->error('WARNING: Registration ' . $p->regID . " is invalid");
					continue;
				}

				$data->name = $data->fname . " " . $data->lname;
				if (array_key_exists($data->name, $registrations)) {
					$registrations[$data->name]['count']++;
					continue;
				}

				$data->email = array_key_exists('email', $data) ? $data->email : '';
				$data->patronType = array_key_exists('patronType', $data) ? $data->patronType : '';
				
				$registrations[$data->name] = [
				    'aleph_id' => substr($data->fname, 0, 1) . "." . $data->lname,
				    'aleph_alt_id' => substr($data->fname, 0, 1) . $data->lname,
					'email' => $data->email,
					'role' => $data->patronType,
					'count' => 1
				];
			} 
		}

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
				"ID (main)",
				"ID (alternate)",
				"Email Address", 
				"Role", 
				"Registrations"]
			);
			foreach (array_keys($registrations) as $visitor) {
				fputcsv($fh, [
					$visitor,
					$registrations[$visitor]['aleph_id'],
					$registrations[$visitor]['aleph_alt_id'],
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
