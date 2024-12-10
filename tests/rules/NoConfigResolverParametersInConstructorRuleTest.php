<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\NoConfigResolverParametersInConstructorRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\Ibexa\PHPStan\Rules\NoConfigResolverParametersInConstructorRule>
 */
final class NoConfigResolverParametersInConstructorRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
       return new NoConfigResolverParametersInConstructorRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/NoConfigResolverParametersInConstructorFixture.php',
            ],
            [
                [
                    'Referring to ConfigResolver parameters in constructor is not allowed due to potential scope change.',
                    21,
                ],
                [
                    'Referring to ConfigResolver parameters in constructor is not allowed due to potential scope change.',
                    22,
                ],
            ]
        );
    }
}
