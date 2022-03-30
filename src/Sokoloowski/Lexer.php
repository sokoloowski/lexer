<?php

namespace Sokoloowski;

use Exception;

/**
 * https://ichi.pro/pl/pisanie-parsera-czesc-i-pierwsze-kroki-75567545937097
 */
class Lexer
{
    private string $input;
    private int $column;
    private int $line;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->column = 1;
        $this->line = 1;
    }

    public function __toString()
    {
        return $this->input;
    }

    public function getColumn() : int
    {
        return $this->column;
    }

    public function getLine() : int
    {
        return $this->line;
    }

    public function peek(int $k = 0) : string
    {
        try {
            return $this->input[$k];
        } catch (Exception $e) {
            return "";
        }
    }

    public function consume(int $k = 0) : string
    {
        try {
            $char = $this->input[$k];
        } catch (Exception $e) {
            return "";
        }

        if ($char === "\n") {
            $this->line++;
            $this->column = -1;
        } else {
            $this->column++;
        }

        $this->input = substr($this->input, 0, $k) . substr($this->input, $k + 1);
    
        return $char;
    }
    
    public function isEOF() : bool
    {    
        return strlen($this->input) === 0;
    }
}
