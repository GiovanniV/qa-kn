<?php
/**
 * Namespace
 */
namespace Born\Createxml\Observer;
/**
 * Dependencies
 */
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;

/**
 * Observer Class
 */
class AfterAddressSaveObserver implements ObserverInterface {

   /** @var CustomerRepositoryInterface */
    protected $customerRepository;
    protected $directory_list;
	 protected $session;
    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
		 Session $customerSession,
		\Magento\Framework\App\Filesystem\DirectoryList $directory_list
    ) {
        $this->customerRepository = $customerRepository;
		$this->session = $customerSession;
		 $this->directory_list = $directory_list;  
    }

    /**
     * fetching address bof customer both new added and updated
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {        
     $customerAddress = $observer->getCustomerAddress();
	 //convert address object to array
      $addr=$customerAddress->toArray();
	   $xmlString='<?xml version="1.0"?>
		<addresses>
		<id>'.$addr['id'].'</id>
		<customerId>'.$addr['customer_id'].'</customerId>
		<regionCode>'.$addr['region_code'].'</regionCode>
		<region>'.$addr['region'].'</region>
		<regionId>'.$addr['region_id'].'</regionId>
		<countryId>'.$addr['country_id'].'</countryId>
		<street>'.$addr['street'].'</street>
		<company>'.$addr['company'].'</company>
		<telephone>'.$addr['telephone'].'</telephone>
		<postcode>'.$addr['postcode'].'</postcode>
		<city>'.$addr['city'].'</city>
		<createdAt>'.$addr['created_at'].'</createdAt>
		<updatedAt>'.$addr['updated_at'].'</updatedAt>
		<firstname>'.$addr['firstname'].'</firstname>
		<lastname>'.$addr['lastname'].'</lastname>
		<defaultShipping>'.$addr['default_shipping'].'</defaultShipping>
		<defaultBilling>'.$addr['default_billing'].'</defaultBilling>
		<isDefaultShipping>'.$addr['is_default_shipping'].'</isDefaultShipping>
        <isDefaultBilling>'.$addr['is_default_billing'].'</isDefaultBilling>
		</addresses>';
	 
     $xml=new \DOMDocument();
		 $xml->loadXML($xmlString);		  
		 $xml->save($this->directory_list->getPath('media')."/customer/".date('Y-m-d_H-i-s')."address.xml");
       
    }
}
