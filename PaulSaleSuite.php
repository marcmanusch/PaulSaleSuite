<?php

/**
 * Marc Manusch
 * PaulGurkes GmbH 23.02.2018
 * FAQ Plugin
 */

namespace PaulSale;

use Doctrine\DBAL\Schema\MySqlSchemaManager;
use Doctrine\ORM\Tools\SchemaTool;
use PaulSale\Models\Sale;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class PaulSale extends Plugin
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('paul_sale.plugin_dir', $this->getPath());
        parent::build($container);
    }

    public function install(InstallContext $context)
    {
        $this->updateSchema();
    }

    private function updateSchema()
    {
        $tool = new SchemaTool(Shopware()->Container()->get('models'));
        $schemas = [
            Shopware()->Container()->get('models')->getClassMetadata(Sale::class),
        ];

        /** @var MySqlSchemaManager $schemaManager */
        $schemaManager = Shopware()->Container()->get('models')->getConnection()->getSchemaManager();

        foreach ($schemas as $class) {
            if (!$schemaManager->tablesExist($class->getTableName())) {
                $tool->createSchema([$class]);
            } else {
                $tool->updateSchema([$class], true);
            }
        }
    }
}