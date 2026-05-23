<?php
require_once "Heure.php";
require_once "utilisateur.php";
class Joueur extends Utilisateur
{
    private int $idJoueur;
    private string $pseudo;
    private string $Photo;
    private string $description;
    private int $idUtilisateur;

    public function __construct(int $idJoueur, string $image, string $pseudo, string $description,int $idUtilisateur)
    {
        $this->setIdJoueur($idJoueur);
        $this->setDescription($description);
        $this->setImage($image);
        $this->setPseudo($pseudo);
        $this->setIdUtilisateur($idUtilisateur);

    }
    public function setIdJoueur($idJoueur) {
        if (empty($idJoueur)) {
            throw new invalidArgumentException("l'id ne peut pas être null");
        }
        $this->idJoueur = $idJoueur;
    }
    public function setIdUtilisateur($idUtilisateur) {
        if (empty($idUtilisateur)) {
            throw new invalidArgumentException("l'id ne peut pas être null");
        }
        $this->idUtilisateur = $idUtilisateur;
    }
    public function setPseudo($pseudo)
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
        $this->Photo = $image;
    }

   
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getImage(): string
    {
        return $this->Photo;
    }
    public function getPseudo(): string
    {
        return $this->pseudo;
    }
    


}
?>