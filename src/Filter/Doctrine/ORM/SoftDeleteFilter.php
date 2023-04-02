<?php

declare(strict_types=1);

namespace App\Filter\Doctrine\ORM;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Class SoftDeleteFilter
 *
 * A Doctrine ORM SQLFilter to enable soft deletion by filtering out deleted entities.
 * Entities are considered soft-deleted if they have a `deleted` field that is not null and
 * whose value is less than or equal to the current timestamp.
 *
 * @package App\Filter\Doctrine\ORM
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class SoftDeleteFilter extends SQLFilter
{
    /**
     * Adds the filter constraint to the SQL query.
     *
     * @param ClassMetadata<object> $targetEntity The metadata for the targeted entity
     * @param string $targetTableAlias The alias of the target table
     *
     * @return string The SQL filter constraint
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (! $targetEntity->hasField('deleted')) {
            return (string) null;
        }

        return sprintf(
            "%s.deleted IS NULL OR %s.deleted > CURRENT_TIMESTAMP()",
            $targetTableAlias,
            $targetTableAlias
        );
    }
}
