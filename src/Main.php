<?php

error_reporting(E_ALL);

use Sokoloowski\Lexer;

class Main
{
    private static function color(string $color): string
    {
        $colors = [
            "php_tag" => "#1e88e5",
            "numeric" => "#a5d6a7",
            "math_operator" => "#ffccbc",
            "variable" => "#81d4fa",
            "text" => "#ff8a65"
        ];

        return sprintf('<span style="color: %s">', $colors[$color]);
    }

    public static function main(): void
    {
        $tokens = [];

        $html = <<<EOT
<html>
    <head>
        <style>
            html,
            body {
                background-color: #263238;
                color: #fefefe;
            }

            pre {
                font-family: "Fira Code";
            }
        </style>
    </head>

    <body>
        <!-- Auto-generated stuff -->
<pre>
EOT;

        $lex = new Lexer(
            file_get_contents(__DIR__ . "/example.php")
        );

        do {
            $char = $lex->peek();

            if (preg_match("/\s/", $char)) {
                $html .= $lex->consume();
                continue;
            }

            if (preg_match("/\d/", $char)) {
                $html .= self::color("numeric");
                while (preg_match("/\d/", $lex->peek())) {
                    $html .= $lex->consume();
                }
                if ($lex->peek() === ".") {
                    $html .= $lex->consume();
                    
                    if (!preg_match("/\d/", $lex->peek())) {
                        die(sprintf("Expected digit on line %d at position %d\n", $lex->getLine(), $lex->getColumn()));
                    }

                    while (preg_match("/\d/", $lex->peek())) {
                        $html .= $lex->consume();
                        if ($lex->peek() === ".") {
                            die(sprintf("Expected digit on line %d at position %d, got \".\"\n", $lex->getLine(), $lex->getColumn()));
                        }
                    }

                    $tokens[] = "float ";
                } else {
                    $tokens[] = "integer ";
                }
            } elseif ($char === "$") {
                $html .= self::color("variable");
                $html .= $lex->consume();

                if (preg_match("/[^_a-zA-Z]/", $lex->peek())) {
                    die(sprintf("Expected letter or `_` on line %d at position %d\n", $lex->getLine(), $lex->getColumn()));
                }

                while (preg_match("/[^\s]/", $lex->peek())) {
                    $html .= $lex->consume();
                }
                $tokens[] = "variable ";
            } elseif ($char === "<") {
                if ($lex->peek(1) === "?") {
                    $html .= self::color("php_tag");

                    // <?
                    $html .= $lex->consume();
                    $html .= $lex->consume();

                    $php = "";
                    $php .= $lex->consume();
                    $php .= $lex->consume();
                    $php .= $lex->consume();

                    if ($php !== "php") {
                        die(sprintf("Expected \"<?php\" on line %d at position %d\n", $lex->getLine(), $lex->getColumn()));
                    }

                    $html .= $php;

                    if (!preg_match("/\s/", $lex->peek())) {
                        die(sprintf("Expected \" \" on line %d at position %d, found \"%s\"\n", $lex->getLine(), $lex->getColumn(), $lex->consume()));
                    }
                    $tokens[] = "php_opening_tag ";
                } elseif ($lex->peek(1) === " " || $lex->peek(1) === "=" || preg_match("/\d/", $lex->peek(1))) {
                    $html .= self::color("math_operator");
                    $html .= $lex->consume();
                    $tokens[] = "math_operator ";
                }
            } elseif (preg_match("/[\+\-\/\*=\>]/", $char)) {
                if ($lex->peek(1) === " " || ($char === ">" && $lex->peek(1) === "=") || preg_match("/\d/", $lex->peek(1))) {
                    $html .= self::color("math_operator");
                    $html .= $lex->consume();
                    $tokens[] = "math_operator ";
                }
            } elseif ($char === "'" || $char === '"') {
                $html .= self::color("text");
                $html .= $lex->consume();
                while ($lex->peek() !== '"' && $lex->peek() !== "'") {
                    if ($lex->peek() === "\n") {
                        die(sprintf("Unexpected new line on line %d at position %d\n", $lex->getLine(), $lex->getColumn()));
                    }

                    $html .= $lex->consume();
                }
                $html .= $lex->consume();
                $tokens[] = "text ";
            } elseif ($char === ";") {
                $html .= $lex->consume();

                $tokens[] = "semicolon\n";
            } else {
                $html .= $lex->consume();

                $tokens[] = "other ";
            }

            $html .= "</span>";

            // echo sprintf("--- %s ---", $lex);
        } while (!$lex->isEOF());

        $html .= <<<EOT
</pre>
        <!-- Auto-generated stuff -->
    </body>
</html>
EOT;
        if (!file_exists(__DIR__ . '/../out')) {
            mkdir(__DIR__ . '/../out');
        }
        file_put_contents(__DIR__ . '/../out/index.html', $html);

        echo implode(" ", $tokens);

        echo PHP_EOL;
    }
}
