<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Estate Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $summary
 * @property string $address
 * @property string $access
 * @property string $property_form
 * @property string $structure
 * @property string $build
 * @property string $sale_term
 * @property string $rent_term
 * @property string $patients
 * @property string $pharmacy
 * @property string $equipment
 * @property string $transaction
 * @property string $terms
 * @property string $contact
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Estate extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
