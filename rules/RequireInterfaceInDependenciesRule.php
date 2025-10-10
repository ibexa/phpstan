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
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassMethod>
 */
final class RequireInterfaceInDependenciesRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Stmt\ClassMethod::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        if (!$node->params) {
            return [];
        }

        foreach ($node->params as $param) {
            if (!$param->type instanceof Node\Name) {
                continue;
            }
            if ($param->var instanceof Error) {
                continue;
            }
            $typeName = $param->type->toString();

            // Skip built-in types and primitives
            if ($this->isBuiltInType($typeName)) {
                continue;
            }

            if (!interface_exists($typeName) && class_exists($typeName)) {
                // Check if this class implements any interface
                $interfaces = class_implements($typeName);

                if (!empty($interfaces)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf(
                            'Parameter $%s uses concrete class %s instead of an interface. Available interfaces: %s',
                            is_string($param->var->name) ? $param->var->name : $param->var->name->getType(),
                            $typeName,
                            implode(', ', $interfaces)
                        )
                    )->build();
                }
            }
        }

        return $errors;
    }

    private function isBuiltInType(string $type): bool
    {
        $builtInTypes = [
            'string', 'int', 'float', 'bool', 'array', 'object',
            'callable', 'iterable', 'mixed', 'void', 'never',
        ];

        return in_array(strtolower($type), $builtInTypes);
    }
}
