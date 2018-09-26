<?php
/**
 * Namespace
 */
namespace Born\Createxml\Cron;
/**
 * Dependencies
 */
class Aftersearchcron{
     protected $directory_list;
	 protected $_urlInterface;
	 private $_objectManager;
     protected $_logger;

     public function __construct(
		\Magento\Framework\App\Filesystem\DirectoryList $directory_list,
	    \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
         \Psr\Log\LoggerInterface $logger		 
    )
    {
		$this->directory_list = $directory_list;  
		$this->_urlInterface = $urlInterface;
		 $this->_objectManager = $objectmanager;
          $this->_logger = $logger;
    }

 public function execute()
		{		
		$resource =$this->_objectManager->get('Magento\Framework\App\ResourceConnection');
         $connection = $resource->getConnection();
		 $tableName = $resource->getTableName('search_query'); //gives table name with prefix
//Select Data from table
     $date=date('Y-m-d H:i:s');
$sql = "Select * FROM " . $tableName." where TIMESTAMPDIFF(HOUR, updated_at,'".$date."')<12  ";
$result = $connection->fetchAll($sql); // gives associated array, table fields as key in array. 
		$this->_logger->info(json_encode($result));

        return $this;
		}
}