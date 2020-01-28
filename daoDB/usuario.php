<?php

namespace daoDB;

use models\Usuario as User;
use models\PerfilUser as perfilUser;
use interfaces\IDao as IDao;

class Usuario implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($user)
    {
        $userExist = null;
        $userExist = $this->read($user->getEmail());
        if (empty($userExist)) {
            $sql = "INSERT INTO usuarios (nombre, apellido, dni, email, contrasenia, rol, baja) VALUES (:nombre, :apellido, :dni, :email, :contrasenia, :rol, :baja)";
            $parameters['nombre'] = $user->getPerfilUsuario()->getNombre();
            $parameters['apellido'] = $user->getPerfilUsuario()->getApellido();
            $parameters['dni'] = $user->getPerfilUsuario()->getDni();
            $parameters['email'] = $user->getEmail();
            $parameters['contrasenia'] = $user->getContrasenia();
            $parameters['rol'] = $user->getRol();
            $baja = $user->getBaja();
            if ($baja)
                $parameters['baja'] = 1;
            else
                $parameters['baja'] = 0;

            try {
                // creo la instancia connection
                $this->connection = Connection::getInstance();
                // Ejecuto la sentencia.
                return $this->connection->ExecuteNonQuery($sql, $parameters); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
            } catch (\PDOException $ex) {
                throw $ex;
            }
        }
    }
    /**
     *
     */
    public function getAll()
    {
        $sql = "SELECT * FROM usuarios";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        if (!empty($resultSet))
            return $this->mapear($resultSet);
        else
            return false;
    }

    public function read($email)
    {
        $sql = "SELECT * FROM usuarios where email = '$email'";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql); //Sino va execute($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
        if (!empty($resultSet)) {
            return $this->mapear($resultSet);
        } else
            return false;
    }
    //los hicimos para crear la compra
    public function readIdUsuario($email)
    {
        $sql = "SELECT idUsuario FROM usuarios where email = '$email'";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql); //Sino va execute($sql, $parameters);
            $resp = array_map(function ($p) {
                $idUsuario = ($p['idUsuario']);
                return $idUsuario;
            }, $resultSet);
            return (count($resp) > 1 ? $resp : $resp['0']);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function readEmail($idUsuario)
    {
        $sql = "SELECT email FROM usuarios where idUsuario = '$idUsuario'";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql); //Sino va execute($sql, $parameters);
            $resp = array_map(function ($p) {
                $idUsuario = ($p['idUsuario']);
                return $idUsuario;
            }, $resultSet);
            return (count($resp) > 1 ? $resp : $resp['0']);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    /**
     *
     */
    public function update($user, $email)
    {
        $userExist = $this->read($user->getEmail());
        if (!empty($userExist)) {
            $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, contrasenia = :contrasenia, rol = :rol, baja = :baja WHERE email = '$email'";
            $parameters['nombre'] = $user->getPerfilUsuario()->getNombre();
            $parameters['apellido'] = $user->getPerfilUsuario()->getApellido();
            $parameters['dni'] = $user->getPerfilUsuario()->getDni();
            $parameters['email'] = $user->getEmail();
            $parameters['contrasenia'] = $user->getContrasenia();
            $parameters['rol'] = $user->getRol();
            $baja = $user->getBaja();
            if ($baja)
                $parameters['baja'] = 1;
            else
                $parameters['baja'] = 0;
            try {
                // creo la instancia connection
                $this->connection = Connection::getInstance();
                // Ejecuto la sentencia.
                return $this->connection->ExecuteNonQuery($sql, $parameters);
            } catch (\PDOException $ex) {
                throw $ex;
            }
        }
    }

    /**
     *
     */
    public function delete($email)
    {
        $sql = "UPDATE usuarios SET baja = true where email = '$email'";
        try {
            $this->connection = Connection::getInstance();
            return $this->connection->ExecuteNonQuery($sql);
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
    /**
     * Transforma el listado de usuario en
     * objetos de la clase Usuario
     *
     * @param  Array $gente Listado de personas a transformar
     */
    protected function mapear($value)
    {
        $value = is_array($value) ? $value : [];
        $resp = array_map(function ($p) {
            $perfilUser = new perfilUser($p['nombre'], $p['apellido'], $p['dni']);
            $user = new User($p['email'], $p['contrasenia'], $perfilUser, $p['rol']);
            if ($p['baja'] == 1)
                $user->setBaja(true);
            else
                $user->setBaja(false);
            $user->setIdUsuario($p['idUsuario']);
            return $user;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}
