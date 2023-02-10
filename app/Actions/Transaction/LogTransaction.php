<?php

namespace App\Actions\Transaction;

class LogTransaction
{
    private $from_loggable;
    private array $from_log_details;
    private $to_loggable;
    private array $to_log_details;
    public function __construct($from_loggable, $from_log_details, $to_loggable, $to_log_details)
    {
        $this->from_loggable = $from_loggable;
        $this->from_log_details = $from_log_details;
        $this->to_loggable = $to_loggable;
        $this->to_log_details = $to_log_details;
    }

    public function execute()
    {
        $this->from_loggable->create($this->from_log_details);
        $this->to_loggable->create($this->to_log_details);
    }
}
