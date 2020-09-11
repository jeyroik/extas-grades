<?php
namespace extas\components\operations\jsonrpc;

use extas\components\api\jsonrpc\operations\OperationRunner;
use extas\interfaces\grades\IHasCoefficients;
use extas\interfaces\grades\IHasTerms;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\stages\IStageGrade;
use extas\interfaces\stages\IStageGradeCoefficients;
use extas\interfaces\stages\IStageGradeTerms;

/**
 * Class GradeCalculate
 *
 * @package extas\components\operations\jsonrpc
 * @author jeyroik <jeyroik@gmail.com>
 */
class GradeCalculate extends OperationRunner implements IHasHttpIO
{
    public const SPECS__NAMES = 'names';
    public const SPECS__GRADES = 'grades';

    /**
     * @return array
     */
    public function run(): array
    {
        $request = $this->getJsonRpcRequest();
        $params = $request->getParams();
        $gradesNames = $params[static::SPECS__NAMES] ?? [];
        $result = [];

        foreach ($gradesNames as $gradeName) {
            $terms = $this->runTermsStage($gradeName);
            $coefficients = $this->runCoefficientsStage($gradeName, $terms);
            $result[$gradeName] = $this->runGradesStage($gradeName, $coefficients);
        }

        return [static::SPECS__GRADES => $result];
    }

    /**
     * @param string $gradeName
     * @return array
     */
    protected function runTermsStage(string $gradeName): array
    {
        $terms = [];
        $config = $this->getHttpIO();

        foreach ($this->getPluginsByStage(IStageGradeTerms::NAME, $config) as $plugin) {
            $terms = $plugin($terms);
        }

        foreach ($this->getPluginsByStage(IStageGradeTerms::NAME . '.' . $gradeName, $config) as $plugin) {
            $terms = $plugin($terms);
        }

        return $terms;
    }

    /**
     * @param string $gradeName
     * @param array $terms
     * @return array
     */
    protected function runCoefficientsStage(string $gradeName, array $terms): array
    {
        $coefficients = [];
        $config = $this->getHttpIO([IHasTerms::FIELD__TERMS => $terms]);

        foreach ($this->getPluginsByStage(IStageGradeCoefficients::NAME, $config) as $plugin) {
            $coefficients = $plugin($coefficients);
        }

        foreach ($this->getPluginsByStage(IStageGradeCoefficients::NAME . '.' . $gradeName, $config) as $plugin) {
            $coefficients = $plugin($coefficients);
        }

        return $coefficients;
    }

    /**
     * @param string $gradeName
     * @param array $coefficients
     * @return array
     */
    protected function runGradesStage(string $gradeName, array $coefficients): array
    {
        $grade = [];
        $config = $this->getHttpIO([IHasCoefficients::FIELD__COEFFICIENTS => $coefficients]);

        foreach ($this->getPluginsByStage(IStageGrade::NAME, $config) as $plugin) {
            $grade = $plugin($grade);
        }

        foreach ($this->getPluginsByStage(IStageGrade::NAME . '.' . $gradeName, $config) as $plugin) {
            $grade = $plugin($grade);
        }

        return $grade;
    }
}
