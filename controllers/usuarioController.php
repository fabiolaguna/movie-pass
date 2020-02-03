<?php

namespace controllers;

use models\Usuario as User;
use models\PerfilUser as PerfilUser;
use daojson\Usuario as UserDao;

class UsuarioController
{

    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDao();
    }
    public function index($msg=null){ //Tuve que hacer un index para cargar la vista del adminAdd desde el navAdmin
        include_once(VIEWS.'/header.php');

        if ($_SESSION['loggedRole'] == 'admin'){ 
            include_once(VIEWS.'/navAdmin.php');
            if (!isset($_GET["action"]))
                include_once(VIEWS.'/adminAdd.php');
            else
                include_once(VIEWS.'/miCuenta.php');
        } else { 
            include_once(VIEWS.'/navClient.php');
            include_once(VIEWS.'/miCuenta.php');
        }

        include_once(VIEWS.'/footer.php');
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
    public function updateAccount($nombre=null, $apellido=null, $dni=null, $email=null, $password=null, $passwordRepeated=null){

        $userDao = new UserDao();
        $usuarioAux = $userDao->read($_SESSION["loggedEmail"]); //implementarlo en json (y modificar el metodo de update)
        $perfilUser = $usuarioAux->getPerfilUsuario();

        if ($nombre == null){
            $nombre = $perfilUser->getNombre();
        }  
        if ($apellido == null){
            $apellido = $perfilUser->getApellido();
        }  
        if ($dni == null){
            $dni = $perfilUser->getDni();
        }  
        if ($email == null){
            $email = $usuarioAux->getEmail();
        }      
        if ($password == null){
            $password = $usuarioAux->getContrasenia();
        }  
        if ($passwordRepeated == null){
            $passwordRepeated = $usuarioAux->getContrasenia();
        }  
        if($password==$passwordRepeated)
        {
            $perfilUser = new PerfilUser($nombre, $apellido, $dni);
            $newUser = new User($email, $password, $perfilUser, $_SESSION["loggedRole"]);
            $msg = null;
            $msg = $this->userDAO->update($newUser, $_SESSION["loggedEmail"]);
            if ($msg == null){
                $msg = 'No se pudo modificar, usuario ya existente';
            }else {
                $msg = 'Modificado con éxito';
                if(isset($email))
                    $_SESSION["loggedEmail"]=$email;
            }
        }
        else {
            $msg = "No se pudo modificar, las contraseñas no coinciden";
        }
        $_GET["action"]=5; //porque sino se unsetea y no entra a la vista correspondiente
        $this->index($msg);
    }

    public function delete(){

            $this->userDAO->delete($_SESSION["loggedEmail"]);
            $this->index("Usuario eliminado con exito");
    }
}
