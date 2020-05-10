<?php


// use WHMCS\Module\Addon\Rewards\functions;
namespace WHMCS\Module\Addon\Rewards\Client;
// include "../functions.php";
use WHMCS\Module\Addon\Rewards\functions;
/**
 * Sample Client Area Controller
 */
class Constants {

  const WEBHOSTING = 1.5;
  const REDIRECTS = 1.5;
  const DOMAIN = 1.0;
  const SEO = 1.0;
  const ADWORDS = 1.0;
  const RETARGETING = 1.0;
  const REMINDERS = 2.0;
  const ONLINESCHEDULING = 2.0;
  const VOIP = 3.0;
  const WEBDEVELOPMENT = 1.0;
  const EMAILS = 1.0;
  const HHFORMS = 1.0;


  const BENEFITS = array(
    "diamond" => array(
      "Priority Email Address",
      "Free Access to Rooster Grin Domains",
      "Template Website - $100 (every 3 years)",
      "Custom Website - $500 (every 3 years)",
      "Free VOIP Phone Hardware",
      "50 Additional Keywords for SEO Free",
      "Unlimited AdWords budget, no additional charge"
    ),
    "platinum" => array(
      "Priority Email Address",
      "Free Access to Rooster Grin Domains",
      "Template Website - $200 (every 3 years)",
      "Custom Website - $1,250 (every 3 years)",
      "Free VOIP Phone Hardware",
      "25 Additional Keywords for SEO Free",
      "Additional $1,000 of AdWords budget, no additional charge"
    ),
    "gold" => array(
      "Priority Email Address",
      "Free Access to Rooster Grin Domains",
      "Template Website - $400 (every 3 years)",
      "Custom Website - $2,000 (every 3 years)",
      "Free VOIP Phone Hardware",
      "10 Additional Keywords for SEO Free"
    ),
    "silver" => array(
      "Priority Email Address",
      "Free Access to Rooster Grin Domains",
      "5 Additional Keywords for SEO Free"
    )
  );

  const POINTS_REQUIRED = array(
    "diamond" => array(
      "value" => 100000,
      "formatted" => "100,000"
    ),
    "platinum" => array(
      "value" => 50000,
      "formatted" => "50,000"
    ),
    "gold" => array(
      "value" => 30000,
      "formatted" => "30,000"
    ),
    "silver" => array(
      "value" => 10000,
      "formatted" => "10,000"
    ),
  );

}



class Controller {

    /**
     * Index action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return array
     */
    public function index($vars) {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables



        // Get module configuration parameters
        $configTextField = $vars['Text Field Name'];
        $configPasswordField = $vars['Password Field Name'];
        $configCheckboxField = $vars['Checkbox Field Name'];
        $configDropdownField = $vars['Dropdown Field Name'];
        $configRadioField = $vars['Radio Field Name'];
        $configTextareaField = $vars['Textarea Field Name'];

        return array(
            'pagetitle' => 'Rooster Grin Rewards Program',
            'breadcrumb' => array(
                'index.php?m=rewards' => 'Rewards Program',
            ),
            'templatefile' => 'publicpage',
            'requirelogin' => false, // Set true to restrict access to authenticated client users
            'forcessl' => false, // Deprecated as of Version 7.0. Requests will always use SSL if available.
            'vars' => array(
                'modulelink' => $modulelink,
                'configTextField' => $configTextField,
                'customVariable' => 'your own content goes here',
            ),
        );
    }


    /**
     * Secret action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return array
     */
    public function secret($vars)
    {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. addonmodules.php?module=addonmodule
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables




        return self::main(null);


    }

    public function summarypageoutput($vars) {
      if (isset($vars['userid'])) {
        $id = $vars['userid'];
        return self::main($id);
      } else {
        return 'NO USER ID';
      }
    }

