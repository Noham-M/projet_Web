<?php
require_once "utilisateur.php";
require_once "prestation.php";
require_once __DIR__ . "/../database/database.php";

class Joueur 
{
    private int $idJoueur;
    private string $pseudo;
    private string $photo; 
    private string $description;
    private int $idUtilisateur;

    
    public function __construct(
        ?int $idJoueur = null, 
        ?string $image = null, 
        ?string $pseudo = null, 
        ?string $description = null, 
        ?int $idUtilisateur = null
    ) {
       
        if ($idJoueur !== null) $this->setIdJoueur($idJoueur);
        if ($description !== null) $this->setDescription($description);
        if ($image !== null) $this->setImage($image);
        if ($pseudo !== null) $this->setPseudo($pseudo);
        if ($idUtilisateur !== null) $this->setIdUtilisateur($idUtilisateur);
    }

    public function setIdJoueur(String $idJoueur) {
        if (empty($idJoueur)) {
            throw new invalidArgumentException("l'id ne peut pas être null");
        }
        $this->idJoueur = $idJoueur;
    }

    public function setIdUtilisateur(String $idUtilisateur) {
        if (empty($idUtilisateur)) {
            throw new invalidArgumentException("l'id ne peut pas être null");
        }
        $this->idUtilisateur = $idUtilisateur;
    }

    public function setPseudo(String $pseudo)
    {
        if (empty($pseudo)) {
            throw new InvalidArgumentException("le pseudo ne peut pas être null ou vide");
        }
        $this->pseudo = $pseudo;
    }

    public function setDescription(string $description)
    {
        if (empty($description)) {
            throw new InvalidArgumentException("la description ne peut pas être null ou vide");
        }
        $this->description = $description;
    }

    public function setImage(string $image)
    {
        if (empty($image)) {
            throw new InvalidArgumentException("l'image ne peut pas être null ou vide");
        }
        $this->photo = $image; 
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->photo; 
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function getIdUtilisateur(): int {
        return $this->idUtilisateur;
    }

    public function getIdJoueur() :int {
        return $this->idJoueur;
    }

    public static function findAll() {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * from joueur order by idJoueur desc");
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Joueur::class);
        return $requete->fetchAll();
    }
    
    public static function findById(int $id) {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("Select * from Joueur where idJoueur = :id");
        $requete->bindValue(':id',$id,PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS,Joueur::class);
        return $requete->fetch() ?: null;
    }

    public function getPrestations() {
         $pdo = Database::getPDO();
        $requete = $pdo->prepare("Select * from prestation where idJoueur = :id");
        $requete->bindValue(':id',$this->getIdJoueur(),PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS,Prestation::class);
        return $requete->fetchAll() ;
    } 
}
?>