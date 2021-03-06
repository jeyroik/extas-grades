<?php
namespace extas\components\grades;

use extas\components\http\THasHttpIO;
use extas\interfaces\grades\IHasCoefficients;

/**
 * Trait THasCoefficients
 *
 * @package extas\components\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasCoefficients
{
    use THasHttpIO;
    use THasGradeName;

    /**
     * @return array
     */
    public function getCoefficientsWithIO(): array
    {
        return $this->getHttpIO([
            IHasCoefficients::FIELD__COEFFICIENTS => $this->config[IHasCoefficients::FIELD__COEFFICIENTS] ?? [],
            IHasCoefficients::FIELD__GRADE_NAME => $this->getGradeName()
        ]);
    }
}