    function main($id) {

      $constants = new Constants();
      $f = new functions();

      $products_command = 'GetClientsProducts';
      $invoices_command = 'GetInvoices';
      $invoice_command = 'GetInvoice';
      $client_command = 'GetClientsDetails';

      $request_clientID = "";
      if (!isset($id)) {
        $request_clientID = $_SESSION['uid'];
      } else {
        $request_clientID = $id;
      }
      $adminUsername = 'Cooper';

      $postData_products = array(
        'clientid' => $request_clientID,
        'stats' => true,
      );


      $postData_invoices = array(
        'userid' => $request_clientID,
        'orderby' => 'invoiceid',
        'limitnum' => 999
      );

      $postData_clients = array(
          'clientid' => $request_clientID,
          'stats' => true,
      );





      $results_invoices = localAPI($invoices_command, $postData_invoices, $adminUsername);
      $results_clients = localAPI($client_command, $postData_clients, $adminUsername);


      $clientInvoices = [];
      $invoices = $results_invoices["invoices"]["invoice"];

      $clientData = [];
      $clientData["clientid"] = $request_clientID;
      $clientData["fullname"] = $results_clients["client"]["fullname"];
      $clientData["status"] = $results_clients["client"]["status"];
      $groupid = $results_clients["client"]["groupid"];

      $clientData["companyName"] = $invoices[0]["companyname"];
      $clientData["totalPoints"] = 0;
      $clientData["amountSpent"] = 0;
      $minDate = date('Y-m-d');
      $datesTest = [];
      $clientData["signUpDate"] = "";
      $clientData["yearsAsClient"] = 0;
      $clientData["tier"] = "unknown";
      $clientData["groupid"] = 0;
      $clientData["products"] = [];
      $productPoints = [];


      $firstname = $results_clients["client"]["firstname"];



      for ($i=0; $i < count($invoices); $i++) {
        $currentInvoice = $invoices[$i];
        $invoice_id = $currentInvoice["id"];

        $postData_invoice = array(
          'invoiceid' => $invoice_id
        );

        $m = strtotime($currentInvoice["date"]); //->format('Y-m-d');
        $n = date('Y-m-d', $m);
        $minDate = min($n, $minDate);
        $returned_invoice = localAPI($invoice_command, $postData_invoice, $adminUsername);
        $invoice_items = $returned_invoice["items"]["item"];

        for ($j=0; $j < count($invoice_items); $j++) {

          $currentItem = $invoice_items[$j];
          $productName = $currentItem["description"];
          $amount = floatval($currentItem["amount"]);
          $multiplier = $f->getMultiplier($productName);
          $cleanedName = $f->cleanProductName($productName);
          $points = intval(ceil($amount*$multiplier));

          $clientData["totalPoints"] += $points;
          $clientData["amountSpent"] += $amount;

          if (!isset($clientData["products"][$cleanedName])) {
            $clientData["products"][$cleanedName] = array(
              "productName" => $cleanedName,
              "amountSpent" => $amount,
              "points" => $points,
              "multiplier" => $multiplier
            );
          } else {
            $clientData["products"][$cleanedName]["amountSpent"] += $amount;
            $clientData["products"][$cleanedName]["points"] += $points;
          }

        }
      }

      $clientData["signUpDate"] = $minDate;
      $clientData["yearsAsClient"] = $f->getYearsAsClient($minDate);
      $clientData["tier"] = $f->getClientTier($clientData);
      if($clientData["status"] != "Active") {
        $clientData["groupid"] = 7;
      } else {
        $clientData["groupid"] = $f->getGroupID($clientData["tier"]);
      }
      $clientBenefits = $constants::BENEFITS[$clientData["tier"]];

      // $clientData["tier"] = "gold";

      $years = number_format($clientData["yearsAsClient"], 1, '.', '');
      $points = number_format($clientData["totalPoints"]);
      // usort($clientData["products"], "sortByPoints");

      foreach ($clientData["products"] as $key => $value) {
        $productPoints[$key] = $value["points"];
      }
      arsort($productPoints);
      foreach ($productPoints as $key => $value) {
        $productPoints[$key] = number_format($value);
      }

      $groupUpdate = $f->updateGroupID($clientData, $groupid);
      $clientData["groupUpdate"] = $groupUpdate;

      if (!isset($id)) {
        return array(
            'pagetitle' => 'Rooster Grin Rewards Program',
            'breadcrumb' => array(
                // 'index.php?m=rewards' => 'Rewards Program',
                'index.php?m=rewards&action=secret' => 'Rewards Program'
            ),
            'templatefile' => 'secretpage',
            'requirelogin' => true, // Set true to restrict access to authenticated client users
            'forcessl' => false, // Deprecated as of Version 7.0. Requests will always use SSL if available.
            'vars' => array(

                'clientData' => $clientData,
                'clientBenefits' => $clientBenefits,
                'productPoints' => $productPoints,
                'firstname' => $firstname,
                'years' => $years,
                'status' => $clientData['status'],
                'points' => $points,
                'benefits' => $constants::BENEFITS,
                'points_required' => $constants::POINTS_REQUIRED,
            ),
        );
      } else {
        return $clientData;
      }



    }
}
