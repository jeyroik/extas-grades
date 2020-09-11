<?php
namespace tests\grades\misc;

use extas\components\terms\TermCalculator;
use extas\interfaces\grades\IHasGradeName;
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
        $gradeName = $args[IHasGradeName::FIELD__GRADE_NAME] ?? '';
        $terms = $args[IHasTerms::FIELD__TERMS] ?? [];

        return !empty($terms) && ($gradeName != 'test_is_failed');
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
