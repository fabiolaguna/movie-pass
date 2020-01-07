<?php

namespace models;

class Direccion
{	
	private $idDireccion;
	private $provincia;
	private $ciudad;
	private $calle;
	private $altura;

	function __construct($provincia, $ciudad, $calle, $altura)
	{
		$this->idDireccion = null;
		$this->provincia = $provincia;
		$this->ciudad = $ciudad;
		$this->calle = $calle;
		$this->altura = $altura;
	}

	public function getIdDireccion(){
		return $this->idDireccion;
	}
	public function getCalle()
	{
		return $this->calle;
	}
	public function getAltura()
	{
		return $this->altura;
	}
	public function getProvincia()
	{
		return $this->provincia;
	}
	public function getCiudad()
	{
		return $this->ciudad;
	}

	public function setIdDireccion($direccion){
		$this->idDireccion = $direccion;
	}
	public function setCalle($calle)
	{
		$this->calle = $calle;
	}
	public function setAltura($altura)
	{
		$this->altura = $altura;
	}
	public function setProvincia($provincia)
	{
		$this->provincia = $provincia;
	}
	public function setCiudad($ciudad)
	{
		$this->ciudad = $ciudad;
	}
}
