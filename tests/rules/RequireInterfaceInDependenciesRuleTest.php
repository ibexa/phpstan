<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\RequireInterfaceInDependenciesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\Ibexa\PHPStan\Rules\RequireInterfaceInDependenciesRule>
 */
final class RequireInterfaceInDependenciesRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
       return new RequireInterfaceInDependenciesRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/RequireInterfaceInDependenciesFixture.php',
            ],
            [
                [
                    'Parameter $concreteClass uses concrete class Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies\ConcreteClass instead of an interface. Available interfaces: Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies\TestInterface',
                    23,
                ],
                [
                    'Parameter $class uses concrete class Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies\ConcreteClass instead of an interface. Available interfaces: Ibexa\Tests\PHPStan\Rules\Fixtures\Dependencies\TestInterface',
                    33,
                ],
            ]
        );
    }
}
