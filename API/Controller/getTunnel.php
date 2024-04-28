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

    public function toGetFaculty($id)
    {
        return $this->faculty->getFacultyInfo($id);
    }

    public function toGetSchedule($id)
    {
        return $this->schedule->getScheduleFaculty($id);
    }

    public function toGetCommex($id = null, $query)
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

    public function toGetCollege($id)
    {
        return $this->college->getCollege();
    }

    public function toGetResumeInfo($id)
    {
        return $this->resume->getResumeInfo($id);
    }

    public function getCert($id)
    {
        return $this->resume->getCert($id);
    }
    public function getExp($id)
    {
        return $this->resume->getExp($id);
    }
    public function getEduc($id)
    {
        return $this->resume->getEduc($id);
    }

    public function getProj($id)
    {
        return $this->resume->getProj($id);
    }

    public function getSpec($id)
    {
        return $this->resume->getSpec($id);
    }

    public function toGetEvaluation($id)
    {
        return $this->evaluation->getEvaluation($id);
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
