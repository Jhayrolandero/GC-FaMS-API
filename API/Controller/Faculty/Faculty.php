<?php

include_once __DIR__ . '/../../Model/Faculty/FacultyModel.php';
class FacultyController
{

    private $faculty;

    function __construct($pdo)
    {
        $this->faculty = new Faculty($pdo);
    }
    public function test()
    {
        return 'FacultyController is working';
    }

    public function sendPaylood()
    {
        return;
    }

    public function addFaculty($data)
    {
        return $this->faculty->addFaculty($data);
    }
}
