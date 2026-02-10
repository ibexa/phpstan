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
final readonly class RequireMockObjectInReturnTypeRule implements Rule
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

        if ($this->typeNodeIncludesMockObject($node->returnType)) {
            return [];
        }

        return [
            RuleErrorBuilder::message('Method returns a mock but return type is missing MockObject intersection.')
                ->identifier('Ibexa.requireMockObjectReturnType')
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

    private function typeNodeIncludesMockObject(Node $type): bool
    {
        if ($type instanceof NullableType) {
            return $this->typeNodeIncludesMockObject($type->type);
        }

        if ($type instanceof UnionType || $type instanceof IntersectionType) {
            foreach ($type->types as $innerType) {
                if ($this->typeNodeIncludesMockObject($innerType)) {
                    return true;
                }
            }

            return false;
        }

        if ($type instanceof Identifier) {
            return $type->toString() === 'MockObject';
        }

        if ($type instanceof Name) {
            return $type->getLast() === 'MockObject';
        }

        return false;
    }
}
