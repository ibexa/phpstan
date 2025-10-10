<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Reflection\ReflectionProvider;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
final class FinalClassRule implements Rule
{
    private ReflectionProvider $reflectionProvider;

    public function __construct(
        ReflectionProvider $reflectionProvider
    ) {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        // Skip anonymous classes
        if (!isset($node->namespacedName)) {
            return [];
        }

        $className = $node->namespacedName->toString();

        if (!$this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $reflection = $this->reflectionProvider->getClass($className);

        // Skip if already final
        if ($reflection->isFinal()) {
            return [];
        }

        // Skip if abstract (abstract classes shouldn't be final)
        if ($reflection->isAbstract()) {
            return [];
        }

        // Skip interfaces and traits
        if ($reflection->isInterface() || $reflection->isTrait()) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Class %s is not final. All non-abstract classes should be final.',
                    $reflection->getName()
                )
            )
                ->identifier('class.notFinal')
                ->tip('Add "final" keyword to the class declaration.')
                ->build()
        ];
    }
}
