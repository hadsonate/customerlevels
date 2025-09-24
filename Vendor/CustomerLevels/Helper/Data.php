<?php
/**
 * Copyright (c) 2025 Hadrian Oñate. All rights reserved.
 *
 * Unauthorized copying, modification, distribution, or use of this file,
 * via any medium, is strictly prohibited without prior written permission.
 */
namespace Vendor\CustomerLevels\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const XML_PATH_CUSTLVL_TIERSETS = 'customer_levels/general/tier_levels';
    const TIER_LVL = 'tier_level';
    const TIER_CAP = 'tier_cap';
    const TIER_ENABLE = 'enable';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManagerInterface;

    protected $_tierConfig;

    /**
     * Get the config data for Tier Levels
     * @return mixed
     */
    protected function getTierLevelsConfig()
    {
        $tiers = $this->scopeConfig->getValue(self::XML_PATH_CUSTLVL_TIERSETS, ScopeInterface::SCOPE_STORE);
        if (empty($tiers)) {
            return [];
        }
        $tierCollection = json_decode($tiers);
        $this->_tierConfig = $tierCollection;
        return $this->_tierConfig;
    }

    /**
     * Identify the tier level by Order Count
     *
     * @param $orderCount
     * @return int|mixed
     */
    public function getTierLvlByCustOrderCountValue($orderCount)
    {
        if (!isset($this->_tierConfig)) {
            $this->getTierLevelsConfig();
        }
        $tiers = $this->_tierConfig;
        $lastTier = [];
        foreach ($tiers as $tier) {
            $tier = (array) $tier;

            if ($tier[self::TIER_ENABLE] && $orderCount <= (int)$tier[self::TIER_CAP]) {
                return $tier[self::TIER_LVL];
            } else if ($tier[self::TIER_ENABLE]) {
                $lastTier = $tier;
            }
        }
        if ($orderCount > $lastTier[self::TIER_CAP]) {
            return (int) $lastTier[self::TIER_LVL];
        }
        return 0;
    }

}
