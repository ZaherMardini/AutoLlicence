<?php
namespace App\Global;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class Methods{
  public static function collectQueryResults(array $columns, array $items){
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

  public static function filter(Builder $builder, Request $request, array $searchBy, array $numericKeys){
    $key = $request['searchKey'];
    $value = $request['value'];
    if(!in_array($key, $searchBy, true)){
        return response()->json(abort('401','Invalid filter'));
    };
    if(in_array($key, $numericKeys, true) && $value != ''){
      $builder->where($key, $value);
    }
    else{
      $builder->where($key, 'like', "%{$value}%");
    };
    $items = $builder->get();
    return $items;
  }
}