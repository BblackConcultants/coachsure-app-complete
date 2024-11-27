<?php

namespace App\Controllers;

use SoapClient;
use Exception;

class Premium extends BaseController
{
    protected $wsdl = 'https://api-uat.tihsa.co.za/QuickQuotes/GenericControls.asmx?wsdl';
    protected $options;

    public function __construct()
    {
        $this->options = [
            'login' => getenv('TELE_USERNAME'), 
            'password' => getenv('TELE_PASSWORD'),
            'trace' => true, 
            'exceptions' => true,
        ];
    }

    public function index(){
        $all_titles = $this->titleItems();
        $marital_statuses = $this->maritalStatuses();
        $all_genders = $this->genderItems();

        log_message('debug', 'Data passed to view: ' . json_encode($all_genders));die;
        
        // return view('wsdl_view', ['data' => $marital_statuses]);
    }

    public function genderItems()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetGenderItems([
                'p_LangID' => 'EN', // 
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetGenderItemsResult']['any'])) {
                    // Get the 'any' content
                    $anyString = $responseArray['GetGenderItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processGender($anyString);
                    if (!empty($associativeArray)) {
                        return $associativeArray;
                    } else {
                        return ['error' => 'Processed array is empty.'];
                    }
                } else {
                    return ['error' => 'Expected keys not found in the response.'];
                }

        } catch (Exception $e) {
            // Handle exceptions and log errors
            return view('error_view', ['error' => $e->getMessage()]);
        }
    }
    public function maritalStatuses()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client-> GetMaritalStatusItems([
                'p_LangID' => 'EN', // 
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            if (isset($responseArray['GetMaritalStatusItemsResult']['any'])) {
                    // Get the 'any' content
                    $anyString = $responseArray['GetMaritalStatusItemsResult']['any'];

                    
                    $anyString = '<root>' . $anyString . '</root>';

                   
                    $associativeArray = $this->processMarital($anyString);
                    if (!empty($associativeArray)) {
                        return $associativeArray;
                    } else {
                        return ['error' => 'Processed array is empty.'];
                    }
                } else {
                    return ['error' => 'Expected keys not found in the response.'];
                }

        } catch (Exception $e) {
            // Handle exceptions and log errors
            return view('error_view', ['error' => $e->getMessage()]);
        }
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
private function processGender($anyString) {
    // Log the raw XML string
    log_message('debug', 'Raw XML content: ' . $anyString);

    // Wrap in <root> tags to make it valid XML
    $anyString = '<root>' . $anyString . '</root>';

    // Log the updated XML string
    log_message('debug', 'Wrapped XML content: ' . $anyString);

    // Attempt to parse XML with error handling
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }

    // Register namespaces if necessary
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');

    // Query for Titles
    $genders = $xml->xpath('//diffgr:diffgram/DocumentElement/Gender');

    // Log the result of the XPath query
    log_message('debug', 'Genders found by XPath: ' . print_r($genders, true));

    // Convert to associative array
    $array = [];
    foreach ($genders as $gender) {
        $array[] = [
            'Value' => (string) $gender->Value,
            'Description' => (string) $gender->Description,
        ];
    }

    // Log the final array
    log_message('debug', 'Decoded Array: ' . print_r($array, true));

    return $array;
}

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

public function processMarital($anyString) {
    // Log the raw XML string
    log_message('debug', 'Raw XML content: ' . $anyString);

    // Wrap in <root> tags to make it valid XML
    $anyString = '<root>' . $anyString . '</root>';

    // Log the updated XML string
    log_message('debug', 'Wrapped XML content: ' . $anyString);

    // Attempt to parse XML with error handling
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }

    // Register namespaces if necessary
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');

    // Query for Titles
    $statuses = $xml->xpath('//diffgr:diffgram/DocumentElement/MaritalStatus');

    // Log the result of the XPath query
    log_message('debug', 'Marital Statuses found by XPath: ' . print_r($statuses, true));

    // Convert to associative array
    $array = [];
    foreach ($statuses as $status) {
        $array[] = [
            'Value' => (string) $status->Value,
            'Description' => (string) $status->Description,
        ];
    }

    // Log the final array
    log_message('debug', 'Decoded Array: ' . print_r($array, true));

    return $array;
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
