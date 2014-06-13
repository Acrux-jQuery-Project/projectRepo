<?php

    $operation      = $_POST["operation"];

    if($operation == "setNewQuestion")
    {
        $questionObject = $_POST["questionObject"];
        include_once "Template.php";
        $def = new CTemplate();
        $def->writeConfigFile($questionObject,"one","main");
    }

    if($operation == "getAllQuestions")
    {
        include_once "QuestionPicker.php";
        $questionPicker = new QuestionPicker();
        print $questionPicker->getAllFiles();
    }

?>