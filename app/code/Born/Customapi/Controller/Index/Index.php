<?php
namespace Born\Customapi\Controller\Index;
 
class Index extends \Magento\Framework\App\Action\Action
{
  protected $jsonHelper;
  public function __construct(
\Magento\Framework\App\Action\Context $context,
                                 \Magento\Catalog\Model\Product $_product,\Magento\Framework\Json\Helper\Data $jsonHelper,\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,\Magento\Catalog\Api\ProductRepositoryInterface $producteRepository,\Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository)
  {
	    $this->_product = $_product;
	    $this->jsonHelper = $jsonHelper;
        $this->_eventManager = $context->getEventManager();
	   $this->resultJsonFactory = $resultJsonFactory;
	  	$this->productAttributeRepository=$productAttributeRepository;
		$this->producteRepository=$producteRepository;

    return parent::__construct($context);
  }
 
  public function execute()
  {
	         
		$response = $this->resultJsonFactory->create();  
       $products =json_decode(file_get_contents("php://input"),true);	
	    if(count($products)>0){
	    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$fileSystem = $objectManager->create('\Magento\Framework\Filesystem');
        $mediaPath = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
		$newProductId=array();
			foreach($products as $product){		
	    $flag=0;		
		try {
		$get_productid = $objectManager->create('\Magento\Catalog\Model\Product')->getIdBySku($product['sku']);
	    $newProduct = $objectManager->create('\Magento\Catalog\Model\Product')->load($get_productid);
		$flag=1;
		} catch (\Exception $e) {
		$newProduct=$objectManager->create('\Magento\Catalog\Model\Product');
		$flag=0;
		}
		//return 'dddd='.$flag;
		$cats=explode('/',$product['category_ids']);
		$categy=array('Air Filter Set'=>3,'Oil Filter'=>4,'Air Cleaner Mount'=>5,'Air Cleaner Mount'=>6,'Apparel - Other'=>7,'Fuel Filter'=>8,'Display Banner'=>9,'Air Cleaner Cover'=>8,'Other'=>8,'Air Filter'=>8,'Air Intake Hose'=>8,'Air Intake Hose Clamp'=>8,'Air Cleaner Mounting Gasket'=>9,'Air Intake Scoop'=>8,'Air Filter Wrap'=>8,'Air Cleaner Assembly'=>8,'Cold Air Intake Performance Kit'=>8,'Powersports Accessories'=>8,'Crankcase Breather Element'=>8,'Air Filter Cover Assembly'=>8,'Air Filter Cover Assembly'=>8,'Jet Kit'=>8,'Catalogs and Promotional Materials'=>8,'Air Filter Cleaner'=>8,'Cabin Air Filter'=>8);
		try
		{
        $newProduct->setSku($product['sku']);
        $newProduct->setName( $product['name'] );
		$newProduct->setPrice($product['price'] );
		if($product['description']!='')$newProduct->setDescription( $product['description'] ); 
        if($flag==0){		
        if($product['url']!='')  $newProduct->setUrlKey( $product['url'] );	
		}		
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
		if($product['attributes']!='')$newProduct->setAvailableInformation($product['attributes']);
		if($product['model']!=''){
			$optionId=array();
		foreach($product['model'] as $model){
		$optionIds=$this->getSelectedAttributes('model', $model);
		if(count($optionId)==1){
		$this->insertAttributeOptions('model', array(array('value'=>$model),));
		$optionId[]=$this->getSelectedAttributes('model', $model);
		}else{
		$optionId[]=$optionIds;	
		}
		}
		$newProduct->setModel($optionId);
		
		}
		if($product['material']!=''){
		$optionId=$this->getSelectedAttributes('filter_material', $product['material']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('filter_material', array(array('value'=>$product['material']),));
		$optionId=$this->getSelectedAttributes('filter_material', $product['material']);
		}
		$newProduct->setFilterMaterial($optionId);
		
		}
		if(count($product['engine_size'])>0){
		$optionId=array();
		foreach($product['engine_size'] as $size){		
		$optionIds=$this->getSelectedAttributes('engine_size', $size);
		if(count($optionId)==1){
		$this->insertAttributeOptions('engine_size', array(array('value'=>$size),));
		$optionId[]=$this->getSelectedAttributes('engine_size', $size);
		}else{
		$optionId[]=$optionIds;	
		}
		}
		$newProduct->setEngineSize($optionId);		
		}
		
		if(count($product['year_of_vehicle'])>0){
		$optionId=array();
		foreach($product['year_of_vehicle'] as $vehicle){
		$optionIds=$this->getSelectedAttributes('year_of_vehicle', $vehicle);
		if(count($optionIds)==1){
		$this->insertAttributeOptions('year_of_vehicle', array(array('value'=>$vehicle),));
		$optionId[]=$this->getSelectedAttributes('year_of_vehicle', $vehicle);
		}else{
		$optionId[]=$optionIds;	
		}
		}
		$newProduct->setYearOfVehicle($optionId);		
		}
			
		if(count($product['make'])>0){
			$optionId=array();
		foreach($product['make'] as $make){
		$optionIds=$this->getSelectedAttributes('make', $make);
		if(count($optionId)==1){
		$this->insertAttributeOptions('make', array(array('value'=>$make),));
		$optionId[]=$this->getSelectedAttributes('make', $make);
		}else{
		$optionId[]=$optionIds;	
		}
		}
		$newProduct->setMake($optionId);		
		}
		if($product['vehicle_type']!=''){
		$optionId=$this->getSelectedAttributes('vehicle_type', $product['vehicle_type']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('vehicle_type', array(array('value'=>$product['vehicle_type']),));
		$optionId=$this->getSelectedAttributes('vehicle_type', $product['vehicle_type']);
		}
		$newProduct->setVehicleType($optionId);
		}
		if($product['oversize_shipping']!=''){
		$optionId=$this->getSelectedAttributes('oversize_shipping', $product['oversize_shipping']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('oversize_shipping', array(array('value'=>$product['oversize_shipping']),));
		$optionId=$this->getSelectedAttributes('oversize_shipping', $product['oversize_shipping']);
		}
		$newProduct->setOversizeShipping($optionId);
		}
		if($product['finish']!=''){
		$optionId=$this->getSelectedAttributes('finish', $product['finish']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('finish', array(array('value'=>$product['finish']),));
		$optionId=$this->getSelectedAttributes('finish', $product['finish']);
		}
		$newProduct->setFinish($optionId);
		}
		
		if($product['air_filter_shape']!=''){
		$optionId=$this->getSelectedAttributes('air_filter_shape', $product['air_filter_shape']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('air_filter_shape', array(array('value'=>$product['air_filter_shape']),));
		$optionId=$this->getSelectedAttributes('air_filter_shape', $product['air_filter_shape']);
		}
		$newProduct->setAirFilterShape($optionId);
		}
		if($product['color']!=''){
		$optionId=$this->getSelectedAttributes('color', $product['color']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('color', array(array('value'=>$product['color']),));
		$optionId=$this->getSelectedAttributes('color', $product['color']);
		}
		$newProduct->setColor($optionId);
		}if($product['material_used']!=''){
		$optionId=$this->getSelectedAttributes('material_used', $product['material_used']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('material_used', array(array('value'=>$product['material_used']),));
		$optionId=$this->getSelectedAttributes('material_used', $product['material_used']);
		}
		$newProduct->setMaterialUsed($optionId);
		}if($product['style']!=''){
		$optionId=$this->getSelectedAttributes('style', $product['style']);
		if(count($optionId)==1){
		$this->insertAttributeOptions('finish', array(array('value'=>$product['style']),));
		$optionId=$this->getSelectedAttributes('style', $product['style']);
		}
		$newProduct->setStyle($optionId);
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
		$newProduct->setMediaGallery(array('images' => array(), 'values' => array()));
	    $newProduct->addImageToMediaGallery($mediaPath.'import/'.$product['basimage'], array('image', 'small_image', 'thumbnail'), false, false);
		}else{
         $this->downloadim($product['basimage'],$mediaPath);
		 $newProduct->setMediaGallery(array('images' => array(), 'values' => array()));
      	 $newProduct->addImageToMediaGallery($mediaPath.'import/'.$product['basimage'], array('image', 'small_image', 'thumbnail'), false, false);

		}		
		 
      if($product['attributes']!='')$newProduct->setAvailableInformation(str_replace(',','<br>',$product['attributes']));
        $newProduct->save();        
		 if($flag==0){
			$msg="inserted simple product id :: ". $newProduct->getId()."\n"; 
		 }else{
	      $msg="update simple product id :: ". $newProduct->getId()."\n"; 		 
		}
		unset($newProduct);
		}
		catch(Exception $exception) 
		{
        $msg=$exception->getMessage();	   
        }
		
		}     
		
    }else{
		
		$msg='you cant access it';	 
	}
	return $response->setData($msg);die;
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
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* * Insert Attribute Options * */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
public function insertAttributeOptions($code, $opts) {
    $productAttributeRepository=$this->productAttributeRepository;
	 $all_attributes=array();
    if (!isset($all_attributes[$code])) {
        $all_attributes[$code] = $this->attributeValues($code);
    } $attribute = $productAttributeRepository->get($code);
    foreach ($opts as $option_obj) {
        $option_value = trim($option_obj['value']);
        if ($option_value != '' && !in_array($option_value, $all_attributes[$code])) {
            $all_attributes[$code][] = $option_value;
            $value['option'] = array($option_value, $option_value);
            $result = array('value' => $value);
            $attribute->setData('option', $result)->save();
			//return getSelectedAttributes($code, $value);
        }
    } $all_attributes[$code] = $this->attributeValues($code);
	//return $all_attributes;
}

/* * Get Attribute options by name * */

public function attributeValues($name) {
    $productAttributeRepository=$this->productAttributeRepository;
    $attributeOptions = $productAttributeRepository->get($name)->getOptions();
    $values = array();
    foreach ($attributeOptions as $option) {
        $lable = trim($option->getLabel());
        if ($lable != '') {
            $values[$option->getValue()] = $lable;
        }
    } return $values;
}

/* * Get attribute options by attribute code * */

public function getSelectedAttributes($code, $list) {
	 $all_attributes=array();
    if (!isset($all_attributes[$code])) {
        $all_attributes[$code] = $this->attributeValues($code);
    } if (!is_array($list)) {
        $list = trim($list);
        return array_search($list, $all_attributes[$code]);
    } $options = array();
    foreach ($list as $option) {
        $option = trim($option);
        if ($option != '') {
            $options[] = array_search($option, $all_attributes[$code]);
        }
    } return $options;
}
}

