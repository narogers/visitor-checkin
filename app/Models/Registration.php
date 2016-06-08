<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model {
	use SoftDeletes;

	protected $fillable = [
		'address_city',
		'address_street',
		'address_zip',
		/**
		 * Contact information
		 */
		'telephone',
		'extension',
		/**
		 * Internal registrations only
		 */
		'department',
		'job_title',
		'supervisor',
		'ending_on',
		/**
		 * Lookup details for members
		 */
		'badge_number'
	];

	public function user() {
		return $this->belongsTo('App\Models\User');
	}
	
	/**
	 * Convert the integer into xxxxxx-xxxx format when it comes out of the 
	 * database. 
     *
     * @param $value
     * @return string $telephone
	 */
	public function getTelephoneAttribute($value) {
	    $value = strval($value);
	    switch (strlen($value)) {
	    	case 7:
	    		return preg_replace('/(\d{3})(\d{4})/', '$1-$2', $value);
	    	case 10:
	    		return preg_replace("/(\d{3})(\d{3})(\d{4})/", "($1)$2-$3", 
	    			$value);
	    	default:
	    		return $value;
	    }
	}

	/**
	 * Sets the telephone attribute
     *
     * @param $value
	 */
	public function setTelephoneAttribute($value) {
		$this->attributes['telephone'] = preg_replace("/\D/", "", $value);
	}
}
