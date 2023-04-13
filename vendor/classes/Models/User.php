<?php
    namespace Model;

use Exception;
use \Model\Model;
    use \Sql\Sql;
    class User extends Model{
        const SESSION = "USER";

        public  static function login($login, $password){
            $sql = new Sql();

            $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
                ":LOGIN" => $login
            ));

            if(count($results) === 0){
                throw new \Exception("Usuáriio não existente ou senha inválida");
            }


            $data = $results[0];

            if(password_verify($password, $data["despassword"])){
                $user = new User();

                $user->setData($data);

                $_SESSION[User::SESSION] = $user->getValues();

                return $user;


            }
            else{
                throw new \Exception("Usuário não existente ou senha inválida");
            }
        }



        public static function verifyLogin($inAdmin = true)
        {
            if(
                !isset($_SESSION[User::SESSION])
                ||
                !$_SESSION[User::SESSION]
                ||
                !(int)$_SESSION[User::SESSION]["iduser"] > 0
                ||
                (bool)$_SESSION[User::SESSION]['inadmin'] !== $inAdmin
                ){
                    header("Location: /admin/login");
                    exit;
            }
            return true;
        }

        public static function logout(){
            $_SESSION[User::SESSION] = NULL;
        }



        public static function listAll(){
            $sql = new Sql();

            return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
        }

        public function save() {
            $sql = new Sql();

            $results = $sql->select("CALL sp_users_save(:desperson, :deslogin,:despassword,  :desemail, :nrphone, :inadmin)", array(
                ":desperson"=>$this->getdesperson(),
                ":deslogin"=>$this->getdeslogin(),
                ":despassword"=>$this->getdespassword(),
                ":desemail"=>$this->getdesemail(),
                ":nrphone"=>$this->getnrphone(),
                ":inadmin"=>$this->getinadmin()
            ));
            // todos esses gets são dinâmicos   

            $this->setData($results[0]);
        }

        public function get($iduser){

            $sql = new Sql();

            $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser",
                array(
                    ":iduser" => $iduser
                )
            );

            $this->setData($results[0]);
        }

        public function update(){
            $sql = new Sql();

            $results = $sql->select("CALL sp_usersupdate_save(:iduser ,:desperson, :deslogin,:despassword,  :desemail, :nrphone, :inadmin)", array(
                ":iduser"=> $this->getiduser(),
                ":desperson"=>$this->getdesperson(),
                ":deslogin"=>$this->getdeslogin(),
                ":despassword"=>$this->getdespassword(),
                ":desemail"=>$this->getdesemail(),
                ":nrphone"=>$this->getnrphone(),
                ":inadmin"=>$this->getinadmin()
            ));
            // todos esses gets são dinâmicos   

            $this->setData($results[0]);

        }

        public function delete(){
            $sql = new Sql();

            $sql->myQuery("CALL sp_users_delete(:iduser)",
                array(":iduser" =>$this->getiduser())
            );
        }

        public static function forgot($email){
            $sql = new Sql();

            $results = $sql->select("SELECT * FROM a tb_pesons INNER JOIN tb_usuarios b USING(idperson) WHERE a.desemail = :email", 
                array(":email" => $email)
            );

            if(count($results) === 0){
                throw new Exception("Email não cadastrado! Não foi possível recuperar a senha"); 
            }

            

        }
    }





?>