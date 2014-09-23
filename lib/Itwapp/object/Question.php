<?php

class Question {

    /**
     * @var String the question
     */
    public $content;

    /**
     * @var Int the max duration the applicant have to think about the question. Accepted value are : 60, 120, 180, 240, 300
     */
    public $readingTime;

    /**
     * @var Int the max duration the applicant have to answer the question. Accepted value are : 60, 120, 180, 240, 300
     */
    public $answerTime;

    /**
     * @var Int the position of the question on one interview.
     */
    public $number;

    /**
     * @param $content String
     * @param $readingTime Int
     * @param $answerTime Int
     * @param $number Int
     */
    public function __construct($content, $readingTime, $answerTime, $number)  {
        $this->content = $content;
        $this->readingTime = $readingTime;
        $this->answerTime = $answerTime;
        $this->number = $number;
    }

} 