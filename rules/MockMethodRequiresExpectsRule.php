<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Reports `$mock->method('x')` called directly on a MockObject, without `expects()` in front.
 *
 * Use `->expects(...)->method(...)` on mocks, or createStub() when the call count does not matter.
 *
 * @implements Rule<MethodCall>
 */
final readonly class MockMethodRequiresExpectsRule implements Rule
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(
        Node $node,
        Scope $scope
    ): array {
        if (!$node->name instanceof Node\Identifier || $node->name->toLowerString() !== 'method') {
            return [];
        }

        if (!(new ObjectType(MockObject::class))->isSuperTypeOf($scope->getType($node->var))->yes()) {
            return [];
        }

        return [
            RuleErrorBuilder::message('Missing expects(): call ->expects(...) before ->method(...) on a mock, or use createStub().')
                ->identifier('Ibexa.mockMethodWithoutExpects')
                ->build(),
        ];
    }
}
