<?php
require_once __DIR__ . "/utilisateur.php";
require_once __DIR__ . "/prestation.php";
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

    public function setIdJoueur(int $idJoueur) {
        if ($idJoueur <= 0) {
            throw new InvalidArgumentException("L'id ne peut pas être inférieur ou égal à 0");
        }
        $this->idJoueur = $idJoueur;
    }

    public function setIdUtilisateur(int $idUtilisateur) {
        if ($idUtilisateur <= 0) {
            throw new InvalidArgumentException("L'id utilisateur ne peut pas être inférieur ou égal à 0");
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

    public static function findAll(?bool $programmed = false): array {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM joueur";
        if ($programmed) {
            $sql .= " WHERE idJoueur IN (SELECT DISTINCT idJoueur FROM prestation WHERE idScene IS NOT NULL AND idheure IS NOT NULL)";
        }
        $requete = $pdo->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Joueur::class);
        return $requete->fetchAll();
    }
    
    public static function findById(int $id) {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * FROM joueur WHERE idJoueur = :id");
        $requete->bindValue(':id',$id,PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS,Joueur::class);
        return $requete->fetch() ?: null;
    }

    public function getPrestations() {
         $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT idPrestation AS idPrestation, idScene AS idScene, idJoueur AS idJoueur, idheure AS idHeure, titre AS titre, description AS description, image AS image FROM prestation WHERE idJoueur = :id");
        $requete->bindValue(':id',$this->getIdJoueur(),PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS,Prestation::class);
        return $requete->fetchAll() ;
    } 

    public static function deleteWithPrestations(int $id): bool {
        $pdo = Database::getPDO();
        try {
            $pdo->beginTransaction();
            $requete = $pdo->prepare("DELETE FROM prestation WHERE idJoueur = :id");
            $requete->bindValue(':id', $id, PDO::PARAM_INT);
            $requete->execute();

            $requete = $pdo->prepare("DELETE FROM joueur WHERE idJoueur = :id");
            $requete->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $requete->execute();

            $pdo->commit();
            return $result;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return false;
        }
    }

    public function create(): bool {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT MAX(idJoueur) AS maxId FROM joueur");
        $requete->execute();
        $maxId = $requete->fetchColumn();
        $newId = $maxId !== false ? ((int)$maxId + 1) : 1;

        $requete = $pdo->prepare("INSERT INTO joueur (idJoueur, pseudo, photo, description, idUtilisateur) VALUES (:idJoueur, :pseudo, :photo, :description, :idUtilisateur)");
        $requete->bindValue(':idJoueur', $newId, PDO::PARAM_INT);
        $requete->bindValue(':pseudo', $this->getPseudo(), PDO::PARAM_STR);
        $requete->bindValue(':photo', $this->getImage(), PDO::PARAM_STR);
        $requete->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);
        $requete->bindValue(':idUtilisateur', $this->getIdUtilisateur(), PDO::PARAM_INT);

        if ($requete->execute()) {
            $this->setIdJoueur($newId);
            return true;
        }
        return false;
    }

    public function findUtilisateur(int $idUtilisateur) {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT idUser AS idUser, nom, prenom, motDePasse AS password, Email AS email FROM utilisateur WHERE idUser = :id");
        $requete->bindValue(':id', $idUtilisateur, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Utilisateur::class);
        return $requete->fetch() ?: null;
    }

    public function getUtilisateur(): ?Utilisateur {
        return $this->idUtilisateur ? $this->findUtilisateur($this->idUtilisateur) : null;
    }

    public function update(): bool {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("UPDATE joueur SET pseudo = :pseudo, photo = :photo, description = :description WHERE idJoueur = :id");
        $requete->bindValue(':pseudo', $this->getPseudo(), PDO::PARAM_STR);
        $requete->bindValue(':photo', $this->getImage(), PDO::PARAM_STR);
        $requete->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);
        $requete->bindValue(':id', $this->getIdJoueur(), PDO::PARAM_INT);
        return $requete->execute();
    }

    
}
?>