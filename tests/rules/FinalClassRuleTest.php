<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\FinalClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\Ibexa\PHPStan\Rules\FinalClassRule>
 */
final class FinalClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FinalClassRule($this->createReflectionProvider());
    }

    public function testRule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/FinalClass/NonFinalClass.php',
            ],
            [
                [
                    'Class Ibexa\Tests\PHPStan\Rules\Fixtures\FinalClass\NonFinalClass is not final. All non-abstract classes should be final.',
                    11,
                    'Add "final" keyword to the class declaration.',
                ],
            ]
        );
    }

    public function testNoErrorsOnFinalAndAbstractClassesAndInterfaces(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/FinalClass/FinalClass.php',
                __DIR__ . '/Fixtures/FinalClass/AbstractClass.php',
                __DIR__ . '/Fixtures/FinalClass/SomeInterface.php',
                __DIR__ . '/Fixtures/FinalClass/SomeTrait.php',
            ],
            []
        );
    }
}
