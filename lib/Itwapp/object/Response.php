<?php

class Response {

    /**
     * @var String the url of the response video
     */
    public $file;

    /**
     * @var Int the duration of the response video
     */
    public $duration;

    /**
     * @var Int the file size of this video in octet
     */
    public $fileSize;

    /**
     * @var Int the position of the response on one interview.
     */
    public $number;

    /**
     * @var String This is the thumbnail url for this video.
     */
    public $thumbnail;


    /**
     * @param $file
     * @param $duration
     * @param $fileSize
     * @param $number
     */
    public function __construct($file, $duration, $fileSize, $number, $thumbnail)  {
        $this->file = $file;
        $this->duration = $duration;
        $this->fileSize = $fileSize;
        $this->number = $number;
        $this->thumbnail = $thumbnail;
    }

} 