<?php
namespace CMAIngalls\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VisitorsFixture
 *
 */
class VisitorsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'visitors_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'email_address' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'id_verified' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => 'Status flag that indicates whether the visitor\'s identification has been verified. This happens once after which this flag and exported_to_aleph determine which items are hidden from the list of pending registrations', 'precision' => null],
        'exported_to_aleph' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => 'Indicates if the visitor\'s information has been exported to Aleph. If this flag and id_verified are set then the record can be hidden from the pending view', 'precision' => null],
        'signature' => ['type' => 'binary', 'length' => null, 'null' => true, 'default' => null, 'comment' => 'Store the signature as a base64 encoded image within the database since it does not consume that much space and keeps everything in one neat package', 'precision' => null],
        'street_address' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'city' => ['type' => 'string', 'length' => 64, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'state' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'comment' => 'Keep only the state abbreviation', 'precision' => null, 'fixed' => null],
        'zip_code' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'telephone' => ['type' => 'string', 'length' => 24, 'null' => true, 'default' => null, 'comment' => 'Stored as a free text string such as  "(216)707-1234" or "216.707.1234" - any validation should take place on the front end', 'precision' => null, 'fixed' => null],
        'extension' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'Optional column for staff members. The value is stored as an integer (1234) rather than \'x1234\' so any visual changes should be done at a higher level if needed', 'precision' => null, 'autoIncrement' => null],
        'supervisor' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'comment' => 'Supervisor column is most useful for interns. In most cases (public, member, staff, etc) this column will be empty and consume minimal space', 'precision' => null, 'fixed' => null],
        'end_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => 'Ending date field used primarily for fellowships and interns. For other types of visitors this field could possibly to used to indicate expiration but could also be safely ignored. Once a decision is made be sure to keep this documentation up to date.', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['visitors_id'], 'length' => []],
            'email_address_UNIQUE' => ['type' => 'unique', 'columns' => ['email_address'], 'length' => []],
        ],
        '_options' => [
'engine' => 'InnoDB', 'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'visitors_id' => 1,
            'name' => 'Lorem ipsum dolor sit amet',
            'email_address' => 'Lorem ipsum dolor sit amet',
            'id_verified' => 1,
            'exported_to_aleph' => 1,
            'signature' => 'Lorem ipsum dolor sit amet',
            'street_address' => 'Lorem ipsum dolor sit amet',
            'city' => 'Lorem ipsum dolor sit amet',
            'state' => '',
            'zip_code' => 1,
            'telephone' => 'Lorem ipsum dolor sit ',
            'extension' => 1,
            'supervisor' => 'Lorem ipsum dolor sit amet',
            'end_date' => '2015-02-12'
        ],
    ];
}
