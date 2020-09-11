<?php
namespace extas\components\grades;

use extas\components\http\THasHttpIO;
use extas\interfaces\grades\IHasTerms;

/**
 * Trait THasTerms
 *
 * @package extas\components\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasTerms
{
    use THasHttpIO;

    /**
     * @return array
     */
    public function getTermsWithIO(): array
    {
        return $this->getHttpIO([
            IHasTerms::FIELD__TERMS => $this->config[IHasTerms::FIELD__TERMS] ?? []
        ]);
    }
}
