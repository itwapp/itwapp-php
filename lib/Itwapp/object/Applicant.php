<?php

class Applicant {

    /**
     * @var string the unique identifier of the applicant.
     */
    public $id;

    /**
     * @var string mail of the applicant.
     */
    public $mail;

    /**
     * @var Question[] list of question send to the applicant.
     */
    public $questions;

    /**
     * @var Response[] list of response video for each question.
     */
    public $responses;

    /**
     * @var string the identifier of the associated interview.
     */
    public $interview;

    /**
     * @var int (timestamp in millisecond) the date when the interview was sent to the applicant.
     */
    public $dateBegin;

    /**
     * @var
     */
    public $dateEnd;

    /**
     * @var int (timestamp in millisecond) the date when the applicant have respond to the interview. If he don’t have answer dateAnswer equal to 0.
     */
    public $dateAnswer;

    /**
     * @var boolean specify if the applicant have view the invitation mail. It will use only if mail was send to the applicant see applicant creation
     */
    public $emailView;

    /**
     * @var boolean specify id the applicant have click to the link inside the invitation mail. It will use only if mail was send to the applicant see applicant creation
     */
    public $linkClicked;

    /**
     * @var string the firstname of the applicant.
     */
    public $firstname;

    /**
     * @var string the lastname of the applicant.
     */
    public $lastname;

    /**
     * @var string the language set for the interview.
     */
    public $lang;

    /**
     * @var string the video associated to the interview.
     */
    public $videoLink;

    /**
     * @var string the short description associated to the interview.
     */
    public $text;

    /**
     * @var boolean say if the applicant was deleted.
     */
    public $deleted;

    public function __construct($id, $mail, $questions, $responses, $interview, $dateBegin, $dateEnd, $dateAnswer, $emailView, $linkClicked, $firstname, $lastname, $lang, $videoLink, $text, $deleted) {
        $this->id = $id;
        $this->dateAnswer = $dateAnswer;
        $this->dateBegin = $dateBegin;
        $this->dateEnd = $dateEnd;
        $this->deleted = $deleted;
        $this->emailView = $emailView;
        $this->firstname = $firstname;
        $this->interview = $interview;
        $this->lang = $lang;
        $this->lastname = $lastname;
        $this->linkClicked = $linkClicked;
        $this->mail = $mail;
        $this->questions = $questions;
        $this->responses = $responses;
        $this->text = $text;
        $this->videoLink = $videoLink;
    }

    /**
     * This method retrieves a specific applicant.
     * @param $id string
     * @return Applicant
     */
    public static function findOne($id) {
        $app = ApiRequest::get("/api/v1/applicant/".$id);
        $questions = array();
        foreach($app["questions"] as $q)    {
            $questions[] = new Question($q["content"], $q["readingTime"], $q["answerTime"], $q["number"]);
        }

        $responses = array();
        foreach($app["responses"] as $q)    {
            $responses[] = new Response($q["file"], $q["duration"], $q["fileSize"], $q["number"]);
        }

        return new Applicant($app["_id"], $app["mail"], $questions, $responses, $app["interview"], $app["dateBegin"], $app["dateEnd"], $app["dateAnswer"], $app["emailView"], $app["linkClicked"], $app["firstname"], $app["lastname"], $app["lang"], $app["videoLink"], $app["text"], $app["deleted"]);
    }

    private static $acceptedValue = array(60, 120, 180, 240, 300);

    /**
     * This endpoint create an interview. It return the new interview if success.
     * @param $param string[]
     * @throws InvalidRequestError
     * @return Applicant
     */
    public static function create($param)   {
        if(!isset($param["mail"]) || !isset($param["alert"]) || !isset($param["lang"]) || !isset($param["deadline"]))  {
            $missing = array();
            !isset($param["mail"]) ? $missing[] = "mail" : null;
            !isset($param["alert"]) ? $missing[] = "alert" : null;
            !isset($param["lang"]) ? $missing[] = "lang" : null;
            !isset($param["deadline"]) ? $missing[] = "deadline" : null;
            throw new InvalidRequestError("missing param: ".implode($missing, ", "));
        }

        if(isset($param["questions"]) && is_array($param["questions"]) && count($param["questions"]) != 0 )    {
            foreach($param["questions"] as $q) {
                if (!isset($q["content"]) || !isset($q["readingTime"]) || !isset($q["answerTime"]) || !isset($q["number"])) {
                    $missing = array();

                    if (!isset($q["content"]))
                        $missing[] = "content";
                    if (!isset($q["readingTime"]))
                        $missing[] = "readingTime";
                    if (!isset($q["answerTime"]))
                        $missing[] = "answerTime";
                    if (!isset($q["number"]))
                        $missing[] = "number";

                    throw new InvalidRequestError("missing param: " . implode($missing, ", "));
                }else if(!in_array($q["readingTime"], Applicant::$acceptedValue) || !in_array($q["answerTime"], Applicant::$acceptedValue) )  {
                    throw new InvalidRequestError("invalid duration of readingTime or answerTime");
                }
            }
        }else if(!isset($param["interview"])){
            throw new InvalidRequestError("missing param: interview and questions");
        }

        $app = ApiRequest::post("/api/v1/applicant/", $param);
        $questions = array();
        foreach($app["questions"] as $q)    {
            $questions[] = new Question($q["content"], $q["readingTime"], $q["answerTime"], $q["number"]);
        }

        $responses = array();
        foreach($app["responses"] as $q)    {
            $responses[] = new Response($q["file"], $q["duration"], $q["fileSize"], $q["number"]);
        }

        return new Applicant($app["_id"], $app["mail"], $questions, $responses, $app["interview"], $app["dateBegin"], $app["dateEnd"], $app["dateAnswer"], $app["emailView"], $app["linkClicked"], $app["firstname"], $app["lastname"], $app["lang"], $app["videoLink"], $app["text"], $app["deleted"]);
    }

    /**
     * this endpoint delete a specific applicant.
     * @param $id string the ID of the applicant to delete
     * @return bool
     */
    public static function delete($id)  {
        $result = ApiRequest::delete("/api/v1/applicant/".$id);
        return $result != null;
    }

} 