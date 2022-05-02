<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Faker\Factory;
class StudentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_student_request()
    {
        $facker= Factory::create();
        $school= \App\Models\School::first();
    Session::start();
    $response = $this->call('POST', 'api/students/join', array(
        'name'=>$facker->name(),
        'email'=> time().$facker->email,
        'school_id'=>$school->id,
        'password'=>'123456'
    ));
    $this->assertEquals(200, $response->getStatusCode());
    }
    function test_get_students(){
                $school= \App\Models\School::first();
$response = $this->call('POST', 'api/login', array(
        
        'email'=> 'admin@backend.com',
       
        'password'=>'12345678'
    ));
$access_token=$response->getData()->data->access_token;
$response= $this->withHeaders([
            'Authorization' => 'Bearer '. $access_token,
        ])->json('GET','api/students/get', array('school_id'=>$school->id));
    $this->assertEquals(200, $response->getStatusCode());

    }
}
