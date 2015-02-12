<?php
namespace CMAIngalls\Model\Table;

use CMAIngalls\Model\Entity\Visitor;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Visitors Model
 */
class VisitorsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('visitors');
        $this->displayField('name');
        $this->primaryKey('visitors_id');
        $this->hasMany('Checkins', [
            'foreignKey' => 'visitor_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('visitors_id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('visitors_id', 'create')
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->requirePresence('email_address', 'create')
            ->notEmpty('email_address')
            ->add('id_verified', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('id_verified')
            ->add('exported_to_aleph', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('exported_to_aleph')
            ->allowEmpty('signature')
            ->allowEmpty('street_address')
            ->allowEmpty('city')
            ->allowEmpty('state')
            ->add('zip_code', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('zip_code')
            ->allowEmpty('telephone')
            ->add('extension', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('extension')
            ->allowEmpty('supervisor')
            ->add('end_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('end_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['visitors_id'], 'Visitors'));
        return $rules;
    }
}
