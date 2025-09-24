<?php
/**
 * Copyright (c) 2025 Hadrian Oñate. All rights reserved.
 *
 * Unauthorized copying, modification, distribution, or use of this file,
 * via any medium, is strictly prohibited without prior written permission.
 */

namespace Vendor\CustomerLevels\Api;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Sales\Api\Data\OrderInterface;

interface CustomerOrderCountRepositoryInterface
{
    public function getCustomerOrderCount($customerId);
    public function checkIncrementByCustomer(CustomerInterface $customer);
    public function checkIncrementByOrder(OrderInterface $order);
    public function incrementCustomerOrderCount(int $customerId, int $amount=1);
    public function determineTierLevelByCustomer(CustomerInterface $customer);
    public function determinTierLevelByIncrement(int $incrementValue);

}
