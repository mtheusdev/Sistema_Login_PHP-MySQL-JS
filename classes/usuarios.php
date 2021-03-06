<?php
class Usuario{
    public $pdo;
    public $msgErro = "";
    public function conectar($nome, $host, $usuario, $senha){
    global $pdo;
    try{
            $pdo = new PDO("mysql:dbname=".$nome.";host=".$host,$usuario,$senha);
        }
        catch(PDOException $e){
            $msgErro = $e->getMessage();
        }
        
    }

    public function cadastrar($nome, $telefone, $email, $senha){
        global $pdo;
        //verificar se ja existe email cadastrado
        $sql = $pdo->prepare("select id from usuario where email = :e");
        $sql->bindValue(":e", $email);
        $sql->execute();
        if($sql->rowCount() > 0){
            return false; // ja ta cadastrada
        }
        else{
            $sql = $pdo->prepare("insert into usuario (nome, telefone, Senha, email) values(:n,:t,:s,:e)");
            $sql->bindValue(":e", $email);
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":s", md5($senha));
            $sql->execute();
            return true;
        }
        //senao cadastra
    }
    public function mostrar($id){
        global $pdo;
        $sql = $pdo->prepare("select * from usuario where id = :i");
        $sql->bindValue(":i", $id);
        $sql->execute();
        return $sql->fetch();
    }
    public function logar($email, $senha){
        global $pdo;
        $sql = $pdo->prepare("select id from usuario where email = :e and Senha = :s");
        $sql->bindValue(":s", md5($senha));
        $sql->bindValue(":e", $email);
        $sql->execute();
        if($sql->rowCount() > 0){
            $dado = $sql->fetch();
            session_start();
            $_SESSION['id'] = $dado['id'];
            return true; // logado com sucesso
        }else{
            return false; // nao consegiu logar
        }
    }

}

?>