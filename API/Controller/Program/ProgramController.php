<?php

include_once __DIR__ . '/../../Model/Program/ProgramModel.php';
class ProgramController
{

    private $program;

    function __construct($pdo)
    {
        $this->program = new Program($pdo);
    }


    public function getProgram($id = null)
    {

        return $this->program->getPrograms($id);
    }
}
