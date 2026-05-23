<?php
class Heure {
    private int $idHeure;
    private int $Heure1;
    private int $Heure2;

    public function __construct($Heure1) {
        $this->setHeures($Heure1);
    }

    public function setHeures(int $Heure1) {
        if ($Heure1 < 1 || $Heure1 > 5) {
            throw new InvalidArgumentException("la variable heure1 ne peut que être comprise entre 1 et 5");
        }
        switch ($Heure1) {
            case 1: 
                $this->Heure1 = 8;
                $this->Heure2 = 10;
                break;
            case 2:
                $this->Heure1 = 10;    
                $this->Heure2 = 12;
                break;
            case 3:
                $this->Heure1 = 12;
                $this->Heure2 = 13;
                break;
            case 4:
                $this->Heure1 = 13;
                $this->Heure2 = 15;
                break;
            default:
                $this->Heure1 = 15;
                $this->Heure2 = 18;
                break;
        }

    }

    public function getHeure1(): int {
        return $this->Heure1;
    }
    public function getHeure2(): int {
        return $this->Heure2;
    }
    public function toString(): String {
        return $this->Heure1 . "h - ". $this->Heure2. "h";
    }
}
?>