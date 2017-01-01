<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 31-Dec-16
 * Time: 13:29
 */
namespace AppBundle\Controller;
require_once __DIR__ . '/../../../src/AppBundle/Entity/Client.php';
use GuzzleHttp\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    protected $_client;



    public function setUp()
    {
        parent::setUp();

        $this->_client = new Client([
            'base_uri' => 'http://localhost/egdemo/web/app_dev.php/api/client',
            'timeout'  => 2.0,
        ]);

    }


    public function testGetClient()
    {
        $response = $this->_client->request('GET', '1');
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
         }


    public function testPostClient()
    {
        $response = $this->_client->request('POST', '1', ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());
    }




}