<?php
/**
 * Created by PhpStorm.
 * User: franzwilding
 * Date: 18.10.18
 * Time: 09:21
 */

namespace UniteCMS\CoreBundle\Tests\DependencyInjection;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use UniteCMS\CoreBundle\SchemaType\Types\QueryType;

class DependencyInjectionTest extends KernelTestCase
{
    public function testDefaultDomainConfigDirInjection() {

        // Test (empty) default configuration.
        $kernel = static::bootKernel(['environment' => 'dev']);

        $this->assertEquals(
            $kernel->getContainer()->getParameter('kernel.project_dir').'/config/unite/',
            $kernel->getContainer()->get('unite.cms.domain_config_manager')->getDomainConfigDir()
        );

        // Test default maximum nesting level is 8
        $this->assertEquals(8, $kernel->getContainer()->get('unite.cms.graphql.schema_type_manager')->getMaximumNestingLevel());

        // Test default maximum_query_limit is 100
        $queryType = $kernel->getContainer()->get('unite.cms.graphql.schema_type_manager')->getSchemaType('Query');
        $accessor = new \ReflectionProperty($queryType, 'maximumQueryLimit');
        $accessor->setAccessible(true);
        $this->assertEquals(100, $accessor->getValue($queryType));
    }

    public function testOverrideDomainConfigDirInjection() {

        // Test (overridden) test configuration.
        $kernel = static::bootKernel(['environment' => 'test']);

        $this->assertEquals(
            $kernel->getContainer()->getParameter('kernel.cache_dir').'/unite/config/',
            $kernel->getContainer()->get('unite.cms.domain_config_manager')->getDomainConfigDir()
        );

        // Test default maximum nesting level is set to overridden value
        $this->assertEquals(6, $kernel->getContainer()->get('unite.cms.graphql.schema_type_manager')->getMaximumNestingLevel());

        // Test default maximum_query_limit is set to overridden value
        $queryType = $kernel->getContainer()->get('unite.cms.graphql.schema_type_manager')->getSchemaType('Query');
        $accessor = new \ReflectionProperty($queryType, 'maximumQueryLimit');
        $accessor->setAccessible(true);
        $this->assertEquals(101, $accessor->getValue($queryType));
    }
}