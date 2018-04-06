<?php

namespace Guzzle\Service\Exception;

use Guzzle\Common\Exception\RuntimeException;

class ValidationException extends RuntimeException
{
protected $errors = array();






public function setErrors(array $errors)
{
$this->errors = $errors;
}






public function getErrors()
{
return $this->errors;
}
}
