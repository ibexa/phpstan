<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<ClassMethod>
 */
final readonly class RequireConcreteTypeForMockReturnRule implements Rule
{
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->returnType === null || $node->stmts === null) {
            return [];
        }

        if (!$this->returnsMock($node)) {
            return [];
        }

        if (!$this->typeNodeIsMockObjectOnly($node->returnType)) {
            return [];
        }

        return [
            RuleErrorBuilder::message('Method returns a mock and declares only MockObject as return type. Use an intersection with a concrete type.')
                ->identifier('Ibexa.requireConcreteTypeForMockReturn')
                ->build(),
        ];
    }

    private function returnsMock(ClassMethod $node): bool
    {
        $mockVariables = [];
        foreach ($node->getStmts() ?? [] as $stmt) {
            if ($stmt instanceof Node\Stmt\Expression && $stmt->expr instanceof Node\Expr\Assign) {
                $assign = $stmt->expr;
                if ($assign->var instanceof Variable && is_string($assign->var->name)) {
                    if ($assign->expr instanceof MethodCall && $this->isCreateMockCall($assign->expr)) {
                        $mockVariables[$assign->var->name] = true;
                    }

                    if ($assign->expr instanceof StaticCall && $this->isCreateMockCall($assign->expr)) {
                        $mockVariables[$assign->var->name] = true;
                    }
                }
            }

            if (!$stmt instanceof Node\Stmt\Return_ || $stmt->expr === null) {
                continue;
            }

            $expr = $stmt->expr;
            if ($expr instanceof MethodCall && $this->isCreateMockCall($expr)) {
                return true;
            }

            if ($expr instanceof StaticCall && $this->isCreateMockCall($expr)) {
                return true;
            }

            if ($expr instanceof Variable && is_string($expr->name) && isset($mockVariables[$expr->name])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall|\PhpParser\Node\Expr\StaticCall $call
     */
    private function isCreateMockCall(Node $call): bool
    {
        if (!$call->name instanceof Node\Identifier) {
            return false;
        }

        if ($call->name->toString() !== 'createMock') {
            return false;
        }

        if ($call instanceof MethodCall) {
            return $call->var instanceof Variable && $call->var->name === 'this';
        }

        return true;
    }

    private function typeNodeIsMockObjectOnly(Node $type): bool
    {
        if ($type instanceof NullableType) {
            return $this->typeNodeIsMockObjectOnly($type->type);
        }

        if ($type instanceof IntersectionType) {
            $hasMockObject = false;
            foreach ($type->types as $innerType) {
                if ($this->isMockObjectType($innerType)) {
                    $hasMockObject = true;
                    continue;
                }

                return false;
            }

            return $hasMockObject;
        }

        if ($type instanceof UnionType) {
            $hasMockObject = false;
            foreach ($type->types as $innerType) {
                if ($innerType instanceof Name && $innerType->getLast() === 'null') {
                    continue;
                }

                if ($this->isMockObjectType($innerType)) {
                    $hasMockObject = true;
                    continue;
                }

                return false;
            }

            return $hasMockObject;
        }

        return $this->isMockObjectType($type);
    }

    private function isMockObjectType(Node $type): bool
    {
        if ($type instanceof Identifier) {
            return $type->toString() === 'MockObject';
        }

        if ($type instanceof Name) {
            return $type->getLast() === 'MockObject';
        }

        return false;
    }
}
