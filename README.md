# Behat TextMate 2 Bundle

This [TextMate][tm] bundle adds language support for the [Cucumber][cl] story format. 
A few [basic commands](#commands) which allow interacting with feature files, and helpful [tab completion](#tab-completion).

## Installation

#### Download

Simply download the latest [Behat.tmbundle][dl], and extract `tar.gz` archive into TextMates 
application support directory.

    mkdir -p ~/Library/Application\ Support/Avian/Bundles
    cd ~/Library/Application\ Support/Avian/Bundles
    tar -zxf ~/Downloads/Behat.tmbundle.tar.gz

#### From Source

Simply clone repository into TextMate's bundle directory.

    mkdir -p ~/Library/Application\ Support/Avian/Bundles
    cd ~/Library/Application\ Support/Avian/Bundles
    git clone git://github.com/emcconville/Behat.tmbundle

  
## Commands

&#8984;R &mdash; Run current feature file

&#8997;&#8984;R &mdash; Run current scenario in current feature file

&#8963;H &mdash; Display defined language in feature context

&#8963;M &mdash; Print undefined language missing from feature context

&#8963;&#8679;V &mdash; Quick feature validation

&#8963;&#8679;H &mdash; Reformat & clean ("tidy") current document format

&#8997;&#9099; &mdash; Suggested variables defined under "Example:" section

## Tab Completion

*Fe*&#x21E5; &mdash; becomes **Feature:**

*Sc*&#x21E5; &mdash; becomes **Scenario:**

*Sc*&#x21E5;&#x21E5; &mdash; becomes **Scenario Outline:**

*Ex*&#x21E5; &mdash; becomes **Examples:**


### Todo

 - TextMate friendly documentation
 - Rewrite all commands in native PHP
 - Basic composer.phar install command
 - Support for running tagged featured
 - Build all feature

### History

 - 1.0 August 2013
  - [Cucumber language][cl] syntax
  - Run single feature, or scenario
  - Tab & Escape Completion
  - Variable suggestion
  - Validated & Tidy commands (available only in [composer.phar][composer] install)


 [cl]: http://en.wikipedia.org/wiki/Cucumber_(software) "Cucumber (software)"
 [tm]: https://github.com/textmate/textmate "Text Mate"
 [dl]: https://github.com/emcconville/Behat.tmbundle/releases/download/v1.0/Behat.tmbundle.tar.gz "Download"
 [composer]: http://getcomposer.org/