<?php


namespace API\Models;

use DateTime;
use API\Core\Container\MicroDI;
use API\Core\Utils\Logger;
use API\Core\Utils\Translate;


class Quotes {
    private int $id;
    private string $author;
    private string $quote;
    private int $lastTimeRead;
    private int $hitCounter;

    public function __construct(){
        $args = func_get_args();
        $numArgs = func_num_args();
        if ($numArgs === 0) {
            $this->id = (int)$this->id;
            $this->author = (string)$this->author;
            $this->quote = (string)$this->quote;
            $this->lastTimeRead = (int) $this->lastTimeRead;
            $this->hitCounter = (int)$this->hitCounter;
        } else {
            call_user_func_array([$this,'__construct1'], $args);
        }
    }

    public function __construct1()
    {
        $args = func_get_args();
        $this->id = 0;
        $this->quote = (string)$args[0];
        $this->author = (string)$args[1];
        $this->lastTimeRead = 0;
        $this->hitCounter =0;
    }




    public function getQuote(): string
    {
        return $this->quote;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }
    public function setQuote(string $quote) :  self
    {
        $this->quote = $quote;
        return $this;
    }
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @param int $lastTimeRead
     */
    public function setLastTimeRead(int $lastTimeRead): void
    {
        $this->lastTimeRead = $lastTimeRead;
    }
}

