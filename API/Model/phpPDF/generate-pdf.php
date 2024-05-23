<?php
require  __DIR__ . "/../../../vendor/autoload.php";
// require "cv.html";
// include "cv.html";
use Dompdf\Dompdf;
use Dompdf\Options;

class CurriculumVitae extends GlobalMethods
{
    //A function that dynamically binds certificate data to html
    public function certificateParse()
    {
    }

    //Main function
    public function generateCv($data, $id)
    {
        // $html = file_get_contents("API\Model\phpPDF\cv.html");
        $html = file_get_contents(__DIR__ . "/cv.html");

        //Profile data binding
        $html = str_replace('{{ faculty_name }}', htmlspecialchars($data->profile->first_name . " " . $data->profile->last_name), $html);

        //Configure options
        $options = new Options;
        $options->setChroot(__DIR__);
        $dompdf = new Dompdf($options);

        $dompdf->setPaper("a4", "portrait");

        // $dompdf->loadHtmlFile("index.html");
        // $dompdf->file("index.html");


        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->addInfo("Title", "CV");
        // $dompdf->stream("Cv.pdf", ["Attachment" => 0]);
        $output = $dompdf->output();
        return file_put_contents('Test.pdf', $output);
    }
}
