<?php

namespace App\Controllers;

use SoapClient;
use Exception;

class Premium extends BaseController
{
    public function index(){
        // all titles
        $all_titles = $this->titleItems();
        // log_message('debug', 'Data passed to view: ' . json_encode($all_titles));die;
        
        return view('wsdl_view', ['data' => $all_titles]);
    }

    public function titleItems()
    {
        try {
            // WSDL URL
            $wsdl = 'https://api-uat.tihsa.co.za/QuickQuotes/GenericControls.asmx?wsdl';

            // Authentication credentials
            $options = [
                'login' => 'Coach$uranc3B', // Username for authentication
                'password' => 'C0@cHSu4anc3', // Password for authentication
                'trace' => true, // Enable for debugging
                'exceptions' => true, // Throw exceptions on errors
            ];

            // Initialize the SOAP client
            $client = new SoapClient($wsdl, $options);

            // Call a SOAP method (replace `getMethod` with your method name)
            $response = $client->GetTitleItems([
                'p_LangID' => 'EN', // Replace with actual parameters
                'p_WordCase' => 'PROPERCASE',
            ]);

            // Convert response object to array
            $responseArray = json_decode(json_encode($response), true);

            // Extract and process the `any` string
            $anyString = $responseArray['GetTitleItemsResult']['any'] ?? '';
            $anyString = '<root>' . $anyString . '</root>';

            $associativeArray = $this->processAnyString($anyString);
            // Pass the data to the view
            return $associativeArray;

        } catch (Exception $e) {
            // Handle exceptions and log errors
            return view('error_view', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Process the `any` string into an associative array.
     *
     * @param string $anyString
     * @return array
     */
 private function processAnyString(string $anyString): array
{
    $result = [];

    try {
        // Parse the XML string
        $xml = new \SimpleXMLElement($anyString);
        // Register namespaces used in the XML
        $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
        $xml->registerXPathNamespace('msdata', 'urn:schemas-microsoft-com:xml-msdata');

        // Navigate to the `diffgram` and `DocumentElement/Titles` nodes
        $titles = $xml->xpath('//diffgr:diffgram/DocumentElement/Titles');

        // Iterate over each `<Titles>` node and extract the `Value` and `Description`
        foreach ($titles as $title) {
            $result[] = [
                'Value' => (string) $title->Value,
                'Description' => (string) $title->Description,
            ];
        }
    } catch (\Exception $e) {
        // Handle errors in parsing XML
        log_message('error', 'Failed to parse XML: ' . $e->getMessage());
        $result = ['error' => 'Invalid XML format'];
    }

    return $result;
}
    /**
     * Map a title abbreviation to a label.
     *
     * @param string $abbr
     * @return string
     */
    private function getTitleLabel(string $abbr): string
    {
        $map = [
            'SUPE' => 'Superintendent',
            'SIST' => 'Sister',
            'SGT' => 'Sergeant',
            'REV' => 'Reverend',
            'RABB' => 'Rabbi',
            'PROF' => 'Professor',
            'PRIN' => 'Principal',
            'PSTR' => 'Pastor',
            'MEMs' => 'Members',
            'MSMs' => 'Miss/Ms.',
            'MRS' => 'Mrs.',
            'MR' => 'Mr.',
            'MNR' => 'Minor',
            'MISS' => 'Miss',
            'LTC' => 'Lieutenant Colonel',
            'LT' => 'Lieutenant',
            'JUDG' => 'Judge',
            'INSP' => 'Inspector',
            'GN' => 'General',
            'FATH' => 'Father',
            'DR' => 'Doctor',
            'CONS' => 'Consultant',
            'COMM' => 'Commander',
            'COL' => 'Colonel',
            'CAPT' => 'Captain',
            'BRIG' => 'Brigadier',
            'ADV' => 'Advisor',
            'ADM' => 'Admiral',
        ];

        return $map[$abbr] ?? 'Unknown Title';
    }
}
