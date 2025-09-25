<?php
/**
 * Copyright (c) 2025 Hadrian Oñate. All rights reserved.
 *
 * Unauthorized copying, modification, distribution, or use of this file,
 * via any medium, is strictly prohibited without prior written permission.
 */
namespace Vendor\CustomerLevels\Model;

use Vendor\CustomerLevels\Api\CustomerOrderCountRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface as LoggerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Vendor\CustomerLevels\Helper\Data as CustomerLvlHelper;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class CustomerOrderCountRepository implements CustomerOrderCountRepositoryInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CustomerInterface
     */
    private $customerInterface;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CustomerLvlHelper
     */
    private $customerLvlHelper;

    /**
     * @var OrderCollectionFactory
     */
    private $orderCollection;

    const ATTRIBUTE_CODE = 'customer_order_count';

    /**
     * CustomerOrderCountRepository Contructor
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInterface $customerInterface
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface           $customerInterface,
        CustomerLvlHelper           $customerLvlHelper,
        OrderCollectionFactory      $orderCollection,
        LoggerInterface             $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerInterface = $customerInterface;
        $this->customerLvlHelper = $customerLvlHelper;
        $this->orderCollection = $orderCollection;
        $this->logger = $logger;
    }

    /**
     * Determine the Customer Ordered Count
     *
     * @param CustomerInterface $customer
     * @return int
     */
    public function checkIncrementByCustomer(CustomerInterface $customer)
    {
        $customerOrderCountAttribute = $customer->getCustomAttribute(self::ATTRIBUTE_CODE);
        if ($customerOrderCountAttribute) {
            return (int) $customerOrderCountAttribute->getValue();
        }
        return 0;
    }

    /**
     * Determine the Customer Ordered Count via Order
     *
     * @param OrderInterface $order
     * @return int
     * @throws \Throwable
     */
    public function checkIncrementByOrder(OrderInterface $order)
    {
        $customerId  = $order->getCustomerId();
        if ($customerId) {
            return $this->getCustomerOrderCount($customerId);
        }
        return 0;
    }

    /**
     * Retrieve Customer Order Count via Customer Id
     *
     * @param $customerId
     * @return int
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Throwable
     */
    public function getCustomerOrderCount($customerId)
    {
        try {
            /** @var CustomerInterface $customer */
            $customer = $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException $e) {
            $this->logger->warning(sprintf('Customer does not exist for entity ID: %d', $customerId));
            return 0;
        } catch (\Throwable $e) {
            $this->logger->error('Error while loading customer: ' . $e->getMessage());
            throw $e;
        }
        $orderCountAttr = $customer->getCustomAttribute(self::ATTRIBUTE_CODE);
        if ($orderCountAttr) {
           return $orderCountAttr->getValue();
        }
        return 0;
    }

    /**
     * Increment Customer Order Count
     * @param int $customerId
     * @param int $amount
     * @return false|int|void
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Throwable
     */
    public function incrementCustomerOrderCount(int $customerId, int $amount = 1)
    {
        if (!$this->customerLvlHelper->isCustomerLevelsEnabled()) {
            return (int) $this->getCustomerOrderCount($customerId);
        }
          // Disable this right now & allow negative amount
//        if ($amount <= 0) {
//            return (int) $this->getCustomerOrderCount($customerId);
//        }
        try {
            /** @var CustomerInterface $customer */
            $customer = $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException $e) {
            $this->logger->warning(sprintf('Customer does not exist for entity ID: %d', $customerId));
            return 0;
        } catch (\Throwable $e) {
            $this->logger->error('Error while loading customer: ' . $e->getMessage());
            throw $e;
        }

        $customerOrderCount = 0;
        $orderCountAttr = $customer->getCustomAttribute(self::ATTRIBUTE_CODE);
        if ($orderCountAttr) {
            $customerOrderCount = $orderCountAttr->getValue();
        }

        if ($customerOrderCount <= 0) {
            $customerOrderCount = 0;
        } else {
            $customerOrderCount += (int) $amount;
        }
        $customer->setCustomAttribute(self::ATTRIBUTE_CODE, $customerOrderCount);
        try {
            $this->customerRepository->save($customer);
        } catch (\Throwable $e) {
            $this->logger->error('Error while saving order count attribute: ' . $e->getMessage());
            throw $e;
        }
        return $customerOrderCount;
    }

    /**
     * @param $customerId
     * @return int
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Throwable
     */
    public function determineTierLevelByCustomer($customerId)
    {
        $orderCount = $this->getCustomerOrderCount($customerId);
        if ($orderCount >= 0) {
            return (int) $this->customerLvlHelper->getTierLvlByCustOrderCountValue($orderCount);
        }
        return 0;
    }

    /**
     * @param $increment
     * @return int
     */
    public function determineTierLevelByIncrement($increment)
    {
        if ($increment >= 0) {
            return (int) $this->customerLvlHelper->getTierLvlByCustOrderCountValue($increment);
        }
        return 0;
    }
}
