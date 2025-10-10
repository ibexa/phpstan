<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PHPStan\Rules;

use Ibexa\PHPStan\Rules\NamingConventionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\Ibexa\PHPStan\Rules\NamingConventionRule>
 */
final class NamingConventionRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
       return new NamingConventionRule();
    }

    public function testRule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/NamingConventionFixture.php',
            ],
            [
                [
                    'Interface "WrongName" should have "Interface" suffix',
                    11,
                ],
                [
                    'Trait "SimpleThing" should have "Trait" suffix',
                    19,
                ],
                [
                    'Abstract class "SimpleClass" should have "Abstract" prefix',
                    27,
                ],
            ]
        );
    }
}
