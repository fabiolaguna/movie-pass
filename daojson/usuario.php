<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\Usuario as User;
use models\PerfilUser as PerfilUser;

class Usuario implements IDao
{
    private $usersList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/usuarios.json";
    }

    public function add($user)
    {
        $this->usersList = array();
        $this->retrieveData();
        $userExist = false;
        foreach ($this->usersList as $value) {
            if ($user->getEmail() == $value->getEmail()) {
                $userExist = true;
            }
        }
        if (!$userExist) {
            $indice = 1;
            if (!empty($this->usersList)) {
                $indice = count($this->usersList) + 1;
            }
            $user->setIdUsuario($indice);
            array_push($this->usersList, $user);
            $this->saveData();
            $successMje = 'Agregado con éxito';
            return $successMje;
        } else {
            $errorMje = 'No se pudo agregar, usuario existente';
            return $errorMje;
        }
    }

    public function getAll()
    {
        $this->usersList = array();
        $this->retrieveData();
        return $this->usersList;
    }

    public function read($email)
    {
        $this->usersList = array();
        $this->retrieveData();
        $value = null;
        foreach ($this->usersList as $user) {
            if ($user->getEmail() == $email)
                $value = $user;
        }
        return $value;
    }
    public function readEmail($idUsuario)
    {
        $this->usersList = array();
        $this->retrieveData();
        $value = null;
        foreach ($this->usersList as $user) {
            if ($user->getIdUsuario() == $idUsuario)
                $value = $user->getEmail();
        }
        return $value;
    }
    public function readIdUsuario($email)
    {
        $this->usersList = array();
        $this->retrieveData();
        $value = null;
        foreach ($this->usersList as $user) {
            if ($user->getEmail() == $email)
                $value = $user->getIdUsuario();
        }
        return $value;
    }

    public function delete($email)
    {
        $this->usersList = array();
        $this->retrieveData();
        foreach ($this->usersList as $user) {
            if ($user->getEmail() == $email)
                $user->setBaja(true);
        }
        $this->saveData();
    }

    public function update($user, $email) //traer por GET en un arreglo los valores a modificar, que lo conseguis con un checkbox
    {
        $this->usersList = array();
        $this->retrieveData();
        $msg = null;
        $userExist = null;
        foreach ($this->usersList as $value) {
            if (($value->getEmail() == $user->getEmail()) && ($user->getEmail() != $email))
                $userExist = true;
        }

        if (!$userExist) {
            $i = 0;
            $j = 0;
            foreach ($this->usersList as $value) {
                if ($value->getEmail() == $email)
                    $j = $i;
                $i++;
            }

            $perfilUser = $user->getPerfilUsuario();

            if (isset($user) && !empty($user)) {

                if (($user->getEmail() != null) && !empty($user->getEmail()))
                    $this->usersList[$j]->setEmail($user->getEmail());

                if (($user->getContrasenia() != null) && !empty($user->getContrasenia()))
                    $this->usersList[$j]->setContrasenia($user->getContrasenia());

                if (($perfilUser->getNombre() != null) && !empty($perfilUser->getNombre()))
                    $this->usersList[$j]->getPerfilUsuario()->setNombre($perfilUser->getNombre());

                if (($perfilUser->getApellido() != null) && !empty($perfilUser->getApellido()))
                    $this->usersList[$j]->getPerfilUsuario()->setApellido($perfilUser->getApellido());

                if (($perfilUser->getDni() != null) && !empty($perfilUser->getDni()))
                    $this->usersList[$j]->getPerfilUsuario()->setDni($perfilUser->getDni());

                $msg = "Usuario modificado con exito";
            }
        }
        $this->saveData();
        return $msg;
    }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->usersList as $user) {
            $valuesArray["idUsuario"] = $user->getIdUsuario();
            $valuesArray["email"] = $user->getEmail();
            $valuesArray["pass"] = $user->getContrasenia();
            $valuesArray["rol"] = $user->getRol();
            $valuesArray["baja"] = $user->getBaja();
            $perfilUser = $user->getPerfilUsuario();
            $valuesPerfil["nombre"] = $perfilUser->getNombre();
            $valuesPerfil["apellido"] = $perfilUser->getApellido();
            $valuesPerfil["dni"] = $perfilUser->getDni();
            $valuesArray["perfilUser"] = $valuesPerfil;
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents('Data/usuarios.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->usersList = array();

        if (file_exists('Data/usuarios.json')) {
            $jsonContent = file_get_contents('Data/usuarios.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $perfilUser = new PerfilUser($valuesArray["perfilUser"]["nombre"], $valuesArray["perfilUser"]["apellido"], $valuesArray["perfilUser"]["dni"]);
                $user = new User($valuesArray["email"], $valuesArray["pass"], $perfilUser, $valuesArray["rol"], $valuesArray["baja"]);
                $user->setBaja($valuesArray["baja"]);
                $user->setIdUsuario($valuesArray["idUsuario"]);
                array_push($this->usersList, $user);
            }
        }
    }
}
