<?php namespace Dedupe;

use Illuminate\Support\Collection;

class GroupCompanies implements Deduper {

  private $company_ids;

  public function __construct($company_ids = null)
  {
    $this->company_ids = $company_ids;
  }

  public function run()
  {

    \app_model_DedupeParentCompany::truncate();
    
    $matched = [];
    
    $commonphrases = [
      'DUPE RECORD',
      'DUPE SEE',
      'SEE DUPE',
      'DO NOT USE',
      'SEE OTHER RECORD',
      'NOW PART OF',
      'Asset management'
    ];

    $commonwords = [
      'DUPE',
      'LTD',
      'LIMITED',
      'PLC',
      'LLP',
      'INC',
      'CO',
      'THE',
      'PTE',
      'COMPANY',
      'CORP',
      'LLC',
      'group'
    ];
    
    $query = \app_model_Company::select('id', 'name')
      ->whereNull('parent_company_id')
      ->where('deleted', 0);

    // test
    // $query->whereIn('id', [
    //   11648
    // ]);
    
    // if($this->company_ids){
    //   $query->whereIn('id', $this->company_ids);
    // }

    $companies = $query->orderBy('name')->get();
    
    $undo = include APP_DIRECTORY . 'app/lib/Dedupe/parentcompanyundo.php';
    
    $tokenizer = new Tokenizer;
    foreach ($companies as $c) {
      if(in_array($c->id, $matched)) continue;
      // if in undo list, create own row
      if(in_array($c->id, $undo)){
        $output[] = [
          'company' => $c->name,
          'id' => $c->id,
          'site' => $c->name
        ];
        $matched[] = $c->id;
        \app_model_DedupeParentCompany::create([
          'parent' => trim($c->name),
          'company_ids' => $c->id
        ]);
        continue;
      }
      
      // get companies with same first word
      $tokens = $tokenizer->split($c->name);
      $search = $tokens[0];
      
      $q = \app_model_Company::select('id', 'name')
        ->whereNull('parent_company_id')
        ->where('deleted', 0);
      
      if(strtolower($search) == 'the'){
        $search = $tokens[1];
        // if under so many chars add space so it only matches the full word as first word
        $sQuery = (strlen($search) > 4 || count($tokens) == 2) ? $search : $search . ' ';
        $q->where('name', 'LIKE', 'The ' . $sQuery . '%');
      }else{
        // if under so many chars add space so it only matches the full word as first word
        $sQuery = (strlen($search) > 4 || count($tokens) == 1) ? $search : $search . ' ';
        $q->where('name', 'LIKE', $sQuery . '%');
      }
      
      // don't match on matched or undo companies
      $nomatch = array_unique(array_merge($matched, $undo));
      $similarCompanies = $q->get()->filter(function ($c) use ($nomatch) {
        return !in_array($c->id, $nomatch);
      });
      
      foreach ($similarCompanies as $s) {
        $tokens = Tokenizer::run($s->name, $commonphrases, $commonwords)['tokens'];
        $s->tokens = implode(' ', $tokens);
      }
      
      $matchGroups = Matcher::findSimilar($similarCompanies, 'tokens', 'id', 0.95, true);
      foreach ($matchGroups as $matches) {
        $matches = array_values($matches->toArray());
        
        // get shortest match & add to matched array
        $ids = [];
        $match = '';
        foreach ($matches as $m){
          $matched[] = $m['id'];
          $ids[] = $m['id'];
          
          if(empty($match) || strlen($m['tokens']) < strlen($match)){
            $match = $m['tokens'];
          }
        }
        
        // test output
        if(count($matches) > 1){
          $output[] = [
            'company' => trim($match),
            'id' => $matches[0]['id'],
            'site' => $matches[0]['name']
          ];
          foreach ($matches as $key => $m) {
            if($key == 0) continue;
            $output[] = [
              'company' => '',
              'id' => $m['id'],
              'site' => $m['name']
            ];
          }
        }
        
        \app_model_DedupeParentCompany::create([
          'parent' => trim($match),
          'company_ids' => implode(',', $ids)
        ]);
        
      }
      
    }

    return $output;
  }

}