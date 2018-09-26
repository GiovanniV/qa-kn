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
use Magento\Customer\Model\Session;

/**
 * Observer Class
 */
class Accountedited implements ObserverInterface {

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
     * get update details fo customer in xcml format
     */
    public function execute(Observer $observer)
    {        
     
	 $customer=$this->customerRepository->getById($this->session->getCustomerId());		  
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
		 $xml->save($this->directory_list->getPath('media')."/customer/".date('Y-m-d_H-i-s')."customeraccountedited.xml");
	
    }
}
