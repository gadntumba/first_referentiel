<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serializer;

use Symfony\Component\Serializer\Exception\UnexpectedValueException as Base ;


/**
 * UnexpectedValueException.
 *
 */
class UnexpectedValueException extends \UnexpectedValueException
{
    /**
     * @var array
     */
    private $errors;

    /**
     * 
     */
    public function __construct(array $errors) {
        parent::__construct("Invalid iri");
        $this->errors = $errors;
    }
    /**
     * Get the value of errors
     * @return array
     */ 
    public function getErrors()
    {
        return $this->errors;
    }
}
