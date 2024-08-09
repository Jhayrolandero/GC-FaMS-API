<?php
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="HBI_QUOTATION.xlsx"');
header('Cache-Control: max-age=0');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Controller/global.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Generate extends GlobalMethods
{
    private $data;
    private $spreadsheet;
    private $mainSheet;
    private $quotation;
    private $accessories_data;
    private $title;


    //Function that inputs single occuring cell inputs. (I separated them so no one suffers when mapping this)
    // public function singleInput($p_date, $p_quotref, $p_name, $p_discount, $p_vat, $p_sched_1, $p_sched_2, $p_sched_3, $p_sched_4, $p_deliverysched, $q_date, $q_hbi, $q_name, $q_email, $q_number, $q_discount, $q_vat) {
    //     //Personal details (upper segment of payment schedule)
    //     $this->payment_schedule->setCellValue($p_date, date("Y/m/d"));
    //     $this->payment_schedule->setCellValue($p_quotref, $this->data->quotationNumber);
    //     $this->payment_schedule->setCellValue($p_name, $this->data->customerName);

    //     //Discount and vat values on payment schedule
    //     $this->payment_schedule->setCellValue($p_discount, $this->data->discount);
    //     $this->payment_schedule->setCellValue($p_vat, $this->data->vat);

    //     //4 Stages of payment schedules (Dates only since)
    //     $this->payment_schedule->setCellValue($p_sched_1, $this->payment_schedule->getCell($p_sched_1)->getValue() . ' (' . $this->data->paymentDates[0] . ')');
    //     $this->payment_schedule->setCellValue($p_sched_2, $this->payment_schedule->getCell($p_sched_2)->getValue() . ' (' . $this->data->paymentDates[1] . ')');
    //     $this->payment_schedule->setCellValue($p_sched_3, $this->payment_schedule->getCell($p_sched_3)->getValue() . ' (' . $this->data->paymentDates[2] . ')');
    //     $this->payment_schedule->setCellValue($p_sched_4, $this->payment_schedule->getCell($p_sched_4)->getValue() . ' (' . $this->data->paymentDates[3] . ')');
    //     $this->payment_schedule->setCellValue($p_deliverysched, $this->payment_schedule->getCell($p_deliverysched)->getValue() . $this->data->deliverySched);

    //     //Personal details on quotation sheet
    //     $this->quotation->setCellValue($q_date, date("Y/m/d"));
    //     $this->quotation->setCellValue($q_hbi, $this->data->quotationNumber);
    //     $this->quotation->setCellValue($q_name, $this->data->customerName);
    //     $this->quotation->setCellValue($q_email, $this->data->customerEmail);
    //     $this->quotation->setCellValue($q_number, $this->data->contactNumber);

    //     //Discount and vat values on quotation sheet
    //     $this->quotation->setCellValue($q_discount, $this->data->discount);
    //     $this->quotation->setCellValue($q_vat, $this->data->vat);
    // }

    // public function multiInput($p_accessories_start, $json_list, $p_engine_pos, $p_galvanized_pos, $payment_rows ,$q_letter_box, $q_letter_name, $q_letter_price, $q_accessories_arr) {
    //     $tempAcc = $this->data->boatAccessories;

    //     //A parsed array of all selected accessories, spaces removed and lowercase
    //     $selected_acc_parsed = array_map(function($item) {
    //         return strtolower(str_replace(' ', '', $item));
    //     }, $tempAcc);

    //     //A parse list of motor names (used to get index for price)
    //     $motor_name_parsed = array_map(function($item) {return $item['name'];}, $json_list['motor']);

    //     //A parsed list of accessories all smallcase with spaecs removed (to prevent errors)
    //     $json_list_parsed = array_map(function($item) {
    //         return strtolower(str_replace(' ', '', $item['name']));
    //     }, $json_list['accessories']);

    //     $galvanized_index = array_search('galvanizedtrailer', $json_list_parsed);
    //     $engine_index = array_search($this->data->boatEngine, $motor_name_parsed);

    //     //Payment schedule iteration on additional accessories
    //     for ($i=0; $i < count($tempAcc); $i++) { 
    //         //Index of the current iterated accessory to access price.
    //         $accessory_index = array_search(str_replace(' ', '', strtolower($tempAcc[$i])), $json_list_parsed);
    //         if(str_replace(' ', '', strtolower($tempAcc[$i])) == "galvanizedtrailer") continue;
    //         $this->payment_schedule->setCellValue("D" . ($i + $p_accessories_start), "> " . $tempAcc[$i]);
    //         $this->payment_schedule->setCellValue("D" . ($i + $p_accessories_start), "> " . $tempAcc[$i]);
    //         $this->payment_schedule->setCellValue("V" . ($i + $p_accessories_start), $json_list['accessories'][$accessory_index]['price']);
    //     }
    //     //For engine and galvanized
    //     $this->payment_schedule->setCellValue("G" . $p_engine_pos, $this->data->boatEngine);
    //     $this->payment_schedule->setCellValue("V" . $p_engine_pos, $json_list['motor'][$engine_index]['price']);
    //     if(in_array('galvanizedtrailer', $selected_acc_parsed)) $this->payment_schedule->setCellValue("V" . $p_galvanized_pos, $json_list['accessories'][$galvanized_index]['price']);

    //     //Payment schedule segments
    //     for ($i=0; $i < 4; $i++) { 
    //         //A counter to determine if there's only one or two payment schedule selected for the segment. It will act as an adder for rows to make it dynamic.
    //         $count = 0;

    //         if($this->data->paymentBoat[$i] != ''){
    //             $this->payment_schedule->setCellValue("D" . ($payment_rows[$i] + $count), $this->data->paymentBoat[$i]);
    //             $this->payment_schedule->setCellValue("E" . ($payment_rows[$i] + $count), '% Boat, Accessories & Trailer');
    //                                                                                                  //+1 on Galvanized row because it's always the total price on all templates
    //             $this->payment_schedule->setCellValue("P" . ($payment_rows[$i] + $count), "=Y" . ($p_galvanized_pos) . "*D" . ($payment_rows[$i] + $count) . "/100");
    //             $count += 1;
    //         }

    //         if($this->data->paymentEngine[$i] != ''){
    //             $this->payment_schedule->setCellValue("D" . ($payment_rows[$i] + $count), $this->data->paymentEngine[$i]);
    //             $this->payment_schedule->setCellValue("E" . ($payment_rows[$i] + $count), '% Engine');
    //             $this->payment_schedule->setCellValue("P" . ($payment_rows[$i] + $count), "=Y" . ($p_galvanized_pos - 1) . "*D" . ($payment_rows[$i] + $count) . "/100");
    //         }
    //     }


    //     //Quotation

    //     foreach ($q_accessories_arr as $key => $value) {
    //         $current_acc = str_replace(' ', '', strtolower($this->quotation->getCell($q_letter_name . $value)->getValue()));
    //         $engine_parsed = str_replace(' ', '', strtolower($this->data->boatEngine));
    //         $has_this_accessory = in_array($current_acc, $selected_acc_parsed);

    //         if($has_this_accessory || $engine_parsed == $current_acc){
    //             $this->quotation->setCellValue($q_letter_box . $value, "☒");
    //         }
    //         else{
    //             $this->quotation->setCellValue($q_letter_price . $value, "");
    //         }
    //     }

    // }

    //Main function call for geerating excels
    public function generateExcel($data, $college)
    {
        //Preliminary datya
        $alph = range('A', 'Z');
        $this->data = $data;
        $collegeAbb = '';
        $title = [];

        switch ($college) {
            case '1':
                $collegeAbb = "CCS";
                break;

            case '2':
                $collegeAbb = "CCS";
                break;
            case '3':
                $collegeAbb = "CBA";
                break;
            case '4':
                $collegeAbb = "CAHS";
                break;
            case '5':
                $collegeAbb = "CEAS";
                break;
            case '6':
                $collegeAbb = "CHTM";
                break;
            default:
                $collegeAbb = "GC";
                break;
        }


        $this->renderTemplate($data[1], $collegeAbb);
        $this->title = array_keys(get_object_vars($data[0][0]));

        // Fellas in Paris
        if ($data[1] === "Individual Evaluation Average Timeline") {

            for ($i = 0; $i < count($data[0]) - 1; $i++) {
                $title = ['No.', 'Name', 'College', 'Position'];
                //Makes the last 15 years
                for ($j = 0; $j < 15; $j++) {
                    array_push($title, (date("Y") - (14 - $j)) . ' ');
                    $this->mainSheet->setCellValue($alph[($j + 4)] . '8', (date("Y") - (14 - $j)) . ' ');
                }
            }

            $this->setValue($title, $this->data);
        } else {
            $this->setValue($this->title, $this->data);
        }




        $writer = new Xlsx($this->spreadsheet);
        // $writer->save('text.xlsx');
        ob_end_clean();
        $ret = $writer->save('php://output');
        die();
        // return $ret;
    }


    public function setValue($title, $data)
    {
        $alph = range('A', 'Z');

        $thresholdLength = 30;
        $fontSize = 18;
        $numRows = count($data[0]);
        for ($i = 0; $i < count($data[0]); $i++) {
            for ($x = 0; $x < count($title); $x++) {
                $cell = $alph[$x] . ($i + 9);
                $property = $title[$x];

                // Check if the property exists and set value, otherwise set an empty string or default value
                $value = isset($data[0][$i]->{$property}) ? $data[0][$i]->{$property} : '';
                $this->mainSheet->setCellValue($cell, $value);

                // Center align the data cells
                $this->mainSheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // $spreadsheet->getActiveSheet()->setBreak('A10', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                // Ensure text wrapping
                if (strlen($value) > $thresholdLength) {
                    $this->mainSheet->getStyle($cell)->getAlignment()->setWrapText(true);
                }

                // Set the font size to 18
                $this->mainSheet->getStyle($cell)->getFont()->setSize($fontSize);
            }
        }

        // Auto-size the columns
        foreach (range('A', $alph[count($title) - 1]) as $columnID) {
            $this->mainSheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        // Adjust the print area to fit all the data
        $lastColumn = $alph[count($title) - 1];
        $lastRow = $numRows + 8; // Assuming data starts from row 9
        $printArea = 'A1:' . $lastColumn . $lastRow;
        $this->mainSheet->getPageSetup()->setPrintArea($printArea);

        // Set the page layout to fit all columns and rows on one page if needed
        $this->mainSheet->getPageSetup()->setFitToWidth(1);
        $this->mainSheet->getPageSetup()->setFitToHeight(0);
    }

    public function renderTemplate($title, $collegeAbb)
    {

        // Get the current month and year
        $currentMonth = date('n') - 1; // date('n') returns 1-12, subtract 1 to match JavaScript's 0-11
        $currentYear = date('Y');

        // Get the current semester and academic year
        $currentSemester = $this->getSemester((string)$currentMonth, $currentYear);
        $currSem = $currentSemester['semester'] . " Semester, A.Y. " . $currentSemester['academicYear'];
        // Loads spreadsheet template, and gets the Main sheet
        $this->spreadsheet = IOFactory::load(__DIR__ . "/Templates/$title.xlsx");
        $this->mainSheet = $this->spreadsheet->getSheetByName('Main');

        // Adds the header titles at top.
        $this->mainSheet->setCellValue("A4", $collegeAbb . " $title");
        $this->mainSheet->setCellValue("A5", 'Gordon College - ' . $collegeAbb);
        $this->mainSheet->setCellValue("A6", $currSem);
        // $this->mainSheet->setCellValue("A6", '2nd Semester A.Y. 2024 - 2025');
    }
}
