<?php namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OldRegistration {

	/**
	 * @return void
	 */
	public function __construct()
	{
		$conn = DB::Connection('mysql');
	}

	public function count() {
		return DB::table('registrations_old')->count();
	}

	/**
	 * Because of the load this does not attempt to return everything.
	 * Instead you get a small snapshot of the user name, email address,
	 * and from there you can build up the rest.
	 */
	public function getUsers() {

		// Need to iterate through results 50 at a time so we don't blow up 
		// the stack
		$total_rows = $this->count();
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
					Log::warning('WARNING: Registration ' . $p->regID . " is invalid");
					continue;
				}

				$data->name = $data->fname . " " . $data->lname;
				if (array_key_exists($data->name, $registrations)) {
					$registrations[$data->name]['count']++;
				} else {
					$data->email = array_key_exists('email', $data) ? $data->email : '';

					$registrations[$data->name] = [
					'email' => $data->email,
					'count' => 1
					];
				}
			}
		}

		return $registrations;
	}
}		
?>
