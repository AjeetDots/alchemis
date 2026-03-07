<?php

use Illuminate\Database\Capsule\Manager as DB;

class Dedupe {

  // 1. prepare data
  public static function prepareData()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $prepare = new Dedupe\Prepare();
    return $prepare->run();
  }

  // 2. Find duplicate sites
  public static function siteDuplicates($companies)
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $deduper = new Dedupe\SiteDuplicates($companies);
    return $deduper->run();
  }

  // 3. Prepare sites for merging
  public static function siteMergePrepare()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $deduper = new Dedupe\SiteMergePrepare();
    return $deduper->run();
  }

  // 4. Merge duplicate sites
  public static function mergeSites()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $deduper = new Dedupe\MergeSites();
    return $deduper->run();
  }

  // 5. Group companies based on common name
  public static function groupCompanies($company_ids)
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $deduper = new Dedupe\GroupCompanies($company_ids);
    return $deduper->run();
  }
  
  // 6. Create parent companies
  public static function createCompanies()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $deduper = new Dedupe\CreateCompanies();
    return $deduper->run();
  }

  // 7. Find duplicate posts
  public static function postDuplicates($company_ids)
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $deduper = new Dedupe\PostDuplicates($company_ids);
    return $deduper->run();
  }

  // 8. Merge duplicate posts
  public static function mergePosts()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $deduper = new Dedupe\MergePosts();
    return $deduper->run(); 
  }

}