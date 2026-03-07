<?php namespace Dedupe;

use Illuminate\Database\Capsule\Manager as DB;

class Prepare implements Deduper {

  public function run()
  {
    $this->tokenize();
    $this->trimCompanyNames();
    $this->trimPostcodes();
    $this->trimAddressLine1();
  }

  public function tokenize()
  {
    $commonphrases = [
      'DUPE RECORD',
      'DUPE SEE',
      'SEE DUPE',
      'DO NOT USE',
      'SEE OTHER RECORD',
      'NOW PART OF',
      'Asset management'
    ];

    $commonwords = include APP_DIRECTORY . 'app/lib/Dedupe/commonwords.php';

    // truncate tokens
    \app_model_CompanyToken::truncate();

    $companies = \app_model_Company::select('id', 'name')->get();

    $companies->each(function ($c) use ($commonwords, $commonphrases) {
      // run tokenizer
      $results = Tokenizer::run($c->name, $commonphrases, $commonwords);
      // save results back to token table
      \app_model_CompanyToken::create([
        'company_id' => $c->id,
        'tokens' => $results['tokens'] ? implode(' ', $results['tokens']) : '',
        'now' => $results['now'] ? implode(' ', $results['now']) : null,
        'previous' => $results['previous'] ? implode(' ', $results['previous']) : null,
      ]);
    });

    return $companies->count();
  }

  public function trimCompanyNames()
  {
    DB::update('update tbl_companies set name = TRIM(name)');
  }

  public function trimPostcodes()
  {
    DB::update('update tbl_sites set postcode = TRIM(postcode)');
  }

  public function trimAddressLine1()
  {
    DB::update('update tbl_sites set address_1 = TRIM(address_1)');
  }

}