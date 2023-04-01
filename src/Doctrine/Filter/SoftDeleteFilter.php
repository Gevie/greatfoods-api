<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class SoftDeleteFilter extends SQLFilter
{
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