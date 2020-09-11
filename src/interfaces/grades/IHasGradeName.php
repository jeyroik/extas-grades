<?php
namespace extas\interfaces\grades;

/**
 * Interface IHasGradeName
 *
 * @package extas\interfaces\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasGradeName
{
    public const FIELD__GRADE_NAME = 'grade_name';

    /**
     * @return string
     */
    public function getGradeName(): string;
}
