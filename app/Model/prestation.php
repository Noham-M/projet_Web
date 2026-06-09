<?php
class Prestation
{
    private int $idPrestation;
    private string $titre;
    private string $description;
    private string $image;
    private int $idScene;
    private int $idJoueur;
    private int $idHeure;

    public function __construct(?int $idPrestation = null,?int $idScene = null,?int $idJoueur = null, ?int $idHeure = null, ?String $titre = null, ?string $description = null, ?string $image = null)
    {   
        if ($idPrestation !== null) $this->setIdPrestation($idPrestation);
        if ($titre !== null) $this->setTitre($titre);
        if ($description !== null) $this->setDescription($description);
        if ($image !== null) $this->setImage($image);
        if ($idScene !== null) $this->setIdScene($idScene);
        if ($idJoueur !== null) $this->setIdJoueur($idJoueur);
        if ($idHeure !== null) $this->setIdHeure($idHeure);
    }
    public function setIdHeure(int $idHeure) {
        if (empty($idHeure)) {
            throw new invalidArgumentException("l'id ne peut pas être vide");
        }
        $this->idHeure = $idHeure;
    }
     public function setIdJoueur(int $idJoueur) {
        if (empty($idJoueur)) {
            throw new invalidArgumentException("l'id ne peut pas être vide");
        }
        $this->idJoueur = $idJoueur;
    }
     public function setIdScene(int $idScene) {
        if (empty($idScene)) {
            throw new invalidArgumentException("l'id ne peut pas être vide");
        }
        $this->idScene = $idScene;
    }
    public function setIdPrestation(int $idPrestation) {
        if (empty($idPrestation)) {
            throw new invalidArgumentException("l'id ne peut pas être vide");
        }
        $this->idPrestation = $idPrestation;
    }
    public function setTitre(string $titre)
    {
        if (empty($titre)) {
            throw new InvalidArgumentException("le titre ne peut pas être null ou vide");
        }
        $this->titre = $titre;
    }
    public function setDescription(string $description)
    {
        if (empty($description)) {
            throw new InvalidArgumentException("la déscription ne peut pas être null ou vide");
        }
        $this->description = $description;
    }
    public function setImage(string $image)
    {
        if (empty($image) || !str_starts_with($image, "assets/img/")) {
            throw new InvalidArgumentException("l'image doit commencer par assets/img/ et ne doit pas être vide");
        }
        $this->image = $image;
    }
    public function getTitre(): string
    {
        return $this->titre;
    }


    public function getDescription(): string
    {
        return $this->description;
    }
    public function getImage(): string
    {
        return $this->image;
    }

    public function getIdPrestation(): int
    {
        return $this->idPrestation;
    }
     public function getIdScene(): int
    {
        return $this->idScene;
    }
     public function getIdJoueur(): int
    {
        return $this->idJoueur;
    }
     public function getIdHeure(): int
    {
        return $this->idHeure;
    }

    public static function findAll() {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * from prestation order by idPrestation desc");
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Prestation::class);
        return $requete->fetchAll();
    }
    
    public static function findById(int $id) {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("Select * from Prestation where idPrestation = :id");
        $requete->bindValue(':id',$id,PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS,Prestation::class);
        return $requete->fetch() ?: null;
    }


}