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

    public function __construct(int $idPrestation,int $idScene,int $idJoueur, int $Heure string $titre, string $description, string $image)
    {   
        $this->setIdPrestation($idPrestation);
        $this->setTitre($titre);
        $this->setDescription($description);
        $this->setImage($image);
        $this->setIdScene($idScene);
        $this->setIdJoueur($idJoueur);
        $this->setIdHeure($idHeure);
    }
    public function setIdHeure(int idHeure) {
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
        $this->IdScene = $idScene;
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

    public function getIdPrestation(): string
    {
        return $this->idPrestation;
    }
     public function getIdScene(): string
    {
        return $this->idScene;
    }
     public function getIdJoueur(): string
    {
        return $this->idJoueur;
    }
     public function getIdHeure(): string
    {
        return $this->idHeure;
    }


}