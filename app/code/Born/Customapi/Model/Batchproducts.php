<?php
namespace Born\Customapi\Model;
 
use Symfony\Component\Config\Definition\Exception\Exception;
use Born\Customapi\Api\ProductManagementInterface;
 
class Batchproducts implements ProductManagementInterface
{
	protected $jsonHelper;

    public function __construct( \Magento\Framework\App\Helper\Context $context,
                                 \Magento\Catalog\Model\Product $_product,\Magento\Framework\Json\Helper\Data $jsonHelper)
    {
        $this->_product = $_product;
	    $this->jsonHelper = $jsonHelper;
        $this->_eventManager = $context->getEventManager();;
    }
	 public function decodebym($jsonData)
	{
		$decodedData = $this->jsonHelper->jsonDecode($jsonData);

		return $decodedData;
	}
    /**
     * @api
     * @return int
     */
    public function add(){
        $products =json_decode(file_get_contents("php://input"),true);		
	    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$fileSystem = $objectManager->create('\Magento\Framework\Filesystem');
        $mediaPath = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
		$newProductId=array();
		foreach($products as $product){
	    $newProduct= $objectManager->create('\Magento\Catalog\Model\Product');		
		$cats=explode('/',$product['category_ids']);
		$categy=array('Air Filter Set'=>3,'Oil Filter'=>4,'Air Cleaner Mount'=>5,'Air Cleaner Mount'=>6,'Apparel - Other'=>7,'Fuel Filter'=>8,'Display Banner'=>9,'Air Cleaner Cover'=>8,'Other'=>8,'Air Filter'=>8,'Air Intake Hose'=>8,'Air Intake Hose Clamp'=>8,'Air Cleaner Mounting Gasket'=>9,'Air Intake Scoop'=>8,'Air Filter Wrap'=>8,'Air Cleaner Assembly'=>8,'Cold Air Intake Performance Kit'=>8,'Powersports Accessories'=>8,'Crankcase Breather Element'=>8,'Air Filter Cover Assembly'=>8,'Air Filter Cover Assembly'=>8,'Jet Kit'=>8,'Catalogs and Promotional Materials'=>8,'Air Filter Cleaner'=>8,'Cabin Air Filter'=>8);
		try
		{
        $newProduct->setSku($product['sku'] );
        $newProduct->setName( $product['name'] );
		$newProduct->setPrice($product['price'] );
		if($product['description']!='')$newProduct->setDescription( $product['description'] );        
        if($product['url']!='')  $newProduct->setUrlKey( $product['url'] );		
		if($product['weight']!='')$newProduct->setWeight($product['weight'] );
        if($product['status']!='')$newProduct->setStatus($product['status'] );
		if($product['short_description']!='')$newProduct->setShortDescription($product['short_description'] );
		if($product['product_box_height']!='')$newProduct->setproductBoxHeight($product['product_box_height'] );
		if($product['product_box_length']!='')$newProduct->setproductBoxLength($product['product_box_length'] );
		if($product['product_box_width']!='')$newProduct->setProductBoxWidth($product['product_box_width'] );
		if($product['product_style']!='')$newProduct->setProductStyle($product['product_style'] );
		if($product['filter_re_oiling_amount']!='')$newProduct->setPackageContents($product['filter_re_oiling_amount'] );
		if($product['package_contents']!='')$newProduct->setShortDescription($product['package_contents'] );
		if($product['height']!='')$newProduct->setHeight($product['height'] );
		
		if($product['model']!=''){
		$attribute = $newProduct->getResource()->getAttribute('model');
		 if ($attribute->usesSource()) {
		 $option_id = $attribute->getSource()->getOptionId($product['model']);
	 	$newProduct->setModel($option_id);
		
		 }
		}
		if($product['engine_size']!=''){
		$attribute = $newProduct->getResource()->getAttribute('engine_size');
		 if ($attribute->usesSource()) {
		 $option_id = $attribute->getSource()->getOptionId($product['engine_size']);
	     $newProduct->setEngineSize($option_id);
		
		 }
		}
		if($product['year_of_vehicle']!=''){
		$attribute = $newProduct->getResource()->getAttribute('year_of_vehicle');
		 if ($attribute->usesSource()) {
		 $option_id = $attribute->getSource()->getOptionId($product['year_of_vehicle']);
		 $newProduct->setYearOfVehicle($option_id);		
		 }
		}		
		if($product['make']!=''){
		$attribute = $newProduct->getResource()->getAttribute('make');
		 if ($attribute->usesSource()) {
		 $option_id = $attribute->getSource()->getOptionId($product['make']);
		   $newProduct->setMake($option_id);
		
		 }
		}
		if($product['vehicle_type']!=''){
		$attribute = $newProduct->getResource()->getAttribute('vehicle_type');
		 if ($attribute->usesSource()) {
		 $option_id = $attribute->getSource()->getOptionId($product['vehicle_type']);
		 $newProduct->setVehicleType($option_id);
		
		 }
		}
		
		if($product['location']!=''){
		$attribute = $newProduct->getResource()->getAttribute('country_of_manufacture');
		 if ($attribute->usesSource()) {
		 $option_id = $attribute->getSource()->getOptionId($product['location']);
		 $newProduct->setCountryOfManufacture($option_id);
		 }
		}
		$newProduct->setAttributeSetId(4);
        if($product['type_id']!='')$newProduct->setTypeId($product['type_id'] ); 
		if($product['category_ids']!='')$newProduct->setCategoryIds(array(2,$categy[$cats[1]])); 
		if($product['website_ids']!='')$newProduct->setWebsiteIds(array($product['website_ids']));
		if($product['meta_title']!='')$newProduct->setMetaTitle($product['meta_title']);
		if($product['meta_keywords']!='')$newProduct->setMetaKeyword($product['meta_keywords']);
		if($product['meta_description']!='')$newProduct->setMetaDescription($product['meta_description']);		
		if($product['special_price']!='')$newProduct->setSpecialPrice($product['special_price']);	
		if($product['special_price_from_date']!='')$newProduct->setSpecialPriceFromDate($product['special_price_from_date']);	
		if($product['special_price_to_date']!='')$newProduct->setSpecialPriceToDate($product['special_price_to_date']);	
		$newProduct->setStockData(
        array(
        'use_config_manage_stock' => 0, 
        // checkbox for 'Use config settings' 
        'manage_stock' => $product['stock_data']['manage_stock'], // manage stock
        'min_sale_qty' => 1, // Shopping Cart Minimum Qty Allowed 
        'max_sale_qty' => $product['stock_data']['max_sale_qty'], // Shopping Cart Maximum Qty Allowed
        'is_in_stock' => $product['stock_data']['is_in_stock'], // Stock Availability of product
        'qty' => $product['stock_data']['qty'] // qty of product
         )
         );
		if(file_exists($mediaPath.'import/'.$product['basimage'])){
	    $newProduct->addImageToMediaGallery($mediaPath.'import/'.$product['basimage'], array('image', 'small_image', 'thumbnail'), false, false);
		}else{
         $this->downloadim($product['basimage'],$mediaPath);
      	 $newProduct->addImageToMediaGallery($mediaPath.'import/'.$product['basimage'], array('image', 'small_image', 'thumbnail'), false, false);

		}		
		 
      if($product['attributes']!='')$newProduct->setAvailableInformation(str_replace(',','<br>',$product['attributes']));
        $newProduct->save();
        $msg="inserted simple product id :: ". $newProduct->getId()."\n";
		unset($newProduct);
		}
		catch(Exception $exception) 
		{
        $msg=$exception->getMessage();	   
        }
		
		}
        return $msg;
    }
  
    public function addProduct()
    {
		          
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $newProduct= $objectManager->create('\Magento\Catalog\Model\Product');
		foreach($products as $product){
        $newProduct->setSku( $product['sku'] );
        $newProduct->setName( $product['name'] );
		$newProduct->setPrice( $product['price'] );
        $newProduct->setDescription( $product['description'] );
       // $newProduct->setUrlKey( $product['urlkey'] );
        $newProduct->setStatus( 1 );
        $newProduct->setVisibility( 4 );
        $newProduct->setTypeId( "simple" ); 
        $newProduct->save();
        $newProductId = $newProduct->getId();
		}
        return $newProductId;
    }
	public function update()
    {
		
	}
	public function downloadim($im,$mediaPath)
	{
	$ch = curl_init('http://kandn.com/images/l/'.$im);
	$fp = fopen($mediaPath.'import/'.$im, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);	
	}
}