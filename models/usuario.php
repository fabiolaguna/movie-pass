<?php

namespace models;

class Usuario
{
    private $email;
    private $contrasenia;
    private $perfilUsuario;
    private $rol;
    private $baja;

    function __construct($email=null, $contrasenia=null, $perfilUsuario=null, $rol=null)
    {
        $this->email = $email;
        $this->contrasenia = $contrasenia;
        $this->perfilUsuario = $perfilUsuario;
        $this->rol = $rol;
        $this->baja = false;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function getContrasenia()
    {
        return $this->contrasenia;
    }
    public function getPerfilUsuario()
    {
        return $this->perfilUsuario;
    }
    public function getRol()
    {
        return $this->rol;
    }
    public function getBaja()
    {
        return $this->baja;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setContrasenia($contrasenia)
    {
        $this->contrasenia = $contrasenia;
    }
    public function setPerfilUsuario($perfilUsuario)
    {
        $this->perfilUsuario = $perfilUsuario;
    }
    public function setRol($rol)
    {
        $this->rol = $rol;
    }
    public function setBaja($baja)
    {
        $this->baja = $baja;
    }
}
