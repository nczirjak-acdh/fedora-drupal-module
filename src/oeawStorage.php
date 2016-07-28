<?php

namespace Drupal\oeaw;

class oeawStorage {

  static function getAll() {
    $result = db_query('SELECT * FROM {oeaw}')->fetchAllAssoc('id');
    return $result;
  }

  static function exists($id) {
    return (bool) $this->get($id);
  }

  static function get($id) {
    $result = db_query('SELECT * FROM {oeaw} WHERE id = :id', array(':id' => $id))->fetchAllAssoc('id');
    if ($result) {
      return $result[$id];
    }
    else {
      return FALSE;
    }
  }

  static function add($name, $message) {
    db_insert('oeaw')->fields(array(
      'name' => $name,
      'message' => $message,
    ))->execute();
  }

  static function edit($id, $name, $message) {
    db_update('oeaw')->fields(array(
      'name' => $name,
      'message' => $message,
    ))
    ->condition('id', $id)
    ->execute();
  }
  
  static function delete($id) {
    db_delete('oeaw')->condition('id', $id)->execute();
  }
  
  /* Get All Data from Fedora 4 DB */
  static function getAllSparqlData()
  {
    $sparql = new \EasyRdf_Sparql_Client('http://127.0.0.1:8080/bigdata/sparql');
    $result = $sparql->query('SELECT * WHERE { ?s ?p ?o}');
    
    $returnArray = array();
    
    $i=0;
    
    foreach ($result as $d)
    {
        foreach($d as $key => $value)
        {           
            $returnArray[$i][$key] = $value;
        }
        $i++;
    }
    
    return $returnArray;
    
  }
  
  static function getSparqlDataByUri($uri)
  {
    $sparql = new \EasyRdf_Sparql_Client('http://127.0.0.1:8080/bigdata/sparql');
    $result = $sparql->query('SELECT * WHERE { <'.$uri.'> ?p ?o}');
    
    $returnArray = array();
    
    $i=0;
    
    foreach ($result as $d)
    {
        foreach($d as $key => $value)
        {           
            $returnArray[$i][$key] = $value;
        }
        $i++;
    }
    
    return $returnArray;
    
  }
  
}
