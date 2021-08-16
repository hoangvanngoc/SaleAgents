<?php
namespace AHT\SaleAgents\Plugin\Customer;

class FirstnamesaPlugin
{
    public function afterGetFirstname(\Magento\Customer\Model\Data\Customer $subject, $result)
    {
        if($subject->getCustomAttribute("is_sales_agent")) {
            if($subject->getCustomAttribute("is_sales_agent")->getValue() == \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_YES){
                $result = "Sales Agent:" .$result;
            }
        }
        return $result;
    }
}
