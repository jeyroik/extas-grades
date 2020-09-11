<?php
namespace extas\components\grades;

use extas\interfaces\grades\IHasGradeName;

/**
 * Trait THasGradeName
 *
 * @package extas\components\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasGradeName
{
    /**
     * @return string
     */
    public function getGradeName(): string
    {
        return $this->config[IHasGradeName::FIELD__GRADE_NAME] ?? '';
    }
}
