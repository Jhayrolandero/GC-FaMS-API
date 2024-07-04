<?php
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="HBI_QUOTATION.xlsx"');
header('Cache-Control: max-age=0');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Controller/global.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Generate extends GlobalMethods{
    private $data;
    private $spreadsheet;
    private $mainSheet;
    private $quotation;
    private $accessories_data;

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
    public function generateExcel($data, $college) {
        //Preliminary datya
        $alph = range('A', 'Z');
        $this->data = $data;
        $collegeAbb = '';

        switch ($college) {
            case '1':
                $collegeAbb = "CCS";
                break;
            
            default:
                # code...
                break;
        }

        //This excel generator uses templated excels because :)
        //data[0] is the data itself data[1] is the type of data
        switch ($data[1]) {
            case 'Faculty Student Evaluation':
                //Loads spreadsheet template, and gets the Main sheet
                $this->spreadsheet = IOFactory::load(__DIR__ . "/Templates/Faculty Student Evaluation.xlsx");
                $this->mainSheet = $this->spreadsheet->getSheetByName('Main');

                //Adds the header titles at top.
                $this->mainSheet->setCellValue("A5", 'Gordon College - ' . $collegeAbb);
                $this->mainSheet->setCellValue("A6", '2nd Semester A.Y. 2024 - 2025');

                //Iteration for records row
                for ($i=0; $i < count($data[0]); $i++) { 
                    $title = ['No.', 'Name', 'College', 'Position', 'Knowledge Of Content', 'Flexible Learning Modality', 'Instructional Skills', 'Management of Learning', 'Teaching for Independent Learning', 'Evaluation Average'];
                    for ($x=0; $x < count($title); $x++) { 
                        $this->mainSheet->setCellValue($alph[$x] . ($i + 9), $data[0][$i]->{$title[$x]});
                    }
                }
                break;

            case 'Individual Evaluation Average Timeline':
                    //Loads spreadsheet template, and gets the Main sheet
                    $this->spreadsheet = IOFactory::load(__DIR__ . "/Templates/Individual Evaluation Average Timeline.xlsx");
                    $this->mainSheet = $this->spreadsheet->getSheetByName('Main');
    
                    //Adds the header titles at top.
                    $this->mainSheet->setCellValue("A5", 'Gordon College - ' . $collegeAbb);
                    $this->mainSheet->setCellValue("A6", '2nd Semester A.Y. 2024 - 2025');
    

                    for ($i=0; $i < count($data[0]); $i++) { 
                        $title = ['No.', 'Name', 'College', 'Position'];

                        //Makes the last 15 years
                        for ($j=0; $j < 15; $j++) { 
                            array_push($title, (date("Y") - (14 - $j)) . ' ');
                            $this->mainSheet->setCellValue($alph[($j + 4)] . '8', (date("Y") - (14 - $j)) . ' ');
                        }

                        for ($x=0; $x < count($title); $x++) { 
                            $this->mainSheet->setCellValue($alph[$x] . ($i + 9), $data[0][$i]->{$title[$x]});
                        }
                    }
                break;


            case 'Faculty Details Report':
                //Loads spreadsheet template, and gets the Main sheet
                $this->spreadsheet = IOFactory::load(__DIR__ . "/Templates/Faculty Details Report.xlsx");
                $this->mainSheet = $this->spreadsheet->getSheetByName('Main');

                //Adds the header titles at top.
                $this->mainSheet->setCellValue("A4", $collegeAbb . ' Faculty Details Report');
                $this->mainSheet->setCellValue("A5", 'Gordon College - ' . $collegeAbb);
                $this->mainSheet->setCellValue("A6", '2nd Semester A.Y. 2024 - 2025');


                for ($i=0; $i < count($data[0]); $i++) { 
                    $title = ['Name', 'Email', 'Phone Number', 'Employment Status (FT/PT)', 'Related Certificates', 'Related Professional Experience', 'Teaching Year/s Experience', 'Units Load', 'Courses Taught', 'Student Evaluation Result', 'Expertise', 'Associate', 'Baccalaureate', 'Masterals', 'Doctorate'];

                    for ($x=0; $x < count($title); $x++) { 
                        $this->mainSheet->setCellValue($alph[$x] . ($i + 9), $data[0][$i]->{$title[$x]});
                    }
                }
                break;

            case 'Attainment Timeline':
                //Loads spreadsheet template, and gets the Main sheet
                $this->spreadsheet = IOFactory::load(__DIR__ . "/Templates/Attainment Timeline.xlsx");
                $this->mainSheet = $this->spreadsheet->getSheetByName('Main');

                //Adds the header titles at top.
                $this->mainSheet->setCellValue("A4", $collegeAbb . ' Attainment Timeline');
                $this->mainSheet->setCellValue("A5", 'Gordon College - ' . $collegeAbb);
                $this->mainSheet->setCellValue("A6", '2nd Semester A.Y. 2024 - 2025');


                for ($i=0; $i < count($data[0]); $i++) { 
                    $title = ['Year', 'Certificates Received', 'Certificates Received Change from Previous Year (%)', 'Community Extensions Attended', 'Community Extensions Attended Change from Previous Year (%)', 'Seminars Completed', 'Seminars Completed Change from Previous Year (%)', 'Total Achievements', 'Change from Previous Year (%)'];

                    for ($x=0; $x < count($title); $x++) { 
                        $this->mainSheet->setCellValue($alph[$x] . ($i + 9), $data[0][$i]->{$title[$x]});
                    }
                }
                break;

            case 'Milestone Achieved':
                //Loads spreadsheet template, and gets the Main sheet
                $this->spreadsheet = IOFactory::load(__DIR__ . "/Templates/Milestone Achieved.xlsx");
                $this->mainSheet = $this->spreadsheet->getSheetByName('Main');

                //Adds the header titles at top.
                $this->mainSheet->setCellValue("A4", $collegeAbb . ' Milestone Achieved (' . (date("Y") - 14) . ' - ' . date("Y") . ')');
                $this->mainSheet->setCellValue("A5", 'Gordon College - ' . $collegeAbb);
                $this->mainSheet->setCellValue("A6", '2nd Semester A.Y. 2024 - 2025');


                for ($i=0; $i < count($data[0]); $i++) { 
                    $title = ['Year', 'Community Extensions Attended', 'Community Extensions Attended Change from Previous Year (%)', 'Educational Attainment', 'Educational Attainment Change from Previous Year (%)', 'Certificates Received', 'Certificates Received Change from Previous Year (%)', 'Total Milestone', 'Milestone Change from Previous Year (%)'];

                    for ($x=0; $x < count($title); $x++) { 
                        $this->mainSheet->setCellValue($alph[$x] . ($i + 9), $data[0][$i]->{$title[$x]});
                    }
                }
                break;
    
                
            
            default:
                # code...
                break;
        }




        $writer = new Xlsx($this->spreadsheet);
        // $writer->save('text.xlsx');
        ob_end_clean();
        $ret = $writer->save('php://output');
        die();
        return $ret;
        
    }
}
?>