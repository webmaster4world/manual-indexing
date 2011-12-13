<?

class Antalis_Service_XML_Request
{
    
    public function __construct()
    {
      
    }

    /**
     *  xml frament for requesting Stocklevelinfo 
     *  @param: $enquiryType
     *  @param: $userCode
     *  @param: $customerID                 
     *  @param: $lineNr     
     */    
    public function productStockLevelService($conf = NULL ,$lineNr = '1', $productIDs_XML = '')
    {

    if(is_null($conf))
    {
        die("no serviceconfig avaliable in: ".__METHOD__);
    }

      return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:Services.productStockLevelService.productStockLevelPortType.productStockLevelPortType" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">
   <soapenv:Header/>
   <soapenv:Body>
      <urn:productStockLevelCheck soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
         <productStockLevelInput xsi:type="urn:InputParameters" xmlns:urn="urn:Services.productStockLevelService.Classes">
            <enquiryType xsi:type="xsd:string">{$conf['enquirytype']}</enquiryType>
            <userCode xsi:type="xsd:string">{$conf['usercode']}</userCode>
            <customerID xsi:type="xsd:string">{$conf['customerid']}</customerID>
            <detailRow xsi:type="urn:InputDetailRowArray" soapenc:arrayType="urn:InputDetailRow[]">
		{$productIDs_XML}
	    </detailRow>
         </productStockLevelInput>
      </urn:productStockLevelCheck>
   </soapenv:Body>
</soapenv:Envelope>
XML;
    }

    /**
     * returns productID XML Fragment
     * @param array $products
     * @return xml
     */
    public function getProductIDTag($products=NULL)
    {
    
        $lineNr=1;
        foreach($products['childs'] as $productID =>$product){
   
            $product['bzp_antalis_no'] = strtoupper($product['bzp_antalis_no']);

            $ret.="<InputDetailRow><lineNr>$lineNr</lineNr><productID>{$product['bzp_antalis_no']}</productID></InputDetailRow>";
            $lineNr++;
        }

        return $ret;
    }
}