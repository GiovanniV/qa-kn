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
		//$xml =json_decode(json_encode(simplexml_load_string(file_get_contents("php://input"))),TRUE);	
		$products =json_decode(file_get_contents("php://input"),true);		
	    $msg=array();
	     if(count($products)>0){
		//$products=$xml['products_row'];
	    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$fileSystem = $objectManager->create('\Magento\Framework\Filesystem');
        $mediaPath = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
		$newProductId=array();
		foreach($products as $key=>$product){		
	    $flag=0;		
		try {
		$newProduct = $this->producteRepository->get($product['sku'], true);
		$flag=1;
		} catch (\Exception $e) {
		$newProduct=$objectManager->create('\Magento\Catalog\Model\Product');
		$flag=0;
		}
		$keys = array_keys($product);
		//return 'dddd='.$flag;
		
		if (in_array("categories", $keys))$cats=explode('/',$product['categories']);
		
		$categy=array('Air Filter Set'=>3,'Oil Filter'=>4,'Air Cleaner Mount'=>5,'Air Cleaner Mount'=>6,'Apparel - Other'=>7,'Fuel Filter'=>8,'Display Banner'=>9,'Air Cleaner Cover'=>8,'Other'=>8,'Air Filter'=>8,'Air Intake Hose'=>8,'Air Intake Hose Clamp'=>8,'Air Cleaner Mounting Gasket'=>9,'Air Intake Scoop'=>8,'Air Filter Wrap'=>8,'Air Cleaner Assembly'=>8,'Cold Air Intake Performance Kit'=>8,'Powersports Accessories'=>8,'Crankcase Breather Element'=>8,'Air Filter Cover Assembly'=>8,'Air Filter Cover Assembly'=>8,'Jet Kit'=>8,'Catalogs and Promotional Materials'=>8,'Air Filter Cleaner'=>8,'Cabin Air Filter'=>8);
		try
		{
       
		
        if($flag==0){      		
		$newProduct->setUrlKey($this->format_uri('K&N '.$product['sku'].' '.$product['name']));	
		}		
	
		if (in_array("categories", $keys))$newProduct->setPrice($product['price'] );
		if (in_array("sku", $keys)) $newProduct->setSku($product['sku']);
		if (in_array("name", $keys))$newProduct->setName('K&N '.$product['sku'].' '.$product['name']);
		if (in_array("attribute_set_code", $keys))$newProduct->setAttributeSetId(4);
		if (in_array("categories", $keys)) $newProduct->setCategoryIds(array(2,$categy[$cats[1]])); 
		if (in_array("description", $keys))$newProduct->setDescription($product['description'] ); 
		if (in_array("short_description", $keys))$newProduct->setShortDescription($product['short_description'] );
		if (in_array("weight", $keys))$newProduct->setWeight($product['weight']);
		if (in_array("product_online", $keys))$newProduct->setStatus($product['product_online']);
		if (in_array("tax_class_name", $keys))$newProduct->setTaxClassId(2);
		if (in_array("visibility", $keys))$newProduct->setAttributeSetId(4);
		if (in_array("special_price", $keys))$newProduct->setSpecialPrice($product['special_price']);
		if (in_array("special_price_from_date", $keys))$newProduct->setSpecialPriceFromDate($product['special_price_from_date']);
		if (in_array("special_price_to_date", $keys))$newProduct->setSpecialPriceToDate($product['special_price_to_date']);	
		if (in_array("meta_title", $keys))$newProduct->setMetaTitle($product['meta_title']);
		if (in_array("meta_keywords", $keys))$newProduct->setMetaKeyword($product['meta_keywords']);
		if (in_array("meta_description", $keys))$newProduct->setMetaDescription($product['meta_description']);
	   if (in_array("base_image", $keys)){
		  if($flag==0){
		  //in case of add product going
		if(file_exists($mediaPath.'import/'.$product['base_image'])){
			
		$newProduct->setMediaGallery(array('images' => array(), 'values' => array()));
		 $newProduct->setMediaGallery(array('images' => array(), 'values' => array()));
	    $newProduct->addImageToMediaGallery($mediaPath.'import/'.$product['base_image'], array('image', 'small_image', 'thumbnail'), false, false);
		}else{
         $this->downloadim($product['base_image'],$mediaPath);
		 $newProduct->setMediaGallery(array('images' => array(), 'values' => array()));
      	 $newProduct->addImageToMediaGallery($mediaPath.'import/'.$product['base_image'], array('image', 'small_image', 'thumbnail'), false, false);

		}	
		 }
			
		}
		
	  if (in_array("new_from_date", $keys))$newProduct->setNewFromDate($product['new_from_date']);
      if (in_array("country_of_manufacture", $keys))
		{
		$attribute = $newProduct->getResource()->getAttribute('country_of_manufacture');
		 if ($attribute->usesSource()) {
		 $option_id = $attribute->getSource()->getOptionId($product['country_of_manufacture']);
		 $newProduct->setCountryOfManufacture($option_id);
		 }	
			
		}
		$newProduct->setWebsiteIds(array(1));
		$newProduct->setAttributeSetId(4);
		$newProduct->setVisibility(4);		
        $newProduct->setTypeId('simple'); 
		$qty=0;
		$is_inst=0;
	if (in_array("qty", $keys))$qty=$product['qty'];
	if (in_array("is_in_stock", $keys))	$is_inst=$product['is_in_stock'];
		$newProduct->setStockData(
        array(
        'use_config_manage_stock' => 0, 
        // checkbox for 'Use config settings' 
        'manage_stock' => 1, // manage stock
        'min_sale_qty' => 1, // Shopping Cart Minimum Qty Allowed 
        'max_sale_qty' => 1000, // Shopping Cart Maximum Qty Allowed
        'is_in_stock' => $is_inst, // Stock Availability of product
        'qty' => $qty // qty of product
         )
         );
		 $optionSize=array();
		 $optionyear_of_vehiclee=array();
		 $optionmodel=array();
		 $optionmake=array();
		 $material='';
		$vehicle_type='';
		$oversize_shipping='';
		$finish='';
		$air_filter_shape='';
		$color='';
		$style='';
		$material_used='';
		$extra=array();
		 if(in_array("additional_attributes", $keys)){	
		 if(count($product['additional_attributes'])>0){
		$additional_attributes=explode(',',str_replace('"','',$product['additional_attributes']));
		$warranty='';
		$warrantycode='';
		$warrantyval='';
		
		foreach($additional_attributes as $attributes)		{
		$attribute=explode('=',$attributes);
		
		if(count($attribute)>1)
		{			 
	    if($attribute[0]=='Warranty'){
		$warrantylevel=$attribute[0];
		$warrantyval=$attribute[1];}
		elseif($this->formatcode($attribute[0])=='product_box_height'){
		$newProduct->setProductBoxHeight($attribute[1]);
		}
		elseif($this->formatcode($attribute[0])=='product_box_length'){
		$newProduct->setProductBoxLength($attribute[1]);
		}
		elseif($this->formatcode($attribute[0])=='product_box_width'){
		$newProduct->setProductBoxWidth($attribute[1]);
		}
		elseif($this->formatcode($attribute[0])=='product_style'){
		$newProduct->setProductStyle($attribute[1]);
		}
		elseif($this->formatcode($attribute[0])=='package_contents'){
		//	echo $attribute[1]; die;
		$newProduct->setPackageContents($attribute[1]);
		}
		elseif($this->formatcode($attribute[0])=='height'){
		$newProduct->setHeight($attribute[1]);
		}
		elseif($this->formatcode($attribute[0])=='engine_size'){		
		$optionSize[]=$attribute[1];
		}
		
		elseif($this->formatcode($attribute[0])=='year_of_vehicle'){		
		$optionyear_of_vehiclee[]=$attribute[1];
		}
		elseif($this->formatcode($attribute[0])=='make'){		
		$optionmake[]=$attribute[1];
		}
		elseif($this->formatcode($attribute[0])=='model'){		
		$optionmodel[]=$attribute[1];
		}
		elseif($this->formatcode($attribute[0])=='material'){		
		$material=$attribute[1];
		}
	   elseif($this->formatcode($attribute[0])=='vehicle_type'){		
		$vehicle_type=$attribute[1];
		}
      elseif($this->formatcode($attribute[0])=='oversize_shipping'){		
		$oversize_shipping=$attribute[1];
		}
	   elseif($this->formatcode($attribute[0])=='finish'){		
		$finish=$attribute[1];
		}
	 elseif($this->formatcode($attribute[0])=='air_filter_shape'){		
		$air_filter_shape=$attribute[1];
		}
	elseif($this->formatcode($attribute[0])=='color'){		
		$color=$attribute[1];
		}
	elseif($this->formatcode($attribute[0])=='material_used'){		
		$material_used=$attribute[1];
		}
	   elseif($this->formatcode($attribute[0])=='style'){		
		$style=$attribute[1];
		}else{
	  $extra[]=array($this->formatcode($attribute[0]),$attribute[0],$attribute[1]);
		}
	    		
		}else{
			
		$warranty.=','.$attribute[0];
						
		}
			
		}
		
		$newProduct->setWarranty(addslashes($warrantyval.$warranty));
					
		 }
			
		}
		//echo "<pre>"; print_r($extra);die;
		if(count($optionSize)>0){
		$optionId=array();
		foreach($optionSize as $size){		
		$this->insertAttributeOptions('engine_size', array(array('value'=>$size),));
		$optionId[]=$this->getSelectedAttributes('engine_size', $size);
		
		}
		$newProduct->setEngineSize($optionId);		
		}
		if(count($optionmodel)>0){
			$optionId=array();
		foreach($optionmodel as $model){
		
		$this->insertAttributeOptions('model', array(array('value'=>$model),));
		$optionId[]=$this->getSelectedAttributes('model', $model);
		
		}
		$newProduct->setModel($optionId);
		
		}
		
		if(count($optionmake)>0){
			$optionId=array();
		foreach($optionmake as $make){
		
		$this->insertAttributeOptions('make', array(array('value'=>$make),));
		$optionId[]=$this->getSelectedAttributes('make', $make);
		
		}
		$newProduct->setMake($optionId);
		
		}
		
		if(count($optionyear_of_vehiclee)>0){
			$optionId=array();
		foreach($optionyear_of_vehiclee as $vehicle){
		
		$this->insertAttributeOptions('year_of_vehicle', array(array('value'=>$vehicle),));
		$optionId[]=$this->getSelectedAttributes('year_of_vehicle', $vehicle);
		
		}
		$newProduct->setYearOfVehicle($optionId);
		
		}
		
       if($material!=''){
		$this->insertAttributeOptions('filter_material', array(array('value'=>$material),));
		$optionId=$this->getSelectedAttributes('filter_material', $material);
		$newProduct->setFilterMaterial($optionId);
		
		}	
	
		if($vehicle_type!=''){
	
		$this->insertAttributeOptions('vehicle_type', array(array('value'=>$vehicle_type),));
		$optionId=$this->getSelectedAttributes('vehicle_type', $vehicle_type);
		
		$newProduct->setVehicleType($optionId);
		}
		if($oversize_shipping!=''){
		$this->insertAttributeOptions('oversize_shipping', array(array('value'=>$oversize_shipping),));
		$optionId=$this->getSelectedAttributes('oversize_shipping', $oversize_shipping);
		
		$newProduct->setOversizeShipping($optionId);
		}
		if($finish!=''){
	
		$this->insertAttributeOptions('finish', array(array('value'=>$finish),));
		$optionId=$this->getSelectedAttributes('finish', $finish);
		
		$newProduct->setFinish($optionId);
		}
		
		if($air_filter_shape!=''){
	
		$this->insertAttributeOptions('air_filter_shape', array(array('value'=>$air_filter_shape),));
		$optionId=$this->getSelectedAttributes('air_filter_shape', $air_filter_shape);
		
		$newProduct->setAirFilterShape($optionId);
		}
		if($color!=''){	
		$this->insertAttributeOptions('color', array(array('value'=>$color),));
		$optionId=$this->getSelectedAttributes('color', $color);
		
		$newProduct->setColor($optionId);
		}
		if($material_used!=''){
	
		$this->insertAttributeOptions('material_used', array(array('value'=>$material_used),));
		$optionId=$this->getSelectedAttributes('material_used', $material_used);
		
		$newProduct->setMaterialUsed($optionId);
		}if($style!=''){

		$this->insertAttributeOptions('style', array(array('value'=>$style),));
		$optionId=$this->getSelectedAttributes('style', $style);
		
		$newProduct->setStyle($optionId);
		}		
		 
      if (in_array("additional_attributes", $keys))$newProduct->setAvailableInformation(str_replace(',','<br>',$product['additional_attributes']));
       // $newProduct->save();     
        $newProduct->save();
		foreach($extra as $field){
		$this->createattributesbycust($objectManager,$field[1],'text');		
	    $this->updateval($newProduct->getId(),$field[0],$field[2],$objectManager);		

		}
		
		
		
		 if($flag==0){
		  $msg[]="inserted simple product id :: ". $newProduct->getId()."\n"; 
		 }else{
	      $msg[]="update simple product id :: ". $newProduct->getId()."\n"; 		 
		}
		unset($newProduct);
		}
		catch(Exception $exception) 
		{
        $msg[]=$exception->getMessage();	   
        }
		
		}     
		
    }else{
		
		$msg[]='you cant access it';	 
	}
	
	
	return $response->setData(json_encode($msg));die;
  }
   public function updateval($newProduct,$attr,$val,$objectManager)
   {
	   
	    $array_product = [$newProduct]; //product Ids
		$productActionObject = $objectManager ->create('Magento\Catalog\Model\Product\Action');
		$productActionObject->updateAttributes($array_product, array($attr => $val), 0);
	   
	   
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

public function format_uri($string, $separator = '-' )
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and', "'" => '');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
} 

public function createattributesbycust($objectManager,$val,$type='text')
{
$eavSetupFactory = $objectManager->create('Magento\Eav\Setup\EavSetupFactory');
$setup = $objectManager->create('Magento\Framework\Setup\ModuleDataSetupInterface');
$eavSetup = $eavSetupFactory->create(['setup' => $setup]);

$attr = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $this->formatcode($val));
if(count($attr)==0){
	$eavSetup->addAttribute(
					\Magento\Catalog\Model\Product::ENTITY, $this->formatcode($val), // attribute code
					[
						'group' => 'Product Specifications',
						'type' => 'varchar',
						'backend' => '',
						'frontend' => '',
						'label' => $val, // label
						'input' => $type,
						'class' => '',
						'source' => '',
						'global' => 1,
						'visible' => true,
						'required' => false,
						'user_defined' => false,
						'default' => 0,
						'searchable' => false,
						'filterable' => true,
						'comparable' => true,
						'visible_on_front' => true,
						'used_in_product_listing' => true,
						'unique' => false,
						'apply_to' => '',
						'attribute_set_id'=>4
					]
				);	
}
}
public function formatcode($string, $separator = '_' )
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and', "'" => '');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
} 
}

