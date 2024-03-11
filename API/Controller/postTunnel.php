<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
header("Access-Control-Allow-Headers: *");

// Fetches every single file in Model
foreach (glob("./Model/*/*.php") as $filename) {
    include_once $filename;
}
// include_once "./Model/Login/login.php";

class PostTunnel
{
    private $login;
    private $resume;

    private $faculty;

    public function __construct()
    {
        $this->login = new Login();
        $this->resume = new ResumeInfo();
        $this->faculty = new Faculty();
    }

    // public function addFaculty($data){
    //     return $this->faculty->addFaculty($data);
    // }

    public function toValidateLogin($form)
    {
        return $this->login->validateLogin($form);
    }

    public function addFaculty($data, $id)
    {
        // if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        //     $tmp_name = $_FILES['image']['tmp_name'];
        //     $name = basename($_FILES['image']['name']);
        //     $destination = 'uploads/' . $name;

        //     // Move uploaded file to specified directory
        //     if (move_uploaded_file($tmp_name, $destination)) {
        //         echo 'Image uploaded successfully!';
        //     } else {
        //         echo 'Failed to upload image.';
        //     }
        // } else {
        //     echo 'No image selected or upload error occurred.';
        // }
        return $data;
    }
    public function toAddResume($form, $id, $type)
    {
        switch ($type) {
            case 1:
                return $this->resume->addEduc($form, $id);

            case 2:
                return $this->resume->addExp($form, $id);

            case 3:
                return $this->resume->addCert($form, $id);

            case 4:
                return $this->resume->addProj($form, $id);

            case 5:
                return $this->resume->addSpec($form, $id);

            default:
                # code...
                break;
        }
    }

    //Assuming your table is named 'your_table_name' and has an auto-incremented column named 'id'
    //SELECT AUTO_INCREMENT
    //FROM information_schema.TABLES
    //WHERE TABLE_SCHEMA = 'your_database_name'  -- Replace with your database name
    //AND TABLE_NAME = 'your_table_name';      -- Replace with your table name

    public function toEditResume($form, $id, $type)
    {
        switch ($type) {
            case 1:
                return $this->resume->editEduc($form, $id);

            case 2:
                return $this->resume->editExp($form, $id);

            case 3:
                return $this->resume->editCert($form, $id);

            case 4:
                return $this->resume->editProj($form, $id);

            case 5:
                return $this->resume->editSpec($form, $id);

            default:
                # code...
                break;
        }
    }

    public function toDeleteResume($id, $type)
    {
        switch ($type) {
            case 1:
                return $this->resume->deleteEduc($id);

            case 2:
                return $this->resume->deleteExp($id);

            case 3:
                return $this->resume->deleteCert($id);

            case 4:
                return $this->resume->deleteProj($id);

            case 5:
                return $this->resume->deleteSpec($id);

            default:
                # code...
                break;
        }
    }
}
