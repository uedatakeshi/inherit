<?php
namespace App\Test\TestCase\Controller;

use App\Controller\EstatesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Cake\I18n\Time;

/**
 * App\Controller\UploadsController Test Case
 */
class UploadsControllerTest extends IntegrationTestCase
{

    /**
     * Test upload method
     *
     * @return void
     */
    public function testUpload()
    {
        $data = [
            'files' => [
                'name' => 'small.jpg',
                'type' => 'image/jpeg',
                'size' => 15805,
                'tmp_name' => __DIR__ . '/_files/small.jpg',
                'error' => 0
            ]
        ];
        $this->post('/uploads/upload', $data);

        $this->assertResponseOk();
        $expected = [
            'data' =>
            [
                'name' => 'small.jpg',
                'type' => 'image/jpeg',
                'size' => 15805,
                'tmp_name' => __DIR__ . '/_files/small.jpg',
                'error' => 0
            ]
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, $this->_response->body());
    }

    /*
    public function testUpload()
    {
        $data = [
            'submittedfile' => [
                'name' => 'small.jpg',
                'type' => 'image/jpeg',
                'size' => 15805,
                'tmp_name' => __DIR__ . '/_files/small.jpg',
                'error' => 0
            ]
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/estates/upload.json', $data);

        $this->assertResponseOk();

        $expected = [
            'data' =>
            [
                'name' => 'small.jpg',
                'type' => 'image/jpeg',
                'size' => 15805,
                'tmp_name' => __DIR__ . '/_files/small.jpg',
                'error' => 0
            ]
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, $this->_response->body());
    }
     */

    /**
     * Test add method
     *
     * @return void
     */
    /*
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
     */
    /*
    public function testUpload()
    {
        $data = [
            'submittedfile' => [
                'name' => 'small.jpg',
                'type' => 'image/jpeg',
                'size' => 15805,
                'tmp_name' => __DIR__ . '/_files/small.jpg',
                'error' => 0
            ]
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/estates/upload.json', $data);

        $this->assertResponseOk();

        $expected = [
            'data' =>
            [
                'name' => 'small.jpg',
                'type' => 'image/jpeg',
                'size' => 15805,
                'tmp_name' => __DIR__ . '/_files/small.jpg',
                'error' => 0
            ]
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, $this->_response->body());
    }

    public function testLargeUpload()
    {
        $data = [
            'submittedfile' => [
                'name' => '233472093_1f1d235e7b_o.jpg',
                'type' => 'image/jpeg',
                'size' => 5480110,
                'tmp_name' => __DIR__ . '/_files/233472093_1f1d235e7b_o.jpg',
                'error' => 0
            ]
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/estates/upload.json', $data);

        $this->assertResponseOk();

        $expected = [
            'data' =>
            [
                'name' => '233472093_1f1d235e7b_o.jpg',
                'type' => 'image/jpeg',
                'size' => 5480110,
                'tmp_name' => __DIR__ . '/_files/233472093_1f1d235e7b_o.jpg',
                'error' => 0
            ]
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertEquals($expected, $this->_response->body());
    }
     */
}
