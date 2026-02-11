<?php
namespace App\Global;

class Methods{
  public static function collectQueryResults(array $columns, array $items){
    // dump($columns);
    // dd($items);
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