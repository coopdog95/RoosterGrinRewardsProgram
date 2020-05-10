<?php
/**
 * WHMCS SDK Sample Addon Module Hooks File
 *
 * Hooks allow you to tie into events that occur within the WHMCS application.
 *
 * This allows you to execute your own code in addition to, or sometimes even
 * instead of that which WHMCS executes by default.
 *
 * @see https://developers.whmcs.com/hooks/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

/**
 * Register a hook with WHMCS.
 *
 * This sample demonstrates triggering a service call when a change is made to
 * a client profile within WHMCS.
 *
 * For more information, please refer to https://developers.whmcs.com/hooks/
 *
 * add_hook(string $hookPointName, int $priority, string|array|Closure $function)
 */
use WHMCS\View\Menu\Item;
use WHMCS\Module\Addon\Rewards\Client\Controller;



add_hook('ClientEdit', 1, function(array $params) {
    try {
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
        // echo 'Args: ' . $params;
    } catch (Exception $e) {
        // Consider logging or reporting the error.
        echo 'Error: (ClientEdit Hook): ' . $e;
    }
});

/*
add_hook("AdminAreaFooterOutput", 14, function($vars){


  $c = new Controller();

  $num_clientsUpdated = 0;
  $num_diamond = 0;
  $num_platinum = 0;
  $num_gold = 0;
  $num_silver = 0;
  $num_notier = 0;
  $num_notactive = 0;
  $num_success = 0;
  $num_failed = 0;

  $o = "IDs:";

  $getClientsCommand = 'GetClients';
  $updateClientCommand = 'UpdateClient';
  $adminUsername = 'Cooper';
  $postData = array(
    'limitnum' => 500,
    'sorting' => 'ASC'
    // 'limitstart' => 600
  );
  $results_clients = localAPI($getClientsCommand, $postData, $adminUsername);
  $clients = $results_clients['clients']['client'];
  $t = "Types: ";
  for ($i = 0; $i < count($clients); $i++) {

    $client = $clients[$i];
    if(!isset($client['groupid']) || $client['groupid'] == 6) {

      $id = $client['id'];

      $data = $c->main($id);

      $tier = $data['tier'];
      $status = $data['status'];
      $groupid = 0;

      if($status != 'Active') {
        $groupid = 7;
        $num_notactive += 1;
      }
      else if($tier == 'unknown') {
        $groupid = 6;
        $num_notier += 1;

      } else {
        switch ($tier) {

          case "diamond":
            $groupid = 2;
            $num_diamond += 1;
            break;

          case "platinum":
            $groupid = 3;
            $num_platinum += 1;
            break;

          case "gold":
            $groupid = 4;
            $num_gold += 1;
            break;

          case "silver":
            $groupid = 5;
            $num_silver += 1;
            break;

          default:
            $groupid = 6;
            $num_notier += 1;
            break;
        }
      }
      $postUpdateData = array(
        'clientid' => $id,
        'groupid' => $groupid
      );

      $results = localAPI($updateClientCommand, $postUpdateData, $adminUsername);
      if($results['result'] == 'success') {
        $num_success += 1;
        // $o .= $results['groupid'] . ', ';
      } else {
        $num_failed += 1;
      }

    }
  }
  $outputString = "<p>Updates<br>";
  $outputString .= "Diamond: " . $num_diamond . "<br>";
  $outputString .= "platinum: " . $num_platinum . "<br>";
  $outputString .= "gold: " . $num_gold . "<br>";
  $outputString .= "silver: " . $num_silver . "<br>";
  $outputString .= "notier: " . $num_notier . "<br>";
  $outputString .= "notactive: " . $num_notactive . "<br></p>";
  $outputString .= "<p>RESULTS<br>Success: " . $num_success . "<br>Failed: " . $num_failed . "</p>";
  // $outputString .= "<p>" . $t . "</p>";

  return $outputString;


});
*/



/*
  GROUP IDs
  ---------
  diamond - 2
  platinum - 3
  gold - 4
  silver - 5
  notier - 6
  notactive - 7
*/




