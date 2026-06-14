<?php
require_once __DIR__ . "/../database/database.php";
require_once __DIR__ . "/Scene.php";
require_once __DIR__ . "/Heure.php";
require_once __DIR__ . "/Joueur.php";
class Prestation
{
    private ?int $idPrestation = null;
    private ?string $titre = null;
    private ?string $description = null;
    private ?string $image = null;
    private ?int $idScene = null;
    private ?int $idJoueur = null;
    private ?int $idHeure = null;

    public function __construct(?int $idPrestation = null, ?int $idScene = null, ?int $idJoueur = null, ?int $idHeure = null, ?string $titre = null, ?string $description = null, ?string $image = null)
    {
        if ($idPrestation !== null) $this->setIdPrestation($idPrestation);
        if ($titre !== null) $this->setTitre($titre);
        if ($description !== null) $this->setDescription($description);
        if ($image !== null) $this->setImage($image);
        if ($idScene !== null) $this->setIdScene($idScene);
        if ($idJoueur !== null) $this->setIdJoueur($idJoueur);
        if ($idHeure !== null) $this->setIdHeure($idHeure);
    }
    public function setIdHeure(int $idHeure)
    {
        if (empty($idHeure)) {
            throw new invalidArgumentException("l'id ne peut pas être vide");
        }
        $this->idHeure = $idHeure;
    }
    public function setIdJoueur(int $idJoueur)
    {
        if (empty($idJoueur)) {
            throw new invalidArgumentException("l'id ne peut pas être vide");
        }
        $this->idJoueur = $idJoueur;
    }
    public function setIdScene(int $idScene)
    {
        if (empty($idScene)) {
            throw new invalidArgumentException("l'id ne peut pas être vide");
        }
        $this->idScene = $idScene;
    }
    public function setIdPrestation(int $idPrestation)
    {
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
        $image = trim($image);
        if (empty($image)) {
            throw new InvalidArgumentException("l'image ne peut pas être vide");
        }
        if (str_starts_with($image, "assets/img/")) {
            $image = substr($image, strlen("assets/img/"));
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

    public function getIdPrestation(): ?int
    {
        return $this->idPrestation;
    }
    public function getIdScene(): ?int
    {
        return $this->idScene;
    }
    public function getIdJoueur(): ?int
    {
        return $this->idJoueur;
    }
    public function getIdHeure(): ?int
    {
        return $this->idHeure;
    }

    public static function findAll(?int $idJoueur = null, ?int $idScene = null, bool $programmed = false)
    {
        $pdo = Database::getPDO();
        $sql = "SELECT idPrestation AS idPrestation, idScene AS idScene, idJoueur AS idJoueur, idheure AS idHeure, titre AS titre, description AS description, image AS image FROM prestation";
        $conditions = [];

        if ($idJoueur !== null) {
            $conditions[] = "idJoueur = :idJoueur";
        }
        if ($idScene !== null) {
            $conditions[] = "idScene = :idScene";
        }
        if ($programmed) {
            $conditions[] = "idheure IS NOT NULL AND idheure <> 0";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY idPrestation DESC";

        $requete = $pdo->prepare($sql);
        if ($idJoueur !== null) {
            $requete->bindValue(':idJoueur', $idJoueur, PDO::PARAM_INT);
        }
        if ($idScene !== null) {
            $requete->bindValue(':idScene', $idScene, PDO::PARAM_INT);
        }

        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Prestation::class);
        return $requete->fetchAll();
    }

    public static function findById(int $id)
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT idPrestation AS idPrestation, idScene AS idScene, idJoueur AS idJoueur, idheure AS idHeure, titre AS titre, description AS description, image AS image FROM prestation WHERE idPrestation = :id");
        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Prestation::class);
        return $requete->fetch() ?: null;
    }

    public static function deleteById(int $id): bool
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("DELETE FROM prestation WHERE idPrestation = :id");
        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        return $requete->execute();
    }

    public static function clearHeureById(int $id): bool
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("UPDATE prestation SET idheure = NULL WHERE idPrestation = :id");
        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        return $requete->execute();
    }

    public function create(): bool
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT MAX(idPrestation) AS maxId FROM prestation");
        $requete->execute();
        $maxId = $requete->fetchColumn();
        $newId = $maxId !== false ? ((int)$maxId + 1) : 1;

        $requete = $pdo->prepare("INSERT INTO prestation (idPrestation, idScene, idJoueur, idheure, titre, description, image) VALUES (:idPrestation, :idScene, :idJoueur, :idHeure, :titre, :description, :image)");
        $requete->bindValue(':idPrestation', $newId, PDO::PARAM_INT);
        $requete->bindValue(':idScene', $this->getIdScene(), PDO::PARAM_INT);
        $requete->bindValue(':idJoueur', $this->getIdJoueur(), PDO::PARAM_INT);
        $requete->bindValue(':idHeure', $this->getIdHeure(), PDO::PARAM_INT);
        $requete->bindValue(':titre', $this->getTitre(), PDO::PARAM_STR);
        $requete->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);
        $requete->bindValue(':image', $this->getImage(), PDO::PARAM_STR);
        if ($requete->execute()) {
            $this->setIdPrestation($newId);
            return true;
        }
        return false;
    }

    public function update(): bool
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("UPDATE prestation SET idScene = :idScene, idheure = :idHeure, titre = :titre, description = :description, image = :image WHERE idPrestation = :idPrestation");
        $requete->bindValue(':idScene', $this->getIdScene(), PDO::PARAM_INT);
        $requete->bindValue(':idHeure', $this->getIdHeure(), PDO::PARAM_INT);
        $requete->bindValue(':titre', $this->getTitre(), PDO::PARAM_STR);
        $requete->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);
        $requete->bindValue(':image', $this->getImage(), PDO::PARAM_STR);
        $requete->bindValue(':idPrestation', $this->getIdPrestation(), PDO::PARAM_INT);
        return $requete->execute();
    }

    public function findScene(int $id)
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * FROM scene WHERE idScene = :id");
        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Scene::class);
        return $requete->fetch() ?: null;
    }

    public function findJoueur(int $id)
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * FROM joueur WHERE idJoueur = :id");
        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Joueur::class);
        return $requete->fetch() ?: null;
    }

    public function findHeure(?int $id = null)
    {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * FROM heure WHERE idHeure = :id");
        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Heure::class);
        return $requete->fetch() ?: null;
    }
}
