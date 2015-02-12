<?php
namespace CMAIngalls\Model\Entity;

use Cake\ORM\Entity;

/**
 * Visitor Entity.
 */
class Visitor extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'email_address' => true,
        'id_verified' => true,
        'exported_to_aleph' => true,
        'signature' => true,
        'street_address' => true,
        'city' => true,
        'state' => true,
        'zip_code' => true,
        'telephone' => true,
        'extension' => true,
        'supervisor' => true,
        'end_date' => true,
        'visitor' => true,
        'checkins' => true,
    ];
}
