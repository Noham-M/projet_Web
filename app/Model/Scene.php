<?php
class Scene
{
    private int $idScene;
    private string $nom;

    public function __construct(?string $nom = null, ?int $idScene = null)
    {
        if ($nom !== null) $this->setNom($nom);
        if ($idScene !== null) $this->setIdScene($idScene);
    }

    public function setNom(string $nom)
    {
        if (empty($nom)) {
            throw new InvalidArgumentException("le nom ne peut pas être vide");
        }
        $this->nom = $nom;
    }

    public function setIdScene(int $idScene)
    {
        if ($idScene < 0) {
            throw new InvalidArgumentException("l'id ne peut pas être inférieur à 0");
        }
        $this->idScene = $idScene;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getidScene()
    {
        return $this->idScene;
    }




}
?>