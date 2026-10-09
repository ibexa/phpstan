<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\MockMethodRequiresExpectsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<MockMethodRequiresExpectsRule>
 */
final class MockMethodRequiresExpectsRuleTest extends RuleTestCase
{
    private const ERROR = 'Missing expects(): call ->expects(...) before ->method(...) on a mock, or use createStub().';

    protected function getRule(): Rule
    {
        return new MockMethodRequiresExpectsRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/MockMethodWithoutExpectsFixture.php'],
            [
                [self::ERROR, 21],
                [self::ERROR, 26],
            ]
        );
    }
}
