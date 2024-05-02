<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: *");

//Fetches every single file in Model
foreach (glob("./Model/*/*.php") as $filename) {
    include_once $filename;
}


class GetTunnel extends Connection
{
    private $faculty;
    private $schedule;
    private $commex;
    private $college;
    private $resume;
    private $evaluation;

    public function __construct()
    {
        $this->faculty = new Faculty();
        $this->schedule = new Schedule();
        $this->commex = new Commex();
        $this->college = new College();
        $this->resume = new ResumeInfo();
        $this->evaluation = new Evaluation();
    }

    public function getFaculty($id)
    {
        return $this->faculty->getFacultyInfo($id);
    }

    public function getSchedule($id, $query)
    {
        switch ($query) {
            case 'college':
                return $this->schedule->getSchedule($id, $query);
            case 'faculty':
                return $this->schedule->getSchedule($id, $query);
            case 'all':

        }
    }

    public function getCommex($id = null, $query)
    {
        switch ($query) {
            case 'college':
                return $this->commex->getCommex($id, $query);
            case 'faculty':
                return $this->commex->getCommex($id, $query);
            case 'all':
                return $this->commex->getCommexAll();
        }
    }

    public function getCollege($id)
    {
        return $this->college->getCollege();
    }

    public function getResumeInfo($id)
    {
        return $this->resume->getResumeInfo($id);
    }

    public function getCert($id, $type)
    {
        return $type == 0 ?
        $this->resume->getCert($id) :
        $this->resume->getCollegeCert($id);
    }
    public function getExp($id, $type)
    {
        return $type == 0 ?
        $this->resume->getExp($id) :
        $this->resume->getCollegeExp($id);
    }
    public function getEduc($id, $type)
    {
        return $type == 0 ? 
        $this->resume->getEduc($id) :
        $this->resume->getCollegeEduc($id);
    }

    public function getProj($id, $type)
    {
        return $type == 0 ? 
        $this->resume->getProj($id) :
        $this->resume->getCollegeProj($id);
    }

    public function getSpec($id, $type)
    {
        return $type == 0 ? 
        $this->resume->getSpec($id) :
        $this->resume->getCollegeSpec($id);
    }

    public function getEvaluation($id, $type)
    {
        return $type == 0 ? 
        $this->evaluation->getEvaluation($id) :
        $this->evaluation->getCollegeEvaluation($id);
    }

    public function getFaculties()
    {
        return $this->faculty->getAllFaculty();
    }

    public function getAttendee($id, $query = null)
    {
        return $this->commex->getAttendee($id, $query);
    }
}
