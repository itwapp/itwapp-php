<?php

class InterviewTest extends PHPUnit_Framework_TestCase {

    private static $countInterview = 0;
    private static $interviewId = null;

    static function setUpBeforeClass()  {
        Itwapp::setApiKey(getenv("itwappApiKey"));
        Itwapp::setApiSecretKey(getenv("itwappApiSecret"));
    }

    public function testFindAll()    {
        $res = Interview::findAll(array());
        $this->assertTrue(is_array($res));

        InterviewTest::$countInterview = count($res);

    }

    public function testWrongCreationMissingNameParam() {
        $array = [
            //"name" => "interview 1",
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
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongCreationMissingVideoParam() {
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
            //"video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongCreationMissingTextParam() {
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
            "video"=> ""//,
            //"text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongCreationMissingQuestionParam() {
        $array = [
            "name" => "interview 1",
            /*"questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],*/
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongCreationWrongQuestionParam() {
        $array = [
            "name" => "interview 1",
            "questions" => "RTFM ??!!!!",
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {
            $this->assertEquals("wrong param questions", $e->getMessage());
        }
    }

    public function testWrongCreationMissingParamNumberOnQuestion() {
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    //"number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongCreationMissingParamContentOnQuestion() {
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    //"content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongCreationWrongParamReadingTimeOnQuestion() {
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 72,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (InvalidRequestError $e)   {
            $this->assertRegExp("/invalid duration of readingTime or answerTime/", $e->getMessage());
        }catch (Exception $e)   {}
    }

    public function testWrongCreationWrongParamAnswerTimeOnQuestion() {
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 84,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (InvalidRequestError $e)   {
            $this->assertRegExp("/invalid duration of readingTime or answerTime/", $e->getMessage());
        }catch (Exception $e)   {}
    }

    public function testWrongCreationWithEmptyQuestion() {
        $array = [
            "name" => "interview 1",
            "questions" => [],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::create($array);
            $this->fail("Exception not thrown");
        }catch (InvalidRequestError $e)   {
            $this->assertEquals("wrong param questions", $e->getMessage());
        }catch (Exception $e)   {}
    }

    public function testCreate()    {
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
        $this->assertEquals("Interview", get_class($res));
        $this->assertTrue(isset($res->id));
        $this->assertCount(1, $res->questions);
        InterviewTest::$interviewId = $res->id;
    }

    public function testFindAllAfterCreate()    {
        $res = Interview::findAll(array());
        $this->assertTrue(is_array($res));

        $this->assertEquals(InterviewTest::$countInterview + 1, count($res));

        $res = Interview::findAll(array("next" => $res[count($res)-1]->id));
        $this->assertTrue(is_array($res));
        $this->assertEquals(0, count($res));
    }

    public function testFindOne()   {
        $this->assertNotNull(InterviewTest::$interviewId);

        $res = Interview::findOne(InterviewTest::$interviewId);

        $this->assertEquals("interview 1", $res->name);
        $this->assertEquals("", $res->video);
        $this->assertEquals("", $res->text);
        $this->assertEquals(1, count($res->questions));
        $this->assertEquals("question 1", $res->questions[0]->content);

    }

    public function testFindAllApplicant()  {
        $this->assertNotNull(InterviewTest::$interviewId);

        $res = Interview::findAllApplicant(InterviewTest::$interviewId, array());
        $this->assertEquals(0, count($res));
    }

    public function testWrongUpdateMissingNameParam() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            //"name" => "interview 1",
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
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateMissingVideoParam() {
        $this->assertNotNull(InterviewTest::$interviewId);
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
            //"video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateMissingTextParam() {
        $this->assertNotNull(InterviewTest::$interviewId);
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
            "video"=> ""//,
            //"text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateMissingQuestionParam() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            /*"questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],*/
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateWrongQuestionParam() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => "RTFM ??!!!!",
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {
            $this->assertEquals("wrong param questions", $e->getMessage());
        }
    }

    public function testWrongUpdateMissingParamNumberOnQuestion() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    //"number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateMissingParamContentOnQuestion() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    //"content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateWrongParamReadingTimeOnQuestion() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 72,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (InvalidRequestError $e)   {
            $this->assertRegExp("/invalid duration of readingTime or answerTime/", $e->getMessage());
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateWrongParamAnswerTimeOnQuestion() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1",
                    "readingTime"=> 60,
                    "answerTime"=> 84,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (InvalidRequestError $e)   {
            $this->assertRegExp("/invalid duration of readingTime or answerTime/", $e->getMessage());
        }catch (Exception $e)   {}
    }

    public function testWrongUpdateWithEmptyQuestion() {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => [],
            "video"=> "",
            "text"=> ""
        ];
        try {
            Interview::update(InterviewTest::$interviewId, $array);
            $this->fail("Exception not thrown");
        }catch (InvalidRequestError $e)   {
            $this->assertEquals("wrong param questions", $e->getMessage());
        }catch (Exception $e)   {}
    }

    public function testUpdate()    {
        $this->assertNotNull(InterviewTest::$interviewId);
        $array = [
            "name" => "interview 1",
            "questions" => [
                [
                    "content" => "question 1 - Updated",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ],
                [
                    "content" => "question 2",
                    "readingTime"=> 60,
                    "answerTime"=> 60,
                    "number"=> 1
                ]
            ],
            "video"=> "",
            "text"=> ""
        ];

        $res = Interview::update(InterviewTest::$interviewId, $array);
        $this->assertEquals("Interview", get_class($res));
        $this->assertTrue(isset($res->id));

        $this->assertCount(2, $res->questions);
        $this->assertEquals("question 1 - Updated", $res->questions[0]->content);

    }

    public function testDelete()    {
        $this->assertNotNull(InterviewTest::$interviewId);
        Interview::delete(InterviewTest::$interviewId, array());
    }

    public function testFindAllAfterDelete()    {
        $res = Interview::findAll(array());
        $this->assertTrue(is_array($res));

        $this->assertEquals(InterviewTest::$countInterview, count($res));
    }

}
 