<?php
include_once "./Controller/global.php";

class Research extends GlobalMethods
{
    private function executeQuery($sql, $variable = null)
    {
        $stmt = $this->pdo->prepare($sql);

        if (isset($variable)) {
            $stmt->bindParam(1, $variable, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getResearch($id)
    {
        //Fetches certs attained by faculty, and all certs template within the faculty. Pinag-isa ko na since they're mostly both needed.
        $existCertSQL = "SELECT * FROM `research-author` 
                         INNER JOIN research on `research-author`.`research_ID`=`research`.`research_ID`;";
        $certSQL = "SELECT * FROM `research`";

        $data =  [$this->executeGetQuery($certSQL)["data"], $this->executeGetQuery($existCertSQL)["data"]];
        return $this->secured_encrypt($data);
        // return [$this->executeGetQuery($existCertSQL)["data"], $this->executeGetQuery($certSQL)["data"]];
    }

    public function addResearch($form, $id)
    {
        try {
            #Add new research
            $params = array('faculty_ID', 'research_name', 'publish_date', 'research_link');
            $tempForm = array(
                $id,
                $form->research_name,
                $form->publish_date,
                $form->research_link,
            );
            $this->prepareAddBind('research', $params, $tempForm);


            #Append all authors on the research
            $params = array('research_ID', 'research_author_name');
            #For co authors
            for ($i = 0; $i < count($form->research_authors); $i++) {
                $tempForm = array(
                    $this->getLastID('research') - 1,
                    $form->research_authors[$i],
                );
                $this->prepareAddBind('research-author', $params, $tempForm);
            }

            return "Project Added!";
        } catch (\Throwable $th) {
            return $th;
        }
        


    }
}
