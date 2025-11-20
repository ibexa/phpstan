<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Error;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassMethod>
 */
final class RequireAbstractionInDependenciesRule implements Rule
{
    private ReflectionProvider $reflectionProvider;

    public function __construct(
        ReflectionProvider $reflectionProvider
    ) {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function getNodeType(): string
    {
        return Node\Stmt\ClassMethod::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->params) {
            return [];
        }

        $errors = [];

        foreach ($node->params as $param) {
            $error = $this->validateParameter($param);
            if ($error !== null) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    private function validateParameter(Node\Param $param): ?RuleError
    {
        if (!$param->type instanceof Node\Name) {
            return null;
        }

        if ($param->var instanceof Error) {
            return null;
        }

        $typeName = $param->type->toString();

        // Skip if the type doesn't exist in reflection
        if (!$this->reflectionProvider->hasClass($typeName)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($typeName);

        // Skip interfaces - they are always acceptable
        if ($classReflection->isInterface()) {
            return null;
        }

        // Skip abstract classes - they are acceptable
        if ($classReflection->isAbstract()) {
            return null;
        }

        $reflection = $classReflection->getNativeReflection();

        // This is a concrete class - check if it has interfaces or extends an abstract class
        $interfaces = class_implements($typeName);
        $parentClass = $reflection->getParentClass();
        $hasAbstractParent = $parentClass && $parentClass->isAbstract();

        // If there are no interfaces and no abstract parent, it's acceptable (no violation)
        if (empty($interfaces) && !$hasAbstractParent) {
            return null;
        }

        // Build error with suggestions
        $suggestions = [];

        if (!empty($interfaces)) {
            $suggestions[] = 'Available interfaces: ' . implode(', ', $interfaces);
        }

        if ($hasAbstractParent) {
            $suggestions[] = 'Abstract parent: ' . $parentClass->getName();
        }

        return RuleErrorBuilder::message(
            sprintf(
                'Parameter $%s uses concrete class %s instead of an interface or abstract class. %s',
                is_string($param->var->name) ? $param->var->name : $param->var->name->getType(),
                $typeName,
                implode('. ', $suggestions)
            )
        )->build();
    }
}
