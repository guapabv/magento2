<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Config\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $this->upgradeAcl($setup);
        }
    }

    /**
     * ACL roles were moved to Magento_Backend
     * for backward compatibility, roles that are already defined as Magento_Config should be updated to Magento_Backend
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    private function upgradeAcl(ModuleDataSetupInterface $setup)
    {

        $aclUpdates = [
            'Magento_Config::dev'           => 'Magento_Backend::dev',
            'Magento_Config::web'           => 'Magento_Backend::web',
            'Magento_Config::trans_email'   => 'Magento_Backend::trans_email',
            'Magento_Config::currency'      => 'Magento_Backend::currency',
            'Magento_Config::advanced'      => 'Magento_Backend::advanced',
        ];

        foreach ($aclUpdates as $aclKey => $aclUpdate) {
            $setup->getConnection()->update(
                $setup->getTable('authorization_rule'),
                ['resource_id' => $aclUpdate],
                ['resource_id = ?' => $aclKey]
            );
        }

    }
}
