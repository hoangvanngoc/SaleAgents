<?php
namespace AHT\SaleAgents\Api;

interface SaleAgentRepositoryInterface
{
   /**
     * Undocumented function
     *
     * @param \AHT\SaleAgents\Api\Data\SalesagentInterface $salesagent
     * @return \AHT\SaleAgents\Api\Data\SalesagentInterface
     */
    public function save(\AHT\SaleAgents\Api\Data\SalesagentInterface $salesagent);


    /**
     * Undocumented function
     *
     * @param int $salesagentId
     * @return \AHT\SaleAgents\Api\Data\SalesagentInterface
     */
    public function getById($salesagentId);

    /**
     * Undocumented function
     *
     * @param \AHT\SaleAgents\Api\Data\SalesagentInterface $salesagents
     * @return \AHT\SaleAgents\Api\Data\SalesagentInterface
     */
    public function delete(\AHT\SaleAgents\Api\Data\SalesagentInterface $salesagent);

    /**
     * Undocumented function
     *
     * @param  int $salesagentsId
     * @return \AHT\SaleAgents\Api\Data\SalesagentInterface
     */
    public function deleteById($salesagentId);
}
