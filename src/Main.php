<?php

error_reporting(E_ALL);

use Sokoloowski\Lexer;

class Main
{
    public static function main() : void
    {
        $lex = new Lexer("input string");

        do {
            echo $lex->consume();
        } while (!$lex->isEOF());

        echo PHP_EOL;
    }
}
