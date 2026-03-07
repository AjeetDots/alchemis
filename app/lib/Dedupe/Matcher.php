<?php

namespace Dedupe;

use Illuminate\Support\Collection;
use webd\language\StringDistance;

class Matcher
{

  public $weighting = 0.75;

  public function tokenMatch($arr1, $arr2)
  {

    $tokens1 = !is_array($arr1) ? explode(' ', $arr1) : $arr1;
    $tokens2 = !is_array($arr2) ? explode(' ', $arr2) : $arr2;
    $tokens1lower = array_map('strtolower', $tokens1);
    $tokens2lower = array_map('strtolower', $tokens2);

    $matches = [];
    // create matches and scores
    foreach($tokens1lower as $index => $value){
      $keys = array_keys($tokens2lower, $value);
      if(!empty($keys)){
        foreach($keys as $key){
          if(array_key_exists($key, $matches)){
            $matches[$key]['score'] += $this->score($index, $key);
          }else{
            $matches[$key] = [
              'value' => $tokens1[$index],
              'score' => $this->score($index, $key)
            ];
          }
        }
      }
    }
    if(!$matches) return [];

    ksort($matches);
    $lastKey = @end(array_keys($matches));

    // put words together, check they exist in tokens1
    $finalmatches = [];
    $testAgainst = implode(' ', $tokens1);
    $words = '';
    $score = 0;
    for ($i=0; $i <= $lastKey; $i++) { 
      $match = isset($matches[$i]) ? $matches[$i] : null;
      if($match && stripos($testAgainst, $words . $match['value']) !== false){
        $words .= $match['value'] . ' ';
        $score += $match['score'];
      }elseif($score){
        $words = trim($words . $match['value']);
        if(stripos($testAgainst, $words) !== false){
          $finalmatches[] = [
            'match' => $words,
            'score' => $score
          ];
        }
        $words = $match['value'] . ' ';
        $score = $match['score'];
      }else{
        $words = $match['value'] . ' ';
        $score = $match['score'];
      }
    }
    
    $words = trim($words);
    if($score > 0 && stripos($testAgainst, $words) !== false){
      $finalmatches[] = [
        'match' => $words,
        'score' => $score
      ];
    }

    return $finalmatches;
  }

  public function score($indexA, $indexB)
  {
    $scoreA = $this->weight($indexA, $this->weighting);
    $scoreB = $this->weight($indexB, $this->weighting);
    return round($scoreA * $scoreB, 2);
  }

  public function weight($index, $weighting)
  {
    $score = 1.00;
    $index++;
    for ($i=1; $i < $index; $i++) { 
      $score = round($score * $weighting, 2);
    }
    return $score;
  }

  public function compareItems($item, $items, $fieldA, $fieldsB, $identifier){
    $matches = [];

    foreach($items as $i){
      if($item->$identifier == $i->$identifier) continue;
      if(isset($i->$identifier)){
        $tokenMatches = [];
        foreach($fieldsB as $field){
          if(isset($i->$field)){
            $tokenMatches += $this->tokenMatch($item->$fieldA, $i->$field);
          }
        }
        if(!count($tokenMatches)) continue;

        // get top match & total match score
        $total_score = 0;
        $sort = new Collection($tokenMatches);
        $sort->sortByDesc('score')->values();
        foreach($sort as $s){
          $total_score += $s['score'];
        }
        $top = $sort->first();

        $jaro = $this->similarText($item->$fieldA, $i->$fieldsB[0]);

        $matches[] = [
          'comp_score' => round($top['score'] * $jaro, 2),
          'score' => $top['score'],
          'jaro' => round($jaro, 2),
          'field1' => $item->$fieldA,
          'field2' => $i->$fieldsB[0],
          'value' => $top['match'],
          'total_score' => $total_score,
          $identifier => $i->$identifier,
          $field => $i->$field,
        ];
      }
    }

    // sort matches by score
    $matches = new Collection($matches);
    $matches->sortByDesc('comp_score')->values();
    
    return $matches;
  }

  public static function run($item, $items, $fieldA, $fieldsB, $identifier){

    $instance = new Matcher;
    $compared = $instance->compareItems($item, $items, $fieldA, $fieldsB, $identifier);

    return $compared;

  }

  public static function findSimilar($items, $field, $identifier, $minScore = 0.75, $keep = false)
  {
    // test each item against each other
    // group similar items
    $instance = new Matcher;
    $matrix = $instance->similarityMatrix($items, $field, $identifier);
    $groups = $instance->groupMatrix($matrix, $minScore, $keep);

    // rejoin items by identifier
    $joinedGroups = [];
    $matched = [];
    foreach($groups as $group){
      $filtered = $items->filter(function ($item) use ($identifier, $group, &$matched) {
        if(in_array($item->$identifier, $group)){
          $matched[] = $item->$identifier;
          return true;
        }
        return false;
      });
      if(count($filtered)) $joinedGroups[] = $filtered;
    }

    if($keep){
      // keep unmatched
      $diff = array_unique(array_diff($items->lists($identifier), $matched));
      foreach($diff as $unmatched){
        $joinedGroups[] = new Collection([$items->first(function ($key, $item) use ($unmatched, $identifier) {
          return $item->$identifier == $unmatched;
        })]);
      }
    }

    return $joinedGroups;
  }

  public function similarityMatrix($items, $field, $identifier)
  {
    $matrix = [];
    foreach($items as $itemA){
      $matrix[$itemA->$identifier] = [];

      foreach($items as $itemB){
        if($itemA->$identifier == $itemB->$identifier) continue;
        $score = StringDistance::JaroWinkler(strtolower($itemA->$field), strtolower($itemB->$field));
        $matrix[$itemA->$identifier][$itemB->$identifier] = $score;
      }
    }

    return $matrix;
  }

  public function groupMatrix($matrix, $minScore = 0.75)
  {
    $groups = [];
    $groupMap = [];

    foreach($matrix as $key => $m){
      foreach($m as $id => $item){
        if($item >= $minScore){
          if(array_key_exists($key, $groupMap)){
            // add item to same group as $key
            $groupKey = $groupMap[$key];
          }else{
            // if no group has key create it and add both
            $groups[] = [$key];
            $groupKey = count($groups) - 1;
            $groupMap[$key] = $groupKey;
          }
          if(!array_key_exists($id, $groupMap)){
            $groups[$groupKey][] = $id;
            $groupMap[$id] = $groupKey;
          }
        }

      }

    }

    return $groups;
  }

  public function similarText($str1, $str2)
  {
    return StringDistance::JaroWinkler(strtolower($str1), strtolower($str2));
  }

}
