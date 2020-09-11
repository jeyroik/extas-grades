<?php
namespace extas\components\plugins\grades;

use extas\components\grades\TTermsCalculate;
use extas\components\http\THasHttpIO;
use extas\components\http\THasJsonRpcRequest;
use extas\components\plugins\Plugin;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageGradeTerms;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculationResult;
use extas\interfaces\terms\ITermCalculator;
use extas\interfaces\terms\ITermCalculatorDescription;

/**
 * Class GradeTerms
 *
 * @method IRepository terms()
 * @method IRepository termsCalculators()
 *
 * @package extas\components\plugins\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
class GradeTerms extends Plugin implements IStageGradeTerms
{
    use THasHttpIO;
    use THasJsonRpcRequest;
    use TTermsCalculate;

    public const TAG__PREFIX = 'grade.term.';

    /**
     * @param array $terms
     * @return array
     */
    public function __invoke(array $terms): array
    {
        return $this->prepare($terms, $this->getJsonRpcRequest(), static::TAG__PREFIX, $this->getHttpIO());
    }
}
