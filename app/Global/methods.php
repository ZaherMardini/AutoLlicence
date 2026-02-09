<?php
namespace App\Global;

class Methods{
  public static function collectCustomQueryResults(array $columns, array $items){
    $result = [];
    $results = [];
    foreach ($items as $item) {
      foreach($columns as $title => $column){
        $result[$column] = $item->$column;
      }
    }
    $results[] = $result;
    return collect($results);
  }
}