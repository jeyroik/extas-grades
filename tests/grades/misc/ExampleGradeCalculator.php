<?php
namespace tests\grades\misc;

use extas\components\terms\TermCalculator;
use extas\interfaces\grades\IHasCoefficients;
use extas\interfaces\terms\ITerm;

/**
 * Class ExampleGradeCalculator
 *
 * @package tests\grades\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExampleGradeCalculator extends TermCalculator
{
    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        $cs = $args[IHasCoefficients::FIELD__COEFFICIENTS] ?? [];

        return !empty($cs);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return float|int|mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        $cs = $args[IHasCoefficients::FIELD__COEFFICIENTS];

        return array_sum($cs);
    }
}
