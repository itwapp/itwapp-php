<?php
class ApiRequestTest extends PHPUnit_Framework_TestCase {

    /**
     * @var string
     */
    private static $interviewId = null;

    static function setUpBeforeClass()  {
        Itwapp::setApiKey(getenv("itwappApiKey"));
        Itwapp::setApiSecretKey(getenv("itwappApiSecret"));
    }

    public function testSignRequestWithoutQueryStringParam()   {

        $method = new ReflectionMethod('ApiRequest', 'sign_request');
        $method->setAccessible(true);
        $urlSigned = $method->invokeArgs(null, array("GET", "/api/v1/test/"));

        $this->assertRegExp("/\/api\/v1\/test\/\?apiKey=.*&timestamp=.*&signature=.*/", $urlSigned);
    }

    public function testSignRequestWithQueryStringParam()   {
        $method = new ReflectionMethod('ApiRequest', 'sign_request');
        $method->setAccessible(true);
        $urlSigned = $method->invokeArgs(null, array("GET", "/api/v1/test/?foo=bar"));

        $this->assertRegExp("/\/api\/v1\/test\/\?foo=bar&apiKey=.*&timestamp=.*&signature=.*/", $urlSigned);
    }

    public function testParseResultWithUnauthorizedException()   {
        $method = new ReflectionMethod('ApiRequest', 'parse_result');
        $method->setAccessible(true);

        $client = new GuzzleHttp\Client();
        $res = $client->get(Itwapp::$apiBase."/api/v1/applicant/12", [
            'exceptions' => false
        ]);

        // Service should respond not authorized
        $this->assertEquals(401, $res->getStatusCode());

        try {
            $method->invokeArgs(null, array($res));
            $this->fail();
        }catch(UnauthorizedException $exc)  {
        }catch(Exception $exc)   {
            $this->fail();
        }

    }

    public function testParseResultWithNotFoundException()   {
        $method = new ReflectionMethod('ApiRequest', 'parse_result');
        $method->setAccessible(true);

        $client = new GuzzleHttp\Client();
        $res = $client->get(Itwapp::$apiBase."/api/v1/not_found_page", [
            'exceptions' => false
        ]);

        // Service should respond not authorized
        $this->assertEquals(404, $res->getStatusCode());

        try {
            $method->invokeArgs(null, array($res));
            $this->fail();
        }catch(ResourceNotFoundException $exc)  {
        }catch(Exception $exc)   {
            $this->fail();
        }

    }

    public function testParseResultWithBadRequestException()   {
        $method = new ReflectionMethod('ApiRequest', 'sign_request');
        $method->setAccessible(true);
        $urlSigned = $method->invokeArgs(null, array("POST", "/api/v1/applicant/"));

        $this->assertRegExp("/\/api\/v1\/applicant\/\?apiKey=.*&timestamp=.*&signature=.*/", $urlSigned);

        $client = new GuzzleHttp\Client();
        $res = $client->post(Itwapp::$apiBase.$urlSigned, [
            'exceptions' => false,
            'json' => array()
        ]);

        $method = new ReflectionMethod('ApiRequest', 'parse_result');
        $method->setAccessible(true);
        // Service should respond not authorized
        $this->assertEquals(400, $res->getStatusCode());

        try {
            $method->invokeArgs(null, array($res));
            $this->fail();
        }catch(InvalidRequestError $exc)  {
        }catch(Exception $exc2)   {
            $this->fail("Need an InvalidRequestError exception but found: ".get_class($exc2));
        }

    }

    public function testParseResultWithNormalResult()   {
        $method = new ReflectionMethod('ApiRequest', 'sign_request');
        $method->setAccessible(true);
        $urlSigned = $method->invokeArgs(null, array("GET", "/api/v1/interview/"));

        $this->assertRegExp("/\/api\/v1\/interview\/\?apiKey=.*&timestamp=.*&signature=.*/", $urlSigned);

        $client = new GuzzleHttp\Client();
        $res = $client->get(Itwapp::$apiBase.$urlSigned, [
            'exceptions' => false
        ]);

        $method = new ReflectionMethod('ApiRequest', 'parse_result');
        $method->setAccessible(true);
        // Service should respond not authorized
        $this->assertEquals(200, $res->getStatusCode());

        try {
            $result = $method->invokeArgs(null, array($res));
            $this->assertTrue(is_array($result));
        }catch(Exception $exc2)   {
            $this->fail();
        }

    }

    public function testGetRequest()    {
        $res = ApiRequest::get("/api/v1/interview/");
        $this->assertTrue(is_array($res));
    }

    public function testPostRequest()    {
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];

        $res = ApiRequest::post("/api/v1/interview/", $array);
        $this->assertTrue(is_array($res));

        $this->assertTrue(isset($res["_id"]));
        ApiRequestTest::$interviewId = $res["_id"];
    }

    public function testPutRequest()    {
        $this->assertNotNull(ApiRequestTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1 - Updated",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];

        $res = ApiRequest::put("/api/v1/interview/".ApiRequestTest::$interviewId, $array);
        $this->assertTrue(is_array($res));
    }

    public function testDeleteRequest()    {
        $this->assertNotNull(ApiRequestTest::$interviewId);
        $res = ApiRequest::delete("/api/v1/interview/".ApiRequestTest::$interviewId);
        $this->assertNull($res);
    }

}
 