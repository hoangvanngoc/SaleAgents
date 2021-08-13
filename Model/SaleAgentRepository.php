<?php
namespace AHT\SaleAgents\Model;

use AHT\SaleAgents\Api\SaleAgentRepositoryInterface;
use AHT\SaleAgents\Model\ResourceModel\Salesagent as ResourceSalesagent;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Reflection\DataObjectProcessor;

class SaleAgentRepository implements SaleAgentRepositoryInterface
{

    protected $dataObjectProcessor;

    private $storeManager;

    protected $resource;

    protected $saleagentFactory;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'saleagent_repository';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        ResourceSalesagent $resource,
        SalesagentFactory $salesagentFactory,
        StoreManagerInterface $storeManager,
		DataObjectProcessor $dataObjectProcessor,

        array $data = []
    ) {
		$this->salesagentFactory = $salesagentFactory;
        $this->resource = $resource;
        $this->storeManager = $storeManager;
		$this->dataObjectProcessor = $dataObjectProcessor;
    }

    public function save(\AHT\SaleAgents\Api\Data\SalesagentInterface $salesagent)
    {
        // get storeId
        $storeId = $this->storeManager->getStore()->getId();
        if(isset($storeId) && $storeId != null) {
            $salesagent->setStoreId($storeId);
        }

        try {
            $this->resource->save($salesagent);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the salesagent: %1', $exception->getMessage()),
                $exception
            );
        }
        return $salesagent;
    }

    public function getById($salesagentId)
    {
		$salesagent = $this->salesagentFactory->create()->load($salesagentId);
        if (!$salesagent->getEntityId()) {
            throw new NoSuchEntityException(__('Salesagent with id "%1" does not exist.', $salesagentId));
        }
        return $salesagent;
    }

    public function delete(\AHT\SaleAgents\Api\Data\SalesagentInterface $salesagent)
    {
        try {
            $this->resource->delete($salesagent);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the salesagent: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    public function deleteById($salesagentId)
    {
        return $this->delete($this->getById($salesagentId));
    }

}
