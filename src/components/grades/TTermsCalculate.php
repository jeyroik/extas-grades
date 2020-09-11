<?php
namespace extas\components\grades;

use extas\interfaces\grades\ITermsCalculate;
use extas\interfaces\jsonrpc\IRequest;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculationResult;
use extas\interfaces\terms\ITermCalculator;
use extas\interfaces\terms\ITermCalculatorDescription;

/**
 * Trait TTermsCalculate
 *
 * @method IRepository terms()
 * @method IRepository termsCalculators()
 *
 * @package extas\components\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
trait TTermsCalculate
{
    /**
     * @param array $terms
     * @param IRequest $request
     * @param string $tagPrefix
     * @param array $httpIO
     * @return array
     */
    protected function prepare(array $terms, IRequest $request, string $tagPrefix, array $httpIO): array
    {
        $termsDescriptions = $this->getTermsDescriptions($request, $tagPrefix);

        /**
         * @var ITermCalculatorDescription[] $termsCalculatorsDescriptions
         */
        $termsCalculatorsDescriptions = $this->termsCalculators()->all([]);

        foreach ($termsCalculatorsDescriptions as $calculatorDescription) {
            $termsDescriptions = $this->calculateTerms($terms, $termsDescriptions, $calculatorDescription, $httpIO);

            if (empty($termsDescriptions)) {
                break;
            }
        }

        return $terms;
    }

    /**
     * @param array $terms
     * @param ITerm[] $termsDescriptions
     * @param ITermCalculatorDescription $calculatorDescription
     * @param array $httpIO
     * @return ITerm[]
     */
    protected function calculateTerms(
        array &$terms,
        array $termsDescriptions,
        ITermCalculatorDescription $calculatorDescription,
        array $httpIO
    ): array
    {
        $calculator = $this->getCalculator($calculatorDescription);
        $result = $this->calculate($calculator, $termsDescriptions, $httpIO);
        $terms = array_merge($terms, $result->getCalculatedTerms());
        $termsDescriptions = array_column($result->getSkippedTerms(), $result::SKIPPED__TERM);

        return $termsDescriptions;
    }

    /**
     * @param ITermCalculator $calculator
     * @param array $termsDescriptions
     * @param array $httpIO
     * @return ITermCalculationResult
     */
    protected function calculate(
        ITermCalculator $calculator,
        array $termsDescriptions,
        array $httpIO
    ): ITermCalculationResult
    {
        return $calculator->calculateTerms($termsDescriptions, $httpIO);
    }

    /**
     * @param ITermCalculatorDescription $description
     * @return ITermCalculator
     */
    protected function getCalculator(ITermCalculatorDescription $description): ITermCalculator
    {
        return $description->buildClassWithParameters($description->getParametersValues());
    }

    /**
     * @param IRequest $request
     * @param string $tagPrefix
     * @return ITerm[]
     */
    protected function getTermsDescriptions(IRequest $request, string $tagPrefix = ''): array
    {
        $params = $request->getParams();
        $tag = $params[ITermsCalculate::PARAM__TAG] ?? ITermsCalculate::TAG__WILDCARD;

        return $this->terms()->all([
            ITerm::FIELD__TAGS => [
                $tagPrefix . $tag,
                $tagPrefix . ITermsCalculate::TAG__WILDCARD,
                ITermsCalculate::TAG__WILDCARD
            ]
        ]);
    }
}
