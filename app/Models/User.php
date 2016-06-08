<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Models\Checkin;
use App\Models\Registration;
use App\Models\Role;

class User extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email_address'];

	public function role() {
		return $this->belongsTo('App\Models\Role');
	}

	public function registration() {
		return $this->hasOne('App\Models\Registration');
	}

	public function checkins() {
		return $this->hasMany('App\Models\Checkin');
	}

    /**
     * Confirm if a user's registration is complete or incomplete
     *
     * @return boolean
     */
    public function isComplete() {
      return (1 == $this->verified_user);
    }

    /**
     * @see isComplete()
     *
     * @return boolean
     */
    public function isIncomplete() {
      return (0 == $this->verified_user);
    }

	/**
	 * Generate a placeholder email address for situations where a stub is
     * needed
     *
	 * @return String
	 */
	protected function generateEmailStub() {
		$host = "cma.local";
		$address = substr(hash('md5', date('Ymd His' . microtime(true))), 0, 16);

		return "${address}@${host}";
	}
}
