<?php

error_reporting(E_ALL);

use Sokoloowski\Lexer;

class Main
{
    public static function main(): void
    {
        $tokens = [];
        $lex = new Lexer("<?php 512<61.23+132-123*432>2435/5454<=56>=34");

        do {
            $char = $lex->consume();

            if (preg_match("/\d/", $char)) {
                while (preg_match("/\d/", $lex->peek())) {
                    $lex->consume();
                }
                if ($lex->peek() === ".") {
                    $lex->consume();
                    if (!preg_match("/\d/", $lex->peek())) {
                        die(sprintf("Expected digit on line %d at position %d\n", $lex->getLine(), $lex->getColumn()));
                    }
                    
                    while (preg_match("/\d/", $lex->peek())) {
                        $lex->consume();
                    }

                    $tokens[] = "float ";
                } else {
                    $tokens[] = "integer ";
                }
            } elseif ($char === "<") {
                if ($lex->peek() === "?") {
                    if (sprintf("%s%s%s", $lex->peek(1), $lex->peek(2), $lex->peek(3)) === "php") {
                        $lex->consume();
                        $lex->consume();
                        $lex->consume();
                        $lex->consume();
                        if (!preg_match("/\s/", $lex->consume())) {
                            die(sprintf("Expected \" \" on line %d at position %d\n", $lex->getLine(), $lex->getColumn()));
                        }
                        $tokens[] = "php_opening_tag ";
                    }
                } elseif ($lex->peek() === " " || $lex->peek() === "=" || preg_match("/\d/", $lex->peek())) {
                    $tokens[] = "math_operator ";
                }
            } elseif (preg_match("/[\+\-\/\*\>]/", $char)) {
                if ($lex->peek() === " " || (preg_match("/[<>]/", $char) && $lex->peek() === "=") || preg_match("/\d/", $lex->peek())) {
                    $tokens[] = "math_operator ";
                }
            }

            // echo sprintf("--- %s ---", $lex);
        } while (!$lex->isEOF());

        echo implode(" ", $tokens);

        echo PHP_EOL;
    }
}
