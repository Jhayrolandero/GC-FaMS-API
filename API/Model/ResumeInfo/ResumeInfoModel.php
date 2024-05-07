<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class ResumeInfo extends GlobalMethods
{
    //Faculty id GET sched
    // INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`;";
    public function getCert($id)
    {
        //Fetches certs attained by faculty, and all certs template within the faculty. Pinag-isa ko na since they're mostly both needed.
        $existCertSQL = "SELECT * FROM `certifications-faculty` 
                         INNER JOIN certifications on `certifications-faculty`.`cert_ID`=`certifications`.`cert_ID`
                         WHERE faculty_ID = $id;";
        $certSQL = "SELECT * FROM `certifications`";

        $data =  [$this->executeGetQuery($existCertSQL)["data"], $this->executeGetQuery($certSQL)["data"]];
        return $this->secured_encrypt($data);
        // return [$this->executeGetQuery($existCertSQL)["data"], $this->executeGetQuery($certSQL)["data"]];
    }

    public function getCollegeCert($id)
    {
        $existCertSQL = "SELECT * FROM `certifications-faculty` 
                         INNER JOIN certifications on `certifications-faculty`.`cert_ID`=`certifications`.`cert_ID`
                         INNER JOIN facultymembers on `certifications-faculty`.`faculty_ID`=`facultymembers`.`faculty_ID`
                         WHERE college_ID = $id;";

        $data = $this->executeGetQuery($existCertSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getExp($id)
    {
        $expSQL = "SELECT * FROM `experience-faculty`
        WHERE faculty_ID = $id;";
        $data = $this->executeGetQuery($expSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getCollegeExp($id)
    {
        $expSQL = "SELECT * FROM `experience-faculty` 
        INNER JOIN `facultymembers` on `experience-faculty`.`faculty_ID`=`facultymembers`.`faculty_ID`
        WHERE college_ID = $id";
        $data = $this->executeGetQuery($expSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getEduc($id)
    {
        $educSQL = "SELECT * 
        FROM `educattainment` 
        WHERE faculty_ID = $id;";
        $data =  $this->executeGetQuery($educSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getCollegeEduc($id)
    {
        $educSQL = "SELECT * FROM `educattainment` 
        INNER JOIN `facultymembers` on `educattainment`.`faculty_ID`=`facultymembers`.`faculty_ID`
        WHERE college_ID = $id";
        $data =  $this->executeGetQuery($educSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getProj($id)
    {
        $projSQL = "SELECT * 
        FROM `projects` 
        WHERE faculty_ID = $id;";
        $data =  $this->executeGetQuery($projSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getCollegeProj($id)
    {
        $projSQL = "SELECT * FROM `projects` 
        INNER JOIN `facultymembers` on `projects`.`faculty_ID`=`facultymembers`.`faculty_ID`
        WHERE college_ID = $id;";
        $data = $this->executeGetQuery($projSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getSpec($id)
    {
        $specSQL = "SELECT * FROM `expertise`
        WHERE faculty_ID = $id;";
        $data =  $this->executeGetQuery($specSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getCollegeSpec($id)
    {
        $specSQL = "SELECT * FROM `expertise`
        INNER JOIN `facultymembers` on `expertise`.`faculty_ID`=`facultymembers`.`faculty_ID`
        WHERE college_ID = $id;";
        $data = $this->executeGetQuery($specSQL)["data"];
        return $this->secured_encrypt($data);
    }



    public function addEduc($form, $id)
    {
        // $sql = "INSERT INTO `educattainment`(faculty_ID, educ_title, educ_school, year_start, year_end educ_details)
        // VALUES (?,?,?,?,?,?)";
        $params = array('faculty_ID', 'educ_level', 'educ_title', 'educ_school', 'year_start', 'year_end', 'educ_details');
        $tempForm = array(
            $id,
            $form->educ_level,
            $form->educ_title,
            $form->educ_school,
            $form->year_start,
            $form->year_end,
            $form->educ_details
        );
        return $this->prepareAddBind('educattainment', $params, $tempForm);
    }

    public function addExp($form, $id)
    {
        $params = array('faculty_ID', 'experience_place', 'experience_title', 'experience_details', 'experience_from', 'experience_to');
        $tempForm = array(
            $id,
            $form->experience_place,
            $form->experience_title,
            $form->experience_details,
            $form->experience_from,
            $form->experience_to
        );

        return $this->prepareAddBind('experience-faculty', $params, $tempForm);
    }
    public function addFacultyCert($form, $id)
    {
        //Create record only on bridge table, and link it to an existing certificate
        $filepath = null;

        $params = [];
        $tempForm = [];

        array_push($params, 'faculty_ID');
        array_push($tempForm, $id);

        //Calls function that saves image.
        if (!empty($_FILES)) {
            $filepath = $this->saveImage("/../../Image_Assets/Certifications/", "certifications-faculty", "cert_image");
            array_push($params, 'cert_image');
            array_push($tempForm, $filepath);
        }

        //Iterates through FormData, and assigns parameter and value.
        foreach ($_POST as $key => $value) {
            array_push($params, $key);
            array_push($tempForm, $value);
        }

        // return $tempForm;
        return $this->prepareAddBind('certifications-faculty', $params, $tempForm);
    }

    public function addNewCert($form, $id)
    {
        //Create new certificate record, and call addFacultyCert data.
        $filepath = null;
        $params = [];
        $tempForm = [];

        //For adding new certificate
        foreach ($_POST as $key => $value) {
            if ($key != 'accomplished_date') {
                array_push($params, $key);
                array_push($tempForm, $value);
            }
        }
        $this->prepareAddBind('certifications', $params, $tempForm);



        //Recording instance of certifications-faculty to said record
        $params = [];
        $tempForm = [];
        array_push($params, 'faculty_ID');
        array_push($tempForm, $id);
        array_push($params, 'cert_ID');
        array_push($tempForm, $this->getLastID('certifications') - 1);

        if (!empty($_FILES)) {
            $filepath = $this->saveImage("/../../Image_Assets/Certifications/", "certifications-faculty", "cert_image");
            array_push($params, 'cert_image');
            array_push($tempForm, $filepath);
        }

        foreach ($_POST as $key => $value) {
            if ($key == 'accomplished_date') {
                array_push($params, $key);
                array_push($tempForm, $value);
            }
        }

        return $this->prepareAddBind('certifications-faculty', $params, $tempForm);
    }

    public function addProj($form, $id)
    {
        $params = array('faculty_ID', 'project_name', 'project_date', 'project_detail', 'project_link');
        $tempForm = array(
            $id,
            $form->project_name,
            $form->project_date,
            $form->project_detail,
            $form->project_link
        );
        return $this->prepareAddBind('projects', $params, $tempForm);
    }

    public function addSpec($form, $id)
    {
        $params = array('faculty_ID', 'expertise_name', 'expertise_confidence');
        $tempForm = array(
            $id,
            $form->expertise_name,
            $form->expertise_confidence
        );
        return $this->prepareAddBind('expertise', $params, $tempForm);
    }

    public function editEduc($form, $id)
    {
        $params = array('educ_level', 'educ_title', 'educ_school', 'year_start', 'year_end', 'educ_details');
        $tempForm = array(
            $form->educ_level,
            $form->educ_title,
            $form->educ_school,
            $form->year_start,
            $form->year_end,
            $form->educ_details,
            $id
        );
        return $this->prepareEditBind('educattainment', $params, $tempForm, 'educattainment_ID');
    }

    public function editExp($form, $id)
    {
        $params = array('experience_title', 'experience_place', 'experience_from', 'experience_to', 'experience_details');
        $tempForm = array(
            $form->experience_title,
            $form->experience_place,
            $form->experience_from,
            $form->experience_to,
            $form->experience_details,
            $id
        );
        return $this->prepareEditBind('experience-faculty', $params, $tempForm, 'experience_ID');
    }

    public function editCert($form, $id)
    {
        $params = array('accomplished_date', 'cert_name', 'cert_details', 'cert_corporation');
        $tempForm = array(
            $form->accomplished_date,
            $form->cert_name,
            $form->cert_details,
            $form->cert_corporation,
            $id
        );
        return $this->prepareEditBind('certifications-faculty', $params, $tempForm, 'cert_ID');
    }

    public function editProj($form, $id)
    {
        $params = array('project_name', 'project_date', 'project_detail', 'project_link');
        $tempForm = array(
            $form->project_name,
            $form->project_date,
            $form->project_detail,
            $form->project_link,
            $id
        );
        return $this->prepareEditBind('projects', $params, $tempForm, 'project_ID');
    }

    public function editSpec($form, $id)
    {
        $params = array('expertise_name', 'expertise_confidence');
        $tempForm = array(
            $form->expertise_name,
            $form->expertise_confidence,
            $id
        );
        return $this->prepareEditBind('expertise', $params, $tempForm, 'expertise_ID');
    }

    public function deleteEduc($id)
    {
        return $this->prepareDeleteBind('educattainment', 'educattainment_ID', $id);
    }

    public function deleteExp($id)
    {
        return $this->prepareDeleteBind('experience-faculty', 'experience_ID', $id);
    }

    public function deleteCert($id)
    {
        return $this->prepareDeleteBind('certifications-faculty', 'cert_ID', $id);
    }

    public function deleteProj($id)
    {
        return $this->prepareDeleteBind('projects', 'project_ID', $id);
    }

    public function deleteSpec($id)
    {
        return $this->prepareDeleteBind('expertise', 'expertise_ID', $id);
    }
}
