<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExportOldCheckins extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'checkins:export-old';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Dump details about old checkins to a CSV file';

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
		$total_rows = DB::table('checkins_old')->count();

		// Need to iterate through results 50 at a time so we don't blow up the
		// stack
		$offset = 0;
		$rows_per_page = 100;
		$bad_records = 0;
		$checkins = [];

		while (($offset < $total_rows)) {
			$old_checkins = DB::table('checkins_old')->skip($offset)->take($rows_per_page)->get();

			foreach ($old_checkins as $c) {
				$offset++;

				$c->name = $c->patronName;
				if (!array_key_exists($c->name, $checkins)) {
					$checkins[$c->name]['checkins'] = [];
					$checkins[$c->name]['role'] = $c->patronType;
				}
				array_push($checkins[$c->name]['checkins'], $c->dateLog);
			} 
		}

		/** 
		 * Now export to the file. The format will be
		 *
	 	 * Name, Role, Checked In At
	 	 * "Jane Smith", "Academic", "2014-02-12 10:12:32"
	 	 */ 
		$filename = $this->argument('filename');
		$fh = fopen($filename, "w");
		if ($fh) {
			fputcsv($fh, [
				"Name", 
				"Role", 
				"Check Ins"
				]
			);
			foreach (array_keys($checkins) as $checkin) {
				fputcsv($fh, [
					$checkin,
					$checkins[$checkin]['role'],
					sizeof($checkins[$checkin]['checkins'])
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
		$this->info($total_rows . " total checkins processed");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['filename', InputArgument::OPTIONAL, 'File to export to', 'oldCheckins.csv'] 
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
