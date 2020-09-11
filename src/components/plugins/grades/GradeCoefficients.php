<?php
namespace extas\components\plugins\grades;

use extas\components\grades\THasTerms;
use extas\components\grades\TTermsCalculate;
use extas\components\http\THasJsonRpcRequest;
use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageGradeCoefficients;

/**
 * Class GradeCoefficients
 *
 * @package extas\components\plugins\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
class GradeCoefficients extends Plugin implements IStageGradeCoefficients
{
    use THasTerms;
    use TTermsCalculate;
    use THasJsonRpcRequest;

    public const TAG__PREFIX = 'grade.coefficient.';

    /**
     * @param array $coefficients
     * @return array
     */
    public function __invoke(array $coefficients): array
    {
        return $this->prepare(
            $coefficients,
            $this->getJsonRpcRequest(),
            static::TAG__PREFIX,
            $this->getTermsWithIO()
        );
    }
}
