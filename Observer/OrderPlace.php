<?php
namespace AHT\SaleAgents\Observer;

class OrderPlace implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \AHT\SaleAgents\Model\SalesagentFactory $salesagentFactory
     *
     * @return string|null
     */

    protected $salesagentFactory;

    public function __construct(\AHT\SaleAgents\Model\SalesagentFactory $salesagentFactory)
    {
        $this->salesagentFactory = $salesagentFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
         $order = $observer->getEvent()->getOrder();
        /** @var \AHT\SaleAgents\Model\Salesagent $salesagentModel */

        $salesagentModel = $this->salesagentFactory->create();
        $items = $order->getAllItems();
        foreach($items as $item) {

            $haveAgents = explode(",", $item->getProduct()->getSaleAgentId());

            if(count($haveAgents) > 0) {
                for($i = 0; $i < count($haveAgents); $i++){
                    $orderData = [
                        'order_id' => $order->getIncrementId(),
                        'agent_id' => $haveAgents[$i],
                        'order_item_id' => $item->getProductId(),
                        'order_item_sku' => $item->getSku(),
                        'order_item_price' => $item->getPrice(),
                        'commision_type' => $item->getProduct()->getCommissionType(),
                        'commission_value' => $item->getProduct()->getCommissionValue()
                    ];
                    $salesagentModel->setData($orderData);
                    $salesagentModel->save();
                }
            }
        }
        return true;
    }
}
