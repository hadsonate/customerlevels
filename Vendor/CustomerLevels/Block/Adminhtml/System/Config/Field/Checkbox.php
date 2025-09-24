<?php
/**
 * Copyright (c) 2025 Hadrian Oñate. All rights reserved.
 *
 * Unauthorized copying, modification, distribution, or use of this file,
 * via any medium, is strictly prohibited without prior written permission.
 */
namespace Vendor\CustomerLevels\Block\Adminhtml\System\Config\Field;

use Magento\Framework\View\Element\Html\Select;

class Checkbox extends Select
{
    /**
     * Set the input name and ID
     */
    public function setInputName($name)
    {
        return $this->setName($name);
    }

    /**
     * Set the input id
     */
    public function setInputId($id)
    {
        return $this->setId($id);
    }

    /**
     * Render HTML for checkbox
     */
    public function _toHtml()
    {
        return '<input type="checkbox" name="' . $this->getName()
            . '" id="' . $this->getId()
            . '" value="1" />';
    }
}
