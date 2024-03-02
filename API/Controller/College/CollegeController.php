<?php

include_once __DIR__ . '/../../Model/College/CollegeModel.php';
class CollegeController
{

    private $college;
    function __construct($pdo)
    {
        $this->college = new College($pdo);
    }


    public function getCollege()
    {
        return $this->college->getCollege();
    }
}
