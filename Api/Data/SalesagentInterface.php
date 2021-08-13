<?php
namespace AHT\SaleAgents\Api\Data;

interface SalesagentInterface
{
	const ENTITY_ID = 'entity_id';
	const ORDER_ID  = 'order_id';
	const ORDER_ITEM_ID = 'order_item_id';
	const ORDER_ITEM_SKU = 'order_item_sku';
	const ORDER_ITEM_PRICE = 'order_item_price';
	const COMMISION_TYPE = 'commision_type';
	const COMMISSION_VALUE = 'commission_value';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';


	/**
	 * Get category id
	 *
	 * @return int|null
	 */
	public function getEntityId();
	/**
	 * setEntityId
	 *
	 * @param int $entity_id
	 * @return null
	 */
	public function setEntityId($entity_id);

	/**
	 * getOrder_id
	 *
	 * @return int|null
	 */
	public function getOrderId();

	/**
	 * setOrderId
	 *
	 * @param int $order_id
	 * @return null
	 */
	public function setOrderId($order_id);

	/**
	 * Get getOrder_item_id
	 *
	 * @return int|null
	 */
	public function getOrderItemId();

	/**
	 * setOrderItemId
	 *
	 * @param int $order_item_id
	 * @return null
	 */
	public function setOrderItemId($order_item_id);


	/**
	 * Get getOrder_item_sku
	 *
	 * @return string|null
	 */
	public function getOrderItemSku();
	/**
	 * setOrderItemSku
	 *
	 * @param int $order_item_sku
	 * @return null
	 */
	public function setOrderItemSku($order_item_sku);
	/**
	 * Get getOrder_item_price
	 *
	 * @return int|null
	 */
	public function getOrderItemPrice();

	/**
	 * setOrderItemPrice
	 *
	 * @param int $order_item_price
	 * @return null
	 */
	public function setOrderItemPrice($order_item_price);

	/**
	 * Get getCommision_type
	 *
	 * @return int|null
	 */
	public function getCommisionType();

	/**
	 * setCommisionType
	 *
	 * @param int $commision_type
	 * @return null
	 */
	public function setCommisionType($commision_type);

	/**
	 * Get getCommission_value
	 *
	 * @return int|null
	 */
	public function getCommissionValue();

	/**
	 * setCommissionValue
	 *
	 * @param int $commission_value
	 * @return null
	 */
	public function setCommissionValue($commission_value);

	/**
	 * Get getCreated_at
	 *
	 * @return null
	 */
	public function getCreatedAt();
	/**
	 * setCreatedAt
	 *
	 * @param string $created_at
	 * @return null
	 */
	public function setCreatedAt($created_at);
	/**
	 * Get getUpdated_at
	 *
	 * @return null
	 */
	public function getUpdatedAt();

	/**
	 * setCreatedAt
	 *
	 * @param string $updated_at
	 * @return null
	 */
	public function setUpdatedAt($updated_at);
}
