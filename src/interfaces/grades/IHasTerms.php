<?php
namespace extas\interfaces\grades;

use extas\interfaces\http\IHasHttpIO;

/**
 * Interface IHasTerms
 *
 * @package extas\interfaces\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasTerms extends IHasHttpIO, IHasGradeName
{
    public const FIELD__TERMS = 'terms';

    /**
     * @return array
     */
    public function getTermsWithIO(): array;
}
