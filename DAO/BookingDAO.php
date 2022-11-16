<?php

namespace DAO;

use DAO\Connection as Connection;
use \Exception as Exception;
use Models\Coupon;
use Models\Booking;

class BookingDAO implements IBookingDAO
{
    private $bookingList = array();
    private $connection;
    private $tableName = "booking";

    public function Add(Booking $booking)
    {
        $booking->setState(1); // it is true by default
        $this->Insert($booking);
    }

    public function Modify(Booking $booking)
    {
        $this->Update($booking);
    }

    public function GetAll()
    {
        $this->RetrieveData();
        return $this->bookingList;
    }

    public function GetById($id)
    {
        $this->RetrieveData();

        $arrayBooking = array_filter($this->bookingList, function ($booking) use ($id) {
            return $booking->getId() == $id;
        });

        $arrayBooking = array_values($arrayBooking);

        return (count($arrayBooking) > 0) ? $arrayBooking[0] : null;
    }

    public function GetAllAcceptedByDate($startDate, $endDate)
    {
        $this->RetrieveData();

        $arrayBooking = array_filter($this->bookingList, function ($booking) use ($startDate, $endDate) {
            return $booking->getValidate() == true && $booking->getStartDate() >= $startDate && $booking->getEndDate() <= $endDate;
        });

        $arrayBooking = array_values($arrayBooking);

        return (count($arrayBooking) > 0) ? $arrayBooking : null;
    }

    public function GetActiveBookingOfUser($userId)
    {
        $this->RetrieveData();

        $arrayBooking = array_filter($this->bookingList, function ($booking) use ($userId) {
            return ($booking->getUserId() == $userId && $booking->getState() == true) ? $booking : null;
        });

        return $arrayBooking;
    }

    public function GetAllByUserId($userId)
    {
        $this->RetrieveData();

        $array = array_filter($this->bookingList, function ($booking) use ($userId) {
            return $booking->getOwner()->getId() == $userId;
        });

        return $array;
    }

    // Insert a boooking in the table
    private function Insert(Booking $booking) {
        try {

            $query = "INSERT INTO $this->tableName (startDate,endDate,state,validate,total,id_owner,id_keeper,id_pet,id_coupon) VALUES (:startDate,:endDate,:state,:validate,:total,:id_owner,:id_keeper,:id_pet,:id_coupon);";

            $valuesArray["startDate"] = $booking->getStartDate();
            $valuesArray["endDate"] = $booking->getEndDate();
            $valuesArray["state"] = $booking->getState();
            $valuesArray["validate"] = $booking->getValidate();
            $valuesArray["total"] = $booking->getTotal();
            $valuesArray["id_owner"] = $booking->getOwner()->getId();
            $valuesArray["id_keeper"] = $booking->getKeeper()->getId();
            $valuesArray["id_pet"] = $booking->getPet()->getId();
            $valuesArray["id_coupon"] = $booking->getCoupon()->getId();

            $this->connection = Connection::GetInstance();
            $this->connection->ExecuteNonQuery($query, $valuesArray);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // Update a booking in the table
    private function Update(Booking $booking) {
        try {
            $query = "UPDATE $this->tableName SET startDate = :startdate, endDate = :endDate, state = :state, validate = :validate, total = :total, id_owner = :id_owner, id_keeper = :id_keeper, id_pet = :id_pet, id_coupon = :id_coupon WHERE id = {$booking->getId()};";
            
            $parameters["startDate"] = $booking->getStartDate();
            $parameters["endDate"] = $booking->getEndDate();
            $parameters["state"] = $booking->getState();
            $parameters["validate"] = $booking->getValidate();
            $parameters["total"] = $booking->getTotal();
            $parameters["id_owner"] = $booking->getOwner()->getId();
            $parameters["id_keeper"] = $booking->getKeeper()->getId();
            $parameters["id_pet"] = $booking->getPet()->getId();
            $parameters["id_coupon"] = $booking->getCoupon()->getId();

            $this->connection = Connection::GetInstance();
            $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // Set list boookig with info of table
    private function RetrieveData()
    {
        try {

            $query = "SELECT * FROM " . $this->tableName;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $valuesArray) {
                $booking = new Booking();

                $booking->setId($valuesArray["id"]);
                $booking->setStartDate($valuesArray["startDate"]);
                $booking->setEndDate($valuesArray["endDate"]);
                $booking->setState($valuesArray["state"]);
                $booking->setValidate($valuesArray["validate"]);
                $booking->setTotal($valuesArray["total"]);

                $userDAO = new UserDAO();
                $user = $userDAO->GetById($valuesArray["id_owner"]);
                $booking->setOwner($user);
                $keeperDAO = new KeeperDAO();
                $keeper = $keeperDAO->GetById($valuesArray["id_keeper"]);
                $booking->setKeeper($keeper);

                $petDAO = new PetDAO();
                $pet = $petDAO->GetPetById($valuesArray["id_pet"]);
                $booking->setPet($pet);

                //$couponDAO = new CouponDAO();
                //$coupon = CouponDAO->GetById($value["coupon"]);
                $coupon = new Coupon();
                $booking->setCoupon($coupon);

                array_push($this->bookingList, $booking);
            }

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function GetResult($query) {
        try {
            $this->connection = Connection::GetInstance();
            $result = $this->connection->Execute($query);

            $booking = null;

            if(!empty($result)) {
                $booking = new Booking();

                $booking->setId($result[0][]);
                $booking->setStartDate($result[0]["strartDate"]);
                $booking->setEndDate($result[0]["endDate"]);
                $booking->setState($result[0]["state"]);
                $booking->setValidate($result[0]["validate"]);
                $booking->setTotal($result[0]["total"]);

                $userDAO = new UserDAO();
                $user = $userDAO->GetById($result[0]["id_owner"]);
                $booking->setOwner($user);

                $keeperDAO = new KeeperDAO();
                $keeper = $keeperDAO->GetById($result["id_keeper"]);
                $booking->setKeeper($keeper);

                $petDAO = new PetDAO();
                $pet = $petDAO->GetPetById($result["id_pet"]);
                $booking->setPet($pet);

                $booking->setCoupon(new Coupon());
            }
        } catch (Exception $ex){
            throw $ex;
        }
    }

    private function SetAllQuery($query) {

    }
}
