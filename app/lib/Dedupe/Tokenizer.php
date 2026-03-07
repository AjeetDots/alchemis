<?php

namespace Dedupe;

class Tokenizer
{

  public static function run($string, $commonphrases = [], $commonwords = [])
  {
    $tokenizer = new Tokenizer;
    $string = $tokenizer->removePunctuation($string);
    $string = $tokenizer->removeCommonPhrases($string, $commonphrases);
    $tokens = $tokenizer->split($string);
    $tokens = $tokenizer->removeCommonWords($tokens, $commonwords);

    return [
      'tokens' => $tokens,
      'now' => $tokenizer->getNow($tokens),
      'previous' => $tokenizer->getPrevious($tokens)
    ];
  }

  public function removePunctuation($string)
  {
    $string = str_replace('\'', '', $string);
    $string = str_replace('_', ' ', $string);
    $string = trim($string);
    return preg_replace('/[^\w&]+/', ' ', $string);
  }

  public function removeCommonPhrases($string, $commonphrases)
  {
    return trim(str_ireplace($commonphrases, '', $string));
  }

  public function split($string)
  {
    $string = preg_split('/\s+/', $string);
    return array_filter($string);
  }

  public function removeCommonWords($tokens, $commonwords)
  {
    $lowerTokens = array_map('strtolower', $tokens);
    foreach($commonwords as $word){
      $key = array_search(strtolower($word), $lowerTokens);
      if($key !== false) unset($tokens[$key]);
    }
    return $tokens;
  }

  public function getNow($tokens)
  {
    $words = ['see', 'now'];
    $index = $this->arraySearch($tokens, $words);
    if($index && $index > 0){
      return array_slice($tokens, $index + 1);
    }
    return null;
  }

  public function getPrevious($tokens)
  {
    $words = ['previously'];
    $index = $this->arraySearch($tokens, $words);
    if($index && $index > 0){
      return array_slice($tokens, $index + 1);
    }
    return null;
  }

  // search array for words, return last index
  public function arraySearch($array, $words)
  {
    $array = array_map('strtolower', array_reverse($array, true));
    $index = null;
    foreach($words as $word){
      $i = array_search($word, $array);
      if(!$index || $i > $index) $index = $i;
    }
    return $index;
  }

}
