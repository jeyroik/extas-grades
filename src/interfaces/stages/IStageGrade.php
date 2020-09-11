<?php
namespace extas\interfaces\stages;

use extas\interfaces\grades\IHasCoefficients;

/**
 * Interface IStageGrade
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageGrade extends IHasCoefficients
{
    public const NAME = 'extas.grade';

    /**
     * Return result grade.
     *
     * @param array $grade
     * @return array
     */
    public function __invoke(array $grade): array;
}
