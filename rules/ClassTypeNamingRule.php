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
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassLike>
 */
final class ClassTypeNamingRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Stmt\ClassLike::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->isRelevantNode($node)) {
            return [];
        }

        if ($node->name === null) {
            return [];
        }

        $className = $node->name->toString();
        $errors = [];

        if ($node instanceof Node\Stmt\Interface_ && substr($className, -9) !== 'Interface') {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Interface "%s" should have "Interface" suffix',
                    $className
                )
            )->build();
        }

        if ($node instanceof Node\Stmt\Trait_ && substr($className, -5) !== 'Trait') {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Trait "%s" should have "Trait" suffix',
                    $className
                )
            )->build();
        }

        if ($node instanceof Node\Stmt\Class_ && $node->isAbstract() && strpos($className, 'Abstract') !== 0) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Abstract class "%s" should have "Abstract" prefix',
                    $className
                )
            )->build();
        }

        return $errors;
    }

    private function isRelevantNode(Node $node): bool
    {
        return $node instanceof Node\Stmt\Interface_
            || $node instanceof Node\Stmt\Trait_
            || ($node instanceof Node\Stmt\Class_ && $node->isAbstract());
    }
}
