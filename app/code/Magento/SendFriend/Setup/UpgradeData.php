<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SendFriend\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $this->upgradeAcl($setup);
        }
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    protected function upgradeAcl(ModuleDataSetupInterface $setup) {

        $setup->getConnection()->update(
            $setup->getTable('authorization_rule'),
            ['resource_id' => 'Magento_Backend::sendfriend'],
            ['resource_id = ?' => 'Magento_Config::sendfriend']
        );

    }

}