<?php

namespace Sokoloowski;

/**
 * https://ichi.pro/pl/pisanie-parsera-czesc-i-pierwsze-kroki-75567545937097
 */
class Lexer
{
    private string $input;

    public function __construct(string $input)
    {
        $this->input = $input;
    }

    public function peek(int $k = 0) : string
    {        
        return $this->input[$k];
    }

    public function consume(int $k = 0) : string
    {
        $char = $this->input[$k];
        $this->input = substr($this->input, 0, $k) . substr($this->input, $k + 1);
    
        return $char;
    }
    
    public function isEOF() : bool
    {    
        return strlen($this->input) === 0;
    }
}
