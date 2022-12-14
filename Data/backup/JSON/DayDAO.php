<?php

    namespace DAO;

    use Models\Day;
    use Models\Keeper;
    use DAO\KeeperDAO;

    class DayDAO implements IDayDAO {
        private $fileName = ROOT . "/Data/days.json";
        private $dayList = array();

        public function __construct() {
            $this->DesactiveOldDays();
        }

        public function Add(Day $day) {
            $this->RetrieveData();

            $day->setId($this->GetNextId());

            array_push($this->dayList, $day);

            $this->SaveData();
        }

        public function Remove($id) {
            $this->RetrieveData();

            $this->dayList = array_filter($this->dayList, function($day) use($id) {
                return $day->getId() != $id;
            });

            $this->SaveData();
        }

        public function Modify(Day $day) {
            $this->RetrieveData();

            $this->Remove($day->getId());

            array_push($this->dayList, $day);

            $this->SaveData();
        }

        public function GetListByKeeper($keeperId) {
            $this->RetrieveData();

            $array = array_filter($this->dayList, function($day) use($keeperId) {
                return $day->getKeeper()->getId() == $keeperId;
            });
            return $array;
        }

        public function GetActiveListByKeeper($keeperId) {
            var_dump("hola");
            die();
            $this->RetrieveData();
            var_dump("hola");
            die();
            $array = array_filter($this->dayList, function($day) use($keeperId) {
                return ($day->getKeeper()->getId() == $keeperId) && ($day->getIsAvailable());
            });
            return $array;
        }

        public function GetInactiveListByKeeper($keeperId) {
            $this->RetrieveData();

            $array = array_filter($this->dayList, function($day) use($keeperId) {
                return ($day->getKeeper()->getId() == $keeperId) && (!$day->getIsAvailable());
            });
            return $array;
        }

        public function GetAll() {
            $this->RetrieveData();

            return $this->dayList;
        }

        public function GetById($id) {
            $this->RetrieveData();

            $array = array_filter($this->dayList, function($day) use($id) {
                return $day->getId() == $id;
            });

            $array = array_values($array);

            return (count($array) > 0) ? $array[0] : null;
        }

        public function SaveData() {
            sort($this->dayList);
            $arrayEncode = array();

            foreach($this->dayList as $day) {
                $value["id"] = $day->getId();
                $value["keeper"] = $day->getKeeper()->getId();
                $value["date"] = $day->getDate();
                $value["isAvailable"] = $day->getIsAvailable();

                array_push($arrayEncode, $value);
            }
            $jsonContent = json_encode($arrayEncode, JSON_PRETTY_PRINT);
            file_put_contents($this->fileName, $jsonContent);
        }

        public function RetrieveData() {
            $this->dayList = array();

            if(file_exists($this->fileName)) {
                $jsonContent = file_get_contents($this->fileName);
                $arrayDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

                foreach($arrayDecode as $value) {
                    $day = new Day();
                    $day->setId($value["id"]);
                    $day->setDate($value["date"]);
                    $day->setIsAvailable($value["isAvailable"]);

                    //construyo el objeto keeper
                    $keeperDAO = new KeeperDAO();
                    $keeper = $keeperDAO->GetById($value["keeper"]);
                    $day->setKeeper($keeper);

                    array_push($this->dayList, $day);
                }
            }
        }

        private function GetNextId() {
            $id = 0;

            foreach($this->dayList as $day) {
                $id = ($day->getId() > $id) ? $day->getId() : $id;
            }

            return $id + 1;
        }

        private function DesactiveOldDays() {
            $this->RetrieveData();

            $today = strtotime(date("d-m-Y", time()));

            foreach($this->dayList as $day) {
                $date = strtotime($day->getDate());
                if($today > $date) {
                    $day->setIsAvailable(false);
                    $this->Modify($day);
                }
            }

            $this->SaveData();
        }
    }
?>