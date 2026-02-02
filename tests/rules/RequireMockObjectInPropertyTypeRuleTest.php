<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\RequireMockObjectInPropertyTypeRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<RequireMockObjectInPropertyTypeRule>
 */
final class RequireMockObjectInPropertyTypeRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new RequireMockObjectInPropertyTypeRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [__DIR__ . '/data/require-mockobject-property.php'],
            [
                [
                    'Property typed as MockObject only in PHPDoc. Use intersection type with MockObject.',
                    16,
                ],
            ]
        );
    }
}
