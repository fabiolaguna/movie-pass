<?php

namespace controllers;

class ApiController
{

  public static function getMovies()
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.themoviedb.org/3/movie/now_playing?page=1&language=es-AR&api_key=3bb2471ff9b8f2b77329d9c10ab2dfb4",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $arrayToDecode = ($response) ? json_decode($response, true) : array();
      return $arrayToDecode;
    }
  }

  public static function getGenresMovies()
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.themoviedb.org/3/genre/movie/list?language=es-AR&api_key=3bb2471ff9b8f2b77329d9c10ab2dfb4",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $arrayToDecode = ($response) ? json_decode($response, true) : array();
      return $arrayToDecode;
    }
  }
}
