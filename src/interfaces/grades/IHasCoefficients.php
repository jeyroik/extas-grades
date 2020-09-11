<?php
namespace extas\interfaces\grades;

use extas\interfaces\http\IHasHttpIO;

/**
 * Interface IHasCoefficients
 *
 * @package extas\interfaces\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasCoefficients extends IHasHttpIO, IHasGradeName
{
    public const FIELD__COEFFICIENTS = 'coefficients';

    /**
     * @return array
     */
    public function getCoefficientsWithIO(): array;
}
