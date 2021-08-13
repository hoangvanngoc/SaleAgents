<?php
namespace AHT\SaleAgents\Model\ResourceModel;

class Salesagent extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('aht_sales_agent', 'entity_id');
    }
}
