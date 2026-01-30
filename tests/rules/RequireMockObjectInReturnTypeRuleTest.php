<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\RequireMockObjectInReturnTypeRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<RequireMockObjectInReturnTypeRule>
 */
final class RequireMockObjectInReturnTypeRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new RequireMockObjectInReturnTypeRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/data/require-mockobject-return.php'],
            [
                [
                    'Method returns a mock but return type is missing MockObject intersection.',
                    15,
                ],
            ]
        );
    }
}
