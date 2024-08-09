<?php
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class Schedule extends GlobalMethods
{
    public function getSchedule($id, $query)
    {
        $schedule = "SELECT *
                    FROM `course-faculty` 
                    INNER JOIN course on `course-faculty`.`course_code`=`course`.`course_code`
                    INNER JOIN facultymembers on `course-faculty`.`faculty_ID`=`facultymembers`.`faculty_ID` ";

        $courseSql = "SELECT *
                          FROM  `course`";

        switch ($query) {
            case 'faculty':
                $data = [$this->executeGetQuery($schedule . "WHERE facultymembers.faculty_ID = $id;")['data'], $this->executeGetQuery($courseSql)['data']];
                return $this->secured_encrypt($data);

            case 'college':
                $data = [$this->executeGetQuery($schedule . "WHERE facultymembers.college_ID = $id;")['data'], $this->executeGetQuery($courseSql)['data']];
                return $this->secured_encrypt($data);

            case 'all':
                $data = [$this->executeGetQuery($schedule)['data'], $this->executeGetQuery($courseSql)['data']];
                return $this->secured_encrypt($data);

            default:
                # code...
                break;
        }
    }

    public function addCourse($form, $id)
    {
        $params = array('course_code', 'faculty_ID', 'class_count');
        $tempForm = array(
            $form->course_code,
            $id,
            $form->class_count,
        );
        // return $tempForm;
        return $this->prepareAddBind('course-faculty', $params, $tempForm);
    }

    public function deleteCourse($courseId, $id){
        return $this->prepareDeleteBind2('course-faculty', ["course_code", "faculty_ID"], [$courseId, $id]);
    }

    // public function deleteFaculty($id)
    // {
    //     return $this->prepareDeleteBind($this->tableName, 'faculty_ID', $id);
    // }
}
