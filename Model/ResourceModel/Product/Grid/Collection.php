<?php
namespace AHT\SaleAgents\Model\ResourceModel\Product\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;
use Magento\Sales\Model\ResourceModel\Order\Item;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\DB\Select;
use Magento\Sales\Model\Order\Item as ItemOrder;

class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    protected $_map = [
        'fields' => [
            'order_items_sku' => 'order_items.sku',
            'order_items_name' => 'order_items.name',
        ]
    ];

    protected $itemOrder;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;

    public function __construct(
        Attribute $eavAttribute,
        ItemOrder $itemOrder,
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        array $addFilterStrategies = [],
        $mainTable = 'aht_sales_agent',
        $resourceModel = Item::class,
        $identifierName = null,
        $connectionName = null
    )
    {
        $this->addFilterStrategies = $addFilterStrategies;
        $this->itemOrder = $itemOrder;
        $this->eavAttribute = $eavAttribute;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel, $identifierName, $connectionName);
    }

    protected function _initSelect($from = '', $to = '')
    {
        $connection = $this->getConnection();
        $orderTableAliasName = $connection->quoteIdentifier('order');

        $orderJoinCondition = [
            $orderTableAliasName . '.entity_id = order_items.order_id',
            $connection->quoteInto("{$orderTableAliasName}.state <> ?", \Magento\Sales\Model\Order::STATE_CANCELED),
        ];

        if ($from != '' && $to != '') {
            $fieldName = $orderTableAliasName . '.created_at';
            $orderJoinCondition[] = $this->prepareBetweenSql($fieldName, $from, $to);
        }

        $this->getSelect()->reset()->from(
            ['order_items' => $this->getTable('sales_order_item')],
            [
                'ordered_qty' => 'SUM(order_items.qty_ordered)',
                'order_items_name' => 'order_items.name',
                'order_items_sku' => 'order_items.sku'
            ]
        )
        ->joinInner(
        ['order' => $this->getTable('sales_order')],
        implode(' AND ', $orderJoinCondition),
        'status'
    )->joinLeft(
        ['commission_value' => $this->getConnection()->getTableName('catalog_product_entity_decimal')],
        "order_items.product_id = commission_value.entity_id and commission_value.attribute_id = {$this->getProductNameAttributeId('commission_value')}",
        ['commission_value' => 'commission_value.value']
    )
        ->joinLeft(
            ['commission_type' => $this->getConnection()->getTableName('catalog_product_entity_int')],
            "order_items.product_id = commission_type.entity_id and commission_type.attribute_id = {$this->getProductNameAttributeId('commission_type')}",
            ['commission_type' => 'commission_type.value',]
        )
        ->joinLeft(
            ['commission_type_name' => $this->getConnection()->getTableName('commission_type')],
            "commission_type.value = commission_type_name.type_id",
            ['commission_type_name' => 'commission_type_name.type_name',]
        )
        ->joinLeft(
            ['sale_agent' => $this->getConnection()->getTableName('catalog_product_entity_text')],
            "order_items.product_id = sale_agent.entity_id and sale_agent.attribute_id = {$this->getProductNameAttributeId('sale_agent_id')}",
            [
                'sale_agent' => 'sale_agent.value',
            ]
        )
        ->joinLeft(
            ['sale_agent_name' => $this->getConnection()->getTableName('customer_entity')],
            "sale_agent.value = sale_agent_name.entity_id ",
            [
                'saleagent_name' => 'CONCAT(sale_agent_name.lastname," ", sale_agent_name.firstname )',
            ]
        )
        ->where(
            'order_items.parent_item_id IS NULL and sale_agent.value IS NOT NULL'
        )->group(
            'order_items.sku'
        )
        ->columns(
            'order_items.base_row_total_incl_tax as product_price_final'
        )
        ->columns(
            '(order_items.base_price*commission_value.value/100) as result_commission'
        )
        ->having(
            'SUM(order_items.qty_ordered) > ?',
            0
        )

        ->order('order.status');

        return $this;
}


    private function getProductNameAttributeId($attr_code)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $entityAttribute = $objectManager->get('Magento\Eav\Model\ResourceModel\Entity\Attribute');
        $attributeId = $entityAttribute->getIdByCode('catalog_product', $attr_code);

        return $attributeId;
    }

    /**
     * Set store filter to collection
     *
     * @param array $storeIds
     * @return $this
     */
    public function setStoreIds($storeIds)
    {
        if ($storeIds) {
            $this->getSelect()->where('order_items.store_id IN (?)', (array)$storeIds);
        }
        return $this;
    }
    /**
     * @inheritdoc
     *
     * @return Select
     * @since 100.2.0
     */
    public function getSelectCountSql()
    {
        $countSelect = clone parent::getSelectCountSql();

        $countSelect->reset(Select::COLUMNS);
        $countSelect->columns('COUNT(DISTINCT order_items.item_id)');

        return $countSelect;
    }
    /**
     * Set order
     *
     * @param string $attribute
     * @param string $dir
     * @return $this
     */
    public function setOrder($attribute, $dir = self::SORT_ORDER_DESC)
    {
        if (in_array($attribute, ['orders', 'ordered_qty'])) {
            $this->getSelect()->order($attribute . ' ' . $dir);
        } else {
            parent::setOrder($attribute, $dir);
        }

        return $this;
    }

    /**
     * Prepare between sql
     *
     * @param string $fieldName Field name with table suffix ('created_at' or 'main_table.created_at')
     * @param string $from
     * @param string $to
     * @return string Formatted sql string
     */
    protected function prepareBetweenSql($fieldName, $from, $to)
    {
        return sprintf(
            '(%s BETWEEN %s AND %s)',
            $fieldName,
            $this->getConnection()->quote($from),
            $this->getConnection()->quote($to)
        );
    }
}
