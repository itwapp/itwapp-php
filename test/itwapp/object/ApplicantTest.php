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

    /**
     * @depends testCreateWithMissingParamMail
     */
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

    /**
     * @depends testCreateWithMissingParamAlert
     */
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

    /**
     * @depends testCreateWithMissingParamLang
     */
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

    /**
     * @depends testCreateWithMissingParamDeadLine
     */
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

    /**
     * @depends testCreateWithMissingParamInterviewAndQuestion
     */
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

    /**
     * @depends testCreateWithWrongParamQuestion
     */
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

    /**
     * @depends testCreateWithMissingParamContentOnQuestion
     */
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

    /**
     * @depends testCreateWithEmptyParamQuestion
     */
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

    /**
     * @depends testCreateWithMissingParamReadingTimeOnQuestion
     */
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

    /**
     * @depends testCreateWithWrongParamReadingTimeOnQuestion
     */
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

    /**
     * @depends testCreateWithMissingParamAnswerTimeOnQuestion
     */
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

    /**
     * @depends testCreateWithWrongParamAnswerTimeOnQuestion
     */
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

    /**
     * @depends testCreateWithMissingParamNumberOnQuestion
     */
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
        $this->assertEquals(ApplicantStatus::UNKNOW, $res->status);
        ApplicantTest::$applicantId = $res->id;
    }

    /**
     * @depends testCreateWithInterviewId
     */
    public function testFindAllApplicant()  {
        $all = Interview::findAllApplicant(ApplicantTest::$interviewId, array());

        $this->assertCount(1, $all);
        $this->assertEquals(ApplicantTest::$applicantId, $all[0]->id);

        $empty_list = Interview::findAllApplicant(ApplicantTest::$interviewId, array("next" => ApplicantTest::$applicantId));
        $this->assertCount(0, $empty_list);
    }

    /**
     * @depends testFindAllApplicant
     */
    public function testDelete() {
        $this->assertNotNull(ApplicantTest::$applicantId);

        Applicant::delete(ApplicantTest::$applicantId);

        $app = Applicant::findOne(ApplicantTest::$applicantId);
        $this->assertTrue($app->deleted);
    }

    /**
     * @depends testDelete
     */
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

        $interview = Interview::findOne($res->interview);

        $this->assertRegExp('/auto-generated.*/', $interview->name);

        Interview::delete($res->interview, array("withApplicant" => true));

        $app = Applicant::findOne($res->id);
        $this->assertTrue($app->deleted);
    }

    /**
     * @depends testCreateWithQuestion
     */
    public function testCreateWithQuestionAndInterviewName()   {
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
            "interviewName" => "My Super Interview"
        ];


        $res = Applicant::create($param);
        $this->assertEquals("jerome@itwapp.io", $res->mail);
        $this->assertFalse($res->deleted);
        $this->assertCount(1, $res->questions);

        $this->assertEquals("http://itwapp.io", $res->callback);

        $interview = Interview::findOne($res->interview);

        $this->assertEquals('My Super Interview', $interview->name);

        Interview::delete($res->interview, array("withApplicant" => true));

        $app = Applicant::findOne($res->id);
        $this->assertTrue($app->deleted);
    }

    /**
     * @depends testCreateWithQuestionAndInterviewName
     */
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
 