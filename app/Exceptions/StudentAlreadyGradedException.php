<?php

namespace App\Exceptions;

//use Exception;
use Illuminate\Database\QueryException;

class StudentAlreadyGradedException extends \Exception
{
    protected $details;

    public function __construct($details){
        $this->details = $details;
        parent::__construct();
    }
}
