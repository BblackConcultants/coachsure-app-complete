<?php

namespace App\Controllers;

use SoapClient;
use Exception;

class Premium extends BaseController
{
    protected $premium_sandbox_wsdl = 'https://api-uat.tihsa.co.za/QuickQuotes/APIService.asmx?WSDL';
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
        // $all_titles = $this->titleItems();
        // $marital_statuses = $this->maritalStatuses();
        // $all_genders = $this->genderItems();
        // $employment_statuses = $this->employmentStatuses();
        // $id_types = $this->idTypes();
        // $holder_relationships = $this->holderRelationships();
        // $calendar_months = $this->calendarMonths();
        // $year_range = $this->yearRange();
        // $calendar_days = $this->calendarDays();
        // $res_suburbs = $this->residentialSuburbs($hay='joh',$type='Residential');
        // $access_controls = $this->accessControlTypes();
        // $geyser_loss = $this->geyserLossItems();
        // $geyser_heat_sources = $this->geyserHeatSources();
        // $geyser_locations = $this->geyserLocations();
        // $vehicle_years = $this->vehicleYears();
        // $vehicle_makes = $this->vehicleMakes();
        // $vehicle_models = $this->vehicleModels();
        // $vehicle_types = $this->vehicleTypes();
        // $vehicle_descriptions = $this->vehicleDescriptions();
        // $vehicle_mnm = $this->vehicleMNM();
        // $sound_system_insured = $this->soundSystems();
        // $cover_types = $this->coverTypes();
        // $cover_type_with_saver = $this->coverTypesWithSaver();
        // $overnight_parking_facility = $this->overnightParkingFacility();
        // $vehicle_use = $this->vehicleUse();
        // $vehicle_hire_options = $this->vehicleHireOptions();
        // $driver_licence_type = $this->driverLicenceType();
        // $motor_ncb = $this->motorNCB();
        // $motor_insured_items = $this->motorInsuredItems();
        // $motor_tracker_options = $this->motorTrackerOptions();
        // $saver_accident_cover_options = $this->saverAccidentCoverOptions();
        // $saver_accident_cover_options_with_vehicle_values = $this->saverAccidentCoverOptionsWithVehicleValues();
        // $vehicle_colours = $this->vehicleColors();
        // $vehicle_paint_types = $this->vehiclePaintTypes();

        // Motorbike Specific Methods
        // $motorbike_registered_years = $this->motorbikeRegisteredYears();
        // $motorbike_makes = $this->motorbikemakes();
        // $motorbike_descriptions = $this->motorbikeDescriptions();
        // // caravan specific methods
        // $caravan_registered_years = $this->caravanRegisteredYears();
        // $caravan_makes = $this->caravanMakes();

        // trailer specific methods
        // $trailer_registered_years = $this->trailerRegisteredYears();
        // $trailer_makes = $this->trailerMakes();

        // Home stuff
        // $building_structures = $this->buildingStructures();
        // $area_types = $this->areaTypes();
        // $roof_types = $this->roofTypes();
        // 

        log_message('debug', 'Data passed to view: ' . json_encode($vehicle_types));die;
        
