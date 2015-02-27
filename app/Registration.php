<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model {
	/**
	 * Only the intermediate screen needs to be mass assignable. Everything
	 * else should be handled by explicitly setting the name, email address,
	 * signature, and so on.
	 */
	protected $guarded = [
		'name',
		'email_address',
		'role',
		'expires_on'
	];

	public function getBadgeNumberAttribute() {
		return $this->barcode;
	}
	public function setBadgeNumberAttribute($value) {
		$this->barcode = $value;
	}

	/**
	 * Convert the integer into xxxxxx-xxxx format when it comes out of the 
	 * database. Normally you would place a break between each case but since * they return the function will end anyways.
	 */
	public function getTelephoneAttribute($value) {
	    $value = strval($value);
	    switch (strlen($value)) {
	    	case 7:
	    		return preg_replace('/(\d{3])(\d{4})/', '$1-$2', $value);
	    	case 10:
	    		return preg_replace("/(\d{3})(\d{3})(\d{4])/", "$1-$2-$3", 
	    			$value);
	    	default:
	    		return $value;
	    }
	}

	/**
	 * On the flip side when saving a telephone number strip out anything that is not a digit such as '(', ')',
	 * or '-'
	 */
	public function setTelephoneAttribute($value) {
		$this->attributes['telephone'] = preg_replace('/\D/', '', $value);
	}

}
