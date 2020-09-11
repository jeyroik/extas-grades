<?php
namespace tests\grades\misc;

use extas\components\terms\TermCalculator;
use extas\interfaces\grades\IHasGradeName;
use extas\interfaces\terms\ITerm;

/**
 * Class ExampleTermCalculator
 *
 * @package tests\grades\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExampleTermCalculator extends TermCalculator
{
    /**
     * @param ITerm $term
     * @param array $args
     * @return bool
     */
    public function canCalculate(ITerm $term, array $args = []): bool
    {
        $gradeName = $args[IHasGradeName::FIELD__GRADE_NAME] ?? '';

        return ($term->getTitle() == 'is ok') && ($gradeName != 'test_is_failed');
    }

    /**
     * @param ITerm $term
     * @param array $args
     * @return mixed|string
     */
    public function calculateTerm(ITerm $term, array $args = [])
    {
        return $term->getDescription();
    }
}
