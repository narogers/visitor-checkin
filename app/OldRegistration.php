<?php namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OldRegistration {

	/**
	 * @return void
	 */
	public function __construct()
	{
		// TODO: See if this is needed or not
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
				if ((null == $data->fname) or
					(null == $data->lname)) {
					Log::warning('WARNING: Record contains no valid data');
					continue;
				}

				$data->name = $data->fname . " " . $data->lname;

				if (array_key_exists($data->name, $registrations)) {
					$registrations[$data->name]['count']++;
				} else {
					$data->email = array_key_exists('email', $data) ? $data->email : '';

					$registrations[$data->name] = [
					  'id' => $p->regID,
					  'email' => $data->email,
					  'role' => $data->patronType,
					  'count' => 1
					];
				}
			}
		}

		return $registrations;
	}

	/**
	 * Resolves an ID into a Registration object. This is not
	 * completely well formed because it only takes transforms the
	 * JSON and does not actually do any further validation.
	 */
	public function find($id) {
		$record = DB::table('registrations_old')->where('regID', $id)->first();
		if (null == $record) {
			Log::info('WARNING: Registration ' . $id . " could not be resolved");
			return null;
		}

		$registration = json_decode($record->regJSON);
		if (null == $registration) {
			Log::warning('WARNING: Registration details for ' . $p->regID . " are invalid");
			return null;
		}
		$registration->signature = $registration->{'image/png;base64'};
		unset($registration->{'image/png;base64'});

		// Otherwise we are in the clear
		return $registration;
	}
}		
?>
