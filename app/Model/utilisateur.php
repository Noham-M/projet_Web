<?php
class Utilisateur
{ 
    private int $idUser;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
   

    public function __construct(?int $idUser = null, ?string $nom = null, ?string $prenom = null, ?string $email = null, ?string $password = null)
    {
        if ($idUser !== null) $this->setIdUser($idUser);
        if ($nom !== null) $this->setNom($nom);
        if ($prenom !== null) $this->setPrenom($prenom);
        if ($email !== null) $this->setEmail($email);
        if ($password !== null) $this->setPassword($password);
    }

    public function setIdUser(int $idUser)
    {
        if ($idUser < 0) {
            throw new InvalidArgumentException("l'id ne peut pas être inférieur à 0");
        }
        $this->idUser = $idUser;
    }

    public function setPassword(string $password)
    {
        if (empty($password)) {
            throw new InvalidArgumentException("le mdp ne peut pas être null ou vide");
        }
        $this->password = $password;
    }

    public function setPrenom(string $prenom)
    {
        if (empty($prenom)) {
            throw new InvalidArgumentException("le prenom ne peut pas être null ou vide");
        }
        $this->prenom = $prenom;
    }

    public function setEmail(string $email)
    {
        if (empty($email)) {
            throw new InvalidArgumentException("l'email ne peut pas être null ou vide");
        }
        $this->email = $email;
    }

    public function setNom(string $nom)
    {
        if (empty($nom)) {
            throw new InvalidArgumentException("le nom ne peut pas être null ou vide");
        }
        $this->nom = $nom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIdUser(): int {
        return $this->idUser;
    }

    public function getIdUtilisateur(): int {
        return $this->getIdUser();
    }

    public static function findAll() {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT idUser AS idUser, nom, prenom, motDePasse AS password, Email AS email FROM utilisateur ORDER BY idUser DESC");
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Utilisateur::class);
        return $requete->fetchAll();
    }
    
    public static function findById(int $id) {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT idUser AS idUser, nom, prenom, motDePasse AS password, Email AS email FROM utilisateur WHERE idUser = :id");
        $requete->bindValue(':id',$id,PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Utilisateur::class);
        return $requete->fetch() ?: null;
    }
}
?>