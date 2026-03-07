<?php

namespace spec\Dedupe;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dedupe\Tokenizer');
    }

    function it_tokenizes_strings()
    {
      $this->split('Test String')
        ->shouldReturn(['Test', 'String']);

      // handles double space
      $this->split('Test  String')
        ->shouldReturn(['Test', 'String']);
    }

    function it_removes_punctuation()
    {
      $this->removePunctuation('Test.=String')
        ->shouldReturn('Test String');

      $this->removePunctuation('Test_String')
        ->shouldReturn('Test String');
    }

    function it_trims_output()
    {
      $this->removePunctuation(' Test String ')
        ->shouldReturn('Test String');
    }

    function it_removes_passed_words()
    {
      $this->removeCommonWords(['Test', 'String', 'DUPE'], ['DUPE'])
        ->shouldReturn(['Test', 'String']);

      $this->removeCommonPhrases('Test String SEE OTHER RECORD', [
        'see other record'
      ])->shouldReturn('Test String');
    }

    function it_keeps_ampersand()
    {
      $this->removePunctuation('Johnson & Johnson')
        ->shouldReturn('Johnson & Johnson');
    }

    function it_works_on_co()
    {
      $this->run('The Coca-Cola Co.', [], ['CO'])
        ->shouldReturn([
          'tokens' => ['The', 'Coca', 'Cola'],
          'now' => null,
          'previous' => null
        ]);
    }

}
