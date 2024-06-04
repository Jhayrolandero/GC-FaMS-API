<?php
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
// header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class ResumeInfo extends GlobalMethods
{
    //Faculty id GET sched
    // INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`;";


    public function selectCv($data, $id)
    {
        switch ($data[0]) {
            case 1:
                return $this->prepareEditBind('educattainment', array('isSelected'), array(!$data[1]->isSelected, $data[1]->educattainment_ID), 'educattainment_ID');
                break;

            case 2:
                return $this->prepareEditBind('certifications-faculty', array('isSelected'), array(!$data[1]->isSelected, $data[1]->cert_attainment_ID), 'cert_attainment_ID');
                break;

            case 3:
                return $this->prepareEditBind('experience-faculty',  array('isSelected'), array(!$data[1]->isSelected, $data[1]->experience_ID), 'experience_ID');
                break;

            case 4:
                return $this->prepareEditBind('projects',  array('isSelected'), array(!$data[1]->isSelected, $data[1]->project_ID), 'project_ID');
                break;

            case 5:
                return $this->prepareEditBind('expertise-faculty',  array('isSelected'), array(!$data[1]->isSelected, $data[1]->expertise_faculty_ID), 'expertise_faculty_ID');
                break;

            default:
                # code...
                break;
        }
    }


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
        FROM `projects-faculty` 
        INNER JOIN `projects` on `projects`.`project_ID`=`projects-faculty`.`project_ID`
        WHERE faculty_ID = $id;";
        $data =  $this->executeGetQuery($projSQL)["data"];
        return $this->secured_encrypt($data);
    }

    public function getProjAuthors($id)
    {
        $projSQL = "SELECT * 
        FROM `projects-faculty` 
        INNER JOIN `facultymembers` on `facultymembers`.`faculty_ID`=`projects-faculty`.`faculty_ID`
        WHERE project_ID = $id;";
        $data =  $this->executeGetQuery($projSQL)["data"];
        return $data;
    }

    public function getProjImages($id)
    {
        $projSQL = "SELECT * 
        FROM `projects-images`
        WHERE project_ID = $id;";
        $data =  $this->executeGetQuery($projSQL)["data"];
        return $data;
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
        $specFacultySQL = "SELECT * FROM `expertise-faculty`
                    INNER JOIN `expertise` on `expertise`.`expertise_ID`=`expertise-faculty`.`expertise_ID`
                    WHERE faculty_ID = $id;";
        $dataFaculty =  $this->executeGetQuery($specFacultySQL)["data"];

        $specSQL = "SELECT * FROM `expertise`;";

        $dataExpertise =  $this->executeGetQuery($specSQL)["data"];
        return $this->secured_encrypt([$dataFaculty, $dataExpertise]);
    }

    public function getCollegeSpec($id)
    {
        $specFacultySQL = "SELECT * FROM `expertise-faculty`
        INNER JOIN `expertise` on `expertise`.`expertise_ID`=`expertise-faculty`.`expertise_ID`
        INNER JOIN `facultymembers` on `expertise-faculty`.`faculty_ID`=`facultymembers`.`faculty_ID`
        WHERE college_ID = $id;";
        $dataFaculty = $this->executeGetQuery($specFacultySQL)["data"];

        $specSQL = "SELECT * FROM `expertise`;";
        $dataExpertise =  $this->executeGetQuery($specSQL)["data"];

        return $this->secured_encrypt([$dataFaculty, $dataExpertise]);
    }



    public function addEduc($form, $id)
    {
        // $sql = "INSERT INTO `educattainment`(faculty_ID, educ_title, educ_school, year_start, year_end educ_details)
        // VALUES (?,?,?,?,?,?)";
        $params = array('faculty_ID', 'educ_level', 'educ_title', 'educ_school', 'year_start', 'year_end', 'educ_details', 'isSelected');
        $tempForm = array(
            $id,
            $form->educ_level,
            $form->educ_title,
            $form->educ_school,
            $form->year_start,
            $form->year_end,
            $form->educ_details,
            0
        );
        return $this->prepareAddBind('educattainment', $params, $tempForm);
    }

    public function addExp($form, $id)
    {
        $params = array('faculty_ID', 'experience_place', 'experience_title', 'experience_details', 'experience_from', 'experience_to', 'isSelected');
        $tempForm = array(
            $id,
            $form->experience_place,
            $form->experience_title,
            $form->experience_details,
            $form->experience_from,
            $form->experience_to,
            0
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

        array_push($params, 'isSelected');
        array_push($tempForm, 0);


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
        #Add new project
        $params = array('project_name', 'project_date', 'project_detail', 'project_type', 'project_link', 'isFinished');
        $tempForm = array(
            $form->project_name,
            $form->project_date,
            $form->project_detail,
            $form->project_type,
            $form->project_link,
            $form->isFinished,
        );
        $this->prepareAddBind('projects', $params, $tempForm);

        #Append all authors on the project
        $params = array('faculty_ID', 'project_ID', 'author_type', 'isSelected');
        #For co authors
        for ($i = 0; $i < count($form->project_co_author); $i++) {
            $tempForm = array(
                $form->project_co_author[$i],
                $this->getLastID('projects') - 1,
                0,
                0
            );
            $this->prepareAddBind('projects-faculty', $params, $tempForm);
        }
        #For main author
        $tempForm = array(
            $form->project_co_author[count($form->project_co_author) - 1],
            $this->getLastID('projects') - 1,
            1,
            0
        );
        $this->prepareAddBind('projects-faculty', $params, $tempForm);



        #Append and save all images on the project
        $params = array('project_ID', 'project_image');
        for ($i = 0; $i < count($form->project_images); $i++) {

            $tempForm = array(
                $this->getLastID('projects') - 1,
                $this->customSaveImage($form->project_images[$i], "/../../Image_Assets/Projects/", "png", $i, $this->getLastID('projects') - 1)
            );
            $this->prepareAddBind('projects-images', $params, $tempForm);
        }
    }

    public function addSpec($form, $id)
    {
        $params = array('faculty_ID', 'expertise_ID', 'isSelected');
        $tempForm = array(
            $id,
            $form->expertise_ID,
            0
        );



        return $this->prepareAddBind('expertise-faculty', $params, $tempForm);
    }

    public function addNewSpec($form, $id)
    {
        $params = array('expertise_name');
        $tempForm = array(
            $form->expertise_name
        );
        $this->prepareAddBind('expertise', $params, $tempForm);

        $params = array('faculty_ID', 'expertise_ID');
        $tempForm = array(
            $id,
            $this->getLastID('expertise') - 1
        );
        return $this->prepareAddBind('expertise-faculty', $params, $tempForm);
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
        $params = array('experience_title', 'experience_place', 'experience_from', 'experience_to', 'experience_details', 'teaching_related');
        $tempForm = array(
            $form->experience_title,
            $form->experience_place,
            $form->experience_from,
            $form->experience_to,
            $form->experience_details,
            $form->teaching_related,
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
        return $this->prepareDeleteBind('certifications-faculty', 'cert_attainment_ID', $id);
    }

    public function deleteProj($id)
    {
        return $this->prepareDeleteBind('projects', 'project_ID', $id);
    }

    public function deleteSpec($id)
    {
        return $this->prepareDeleteBind('expertise-faculty', 'expertise_faculty_ID', $id);
    }

    public function deleteSupDocs($type, $doc_ID)
    {

        switch ($type) {
            case 'educ':
                return $this->prepareDeleteBind('educ-support', 'support_ID', $doc_ID);
            case 'expertise':
                return $this->prepareDeleteBind('expertise-support', 'support_ID', $doc_ID);
            case 'industry':
                return $this->prepareDeleteBind('industry-support', 'support_ID', $doc_ID);
            case 'certs':
                return $this->prepareDeleteBind('cert-support', 'support_ID', $doc_ID);
        }
    }

    public function addSupDocs($type, $faculty_ID, $doc_ID)
    {

        $filePaths = [];
        switch ($type) {
            case 'educ':
                $filePaths = $this->addSupportingDocs($faculty_ID, $doc_ID, 'educ');

                foreach ($filePaths as $path) {

                    $this->prepareAddBind(
                        'educ-support',
                        ["doc_path", "doc_name", "doc_type", "educattainment_ID"],
                        [$path["doc_path"], $path["doc_name"], $path["doc_type"], $doc_ID]
                    );
                }
                break;
            case 'industry':
                $filePaths = $this->addSupportingDocs($faculty_ID, $doc_ID, 'industry');

                foreach ($filePaths as $path) {

                    $this->prepareAddBind(
                        'industry-support',
                        ["doc_path", "doc_name", "doc_type", "experience_ID"],
                        [$path["doc_path"], $path["doc_name"], $path["doc_type"], $doc_ID]
                    );
                }
                break;
            case 'expertise':
                $filePaths = $this->addSupportingDocs($faculty_ID, $doc_ID, 'expertise');


                foreach ($filePaths as $path) {
                    $this->prepareAddBind(
                        'expertise-support',
                        ["doc_path", "doc_name", "doc_type", "expertise_faculty_ID"],
                        [$path["doc_path"], $path["doc_name"], $path["doc_type"], $doc_ID]
                    );
                }

                break;
            case 'certs':
                $filePaths = $this->addSupportingDocs($faculty_ID, $doc_ID, 'certs');

                foreach ($filePaths as $path) {

                    $this->prepareAddBind(
                        'cert-support',
                        ["doc_path", "doc_name", "doc_type", "cert_attainment_ID"],
                        [$path["doc_path"], $path["doc_name"], $path["doc_type"], $doc_ID]
                    );
                }
                break;
        }
    }


    public function getEducSupportDocs($faculty_ID)
    {

        $script = "SELECT `educ-support`.*, educattainment.faculty_ID
        FROM `educ-support`
        INNER JOIN educattainment ON `educ-support`.`educattainment_ID`= educattainment.educattainment_ID
        WHERE educattainment.faculty_ID = $faculty_ID;";

        $data = $this->executeGetQuery($script)["data"];
        return $this->secured_encrypt($data);
    }
    public function getExpSupportDocs($faculty_ID)
    {

        $script = "SELECT `expertise-support`.*, `expertise-faculty`.`faculty_ID`
        FROM `expertise-support`
        INNER JOIN `expertise-faculty` ON `expertise-support`.expertise_faculty_ID = `expertise-faculty`.expertise_ID
        WHERE `expertise-faculty`.faculty_ID = $faculty_ID;
        ";

        $data = $this->executeGetQuery($script)["data"];
        return $this->secured_encrypt($data);
        // return $this->secured_encrypt($data);
    }
    public function getIndustrySupportDocs($faculty_ID)
    {

        $script = "SELECT `industry-support`.*, `experience-faculty`.`faculty_ID`
        FROM `industry-support`
        INNER JOIN `experience-faculty` ON `industry-support`.`experience_ID` = `experience-faculty`.`experience_ID`
        WHERE `experience-faculty`.`faculty_ID` = $faculty_ID;";

        $data = $this->executeGetQuery($script)["data"];
        return $this->secured_encrypt($data);
    }
    public function getCertSupportDocs($faculty_ID)
    {

        $script = "SELECT `cert-support`.*, `certifications-faculty`.`faculty_ID`
        FROM `cert-support`
        INNER JOIN `certifications-faculty` ON `cert-support`.`cert_attainment_ID` = `certifications-faculty`.`cert_ID`
        WHERE `certifications-faculty`.`faculty_ID` = $faculty_ID;
        ";

        $data = $this->executeGetQuery($script)["data"];
        return $this->secured_encrypt($data);
    }
}
