<?php

/**
 * sfGeshiExample actions.
 *
 * @package     sfGeshiPlugin
 * @author      Tomasz Ducin <tomasz.ducin@gmail.com>
 */

class sfGeshiExampleActions extends sfActions
{
    /**
     * Demo action. Displays code highlighting example.
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->code_single = sfGeshi::parse_single(implode("", file(__FILE__)), 'php');
        $this->code_mixed = sfGeshi::parse_mixed("Hello, mom, this is <b>PHP</b> language!
[php]\n<?php\n    echo \"hello GeSHi\";\n?>\n[/]
Wait, mom, the following is <b>C</b> language!
[cpp]
class Symfony {
  public:
    Symfony() {
    }
};
[/]
Bye, mom!");
    }
}
