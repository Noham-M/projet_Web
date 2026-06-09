<?php
class Utilisateur
{ 
    private int $idUser;
    private string $nom;
    private string $prenom;
    private string $Email;
    private string $passWord;
   

    public function __construct(int $idUser, string $nom, string $prenom, string $Email, string $password)
    {   $this->setidUser($idUser);
        $this->setNom($nom);
        $this->setPassWord($password);
        $this->setPrenom($prenom);
        $this->setEmail($Email);
    }
    public function setidUser(String $idUser) {
        if($idUser < 0 ) {
            throw new InvalidArgumentException("l'id ne peut pas être inférieur à 0");
        }
        $this->idUser;
    }
    public function setPassWord(String $passWord)
    {
        if (empty($passWord)) {
            throw new InvalidArgumentException("le mdp ne peut pas être null ou vide");
        }
        $this->passWord = $passWord;
    }
    public function setPrenom(String $prenom)
    {
        if (empty($prenom)) {
            throw new InvalidArgumentException("le prenom ne peut pas être null ou vide");
        }
        $this->prenom = $prenom;
    }
    public function setEmail(String $Email)
    {
        if (empty($Email)) {
            throw new InvalidArgumentException("l'email ne peut pas être null ou vide");
        }
        $this->Email = $Email;
    }

    public function setNom(string $nom)
    {
        if (empty($nom)) {
            throw new InvalidArgumentException("le nom ne peut pas être null ou vide");
        }
        $this->nom = $nom;
    }

    public function getName(): string
    {
        return $this->nom;
    }
     public function getPrenom(): string
    {
        return $this->prenom;
    }
    public function getEmail(): string
    {
        return $this->Email;
    }
    public function getPassWord(): string
    {
        return $this->passWord;
    }

    public function getIdUser(): int {
        return $this->idUser;
    }

    public static function findAll() {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("SELECT * from utilisateur order by idUtilisateur desc");
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Utilisateur::class);
        return $requete->fetchAll();
    }
    
    public static function findById(int $id) {
        $pdo = Database::getPDO();
        $requete = $pdo->prepare("Select * from Utilisateur where idUtilisateur = :id");
        $requete->bindValue(':id',$id,PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS, Utilisateur::class);
        return $requete->fetch() ?: null;
    }
}
?>