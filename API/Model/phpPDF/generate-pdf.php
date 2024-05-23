<?php
require_once('../vendor/autoload.php');


use Dompdf\Dompdf;
use Dompdf\Options;

class CurriculumVitae extends GlobalMethods{
    private $html;
    private $certModule;
    private $educModule;
    private $expModule;
    private $courseModule;
    private $projectModule;
    private $specModule;

        //A function that dynamically binds certificate data to html
        public function specParse($data){
            $length = count($data->expertise);
            $this->specModule = "";
    
            for ($i=0; $i < $length; $i++) { 
                $this->specModule .= 
                "<div style='margin: 20px;'> 
                <p>" . htmlspecialchars($data->expertise[$i]->expertise_name) . "</p>
              </div>";
            }
        }

        //A function that dynamically binds certificate data to html
        public function projectParse($data){
            $length = count($data->projects);
            $this->projectModule = "";
    
            for ($i=0; $i < $length; $i++) { 
                $this->projectModule .= 
                "<div>
                    <h4 style='font-family: InterBold; font-size: 16px;'>" . htmlspecialchars($data->projects[$i]->project_name) . "</h4>
                    <p style='font-family: InterItalic; font-size: 16px;'>" . htmlspecialchars($data->projects[$i]->project_date) . "</p>
                    <p style='font-family: Inter; font-size: 16px; margin-left: 15px;'>" . htmlspecialchars($data->projects[$i]->project_detail) . "</p>
                </div>";
            }
        }

        //A function that dynamically binds certificate data to html
        public function courseParse($data){
            $length = count($data->courses);
            $this->courseModule = "";
    
            for ($i=0; $i < $length; $i++) { 
                $this->courseModule .= 
                "<div  style='margin-left: 40px; margin-botton: 15px;'>
                    <p style='font-family: InterBold; font-size: 20px;'>" . htmlspecialchars($data->courses[$i]->course_name) . "</p>
                </div>";
            }
        }

    //A function that dynamically binds certificate data to html
    public function experienceParse($data){
        $length = count($data->experience);
        $this->expModule = "";

        for ($i=0; $i < $length; $i++) { 
            $this->expModule .= 
            "<div style='margin-bottom: 20px;'>
                <h4 style='font-family: InterBold; font-size: 16px;'>" . htmlspecialchars($data->experience[$i]->experience_title) . "</h4>
                <p style='font-family: Inter; font-size: 16px;'>". htmlspecialchars($data->experience[$i]->experience_place) ."</p>
                <p style='font-family: InterItalic; font-size: 16px;'>" . htmlspecialchars($data->experience[$i]->experience_from) . " " . htmlspecialchars($data->experience[$i]->experience_to) . "</p>
            </div>";
        }
    }

    //A function that dynamically binds certificate data to html
    public function educationParse($data){
        $length = count($data->education);
        $this->educModule = "";

        for ($i=0; $i < $length; $i++) { 
            $this->educModule .= 
            "<div style='margin-bottom: 20px;'>
            <p style='font-family: InterBold; font-size: 16px;'> ". htmlspecialchars($data->education[$i]->educ_title) . "</p>
            <p style='font-family: Inter; font-size: 16px;'>" . htmlspecialchars($data->education[$i]->educ_school) . "</p>
            <p style='font-family: InterItalic; font-size: 16px; font-style: italics;'>" . htmlspecialchars($data->education[$i]->year_start) . " " . htmlspecialchars($data->education[$i]->year_end) . "</p>
          </div>";
        }
    }

    
    //A function that dynamically binds certificate data to html
    public function certificateParse($data){
        $length = count($data->certificates);
        $this->certModule = "";

        for ($i=0; $i < $length; $i++) { 
            $this->certModule .= 
            "<div style='margin: 20px;'>
                <h4 style='font-family: InterBold; font-size: 16px; line-height: 15px;'>" . htmlspecialchars($data->certificates[$i]->cert_name) ."</h4>
                <hr/>
                <p style='font-family: InterBold; font-size: 13px;'>" . htmlspecialchars($data->certificates[$i]->cert_corporation) . "</p>
            </div>";
        }
    }

    //Binds that only get binded once
    public function singleBind($data){
        $this->html = str_replace('{{ faculty_name }}', htmlspecialchars($data->profile->first_name . " " . $data->profile->last_name), $this->html);
        $this->html = str_replace('{{ college_abbrev }}', htmlspecialchars($data->profile->college_abbrev), $this->html);
        $this->html = str_replace('{{ email }}', htmlspecialchars($data->profile->email), $this->html);
        $this->html = str_replace('{{ number }}', htmlspecialchars($data->profile->phone_number), $this->html);
        // $this->html = str_replace('{{ profile_image }}', htmlspecialchars($data->profile->profile_image), $this->html);

        $this->html = str_replace('{{ exp }}', $this->expModule, $this->html);
        $this->html = str_replace('{{ cert }}', $this->certModule, $this->html);
        $this->html = str_replace('{{ educ }}', $this->educModule, $this->html);
        $this->html = str_replace('{{ course }}', $this->courseModule, $this->html);
        $this->html = str_replace('{{ project }}', $this->projectModule, $this->html);
        $this->html = str_replace('{{ expertise }}', $this->specModule, $this->html);
    }

    //Main function
    public function generateCv($data, $id){
        $this->html = file_get_contents(__DIR__ . "/index.html");

        //Call binding functions
        $this->experienceParse($data);
        $this->certificateParse($data);
        $this->educationParse($data);
        $this->courseParse($data);
        $this->projectParse($data);
        $this->specParse($data);
        $this->singleBind($data);

        // return $this->html;

        //Configure options
        $options = new Options;
        $options->setChroot('/fonts');
        $dompdf = new Dompdf($options);
        
        $dompdf->setPaper("a4", "portrait");
        
        // $dompdf->loadHtmlFile("index.html");
        // $dompdf->file("index.html");
        

        $dompdf->loadHtml($this->html);
        $dompdf->render();
        // $dompdf->addInfo("Title", "CV");
        // $dompdf->stream("Cv.pdf", ["Attachment" => 0]);
        $output = $dompdf->output();

        if (file_exists('../CV_Assets/' . $id . '.pdf')) {
            unlink('../CV_Assets/' . $id . '.pdf');
        }


        file_put_contents('../CV_Assets/' . $id . '.pdf', $output);
        return $data;
    }
}



