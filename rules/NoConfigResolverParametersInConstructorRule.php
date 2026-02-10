<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PHPStan\Rules;

use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
final class NoConfigResolverParametersInConstructorRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Expr\MethodCall::class;
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Identifier) {
            return [];
        }

        $function = $scope->getFunction();
        if ($function !== null && $function->getName() !== '__construct') {
            return [];
        }

        /** @var \PhpParser\Node\Identifier $nodeName */
        $nodeName = $node->name;
        $methodName = $nodeName->name;

        if (
            $methodName !== 'getParameter'
            && $methodName !== 'hasParameter'
            && !isset($node->getArgs()[0])
        ) {
            return [];
        }

        $type = $scope->getType($node->var);
        $configResolverInterfaceType = new ObjectType(ConfigResolverInterface::class);
        if (!$configResolverInterfaceType->isSuperTypeOf($type)->yes()) {
            return [];
        }

        return [
            RuleErrorBuilder
                ::message('Referring to ConfigResolver parameters in constructor is not allowed due to potential scope change.')
                ->identifier('Ibexa.noConfigResolverParametersInConstructor')
                ->nonIgnorable()
                ->build(),
        ];
    }
}