        // return view('wsdl_view', ['data' => $marital_statuses]);
    }
    public function calculateMotorPremium(){
        try {
            $client = new SoapClient($this->premium_sandbox_wsdl); 
            $response = $client->GetNewSessionID();
            $sessionID = $response->GetNewSessionIDResult; 
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        // calculate the motor premium
        try {
            $motorPremiumRequest = [
                'SessionID' => $sessionID, 
                'ReferenceNo' => '10350479', 
                'VehicleYear' => '2011', 
                'VehicleMNMCode' => '02090460', 
                'VehicleKey' => '02090460', 
                'InsuredValue' => 0, 
                'VehicleSeqNo' => 0, 
            ];

            // Call the CalculatePremium SOAP method
            $response = $soapClient->CalculatePremium($premiumRequest);

            // Extract the premium from the response
            $premium = $response->CalculatePremiumResult; // Adjust based on the actual response structure
            echo "Premium Amount: " . $premium . PHP_EOL;
        } catch (Exception $e) {
            die("Error calculating premium: " . $e->getMessage());
        }
        // 

    }
    public function methodList (){
        $soapClient = new SoapClient($this->wsdl);
        $functions = $soapClient->__getFunctions();
        echo "<pre>", print_r($functions);
    }
    // home stuff
    public function roofTypes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GeRoofTypeItems([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GeRoofTypeItemsResult']['any'])) {
                    $anyString = $responseArray['GeRoofTypeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processRoofTypes($anyString);
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
    public function areaTypes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetAreaTypeItems([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetAreaTypeItemsResult']['any'])) {
                    $anyString = $responseArray['GetAreaTypeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processAreaTypes($anyString);
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
    public function buildingStructures()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetBuildingStructureItems([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetBuildingStructureItemsResult']['any'])) {
                    $anyString = $responseArray['GetBuildingStructureItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processBuildingStructures($anyString);
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
    // trailers
    public function trailerMakes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetTrailerMakes([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetTrailerMakesResult']['any'])) {
                    $anyString = $responseArray['GetTrailerMakesResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processTrailerMakes($anyString);
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
    public function trailerRegisteredYears()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetTrailerRegisteredYears();
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetTrailerRegisteredYearsResult']['any'])) {
                    $anyString = $responseArray['GetTrailerRegisteredYearsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processTrailerRegisteredYears($anyString);
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
    // caravans
    public function caravanMakes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetCaravanMakes(
                [
                    "p_LangID" => 'EN',
                    "p_WordCase" => 'PROPERCASE',
                ]
            );
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCaravanMakesResult']['any'])) {
                    $anyString = $responseArray['GetCaravanMakesResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processCaravanMakes($anyString);
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
    public function caravanRegisteredYears()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetCaravanRegisteredYears();
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCaravanRegisteredYearsResult']['any'])) {
                    $anyString = $responseArray['GetCaravanRegisteredYearsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processCaravanRegisteredYears($anyString);
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
    // motorbikes
    public function motorbikeDescriptions($make = "AP")
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetMotorbikeDescriptions([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
                "p_MotorbikeMake" => $make,
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetMotorbikeDescriptionsResult']['any'])) {
                    $anyString = $responseArray['GetMotorbikeDescriptionsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processMotorbikeDescriptions($anyString);
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
    public function motorbikemakes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetMotorbikeMakes([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetMotorbikeMakesResult']['any'])) {
                    $anyString = $responseArray['GetMotorbikeMakesResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processMotorbikeMakes($anyString);
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
    public function motorbikeRegisteredYears()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetMotorbikeRegisteredYears();
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetMotorbikeRegisteredYearsResult']['any'])) {
                    $anyString = $responseArray['GetMotorbikeRegisteredYearsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processMotorbikeRegisteredYears($anyString);
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
    // 
    public function vehiclePaintTypes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehiclePaintTypes([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehiclePaintTypesResult']['any'])) {
                    $anyString = $responseArray['GetVehiclePaintTypesResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehiclePaintTypes($anyString);
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
    public function vehicleColors()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehicleColours([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleColoursResult']['any'])) {
                    $anyString = $responseArray['GetVehicleColoursResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleColors($anyString);
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
    public function saverAccidentCoverOptionsWithVehicleValues($vehicle_value = '203160')
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetSaverAccidentCoverOptionsWithVehicleValue([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
                "p_VehicleValue" => $vehicle_value,
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetSaverAccidentCoverOptionsWithVehicleValueResult']['any'])) {
                    $anyString = $responseArray['GetSaverAccidentCoverOptionsWithVehicleValueResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processSaverAccidentCoverOptionsWithVehicleValue($anyString);
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
    public function saverAccidentCoverOptions()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetSaverAccidentCoverOptions([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetSaverAccidentCoverOptionsResult']['any'])) {
                    $anyString = $responseArray['GetSaverAccidentCoverOptionsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processSaverAccidentCoverOptions($anyString);
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
    public function motorTrackerOptions()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetMotorTrackerOptions([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetMotorTrackerOptionsResult']['any'])) {
                    $anyString = $responseArray['GetMotorTrackerOptionsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processMotorTrackerOptions($anyString);
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
    public function motorInsuredItems()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetMotorInsuredtems([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetMotorInsuredtemsResult']['any'])) {
                    $anyString = $responseArray['GetMotorInsuredtemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processMotorInsuredItems($anyString);
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
    public function motorNCB()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetMotorNCBItems([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetMotorNCBItemsResult']['any'])) {
                    $anyString = $responseArray['GetMotorNCBItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processMotorNCB($anyString);
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
    public function driverLicenceType()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetDriverLicenceType([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetDriverLicenceTypeResult']['any'])) {
                    $anyString = $responseArray['GetDriverLicenceTypeResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processDriverLicenceType($anyString);
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
    public function vehicleHireOptions()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehicleCarHireOptions([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleCarHireOptionsResult']['any'])) {
                    $anyString = $responseArray['GetVehicleCarHireOptionsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleHireOptions($anyString);
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
    public function vehicleTypes($vehicle_key = 7128089)
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehicleType([
                "p_VehicleKey" => 'EN',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleUseItemsResult']['any'])) {
                    $anyString = $responseArray['GetVehicleUseItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleUse($anyString);
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
    public function vehicleUse()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehicleUseItems([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleUseItemsResult']['any'])) {
                    $anyString = $responseArray['GetVehicleUseItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleUse($anyString);
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
    public function overnightParkingFacility()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetOvernightParkingFacility([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetOvernightParkingFacilityResult']['any'])) {
                    $anyString = $responseArray['GetOvernightParkingFacilityResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processOvernightParkingFacility($anyString);
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
    public function coverTypesWithSaver()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetCoverTypeItemsWithSaver([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCoverTypeItemsWithSaverResult']['any'])) {
                    $anyString = $responseArray['GetCoverTypeItemsWithSaverResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processCoverTypeWithSaver($anyString);
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
    public function coverTypes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetCoverTypeItems([
                "p_LangID" => 'EN',
                "p_WordCase" => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCoverTypeItemsResult']['any'])) {
                    $anyString = $responseArray['GetCoverTypeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processCoverType($anyString);
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
    public function soundSystems()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetSoundSystemInsuredValues();
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetSoundSystemInsuredValuesResult']['any'])) {
                    $anyString = $responseArray['GetSoundSystemInsuredValuesResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processSoundSystem($anyString);
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
    public function vehicleMNM($key = 7128089)
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehicleMNMCode([
                'p_VehicleKey' => $key,
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleMNMCodeResult']['any'])) {
                    $anyString = $responseArray['GetVehicleMNMCodeResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleMNM($anyString);
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
    public function vehicleDescriptions($year = 2011, $make = 01, $model = 'D8')
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehiclDescriptions([
                'p_VehicleYear' => $year,
                'P_VehicleMake' => $make,
                'P_VehicleModel' => $model,
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehiclDescriptionsResult']['any'])) {
                    $anyString = $responseArray['GetVehiclDescriptionsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleDescs($anyString);
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
    public function vehicleModels($year = 2011, $make = 01)
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetVehicleModels([
                'p_VehicleYear' => $year,
                'P_VehicleMake' => $make,
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleModelsResult']['any'])) {
                    $anyString = $responseArray['GetVehicleModelsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleModels($anyString);
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
    public function vehicleMakes($year = 2011)
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->  GetVehicleMakes([
                'p_VehicleYear' => $year,
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleMakesResult']['any'])) {
                    $anyString = $responseArray['GetVehicleMakesResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleMakes($anyString);
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
    public function vehicleYears()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client-> GetVehicleYears();
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetVehicleYearsResult']['any'])) {
                    $anyString = $responseArray['GetVehicleYearsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processVehicleYears($anyString);
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
    public function geyserLocations()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->  GetGeyserLocations([
                'p_LangID' => 'EN',
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetGeyserLocationsResult']['any'])) {
                    $anyString = $responseArray['GetGeyserLocationsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processGeyserLocation($anyString);
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
    public function geyserHeatSources()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->  GetGeyserHeatSources([
                'p_LangID' => 'EN',
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetGeyserHeatSourcesResult']['any'])) {
                    $anyString = $responseArray['GetGeyserHeatSourcesResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processHeatSource($anyString);
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
    public function geyserLossItems()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->  GetGeyserLossItems([
                'p_LangID' => 'EN',
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetGeyserLossItemsResult']['any'])) {
                    $anyString = $responseArray['GetGeyserLossItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processGeyserLoss($anyString);
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
    public function accessControlTypes()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->GetAccessControlTypeItems([
                'p_LangID' => 'EN',
                'p_WordCase' => 'PROPERCASE',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetAccessControlTypeItemsResult']['any'])) {
                    $anyString = $responseArray['GetAccessControlTypeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processAccessControl($anyString);
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
    public function residentialSuburbs($hay, $address_type)
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->    GetSuburbs([
                'LanguageID' => 'EN',
                'p_WordCase' => 'PROPERCASE',
                'p_SuburbSearchText' => $hay,
                'p_AddressType' => 'Postal address',
            ]);
            $responseArray = json_decode(json_encode($response), true);
            echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCalendarDayRangeItemsResult']['any'])) {
                    $anyString = $responseArray['GetCalendarDayRangeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processDays($anyString);
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
    public function calendarDays()
    {
        try {
            $client = new SoapClient($this->wsdl, $this->options);
            $response = $client->    GetCalendarDayRangeItems();
            $responseArray = json_decode(json_encode($response), true);
            // echo "<pre>", print_r($responseArray); die();
            if (isset($responseArray['GetCalendarDayRangeItemsResult']['any'])) {
                    $anyString = $responseArray['GetCalendarDayRangeItemsResult']['any'];
                    $anyString = '<root>' . $anyString . '</root>';
                    $associativeArray = $this->processDays($anyString);
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
    // motorbikes
    
    private function processRoofTypes($anyString) {
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
    $roof_types = $xml->xpath('//diffgr:diffgram/DocumentElement/RoofTypes');
    $array = [];
    foreach ($roof_types as $type) {
        $array[] = [
            'Value' => (string) $type->Value,
            'Description' => (string) $type->Description,
        ];
    }
    return $array;
}
    private function processAreaTypes($anyString) {
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
    $area_types = $xml->xpath('//diffgr:diffgram/DocumentElement/AreaTypes');
    $array = [];
    foreach ($area_types as $type) {
        $array[] = [
            'Value' => (string) $type->Value,
            'Description' => (string) $type->Description,
        ];
    }
    return $array;
}
    private function processBuildingStructures($anyString) {
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
    $building_structures = $xml->xpath('//diffgr:diffgram/DocumentElement/BuildingStructure');
    $array = [];
    foreach ($building_structures as $structure) {
        $array[] = [
            'Value' => (string) $structure->Value,
            'Description' => (string) $structure->Description,
        ];
    }
    return $array;
}
    private function processTrailerMakes($anyString) {
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
    $trailer_makes = $xml->xpath('//diffgr:diffgram/DocumentElement/TrailerMakes');
    $array = [];
    foreach ($trailer_makes as $make) {
        $array[] = [
            'Value' => (string) $make->Value,
            'Description' => (string) $make->Description,
        ];
    }
    return $array;
}
    private function processTrailerRegisteredYears($anyString) {
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
    $trailer_years = $xml->xpath('//diffgr:diffgram/DocumentElement/TrailerYears');
    $array = [];
    foreach ($trailer_years as $year) {
        $array[] = [
            'Value' => (string) $year->Value,
            'Description' => (string) $year->Description,
        ];
    }
    return $array;
}
    private function processCaravanMakes($anyString) {
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
    $caravan_makes = $xml->xpath('//diffgr:diffgram/DocumentElement/CaravanMakes');
    $array = [];
    foreach ($caravan_makes as $make) {
        $array[] = [
            'Value' => (string) $make->Value,
            'Description' => (string) $make->Description,
        ];
    }
    return $array;
}
    private function processCaravanRegisteredYears($anyString) {
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
    $caravan_years = $xml->xpath('//diffgr:diffgram/DocumentElement/CaravanYears');
    $array = [];
    foreach ($caravan_years as $year) {
        $array[] = [
            'Value' => (string) $year->Value,
            'Description' => (string) $year->Description,
        ];
    }
    return $array;
}
    private function processMotorbikeDescriptions($anyString) {
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
    $motorbike_makes = $xml->xpath('//diffgr:diffgram/DocumentElement/MotorbikeMakes');
    $array = [];
    foreach ($motorbike_makes as $make) {
        $array[] = [
            'Value' => (string) $make->Value,
            'Description' => (string) $make->Description,
        ];
    }
    return $array;
}
    private function processMotorbikeMakes($anyString) {
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
    $motorbike_makes = $xml->xpath('//diffgr:diffgram/DocumentElement/MotorbikeMakes');
    $array = [];
    foreach ($motorbike_makes as $make) {
        $array[] = [
            'Value' => (string) $make->Value,
            'Description' => (string) $make->Description,
        ];
    }
    return $array;
}
    private function processMotorbikeRegisteredYears($anyString) {
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
    $motorbike_years = $xml->xpath('//diffgr:diffgram/DocumentElement/MotorbikeYears');
    $array = [];
    foreach ($motorbike_years as $year) {
        $array[] = [
            'Value' => (string) $year->Value,
            'Description' => (string) $year->Description,
        ];
    }
    return $array;
}
    // 
    private function processVehiclePaintTypes($anyString) {
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
    $paint_types = $xml->xpath('//diffgr:diffgram/DocumentElement/VehiclePaintTypes');
    $array = [];
    foreach ($paint_types as $paint) {
        $array[] = [
            'Value' => (string) $paint->Value,
            'Description' => (string) $paint->Description,
        ];
    }
    return $array;
}
    private function processVehicleColors($anyString) {
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
    $vehicle_colors = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleColours');
    $array = [];
    foreach ($vehicle_colors as $color) {
        $array[] = [
            'Value' => (string) $color->Value,
            'Description' => (string) $color->Description,
        ];
    }
    return $array;
}
    private function processSaverAccidentCoverOptionsWithVehicleValue($anyString) {
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
    $cover_options = $xml->xpath('//diffgr:diffgram/DocumentElement/AccidentCoverOptions');
    $array = [];
    foreach ($cover_options as $option) {
        $array[] = [
            'Value' => (string) $option->Value,
            'Description' => (string) $option->Description,
        ];
    }
    return $array;
}
    private function processSaverAccidentCoverOptions($anyString) {
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
    $cover_options = $xml->xpath('//diffgr:diffgram/DocumentElement/AccidentCoverOptions');
    $array = [];
    foreach ($cover_options as $option) {
        $array[] = [
            'Value' => (string) $option->Value,
            'Description' => (string) $option->Description,
        ];
    }
    return $array;
}
    private function processMotorTrackerOptions($anyString) {
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
    $device_types = $xml->xpath('//diffgr:diffgram/DocumentElement/AntiTheftDeviceTypes');
    $array = [];
    foreach ($device_types as $device) {
        $array[] = [
            'Value' => (string) $device->Value,
            'Description' => (string) $device->Description,
        ];
    }
    return $array;
}
    private function processMotorInsuredItems($anyString) {
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
    $insured_values = $xml->xpath('//diffgr:diffgram/DocumentElement/InsuredValues');
    $array = [];
    foreach ($insured_values as $value) {
        $array[] = [
            'Value' => (string) $value->Value,
            'Description' => (string) $value->Description,
        ];
    }
    return $array;
}
    private function processMotorNCB($anyString) {
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
    $motor_ncb = $xml->xpath('//diffgr:diffgram/DocumentElement/MotorNCB');
    $array = [];
    foreach ($motor_ncb as $ncb) {
        $array[] = [
            'Value' => (string) $ncb->Value,
            'Description' => (string) $ncb->Description,
        ];
    }
    return $array;
}
    private function processDriverLicenceType($anyString) {
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
    $licence_types = $xml->xpath('//diffgr:diffgram/DocumentElement/DriverLicence');
    $array = [];
    foreach ($licence_types as $licence) {
        $array[] = [
            'Value' => (string) $licence->Value,
            'Description' => (string) $licence->Description,
        ];
    }
    return $array;
}
    private function processVehicleHireOptions($anyString) {
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
    $vehicle_hire = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleCarHire');
    $array = [];
    foreach ($vehicle_hire as $hire) {
        $array[] = [
            'Value' => (string) $hire->Value,
            'Description' => (string) $hire->Description,
        ];
    }
    return $array;
}
    private function processVehicleUse($anyString) {
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
    $vehicle_uses = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleUse');
    $array = [];
    foreach ($vehicle_uses as $use) {
        $array[] = [
            'Value' => (string) $use->Value,
            'Description' => (string) $use->Description,
        ];
    }
    return $array;
}
    private function processOvernightParkingFacility($anyString) {
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
    $parking_facilities = $xml->xpath('//diffgr:diffgram/DocumentElement/ParkingFacilities');
    $array = [];
    foreach ($parking_facilities as $facility) {
        $array[] = [
            'Value' => (string) $facility->Value,
            'Description' => (string) $facility->Description,
        ];
    }
    return $array;
}
    private function processCoverTypeWithSaver($anyString) {
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
    $cover_types = $xml->xpath('//diffgr:diffgram/DocumentElement/CoverTypes');
    $array = [];
    foreach ($cover_types as $cover) {
        $array[] = [
            'Value' => (string) $cover->Value,
            'Description' => (string) $cover->Description,
        ];
    }
    return $array;
}
    private function processCoverType($anyString) {
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
    $cover_types = $xml->xpath('//diffgr:diffgram/DocumentElement/CoverTypes');
    $array = [];
    foreach ($cover_types as $cover) {
        $array[] = [
            'Value' => (string) $cover->Value,
            'Description' => (string) $cover->Description,
        ];
    }
    return $array;
}
    private function processSoundSystem($anyString) {
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
    $sound_system = $xml->xpath('//diffgr:diffgram/DocumentElement/SoundSystem');
    $array = [];
    foreach ($sound_system as $sys) {
        $array[] = [
            'Value' => (string) $sys->Value,
            'Description' => (string) $sys->Description,
        ];
    }
    return $array;
}
    private function processVehicleMNM($anyString) {
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
    $vehicle_mnm = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleMNMCode');
    $array = [];
    foreach ($vehicle_mnm as $mnm) {
        $array[] = [
            'Value' => (string) $mnm->Value,
            'Description' => (string) $mnm->Description,
        ];
    }
    return $array;
}
    private function processVehicleDescs($anyString) {
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
    $vehicle_descs = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleDescriptions');
    $array = [];
    foreach ($vehicle_descs as $desc) {
        $array[] = [
            'Value' => (string) $desc->Value,
            'Description' => (string) $desc->Description,
        ];
    }
    return $array;
}
    private function processVehicleModels($anyString) {
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
    $vehicle_models = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleModels');
    $array = [];
    foreach ($vehicle_models as $model) {
        $array[] = [
            'Value' => (string) $model->Value,
            'Description' => (string) $model->Description,
        ];
    }
    return $array;
}
    private function processVehicleMakes($anyString) {
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
    $vehicle_makes = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleMakes');
    $array = [];
    foreach ($vehicle_makes as $make) {
        $array[] = [
            'Value' => (string) $make->Value,
            'Description' => (string) $make->Description,
        ];
    }
    return $array;
}
    private function processVehicleYears($anyString) {
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
    $vehicle_years = $xml->xpath('//diffgr:diffgram/DocumentElement/VehicleYears');
    $array = [];
    foreach ($vehicle_years as $year) {
        $array[] = [
            'Value' => (string) $year->Value,
            'Description' => (string) $year->Description,
        ];
    }
    return $array;
}
    private function processGeyserLocation($anyString) {
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
    $geyser_locations = $xml->xpath('//diffgr:diffgram/DocumentElement/GetGeyzerLocation');
    $array = [];
    foreach ($geyser_locations as $location) {
        $array[] = [
            'Value' => (string) $location->Value,
            'Description' => (string) $location->Description,
        ];
    }
    return $array;
}
    private function processHeatSource($anyString) {
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
    $heating_source = $xml->xpath('//diffgr:diffgram/DocumentElement/GeyserHeatingSource');
    $array = [];
    foreach ($heating_source as $source) {
        $array[] = [
            'Value' => (string) $source->Value,
            'Description' => (string) $source->Description,
        ];
    }
    return $array;
}
    private function processGeyserLoss($anyString) {
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
    $geyser_loss = $xml->xpath('//diffgr:diffgram/DocumentElement/GeyserLoss');
    $array = [];
    foreach ($geyser_loss as $loss) {
        $array[] = [
            'Value' => (string) $loss->Value,
            'Description' => (string) $loss->Description,
        ];
    }
    return $array;
}
    private function processAccessControl($anyString) {
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
    $access_controls = $xml->xpath('//diffgr:diffgram/DocumentElement/AccessControl');
    $array = [];
    foreach ($access_controls as $control) {
        $array[] = [
            'Value' => (string) $control->Value,
            'Description' => (string) $control->Description,
        ];
    }
    return $array;
}
     private function processDays($anyString) {
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
    $day_range = $xml->xpath('//diffgr:diffgram/DocumentElement/DayRange');
    $array = [];
    foreach ($day_range as $range) {
        $array[] = [
            'Value' => (string) $range->Value,
            'Description' => (string) $range->Description,
        ];
    }
    return $array;
}
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
