<?php

class Interview {

    /**
     * @var string the unique identifier of the interview
     */
    public $id;

    /**
     * @var string the human readable name of this interview
     */
    public $name;

    /**
     * @var Question[]
     */
    public $questions;

    /**
     * @var string This is the link of a youtube video. It will be seen by the candidate before the interview start.
     */
    public $video;

    /**
     * @var string a short description at the same time of the youtube video.
     */
    public $text;

    /**
     * @var string the url where the candidate will be redirected at the end of the interview.
     */
    public $callback;

    /**
     * @param $id
     * @param $name
     * @param $questions
     * @param $video
     * @param $text
     * @param string $callback
     */
    public function __construct($id, $name, $questions, $video, $text, $callback = "http://itwapp.io")    {
        $this->id = $id;
        $this->name = $name;
        $this->questions = $questions;
        $this->video = $video;
        $this->text = $text;
        $this->callback = $callback;
    }

    /**
     * This method retrieves all interview. The interviews are returned sorted by creation date, with the most recently created interviews appearing first.
     * @param $param string[]
     * @return Interview[]
     */
    public static function findAll($param)  {

        $result = null;
        if(isset($param["next"]))   {
            $result = ApiRequest::get("/api/v1/interview/?next=".$param["next"]);
        }else{
            $result = ApiRequest::get("/api/v1/interview/");
        }

        $all = array();
        foreach($result as $itw)    {
            $questions = array();
            foreach($itw["questions"] as $q)    {
                $questions[] = new Question($q["content"], $q["readingTime"], $q["answerTime"], $q["number"]);
            }
            $all[] = new Interview($itw["_id"], $itw["name"], $questions, $itw["video"], $itw["text"], $itw["callback"]);
        }
        return $all;
    }

    /**
     * This method retrieves a specific interview.
     * @param $id string
     * @return Interview
     */
    public static function findOne($id) {
        $itw = ApiRequest::get("/api/v1/interview/".$id);
        $questions = array();
        foreach($itw["questions"] as $q)    {
            $questions[] = new Question($q["content"], $q["readingTime"], $q["answerTime"], $q["number"]);
        }
        return new Interview($itw["_id"], $itw["name"], $questions, $itw["video"], $itw["text"], $itw["callback"]);
    }

    /**
     * @param $id
     * @param $param string[]
     * @return Applicant[]
     */
    public static function findAllApplicant($id, $param)    {
        $result = null;
        if(isset($param["next"]))   {
            $result = ApiRequest::get("/api/v1/interview/".$id."/applicant?next=".$param["next"]);
        }else{
            $result = ApiRequest::get("/api/v1/interview/".$id."/applicant");
        }

        $all = array();
        foreach($result as $app)    {

            $questions = array();
            foreach($app["questions"] as $q)    {
                $questions[] = new Question($q["content"], $q["readingTime"], $q["answerTime"], $q["number"]);
            }

            $responses = array();
            foreach($app["responses"] as $q)    {
                $responses[] = new Response($q["file"], $q["duration"], $q["fileSize"], $q["number"]);
            }

            $all[] = new Applicant($app["_id"], $app["mail"], $questions, $responses, $app["interview"], $app["dateBegin"], $app["dateEnd"], $app["dateAnswer"], $app["emailView"], $app["linkClicked"], $app["firstname"], $app["lastname"], $app["lang"], $app["videoLink"], $app["text"], $app["deleted"], $app["callback"], $app["status"]);
        }
        return $all;
    }

    private static $acceptedValue = array(60, 120, 180, 240, 300);

