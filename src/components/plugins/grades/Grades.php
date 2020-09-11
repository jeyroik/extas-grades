<?php
namespace extas\components\plugins\grades;

use extas\components\grades\THasCoefficients;
use extas\components\grades\TTermsCalculate;
use extas\components\http\THasJsonRpcRequest;
use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageGrade;

/**
 * Class Grades
 *
 * @package extas\components\plugins\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
class Grades extends Plugin implements IStageGrade
{
    use THasCoefficients;
    use TTermsCalculate;
    use THasJsonRpcRequest;

    public const TAG__PREFIX = 'grade.self.';

    /**
     * @param array $terms
     * @return array
     */
    public function __invoke(array $terms): array
    {
        return $this->prepare(
            $terms,
            $this->getJsonRpcRequest(),
            static::TAG__PREFIX,
            $this->getCoefficientsWithIO()
        );
    }
}
