<?php
namespace App\Model\Table;

use App\Model\Entity\Estate;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use App\Event\UploadFilenameListener;

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
        $this->addBehavior('Proffer.Proffer', [
            'photo' => [    // The name of your upload field
                'root' => WWW_ROOT . 'files', // Customise the root upload folder here, or omit to use the default
                'dir' => 'photo_dir',   // The name of the field to store the folder
                'thumbnailSizes' => [ // Declare your thumbnails
                    'square' => [   // Define the prefix of your thumbnail
                        'w' => 200, // Width
                        'h' => 200, // Height
                        'crop' => true,  // Crop will crop the image as well as resize it
                        'jpeg_quality'  => 100,
                        'png_compression_level' => 9
                    ],
                    'portrait' => [     // Define a second thumbnail
                        'w' => 100,
                        'h' => 300
                    ],
                ],
                'thumbnailMethod' => 'gd'  // Options are Imagick, Gd or Gmagick
            ]
        ]);
        $listener = new UploadFilenameListener();
        $this->eventManager()->on($listener);
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
