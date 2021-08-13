<?php
namespace AHT\SaleAgents\Model\Source;

class Commissiontype extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions() {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('--choose an option--'), 'value' => ''],
                ['label' => __('Fixel'), 'value' => 1],
                ['label' => __('Percent'), 'value' => 2]
            ];
        }
        return $this->_options;
    }
}
