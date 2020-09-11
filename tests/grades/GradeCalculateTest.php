<?php
namespace tests\grades;

use Dotenv\Dotenv;
use extas\components\http\TSnuffHttp;
use extas\components\operations\jsonrpc\GradeCalculate;
use extas\components\plugins\grades\GradeCoefficients;
use extas\components\plugins\grades\Grades;
use extas\components\plugins\grades\GradeTerms;
use extas\components\plugins\Plugin;
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\terms\Term;
use extas\components\terms\TermCalculatorDescription;
use extas\components\THasMagicClass;
use extas\interfaces\stages\IStageGrade;
use extas\interfaces\stages\IStageGradeCoefficients;
use extas\interfaces\stages\IStageGradeTerms;
use PHPUnit\Framework\TestCase;
use tests\grades\misc\ExampleCoefficientCalculator;
use tests\grades\misc\ExampleGradeCalculator;
use tests\grades\misc\ExampleTermCalculator;

/**
 * Class GradeCalculateTest
 *
 * @package tests\grades
 * @author jeyroik <jeyroik@gmail.com>
 */
class GradeCalculateTest extends TestCase
{
    use TSnuffHttp;
    use TSnuffRepositoryDynamic;
    use THasMagicClass;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->createSnuffDynamicRepositories([
            ['terms', 'name', Term::class],
            ['termsCalculators', 'name', TermCalculatorDescription::class]
        ]);
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
    }

    public function testCalculate()
    {
        $this->createTerms();
        $this->createCoefficients();
        $this->createGrades();
        $this->createCalculators();
        $this->installDefaultPlugins();

        $operation = new GradeCalculate();
        $result = $operation([
            GradeCalculate::FIELD__PSR_REQUEST => $this->getPsrRequest(),
            GradeCalculate::FIELD__PSR_RESPONSE => $this->getPsrResponse()
        ]);

        $this->assertEquals(
            [
                'grades' =>
                [
                    'test_is_ok' => [
                        'test_term_3' => 3, // From term description.
                        'test_grade' => 15  // Cf1(1+2+3) + Cf2(1+2+3) + Term3(3) = 15
                    ],
                    'test_is_failed' => []  // Grade plugin is available only for test_is_ok.
                ]
            ],
            $result,
            'Incorrect result: ' . print_r($result, true)
        );
    }

    protected function installDefaultPlugins()
    {
        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => GradeTerms::class,
            Plugin::FIELD__STAGE => IStageGradeTerms::NAME
        ]));

        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => GradeTerms::class,
            Plugin::FIELD__STAGE => IStageGradeTerms::NAME . '.test_is_ok'
        ]));

        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => GradeCoefficients::class,
            Plugin::FIELD__STAGE => IStageGradeCoefficients::NAME
        ]));

        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => GradeCoefficients::class,
            Plugin::FIELD__STAGE => IStageGradeCoefficients::NAME . '.test_is_ok'
        ]));

        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => Grades::class,
            Plugin::FIELD__STAGE => IStageGrade::NAME
        ]));

        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => Grades::class,
            Plugin::FIELD__STAGE => IStageGrade::NAME . '.test_is_ok'
        ]));
    }

    protected function createCalculators()
    {
        $this->getMagicClass('termsCalculators')->create(new TermCalculatorDescription([
            TermCalculatorDescription::FIELD__NAME => 'term',
            TermCalculatorDescription::FIELD__CLASS => ExampleTermCalculator::class
        ]));
        $this->getMagicClass('termsCalculators')->create(new TermCalculatorDescription([
            TermCalculatorDescription::FIELD__NAME => 'coefficient',
            TermCalculatorDescription::FIELD__CLASS => ExampleCoefficientCalculator::class
        ]));
        $this->getMagicClass('termsCalculators')->create(new TermCalculatorDescription([
            TermCalculatorDescription::FIELD__NAME => 'grade',
            TermCalculatorDescription::FIELD__CLASS => ExampleGradeCalculator::class
        ]));
    }

    protected function createGrades()
    {
        $this->getMagicClass('terms')->create(new Term([
            Term::FIELD__NAME => 'test_grade',
            Term::FIELD__TITLE => 'is ok too',
            Term::FIELD__DESCRIPTION => 6,
            Term::FIELD__TAGS => [Grades::TAG__PREFIX . 'test']
        ]));
    }

    protected function createCoefficients()
    {
        $this->getMagicClass('terms')->create(new Term([
            Term::FIELD__NAME => 'test_c',
            Term::FIELD__TITLE => 'is ok too',
            Term::FIELD__DESCRIPTION => 4,
            Term::FIELD__TAGS => [GradeCoefficients::TAG__PREFIX . 'test']
        ]));

        $this->getMagicClass('terms')->create(new Term([
            Term::FIELD__NAME => 'test_c_2',
            Term::FIELD__TITLE => 'is ok too',
            Term::FIELD__DESCRIPTION => 5,
            Term::FIELD__TAGS => [GradeCoefficients::TAG__PREFIX . '*']
        ]));
    }

    protected function createTerms()
    {
        $this->getMagicClass('terms')->create(new Term([
            Term::FIELD__NAME => 'test_term',
            Term::FIELD__TITLE => 'is ok',
            Term::FIELD__DESCRIPTION => 1,
            Term::FIELD__TAGS => [GradeTerms::TAG__PREFIX . 'test']
        ]));

        $this->getMagicClass('terms')->create(new Term([
            Term::FIELD__NAME => 'test_term_2',
            Term::FIELD__TITLE => 'is ok',
            Term::FIELD__DESCRIPTION => 2,
            Term::FIELD__TAGS => [GradeTerms::TAG__PREFIX . '*']
        ]));

        $this->getMagicClass('terms')->create(new Term([
            Term::FIELD__NAME => 'test_term_3',
            Term::FIELD__TITLE => 'is ok',
            Term::FIELD__DESCRIPTION => 3,
            Term::FIELD__TAGS => ['*']
        ]));
    }
}
