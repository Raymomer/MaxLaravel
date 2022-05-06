<?php

namespace App\Exceptions;

use Exception;

class CommonException extends Exception
{

    public $status;
    public $message;

    public function __construct(int $status, string $message = '')
    {
        $this->status  = $status;
        $this->message = $message;
        parent::__construct($message, $status);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    // public function report()
    // {
        
    // }
 
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response([
            'status'  => false,
            'message' => $this->message,
        ], $this->status);
    }
}
