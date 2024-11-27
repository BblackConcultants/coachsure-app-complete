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
        $employment_statuses = $this->employmentStatuses();
        $id_types = $this->idTypes();
        $holder_relationships = $this->holderRelationships();
        $calendar_months = $this->calendarMonths();
        $year_range = $this->yearRange();

        log_message('debug', 'Data passed to view: ' . json_encode($year_range));die;
        
        // return view('wsdl_view', ['data' => $marital_statuses]);
    }
    public function yearRange()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client-> GetCalendarYearRangeItems();
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCalendarYearRangeItemsResult']['any'])) {
                    $anyString = $responseArray['GetCalendarYearRangeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processRange($anyString);
                    if (!empty($associativeArray)) {
                        return $associativeArray;
                    } else {
                        return ['error' => 'Processed array is empty.'];
                    }
                } else {
                    return ['error' => 'Expected keys not found in the response.'];
                }

        } catch (Exception $e) {
            return view('error_view', ['error' => $e->getMessage()]);
        }
    }
    public function calendarMonths()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->    GetCalendarMonthsItems([
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCalendarMonthsItemsResult']['any'])) {
                    $anyString = $responseArray['GetCalendarMonthsItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processCalendar($anyString);
                    if (!empty($associativeArray)) {
                        return $associativeArray;
                    } else {
                        return ['error' => 'Processed array is empty.'];
                    }
                } else {
                    return ['error' => 'Expected keys not found in the response.'];
                }

        } catch (Exception $e) {
            return view('error_view', ['error' => $e->getMessage()]);
        }
    }
    public function holderRelationships()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client-> GetPolicyHolderRelationItems([
                'p_LangID' => 'EN', // 
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetPolicyHolderRelationItemsResult']['any'])) {
                    $anyString = $responseArray['GetPolicyHolderRelationItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processRelationships($anyString);
                    if (!empty($associativeArray)) {
                        return $associativeArray;
                    } else {
                        return ['error' => 'Processed array is empty.'];
                    }
                } else {
                    return ['error' => 'Expected keys not found in the response.'];
                }

        } catch (Exception $e) {
            return view('error_view', ['error' => $e->getMessage()]);
        }
    }
    public function idTypes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetIdTypeItems([
                'p_LangID' => 'EN', // 
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetIDTypeItemsResult']['any'])) {
                    // Get the 'any' content
                    $anyString = $responseArray['GetIDTypeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processIDs($anyString);
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
    public function employmentStatuses()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client-> GetEmploymentStatusItems([
                'p_LangID' => 'EN', // 
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetEmploymentStatusItemsResult']['any'])) {
                    // Get the 'any' content
                    $anyString = $responseArray['GetEmploymentStatusItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processEmploymentStatus($anyString);
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
    private function processRange($anyString) {
        // log_message('debug', 'Raw XML content: ' . $anyString);die;
    $anyString = '<root>' . $anyString . '</root>';
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $year_range = $xml->xpath('//diffgr:diffgram/DocumentElement/YearRange');
    $array = [];
    foreach ($year_range as $range) {
        $array[] = [
            'Value' => (string) $range->Value,
            'Description' => (string) $range->Description,
        ];
    }
    return $array;
}

    private function processCalendar($anyString) {
        // log_message('debug', 'Raw XML content: ' . $anyString);die;
    $anyString = '<root>' . $anyString . '</root>';
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $calendar_months = $xml->xpath('//diffgr:diffgram/DocumentElement/CalendarMonths');
    $array = [];
    foreach ($calendar_months as $months) {
        $array[] = [
            'Value' => (string) $months->Value,
            'Description' => (string) $months->Description,
        ];
    }
    return $array;
}
    private function processRelationships($anyString) {
        // log_message('debug', 'Raw XML content: ' . $anyString);die;
    $anyString = '<root>' . $anyString . '</root>';
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $id_types = $xml->xpath('//diffgr:diffgram/DocumentElement/IDTypes');
    $array = [];
    foreach ($id_types as $type) {
        $array[] = [
            'Value' => (string) $type->Value,
            'Description' => (string) $type->Description,
        ];
    }
    return $array;
}
 private function processIDs($anyString) {
        // log_message('debug', 'Raw XML content: ' . $anyString);die;
    $anyString = '<root>' . $anyString . '</root>';
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $id_types = $xml->xpath('//diffgr:diffgram/DocumentElement/IDTypes');
    $array = [];
    foreach ($id_types as $type) {
        $array[] = [
            'Value' => (string) $type->Value,
            'Description' => (string) $type->Description,
        ];
    }
    return $array;
}
    private function processEmploymentStatus($anyString) {
        // log_message('debug', 'Raw XML content: ' . $anyString);
    $anyString = '<root>' . $anyString . '</root>';
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $emp_statuses = $xml->xpath('//diffgr:diffgram/DocumentElement/EmploymentStatus');
    $array = [];
    foreach ($emp_statuses as $status) {
        $array[] = [
            'Value' => (string) $status->Value,
            'Description' => (string) $status->Description,
        ];
    }
    return $array;
}
private function processGender($anyString) {
    $anyString = '<root>' . $anyString . '</root>';
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $genders = $xml->xpath('//diffgr:diffgram/DocumentElement/Gender');
    $array = [];
    foreach ($genders as $gender) {
        $array[] = [
            'Value' => (string) $gender->Value,
            'Description' => (string) $gender->Description,
        ];
    }
    return $array;
}

private function processAnyString(string $anyString): array
{
    $result = [];

    try {
        $xml = new \SimpleXMLElement($anyString);
        $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
        $xml->registerXPathNamespace('msdata', 'urn:schemas-microsoft-com:xml-msdata');
        $titles = $xml->xpath('//diffgr:diffgram/DocumentElement/Titles');
        foreach ($titles as $title) {
            $result[] = [
                'Value' => (string) $title->Value,
                'Description' => (string) $title->Description,
            ];
        }
    } catch (\Exception $e) {
        log_message('error', 'Failed to parse XML: ' . $e->getMessage());
        $result = ['error' => 'Invalid XML format'];
    }
    return $result;
}

public function processMarital($anyString) {
    $anyString = '<root>' . $anyString . '</root>';
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($anyString);
    if ($xml === false) {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            log_message('debug', 'XML Error: ' . $error->message);
        }
        return [];
    }
    $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $statuses = $xml->xpath('//diffgr:diffgram/DocumentElement/MaritalStatus');
    $array = [];
    foreach ($statuses as $status) {
        $array[] = [
            'Value' => (string) $status->Value,
            'Description' => (string) $status->Description,
        ];
    }
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