    /**
     * This endpoint create an interview. It return the new interview if success.
     * @param $param string[]
     * @throws InvalidRequestError
     * @return Interview
     */
    public static function create($param)   {
        if(!isset($param["name"]) || !isset($param["questions"]) || !isset($param["video"]) || !isset($param["text"]))  {
            $missing = array();

            !isset($param["name"]) ? $missing[] = "name" : null;
            !isset($param["questions"]) ? $missing[] = "questions" : null;
            !isset($param["video"]) ? $missing[] = "video" : null;
            !isset($param["text"]) ? $missing[] = "text" : null;

            throw new InvalidRequestError("missing param: ".implode($missing, ", "));
        }else if(!is_array($param["questions"]) || count($param["questions"]) == 0)    {
            throw new InvalidRequestError("wrong param questions");
        }

        foreach ($param["questions"] as $questions) {
            if(!isset($questions["content"]) || !isset($questions["readingTime"]) || !isset($questions["answerTime"]) || !isset($questions["number"]))  {
                $missing = array();

                !isset($param["content"]) ? $missing[] = "content" : null;
                !isset($param["readingTime"]) ? $missing[] = "readingTime" : null;
                !isset($param["answerTime"]) ? $missing[] = "answerTime" : null;
                !isset($param["number"]) ? $missing[] = "number" : null;

                throw new InvalidRequestError("missing param: ".implode($missing, ", "));
            }else if(!in_array($questions["readingTime"], Interview::$acceptedValue) || !in_array($questions["answerTime"], Interview::$acceptedValue) )  {
                throw new InvalidRequestError("invalid duration of readingTime or answerTime");
            }
        }

        $itw = ApiRequest::post("/api/v1/interview/", $param);
        $questions = array();
        foreach($itw["questions"] as $q)    {
            $questions[] = new Question($q["content"], $q["readingTime"], $q["answerTime"], $q["number"]);
        }
        return new Interview($itw["_id"], $itw["name"], $questions, $itw["video"], $itw["text"], $itw["callback"]);
    }

    /**
     * This endpoint update an interview. It return the new interview if success. If some parameters is missing it will return a BadRequest.
     * @param $id string the ID of the interview to update
     * @param $param string[]
     * @throws InvalidRequestError
     * @return Interview
     */
    public static function update($id, $param)  {
        if(!isset($param["name"]) || !isset($param["questions"]) || !isset($param["video"]) || !isset($param["text"]))  {
            $missing = array();

            !isset($param["name"]) ? $missing[] = "name" : null;
            !isset($param["questions"]) ? $missing[] = "questions" : null;
            !isset($param["video"]) ? $missing[] = "video" : null;
            !isset($param["text"]) ? $missing[] = "text" : null;

            throw new InvalidRequestError("missing param: ".implode($missing, ", "));
        }else if(!is_array($param["questions"]) || count($param["questions"]) == 0)    {
            throw new InvalidRequestError("wrong param questions");
        }

        foreach ($param["questions"] as $questions) {
            if(!isset($questions["content"]) || !isset($questions["readingTime"]) || !isset($questions["answerTime"]) || !isset($questions["number"]))  {
                $missing = array();

                !isset($param["content"]) ? $missing[] = "content" : null;
                !isset($param["readingTime"]) ? $missing[] = "readingTime" : null;
                !isset($param["answerTime"]) ? $missing[] = "answerTime" : null;
                !isset($param["number"]) ? $missing[] = "number" : null;

                throw new InvalidRequestError("missing param: ".implode($missing, ", "));
            }else if(!in_array($questions["readingTime"], Interview::$acceptedValue) || !in_array($questions["answerTime"], Interview::$acceptedValue) )  {
                throw new InvalidRequestError("invalid duration of readingTime or answerTime");
            }
        }

        $itw = ApiRequest::put("/api/v1/interview/".$id, $param);
        $questions = array();
        foreach($itw["questions"] as $q)    {
            $questions[] = new Question($q["content"], $q["readingTime"], $q["answerTime"], $q["number"]);
        }
        return new Interview($itw["_id"], $itw["name"], $questions, $itw["video"], $itw["text"], $itw["callback"]);
    }

    /**
     * this endpoint delete a specific interview.
     * @param $id string the ID of the interview to delete
     * @param $param string[]
     * @return void
     */
    public static function delete($id, $param)  {
        if(isset($param["withApplicant"]) && $param["withApplicant"])  {
            ApiRequest::delete("/api/v1/interview/".$id."?withApplicant=".$param["withApplicant"]);
        }else{
            ApiRequest::delete("/api/v1/interview/".$id);
        }
    }

} 