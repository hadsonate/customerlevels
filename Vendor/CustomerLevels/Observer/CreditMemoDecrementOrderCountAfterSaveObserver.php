<?php
/**
 * Copyright (c) 2025 Hadrian Oñate. All rights reserved.
 *
 * Unauthorized copying, modification, distribution, or use of this file,
 * via any medium, is strictly prohibited without prior written permission.
 */
namespace Vendor\CustomerLevels\Observer;

use Laminas\Db\Exception\ErrorException;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Vendor\CustomerLevels\Api\CustomerOrderCountRepositoryInterface;
use Vendor\CustomerLevels\Helper\Data as CustLevelHelper;

class CreditMemoDecrementOrderCountAfterSaveObserver implements ObserverInterface
{
    /**
     * @var CustomerOrderCountRepositoryInterface
     */
    protected $customerOrderCountRepository;

    /**
     * @var CustLevelHelper
     */
    protected $custLvlHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        CustomerOrderCountRepositoryInterface $customerOrderCountRepository,
        CustLevelHelper $custLvlHelper,
        LoggerInterface $logger
    ) {
        $this->customerOrderCountRepository = $customerOrderCountRepository;
        $this->custLvlHelper = $custLvlHelper;
        $this->logger = $logger;
    }

    /**
     * Decrement Order Count once Credit Memo occurs
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws ErrorException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->custLvlHelper->isCustomerLevelsEnabled()) {
            return false;
        }
        $creditmemo = $observer->getEvent()->getCreditmemo();
        try {
            $order = $creditmemo->getOrder();
            if ($order && $order->getCustomerId()) {
                $customerId = (int)$order->getCustomerId();
                $this->customerOrderCountRepository->incrementCustomerOrderCount(
                    $customerId,
                    -1
                );
            } else {
                $this->logger->error('Error while decrementing customer order_count to Credit Memo.');
            }
        } catch (\Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Error while setting customer order_count to Credit Memo: %s',
                    $e->getMessage()
                )
            );
            throw $e;
        }
    }
}
