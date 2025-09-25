<?php
/**
 * Copyright (c) 2025 Hadrian Oñate. All rights reserved.
 *
 * Unauthorized copying, modification, distribution, or use of this file,
 * via any medium, is strictly prohibited without prior written permission.
 */
namespace Vendor\CustomerLevels\Plugin;

use Vendor\CustomerLevels\Api\CustomerOrderCountRepositoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\Order\Invoice as OrderInvoice;
use Vendor\CustomerLevels\Helper\Data as CustLvlHelper;

class OrderInvoiceIncrementCustomerOrderCountPlugin
{
    /**
     * @var CustomerOrderCountRepositoryInterface
     */
    protected $customerOrderCountRepository;

    /**
     * @var CustLvlHelper
     */
    protected $custLvlHelper;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(
        CustomerOrderCountRepositoryInterface $customerOrderCountRepository,
        CustLvlHelper $custLvlHelper,
        LoggerInterface $logger
    ) {
        $this->customerOrderCountRepository = $customerOrderCountRepository;
        $this->custLvlHelper = $custLvlHelper;
        $this->logger = $logger;
    }

    public function aroundSave(
        OrderInvoice $subject,
        callable $proceed,
    ) {
        $isNewInvoice = $subject->isObjectNew();

        $result = $proceed();
        if (!$this->custLvlHelper->isCustomerLevelsEnabled()) {
            return $result;
        }
        // should only trigger the code if its new invoice // update or delete should exclude
        if (!$isNewInvoice) {
            return $result;
        }
        try {
            $order = $subject->getOrder();
            if ($order && $order->getCustomerId()) {
                $customerId = (int)$order->getCustomerId();
                $this->customerOrderCountRepository->incrementCustomerOrderCount(
                    $customerId,
                    1
                );
            } else {
                $this->logger->error('Error while setting customer order_count to new Invoice.');
            }
        } catch (\Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Error while setting customer order_count to new Invoice: %s',
                    $e->getMessage()
                )
            );
            throw $e;
        }
        return $result;
    }
}


