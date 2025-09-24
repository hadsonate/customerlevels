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
    /**
     * @api
     * @param $customerId
     * @return int
     */
    public function getCustomerOrderCount($customerId);

    /**
     * @api
     * @param CustomerInterface $customer
     * @return int
     */
    public function checkIncrementByCustomer(CustomerInterface $customer);

    /**
     * @api
     * @param OrderInterface $order
     * @return mixed
     */
    public function checkIncrementByOrder(OrderInterface $order);

    /**
     * @api
     * @param int $customerId
     * @param int $amount
     * @return int
     */
    public function incrementCustomerOrderCount(int $customerId, int $amount=1);

    /**
     * @api
     * @param int $customerId
     * @return int
     */
    public function determineTierLevelByCustomer(int $customerId);

    /**
     * @api
     * @param int $increment
     * @return int
     */
    public function determineTierLevelByIncrement(int $increment);

}
