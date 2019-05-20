<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if (isset($_POST['name'])) {
    try {
	
        require_once('../class/Model.php');
        require_once('../class/Module.php');
        require_once('../class/Quiz.php');
        $module = new Module();
        $response = new stdClass();
        $name = $_POST['name'];
	
        //If a PDF file was uploaded, set a PDF file and remove any text entered
        if (file_exists($_FILES['pdf']['tmp_name'])) {
            $text = "";
            $pdf = 1;
        } else {
            $text = nl2br($_POST['text']);
            $pdf = 0;
        }
	
	if($_POST['question'][1])
	{
	    $quiz = 1;
	}
	else
	{
	    $quiz = 0;
	}

        $module->addModule($name, $text, $pdf, $quiz);
        $moduleId = $module->getLastInsertId();
        $module->close();

        //create quiz if user entered questions
        $quiz = new Quiz();
	$questionArray = $_POST['question'];

	if($_POST['question'][1])
	{
	    for ($i = 1; $i <= count($questionArray); $i++) {
		$answers = array();
		$question = $questionArray[$i];
		$answers['correctAnswer'] = $_POST['correctAnswer'][$i];
		$answers['otherAnswer1'] = $_POST['otherAnswer1'][$i];
		$answers['otherAnswer2'] = $_POST['otherAnswer2'][$i];
		$answers['otherAnswer3'] = $_POST['otherAnswer3'][$i];
		$answersJson = json_encode($answers);
		$quiz->addQuiz($question, $moduleId, $answersJson);
	    }
	 }

        //If a PDF file was uploaded, save it to the modules directory
        if (file_exists($_FILES['pdf']['tmp_name'])) {
            $tmpFile = $_FILES['pdf']['tmp_name'];
            $file = '../modules/module' . $moduleId . '.pdf';

            move_uploaded_file($tmpFile, $file);
        }

        $response->status = "success";
    } catch (PDOException $e) {
        $response->status = "error";
        $response->message = $e->getMessage();
    }

    echo json_encode($response);
}
?>