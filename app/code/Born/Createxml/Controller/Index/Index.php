<?php
namespace Born\Createxml\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
    protected $_storeManager;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		$this->_storeManager = $storeManager;
		return parent::__construct($context);
	}

	public function execute()
	{
		$url=$this->_storeManager->getStore()->getBaseUrl();
	
	$userData = array("username" => "admin", "password" => "AtLeast8Chars!");
	$ch = curl_init($url."/rest/V1/integration/admin/token");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData)))); 
	 $token = json_decode(curl_exec($ch));
	
 $productUpdateJson = array ('product' => array ('sku' => '1243e21',	'price' => 324.95,'extensionAttributes' => array ('stockItem' =>  array ('qty' => 120,'isInStock' => true, ),), ),'saveOptions' => true,);
 
$ch = curl_init($url."rest/V1/products" ); 
$curlOptions = array(
    CURLOPT_CUSTOMREQUEST  => "POST",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode($productUpdateJson),
    CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: bearer ".$token)); 
    curl_setopt_array( $ch, $curlOptions ); 
	$response = curl_exec( $ch );
	echo '<pre>'; print_r($response);
	}
}