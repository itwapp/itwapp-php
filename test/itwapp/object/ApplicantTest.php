<?php

class ApplicantTest extends PHPUnit_Framework_TestCase {

    private static $interviewId;
    private static $applicantId = null;

    static function setUpBeforeClass()  {
        Itwapp::setApiKey(getenv("itwappApiKey"));
        Itwapp::setApiSecretKey(getenv("itwappApiSecret"));

        //Create an interview
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

        $res = Interview::create($array);
        ApplicantTest::$interviewId = $res->id;
    }

    static function tearDownAfterClass()    {
        // Delete the interview
        Interview::delete(ApplicantTest::$interviewId, array("withApplicant" => true));
    }

    public function testCreateWithMissingParamMail()    {
        $param = [
            //"mail" => "jerome@itwapp.io",
            "alert" => false,
            "interview" => "53fb562418060018063095dd",
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: mail", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamAlert()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            //"alert" => false,
            "interview" => "53fb562418060018063095dd",
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: alert", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamLang()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "interview" => "53fb562418060018063095dd",
            //"lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: lang", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamDeadLine()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "interview" => "53fb562418060018063095dd",
            "lang" => "en"
            //"deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: deadline", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamInterviewAndQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            //"interview" => "53fb562418060018063095dd",
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: interview and questions", $ex->getMessage());
        }
    }

    public function testCreateWithWrongParamQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "question" => "",
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: interview and questions", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamContentOnQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    //"content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: content", $ex->getMessage());
        }
    }

    public function testCreateWithEmptyParamQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [],
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: interview and questions", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamReadingTimeOnQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    "content" => "question 1",
                    //"readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: readingTime", $ex->getMessage());
        }
    }

    public function testCreateWithWrongParamReadingTimeOnQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 72,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("invalid duration of readingTime or answerTime", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamAnswerTimeOnQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    //"answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: answerTime", $ex->getMessage());
        }
    }

    public function testCreateWithWrongParamAnswerTimeOnQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 45,
                    "number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("invalid duration of readingTime or answerTime", $ex->getMessage());
        }
    }

    public function testCreateWithMissingParamNumberOnQuestion()    {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60
                    //"number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568
        ];

        try{
            Applicant::create($param);
            $this->fail("Exception not thrown");
        }catch (Exception $ex){
            $this->assertEquals("missing param: number", $ex->getMessage());
        }
    }

    public function testCreateWithInterviewId() {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "interview" => ApplicantTest::$interviewId,
            "lang" => "en",
            "deadline" => 1409045626568
        ];


        $res = Applicant::create($param);
        $this->assertEquals("jerome@itwapp.io", $res->mail);
        $this->assertFalse($res->deleted);
        ApplicantTest::$applicantId = $res->id;
    }

    public function testDelete() {
        $this->assertNotNull(ApplicantTest::$applicantId);

        Applicant::delete(ApplicantTest::$applicantId);

        $app = Applicant::findOne(ApplicantTest::$applicantId);
        $this->assertTrue($app->deleted);
    }

    public function testFindAllApplicant()  {
        $all = Interview::findAllApplicant(ApplicantTest::$interviewId, array());

        $this->assertCount(1, $all);
        $this->assertEquals(ApplicantTest::$applicantId, $all[0]->id);

        $empty_list = Interview::findAllApplicant(ApplicantTest::$interviewId, array("next" => ApplicantTest::$applicantId));
        $this->assertCount(0, $empty_list);
    }

    public function testCreateWithQuestion()   {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568
        ];


        $res = Applicant::create($param);
        $this->assertEquals("jerome@itwapp.io", $res->mail);
        $this->assertFalse($res->deleted);
        $this->assertCount(1, $res->questions);

        $this->assertEquals("http://itwapp.io", $res->callback);

        Interview::delete($res->interview, array("withApplicant" => true));

        $app = Applicant::findOne($res->id);
        $this->assertTrue($app->deleted);
    }

    public function testCreateWithCallback()   {
        $param = [
            "mail" => "jerome@itwapp.io",
            "alert" => false,
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "lang" => "en",
            "deadline" => 1409045626568,
            "callback" => "http://mycustomeurl.com/done"
        ];


        $res = Applicant::create($param);
        $this->assertEquals("jerome@itwapp.io", $res->mail);
        $this->assertFalse($res->deleted);
        $this->assertCount(1, $res->questions);

        $this->assertEquals("http://mycustomeurl.com/done", $res->callback);

        Interview::delete($res->interview, array("withApplicant" => true));

        $app = Applicant::findOne($res->id);
        $this->assertTrue($app->deleted);
    }

}
 