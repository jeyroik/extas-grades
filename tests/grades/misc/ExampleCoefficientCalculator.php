<?php
namespace tests\grades\misc;

use extas\components\terms\TermCalculator;
use extas\interfaces\grades\IHasTerms;
use extas\interfaces\terms\ITerm;

/**
 * Class ExampleCoefficientCalculator
 *
 * @package tests\grades\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExampleCoefficientCalculator extends TermCalculator
{
    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        $terms = $args[IHasTerms::FIELD__TERMS] ?? [];

        return !empty($terms);
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return float|int|mixed
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        $terms = $args[IHasTerms::FIELD__TERMS];

        return array_sum($terms);
    }
}
