<?php namespace interfaces;

interface IDao {

    function add($value);
    function getAll();
    function delete($value);
    function update($value1, $value2);

}