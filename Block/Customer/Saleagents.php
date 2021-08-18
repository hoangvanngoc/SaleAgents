<?php
namespace AHT\SaleAgents\Block\Customer;

class Saleagents extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_customerSession;
    protected $_resource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\ResourceConnection $Resource,


        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->_resource = $Resource;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getProductCollection()
    {
        $customerId = $this->_customerSession->getCustomer()->getId();

        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*')->addFieldToFilter('sale_agent_id', $customerId);
        $aht_sales_agent = $this->_resource->getTableName('aht_sales_agent');
        $collection->getSelect()->join(
            ['order_key' => $aht_sales_agent],
            'e.entity_id = order_key.order_item_id'
           );
        $collection->setPageSize(5);

        return $collection;
    }
}
