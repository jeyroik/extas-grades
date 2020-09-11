<?php
namespace extas\interfaces\stages;

use extas\interfaces\grades\IHasTerms;

/**
 * Interface IStageGradeCoefficients
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageGradeCoefficients extends IHasTerms
{
    public const NAME = 'extas.grade.coefficients';

    /**
     * Return result coefficients
     *
     * @param array $coefficients
     * @return array
     */
    public function __invoke(array $coefficients): array;
}
