<?php

/**
 * Mockery (https://docs.mockery.io/)
 *
 * @copyright https://github.com/mockery/mockery/blob/HEAD/COPYRIGHT.md
 * @license https://github.com/mockery/mockery/blob/HEAD/LICENSE BSD 3-Clause License
 * @link https://github.com/mockery/mockery for the canonical source repository
 */

namespace Mockery\CountValidator;

use Mockery;
use Mockery\Exception\InvalidCountException;

use const PHP_EOL;

class Exact extends CountValidatorAbstract
{
    /**
     * Validate the call count against this validator
     *
     * @param int $n
     *
     * @throws InvalidCountException
     * @return bool
     */
    public function validate($n)
    {
        if ($this->_limit !== $n) {
            $because = $this->_expectation->getExceptionMessage();

            $exception = new InvalidCountException(
                'Mockery expected ' . $this->_expectation->getMock()->mockery_getName()
                . '::' . (string)$this->_expectation
                . ' to be called exactly '
                . Mockery::formatCount($this->_limit, 'time', 'times'). ', but it was called '
                . Mockery::formatCount($n, 'time', 'times') . '.'
                . ($because ? ' Because ' . $this->_expectation->getExceptionMessage() : '')
            );
            $exception->setMock($this->_expectation->getMock())
                ->setMethodName((string) $this->_expectation)
                ->setExpectedCountComparative('=')
                ->setExpectedCount($this->_limit)
                ->setActualCount($n);
            throw $exception;
        }
    }
}
