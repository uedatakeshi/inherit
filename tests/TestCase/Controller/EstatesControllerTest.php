<?php
namespace App\Test\TestCase\Controller;

use App\Controller\EstatesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Cake\I18n\Time;

/**
 * App\Controller\EstatesController Test Case
 */
class EstatesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.estates'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/estates?page=1');

        $this->assertResponseOk();
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->get('/estates/view/1');

        $this->assertResponseOk();
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $data = [
            'name' => '新物件 新宿',
        ];
        $this->post('/estates/add', $data);

        $this->assertResponseSuccess();

        $articles = TableRegistry::get('Estates');
        $query = $articles->find()->where(['name' => $data['name']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->get('/estates/edit/1');

        $this->assertResponseOk();
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->post('/estates/delete/1');

        $this->assertResponseSuccess();
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testJson()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $result = $this->get('/estates/view/1.json');

        $this->assertResponseOk();

        $expected = [
            'estate' =>
            ['id' => 1,
            'name' => '国分寺ビル',
            'subject' => '内科',
            'summary' => 'サマリー',
            'address' => '東京',
            'access' => '交通',
            'property_form' => '',
            'structure' => '',
            'build' => '',
            'sale_term' => '',
            'rent_term' => '',
            'patients' => '',
            'pharmacy' => '',
            'equipment' => '',
            'transaction' => '',
            'terms' => '',
            'contact' => '',
            'created' => new Time('2015-10-15 06:35:43'),
            'modified' => new Time('2015-10-15 06:35:43')]
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, $this->_response->body());
    }
}
