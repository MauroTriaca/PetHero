<?php

namespace Controllers;

use DAO\BookingDAO;
use DAO\KeeperDAO;
use DAO\PetSizeDAO;
use DAO\UserDAO;
use Models\Keeper;
use Models\PetSize;
use Others\Utilities;

class KeeperController
{
    private $keeperDAO;

    public function __construct()
    {
        $this->keeperDAO = new KeeperDAO();
    }

    // Muestra vista de add keeper
    public function ShowAddView()
    {
        require_once(VIEWS_PATH . "validate-session.php");

        $petSizeDAO = new PetSizeDAO();
        $petSizeList = $petSizeDAO->GetAll();
        $keeper = $this->CheckKeeper($_SESSION["loggedUser"]->getId());
        if ($keeper) {
            $this->SetActive($keeper, true);
        } else {
            require_once(VIEWS_PATH . "add-keeper.php");
        }
    }

    // Muestra un listado de keepers
    public function ShowListView()
    {
        require_once(VIEWS_PATH . "validate-session.php");

        $utilities = new Utilities();
        $keeperList = $this->keeperDAO->GetAll();

        require_once(VIEWS_PATH . "keeper-list.php");
    }

    public function ShowValidateView()
    {
        require_once(VIEWS_PATH . "validate-session.php");

        $bookingController= new BookingController();
        $bookingController->ShowPaymentView();
    }

    // Chequea que un usuario sea keeper
    public function CheckKeeper($userId)
    {
        return $this->keeperDAO->GetByUserId($userId);
    }

    // Agrega un keeper
    public function Add($remuneration, $petSize, $description)
    {
        require_once(VIEWS_PATH . "validate-session.php");

        $keeper = new Keeper();

        $userDAO = new UserDAO();
        $user = $userDAO->GetById($_SESSION["loggedUser"]->getId());

        $keeper->setUser($user);
        $keeper->setRemuneration($remuneration);

        $petSizeDAO = new PetSizeDAO();
        $petSizeObj = $petSizeDAO->GetById($petSize);

        $keeper->setPetSize($petSizeObj);
        $keeper->setDescription($description);

        $keeper->setActive(1);

        $this->keeperDAO->Add($keeper);

        $userController = new UserController();
        $userController->ShowProfileView();
    }

    private function SetActive(Keeper $keeper, $active)
    {
        require_once(VIEWS_PATH . "validate-session.php");

        $keeper->setActive($active);

        $this->keeperDAO->Modify($keeper);

        $this->ShowValidateView();
    }

    public function ReturnOwner()
    {
        require_once(VIEWS_PATH . "validate-session.php");
        $keeper = $this->keeperDAO->GetByUserId($_SESSION["loggedUser"]->getId());

        $keeper->setActive(0);

        $this->keeperDAO->Modify($keeper);

        $petController = new PetController();
        $petController->ShowPetListView();
    }
}
