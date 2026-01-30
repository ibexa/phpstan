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

/**
 * @implements Rule<Node\Expr>
 */
final class RequireClosureReturnTypeRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Expr::class;
    }

    /**
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof Node\Expr\Closure && !$node instanceof Node\Expr\ArrowFunction) {
            return [];
        }

        if ($node->returnType === null) {
            $nodeType = $node instanceof Node\Expr\Closure ? 'Closure' : 'Arrow function';

            return [
                RuleErrorBuilder::message(
                    sprintf('%s is missing a return type declaration', $nodeType)
                )->identifier('phpstan.requireClosureReturnType')->build(),
            ];
        }

        return [];
    }
}
