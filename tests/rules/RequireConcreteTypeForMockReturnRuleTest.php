<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\RequireConcreteTypeForMockReturnRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<RequireConcreteTypeForMockReturnRule>
 */
final class RequireConcreteTypeForMockReturnRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new RequireConcreteTypeForMockReturnRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/RequireConcreteTypeForMockReturnFixture.php'],
            [
                [
                    'Method returns a mock and declares only MockObject as return type. Use an intersection with a concrete type.',
                    27,
                ],
            ]
        );
    }
}
