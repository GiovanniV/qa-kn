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

class Afterplaceorder implements ObserverInterface{
    /**
     * Order Model
     *
     * @var \Magento\Sales\Model\Order $order
     */
     protected $order;
     protected $directory_list;
	 protected $_urlInterface;

     public function __construct(
        \Magento\Sales\Model\Order $order,
		\Magento\Framework\App\Filesystem\DirectoryList $directory_list,
	    \Magento\Framework\UrlInterface $urlInterface          
    )
    {
        $this->order = $order;
		$this->directory_list = $directory_list;  
		$this->_urlInterface = $urlInterface;
    }

 public function execute(\Magento\Framework\Event\Observer $observer)
		{
		$orderId = $observer->getEvent()->getOrderIds();

		$soapTokenUri =  $this->_urlInterface->getBaseUrl().'soap/?wsdl&services=integrationAdminTokenServiceV1';
		$soapResourceUri = $this->_urlInterface->getBaseUrl().'soap/default?wsdl&services=salesOrderRepositoryV1';
		$username = 'admin';
		$password = 'admin@123';
		$options = [
		'soap_version' => SOAP_1_2,
		'trace' => 1,
		'connection_timeout' => 120,];
		// create client and get token response using username and password
		$cli = new \SoapClient($soapTokenUri, $options);
		$response = $cli->integrationAdminTokenServiceV1CreateAdminAccessToken([
		'username' => $username,
		'password' => $password
		]);
		$token = $response->result;

		// create bearer token Authorization header
		$options['stream_context'] = stream_context_create([
		'http' => [
		'header' => sprintf('Authorization: Bearer %s', $token)
		]
		]);

		$cli = new \SoapClient($soapResourceUri, $options);

		$soapResponse = $cli->salesOrderRepositoryV1Get(array('id'=>$orderId));
		$json=json_decode(json_encode($soapResponse),true);

		$xml = new \SimpleXMLElement('<root/>');
		// function callback
		$this->array2XML($xml, $json);

		$xml->asXML($this->directory_list->getPath('media')."/customer/".date('Y-m-d_H-i-s')."order.xml");			
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