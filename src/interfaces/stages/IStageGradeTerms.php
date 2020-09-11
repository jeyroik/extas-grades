<?php
namespace extas\interfaces\stages;

use extas\interfaces\http\IHasHttpIO;

/**
 * Interface IStageGradeTerms
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageGradeTerms extends IHasHttpIO
{
    public const NAME = 'extas.grade.terms';

    /**
     * Return result terms
     *
     * @param array $terms
     * @return array
     */
    public function __invoke(array $terms): array;
}
