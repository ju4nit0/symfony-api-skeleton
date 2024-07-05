<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Exception\Shared;

use App\SharedContext\CriteriaModule\Domain\Exception\Filter\InvalidSyntaxException;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\Filter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\FilterGroup;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\LogicalOperator;
use Exception;

class FilterGroupFactory
{
    public static function fromString(string $filter): FilterGroup
    {
        $filterGroup = FilterGroup::create();
        $length = strlen($filter);

        $charQuoted = null;
        $level = 0;
        $lastSpace = 0;
        $splitFrom = 0;
        $splitTo = 0;

        for ($i = 0; $i < $length; $i++) {
            $char = $filter[$i];

            if ('\\' === $char) {
                $i++;
                continue;
            }

            if ("'" === $char || '"' === $char) {
                $charQuoted = $charQuoted === $char ? null : $char;
            }

            if (null !== $charQuoted) {
                continue;
            }

            if ('(' === $char) {
                $level++;
            }

            if (')' === $char) {
                $level--;
            }

            if (0 !== $level) {
                continue;
            }

            if ($i + 1 === $length) {
                $splitTo = $length;
            } elseif (' ' === $char) {
                $word = trim(substr($filter, $lastSpace, $i - $lastSpace));
                if (preg_match(LogicalOperator::regex(), $word)) {
                    $splitTo = $lastSpace;
                }
                $lastSpace = $i;
            }

            if ($splitFrom === $splitTo) {
                continue;
            }

            $str = trim(substr($filter, $splitFrom, $splitTo - $splitFrom));
            [$operator, $filterExpression] = self::extract($str);

            $filterGroup->add(
                new LogicalOperator($operator),
                $filterExpression[0] === '('
                    ? self::fromString(substr($filterExpression, 1, -1))
                    : Filter::deserialize($filterExpression),
            );

            $splitFrom = $splitTo + 1;
            $splitTo = $splitFrom;
        }

        if (null !== $charQuoted) {
            throw InvalidSyntaxException::invalidQuoteSyntax($charQuoted);
        }

        if (0 !== $level) {
            throw InvalidSyntaxException::invalidGroupSyntax((string)$level);
        }

        return $filterGroup;
    }

    /** @return array<string> */
    private static function extract(string $filter): array
    {
        $firstSpace = strpos($filter, ' ');
        $firstWord = substr($filter, 0, (int)$firstSpace);
        try {
            $logicalOperator = new LogicalOperator($firstWord);
            return [$logicalOperator->value(), trim(substr($filter, (int)$firstSpace))];
        } catch (Exception $e) {
            return [LogicalOperator::and()->value(), trim($filter)];
        }
    }
}
