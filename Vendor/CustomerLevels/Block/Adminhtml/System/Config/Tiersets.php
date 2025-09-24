<?php
/**
 * Copyright (c) 2025 Hadrian Oñate. All rights reserved.
 *
 * Unauthorized copying, modification, distribution, or use of this file,
 * via any medium, is strictly prohibited without prior written permission.
 */
namespace Vendor\CustomerLevels\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Vendor\CustomerLevels\Block\Adminhtml\System\Config\Field\Checkbox;

class Tiersets extends AbstractFieldArray
{

    protected $checkboxRenderer;
    protected $selectRenderer;

    /**
     * {@inheritdoc}
     */
    // @codingStandardsIgnoreStart
    protected function _prepareToRender()
    {
        $this->addColumn(
            'tier_level',
            [
                'label' => __('Tier Lvl'),
                'style' => 'width:50px',
                'renderer' => false
            ]
        );
        $this->addColumn(
            'tier_cap',
            [
                'label' => __('Tier Max Count'),
                'style' => 'width:50px',
                'renderer' => false
            ]
        );
        $this->addColumn('enable', [
            'label' => __('Enable'),
            'renderer' => $this->getCheckboxRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add New Tier');
    }

    /**
     * Get Checkbox Renderer
     */
    protected function getCheckboxRenderer()
    {
        if (!$this->checkboxRenderer) {
            $this->checkboxRenderer = $this->getLayout()->createBlock(
                Checkbox::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->checkboxRenderer;
    }
}
