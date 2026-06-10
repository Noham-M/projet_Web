<?php
class Heure {
    private int $idHeure;
    private int $Heure1;
    private int $Heure2;

    public function __construct(?int $idHeure = null) {
        if ($idHeure !== null) $this->setHeures($idHeure);
    }

    public function setHeures(int $idHeure) {
        if ($idHeure < 1 || $idHeure > 5) {
            throw new InvalidArgumentException("la variable heure1 ne peut que être comprise entre 1 et 5");
        }
        switch ($idHeure) {
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
    public function getIdHeure(): int {
        return $this->idHeure;
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

    public static function findAll() {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * from heure order by idHeure desc");
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Heure::class);
        return $requete->fetchAll();
    }
    
    public static function findById(int $id) {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("Select * from heure where idHeure = :id");
        $requete->bindValue(':id',$id,PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS,Heure::class);
        return $requete->fetch() ?: null;
    }
}
?>