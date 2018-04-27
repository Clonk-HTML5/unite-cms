<?php

namespace UniteCMS\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContentTypeRepository
 */
class ContentTypeRepository extends EntityRepository
{
    public function findByIdentifiers($organization, $domain, $contentType)
    {
        $result = $this->createQueryBuilder('ct')
            ->select('ct', 'dm', 'org')
            ->join('ct.domain', 'dm')
            ->join('dm.organization', 'org')
            ->where('org.identifier = :organization')
            ->andWhere('dm.identifier = :domain')
            ->andWhere('ct.identifier = :contentType')
            ->setParameters(
                [
                    'organization' => $organization,
                    'domain' => $domain,
                    'contentType' => $contentType,
                ]
            )
            ->getQuery()->getResult();

        return (count($result) > 0) ? $result[0] : null;
    }
}
