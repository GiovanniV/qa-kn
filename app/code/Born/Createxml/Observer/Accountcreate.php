<?php
/**
 * Namespace
 */
namespace Born\Createxml\Observer;
/**
 * Dependencies
 */
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Observer Class
 */
class Accountcreate implements ObserverInterface {

   /** @var CustomerRepositoryInterface */
    protected $customerRepository;
    protected $directory_list;
    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
		\Magento\Framework\App\Filesystem\DirectoryList $directory_list
    ) {
        $this->customerRepository = $customerRepository;
		 $this->directory_list = $directory_list;  
    }

    /**
     * using customer registration observer fetching custom xml to work application fast with compare magentyo api
     */
    public function execute(Observer $observer)
    {        
    $customer = $observer->getCustomer();
	$xmlString = '<?xml version="1.0"?><result>
	<id>'.$customer->getId().'</id>
	<groupId>'.$customer->getGroupId().'</groupId>
	<createdAt>'.$customer->getCreatedAt().'</createdAt>
	<updatedAt>'.$customer->getUpdatedAt().'</updatedAt>
	<createdIn>'.$customer->getCreatedIn().'</createdIn>
	<email>'.$customer->getEmail().'</email>
	<firstname>'.$customer->getFirstname().'</firstname>
	<lastname>'.$customer->getLastname().'</lastname>
	<storeId>'.$customer->getStoreId().'</storeId>
	<BirthDate>'.$customer->getDob().'</BirthDate>
	<websiteId>'.$customer->getId().'</websiteId>
	</result>';		   
		 $xml=new \DOMDocument();
		 $xml->loadXML($xmlString);		  
		 $xml->save($this->directory_list->getPath('media')."/customer/".date('Y-m-d_H-i-s')."customerregistration.xml");
	
    }
}
