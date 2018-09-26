<?php
/**
 * Namespace
 */
namespace Born\Createxml\Observer;
/**
 * Dependencies
 */
 use Magento\Customer\Api\CustomerRepositoryInterface;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
 
class Addtocartoberv implements \Magento\Framework\Event\ObserverInterface
{
	
	 /** @var CustomerRepositoryInterface */
    protected $customerRepository;
    protected $directory_list;
     protected $customerSession;
    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
		\Magento\Framework\App\Filesystem\DirectoryList $directory_list,
		\Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerRepository = $customerRepository;
		 $this->directory_list = $directory_list;  
		 $this->customerSession = $customerSession;  
    }


   public function execute(\Magento\Framework\Event\Observer $observer) 
    {
		 $item = $observer->getEvent()->getData('quote_item');         
         $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
		 //convert cart object to array
         $arritems=$item->toArray();
		   
		    $json=json_decode(json_encode($arritems),true);
   
   // create new instance of simplexml
$xml = new \SimpleXMLElement('<root/>');
// function callback
 $this->array2XML($xml, $json);

$xml->asXML($this->directory_list->getPath('media')."/customer/".date('Y-m-d_H-i-s')."addtocart.xml");

    }
	
	public function array2XML($obj, $array)
	{
		foreach ($array as $key => $value)
		{
			if(is_numeric($key))
				$key = 'item' . $key;

			if (is_array($value))
			{
				$node = $obj->addChild($key);
				$this->array2XML($node, $value);
			}
			else
			{
				$obj->addChild($key, htmlspecialchars($value));
			}
		}
}
}