add_hook('AdminAreaClientSummaryPage', 2, function($vars) {

  // if (substr($vars['filename'], 0, 7) == 'clients' && $vars['filename'] != 'clients'){ //only include on clients* pages (like clientssummary)

    $userid = (empty($_REQUEST['userid']))? 0:$_REQUEST['userid'];
    $refid = (empty($_REQUEST['id']))? 0:$_REQUEST['id'];

    $c = new Controller();
    $data = $c->main($userid);

    if (isset($data)) {

      $output = "";
      $points = $data['totalPoints'];
      $detailsLink = "https://roostergrin.com/billing/index.php?m=rewards&action=secret";

      $points_formatted = number_format($points);
      $tier = $data['tier'];
      $hasValidTier = (($tier != 'unknown' && isset($tier)) ? true : false);
      $status = $data['status'];
      $isActive = ($status == 'Active');


      $styles = "<style>
        .tier {
          text-transform: capitalize;
          padding: 1rem 3rem;
          border: 1.5px solid black;
          border-radius: 8px;
          background-position: center;
          background-size: 200px;
          background-repeat: no-repeat;
          font-weight: 600;
        }
        .tier-unknown {
          background: black;
          color: white;
        }
        .tier-silver {
          background-image: url('https://roostergrin.com/billing/assets/img/rewards_program/silver-bg.jpg');
        }
        .tier-gold {
          background-image: url('https://roostergrin.com/billing/assets/img/rewards_program/gold-bg.jpg');
        }
        .tier-platinum {
          background-image: url('https://roostergrin.com/billing/assets/img/rewards_program/platinum-bg.jpg');
        }
        .tier-diamond {
          background-image: url('https://roostergrin.com/billing/assets/img/rewards_program/diamond-bg.png');
        }
        .status-Inactive {
          background-color: #bfbfbf;
          color: white;
        }
        .status-Closed {
          background-color: #c43c35;
          color: white;
        }
        #points {
          font-weight: 300;
          font-size: 25px;
        }
        #tier-points {
          font-weight: bold;
          font-size: 20px;
        }

      </style>";

      $output .= $styles;

      $tierOutput = "<h2 class='tier-heading'><span class='tier tier-{$tier}'>{$tier}</span>"; //</h2>
      $noTierOutput = "<h2 class='tier-heading'><span class='tier tier-unknown'>No tier</span>"; //</h2>
      $inactiveOutput = "<h2 class='tier-heading'><span class='tier status-{$status}'>{$status}</span>"; //</h2>
      $pointsOutput = "<span id='points'> Points: {$points_formatted}</span>";
      if($hasValidTier && $isActive) {
        $output .= $tierOutput . $pointsOutput . "</h2>";
      } else {
        if (!$isActive) {
          $output .= $inactiveOutput . $pointsOutput . "</h2>";
        } else {
          $output .= $noTierOutput . $pointsOutput . "</h2>";
        }
      }
      $output .= "<p>For more details, click <span style='color:#202f60;font-weight:bold'>Login as Client</span> below (open in a new tab), then click <a href='{$detailsLink}'>here</a>.</p>";

      return $output;


    } else {
      return "<span style='color:red'>Error retrieving points.</span>";
    }



});

/*
add_hook('ClientAreaHomepagePanels', 1, function($homePagePanels) {

    $userid = $_SESSION['uid'];
    $c = new Controller();
    $data = $c->main($userid);
    $points = number_format($data["totalPoints"]);
    $tier = $data["tier"];

    $newPanel = $homePagePanels->addChild(
        'rewards-widget',
        array(
            'name' => 'Rewards Program',
            'label' => 'Rewards Program',
            'icon' => 'fas fa-star', //see http://fortawesome.github.io/Font-Awesome/icons/
            'order' => '99',
            'extras' => array(
                'color' => '#811b54', //see Panel Accents in template styles.css
                'btn-link' => 'https://roostergrin.com/billing/index.php?m=rewards&action=secret',
                'btn-text' => 'See Benefits',
                'btn-icon' => 'fas fa-arrow-right',
            ),
        )
    );
// Repeat as needed to add enough children
    $newPanel->addChild(
        'rewards-widget1',
        array(
            'label' => 'You currently have <strong>' . $points . ' points</strong>.<br>Check to see if you have any benefits available!',
            'uri' => 'https://roostergrin.com/billing/index.php?m=rewards&action=secret',
            'order' => 10,
        )
    );
});*/
