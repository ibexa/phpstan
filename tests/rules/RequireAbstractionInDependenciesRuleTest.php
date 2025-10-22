<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\RequireAbstractionInDependenciesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\Ibexa\PHPStan\Rules\RequireAbstractionInDependenciesRule>
 */
final class RequireAbstractionInDependenciesRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
       return new RequireAbstractionInDependenciesRule(
           $this->createReflectionProvider()
       );
    }

    public function testRule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/RequireAbstractionInDependenciesFixture.php',
            ],
            [
                [
                    'Parameter $concreteClass uses concrete class Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\ConcreteClass instead of an interface or abstract class. Available interfaces: Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\TestInterface',
                    29,
                ],
                [
                    'Parameter $concreteExtendingAbstract uses concrete class Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\ConcreteClassExtendingAbstract instead of an interface or abstract class. Abstract parent: Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\AbstractClass',
                    29,
                ],
                [
                    'Parameter $class uses concrete class Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\ConcreteClass instead of an interface or abstract class. Available interfaces: Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\TestInterface',
                    43,
                ],
                [
                    'Parameter $concreteExtendingAbstract uses concrete class Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\ConcreteClassExtendingAbstract instead of an interface or abstract class. Abstract parent: Ibexa\Tests\PHPStan\Rules\Fixtures\RequireAbstractionInDependencies\AbstractClass',
                    55,
                ],
            ]
        );
    }
}
