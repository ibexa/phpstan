<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\RequireClosureReturnTypeRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\Ibexa\PHPStan\Rules\RequireClosureReturnTypeRule>
 */
final class RequireClosureReturnTypeRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
       return new RequireClosureReturnTypeRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/RequireClosureReturnTypeFixture.php',
            ],
            [
                [
                    'Closure is missing a return type declaration',
                    16,
                ],
                [
                    'Arrow function is missing a return type declaration',
                    32,
                ],
                [
                    'Closure is missing a return type declaration',
                    58,
                ],
                [
                    'Closure is missing a return type declaration',
                    60,
                ],
                [
                    'Closure is missing a return type declaration',
                    69,
                ],
            ]
        );
    }
}
