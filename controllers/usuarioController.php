<?php

namespace controllers;

use models\Usuario as User;
use models\PerfilUser as PerfilUser;
use daoDB\Usuario as UserDao;

class UsuarioController
{

    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDao();
    }
    public function index($msg=null){ //Tuve que hacer un index para cargar la vista del adminAdd desde el navAdmin
        
        if ($_SESSION['loggedRole'] == 'admin'){ 
            include_once(VIEWS.'/header.php');
            include_once(VIEWS.'/navAdmin.php');
            include_once(VIEWS.'/adminAdd.php');
            include_once(VIEWS.'/footer.php');
        }
    } 

    public function checkSession()
    {
        $rta = false;
        if (isset($_SESSION['loggedEmail'])) {
            $rta = true;
        }
        return $rta;
    }
    public function login($email, $pass)
    {
        $rta = false;
        $users = $this->userDAO->getAll();
        if (!empty($users)){
            if (is_array($users)){
                foreach ($users as $user) {
                    if (($user->getEmail() == $email) && ($user->getContrasenia() == $pass)) {
                        $_SESSION['loggedEmail'] = $user->getEmail();
                        $_SESSION['loggedPass'] = $user->getContrasenia();
                        $_SESSION['loggedRole'] = $user->getRol();
                        $rta = true;
                    }
                }
            } else {
                if (($users->getEmail() == $email) && ($users->getContrasenia() == $pass)) {
                    $_SESSION['loggedEmail'] = $users->getEmail();
                    $_SESSION['loggedPass'] = $users->getContrasenia();
                    $_SESSION['loggedRole'] = $users->getRol();
                    $rta = true;
                }
            }
        }
        return $rta;
    }
    public function signUp($nombre, $apellido, $dni, $email, $password, $passwordRepeated, $role = 'cliente')
    {
        $perfilUser = new PerfilUser($nombre, $apellido, $dni);
        if($password==$passwordRepeated)
        {
            $newUser = new User($email, $password, $perfilUser, $role);
            $msg = null;
            $msg = $this->userDAO->add($newUser);
            if ($msg == null){
                return $msg = 'No se pudo agregar, usuario existente';
            }else {
                if ($msg>0)
                    $msg = 'Agregado con éxito';
                    return $msg;
            }
        }
        else {
            return "No se pudo agregar, las contraseñas no coinciden";
        }
    }
    public function signUpAdmin($nombre, $apellido, $dni, $email, $password, $passwordRepeated, $role = 'admin')
    {
        $perfilUser = new PerfilUser($nombre, $apellido, $dni);
        if($password==$passwordRepeated)
        {
            $newUser = new User($email, $password, $perfilUser, $role);
            $msg = null;
            $msg = $this->userDAO->add($newUser);
            if ($msg == null)
                $msg = "No se pudo agregar, usuario existente";
            else
                $msg = "Agregado con exito";
        }else
        {
            $msg="No se pudo agregar, las contraseñas no coinciden";
        }  
        $this->index($msg);
    }
    public function logout()
    {
        unset($_SESSION["loggedEmail"]);
        unset($_SESSION["loggedRole"]);
        session_destroy();
    }
    /*public function add($name,$birthdate,$nationality,$email,$password){
            //agrega un usuario al dao
            $user = new User($name,$birthdate,$nationality,$email,$password);
            $this->userDAO->add($user);
            //$this->view->admUsers();
        }
        public function delete($email){
            //borra un user
            $this->userDAO->delete($email);
            //$this->view->admUsers();
        }*/
}
