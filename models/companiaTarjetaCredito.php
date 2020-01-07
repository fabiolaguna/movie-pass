<?php

namespace models;

class CompaniaTarjetaCredito
{
	private $idCompania;
	private $nombreCompania;

	function __construct($idCompania, $nombreCompania)
	{
		$this->idCompania = $idCompania;
		$this->nombreCompania = $nombreCompania;
	}

	public function getIdCompania()
	{
		return $this->idCompania;
	}
	public function getNombreCompania()
	{
		return $this->nombreCompania;
	}

	public function setIdCompania($idCompania)
	{
		$this->idCompania = $idCompania;
	}
	public function setNombreCompania($nombreCompania)
	{
		$this->nombreCompania = $nombreCompania;
	}
}
