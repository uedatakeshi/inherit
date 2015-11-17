<?php
namespace App\Model\Table;

use App\Model\Entity\Estate;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Estates Model
 *
 */
class EstatesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('estates');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('name');

        $validator
            ->allowEmpty('subject');

        $validator
            ->allowEmpty('summary');

        $validator
            ->allowEmpty('address');

        $validator
            ->allowEmpty('access');

        $validator
            ->allowEmpty('property_form');

        $validator
            ->allowEmpty('structure');

        $validator
            ->allowEmpty('build');

        $validator
            ->allowEmpty('sale_term');

        $validator
            ->allowEmpty('rent_term');

        $validator
            ->allowEmpty('patients');

        $validator
            ->allowEmpty('pharmacy');

        $validator
            ->allowEmpty('equipment');

        $validator
            ->allowEmpty('transaction');

        $validator
            ->allowEmpty('terms');

        $validator
            ->allowEmpty('contact');

        return $validator;
    }
}
