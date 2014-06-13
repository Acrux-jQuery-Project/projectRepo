<?php
/**
 * Created by PhpStorm.
 * User: Razvan
 * Date: 5/30/14
 * Time: 3:00 PM
 */

class QuestionPicker {

    private $questionDir = "../question_pool";

    public function getAllFiles()
    {
           $allFiles = scandir($this->questionDir,SCANDIR_SORT_ASCENDING); // Or any other directory
           $files = array_diff($allFiles, array('.', '..', 'default.question.main.config', 'default.question.css.config', 'default.question.validation.config', 'default.question.js.config'));
           return json_encode($files);
    }

    public function getFile($name)
    {

    }


} 