<?php

namespace WHMCS\Module\Addon\Rewards;

use WHMCS\Module\Addon\Rewards\constants;

class functions {

  public function getMultiplier($productName) {

    $c = new constants();

    if (strpos($productName, 'Search Engine Optimization') !== false) {
      return $c::SEO;
    }
    if (strpos($productName, 'SEO') !== false) {
      return $c::SEO;
    }
    if (strpos($productName, 'Web Hosting') !== false) {
      return $c::WEBHOSTING;
    }
    if (strpos($productName, 'E-mail Hosting') !== false) {
      return $c::EMAILS;
    }
    if (strpos($productName, 'Web Domain') !== false) {
      return $c::DOMAIN;
    }
    if (strpos($productName, 'Health History Forms') !== false) {
      return $c::HHFORMS;
    }
    if (strpos($productName, 'Google Ad Words') !== false) {
      return $c::ADWORDS;
    }
    if (strpos($productName, 'Redirect') !== false) {
      return $c::REDIRECTS;
    }
    if (strpos($productName, 'Retargeting') !== false) {
      return $c::RETARGETING;
    }
    if (strpos($productName, 'Online Scheduling') !== false) {
      return $c::ONLINESCHEDULING;
    }
    if (strpos($productName, 'Reminders') !== false) {
      return $c::REMINDERS;
    }

    return 1.0;

  }

  public function cleanProductName($productName) {


    if (strpos($productName, 'Search Engine Optimization') !== false) {
      return 'SEO';
    }
    if (strpos($productName, 'SEO') !== false) {
      return 'SEO';
    }
    if (strpos($productName, 'Web Hosting') !== false) {
      return 'Web Hosting';
    }
    if (strpos($productName, 'E-mail Hosting') !== false) {
      return 'E-mail Hosting';
    }
    if (strpos($productName, 'Web Domain') !== false) {
      return 'Web Domain';
    }
    if (strpos($productName, 'Health History Forms') !== false) {
      return 'Health History Forms';
    }
    if (strpos($productName, 'Google Ad Words') !== false) {
      return 'Google Ad Words';
    }
    if (strpos($productName, 'Redirect') !== false) {
      return 'Redirect';
    }
    if (strpos($productName, 'Retargeting') !== false) {
      return 'Retargeting';
    }
    if (strpos($productName, 'Online Scheduling') !== false) {
      return 'Online Scheduling';
    }
    if (strpos($productName, 'Reminders') !== false) {
      return 'Reminders';
    }
    if (strpos($productName, 'Choice Social') !== false) {
      return 'Choice Social';
    }
    if (strpos($productName, 'Web Development') !== false) {
      return 'Web Development';
    }

    return 'Other';

  }

  public function getYearsAsClient($signUpDate) {

    $sud = date("U", strtotime($signUpDate));
    $now = date("U");

    $diff = $now - $sud;
    $diff = floatval($diff / (365*24*60*60));

    // $diff = number_format($diff, 2, '.', '');

    return $diff;

  }

  public function getAverageMonthlyPoints($data) {

    $amountSpent = $data["totalPoints"];
    $yearsAsClient = $data["yearsAsClient"];
    $monthsAsClient = intval($yearsAsClient*12);

    return floatval($amountSpent / $monthsAsClient);


  }

  public function getClientTier($data) {

    $years = $data["yearsAsClient"];
    $points = $data["totalPoints"];
    $monthly = $data["averageMonthlyPoints"];

    $yearsInt = intval(floor($years));


    $silver = "silver";
    $gold = "gold";
    $platinum = "platinum";
    $diamond = "diamond";
    $unknown = "unknown";

    $tier_by_years = self::tierByYears($data);
    $tier_by_monthly = self::tierByAverageMonthlyPoints($data);
    $tier_by_points = self::tierByPoints($data);

    $groupId_monthly = self::getGroupID($tier_by_monthly);
    $groupId_points = self::getGroupID($tier_by_points);

    if ($groupId_monthly > $groupId_points) {
      return $tier_by_monthly;
    } else {
      return $tier_by_points;
    }
    // return $tier_by_points;

  }

  public function tierByPoints($data) {
    $points = $data["totalPoints"];

    $silver = "silver";
    $gold = "gold";
    $platinum = "platinum";
    $diamond = "diamond";
    $unknown = "unknown";

    if (isset($points) && gettype($points) === 'integer' && $points > 0) {
      switch ($points) {

        case $points>=100000:
          return $diamond;

        case $points>=50000:
          return $platinum;

        case $points>=30000:
          return $gold;

        case $points>=10000:
          return $silver;

        default:
          return $unknown;
      }
    } else {
      return $unknown;
    }


  }

  public function tierByYears($data) {
    $years = $data["yearsAsClient"];
    // $yearsInt = intval(floor($years));

    $silver = "silver";
    $gold = "gold";
    $platinum = "platinum";
    $diamond = "diamond";
    $unknown = "unknown";

    if (isset($years) && $years > 0) {
      switch ($years) {

        case $years>=5:
          return $diamond;

        case $years>=3:
          return $platinum;

        case $years>=2:
          return $gold;

        case $years>=1:
          return $silver;

        default:
          return $unknown;
      }
    } else {
      return $unknown;
    }
  }

  public function tierByAverageMonthlyPoints($data) {

    //Tiers:

    //Diamond = $10000 spent/yr (833.33/mo)
    //Platinum = 7000/yr (583.33/mo)
    //Gold = 5500/yr (458.33/mo)
    //Silver = 3000/yr (250/mo)

    $monthly = $data["averageMonthlyPoints"];

    $silver = "silver";
    $gold = "gold";
    $platinum = "platinum";
    $diamond = "diamond";
    $unknown = "unknown";

    if (isset($monthly) && $monthly > 0) {
      switch ($monthly) {

        case $monthly>=833.33:
          return $diamond;

        case $monthly>=583.33:
          return $platinum;

        case $monthly>=458.33:
          return $gold;

        case $monthly>=250.0:
          return $silver;

        default:
          return $unknown;
      }
    } else {
      return $unknown;
    }


  }

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

  public function getGroupID($tier) {
    if(!isset($tier)) {
      return 6;
    } else {
      switch ($tier) {
        case "diamond":
          return 2;
        case "platinum":
          return 3;
        case "gold":
          return 4;
        case "silver":
          return 5;
        default:
          return 6;
      }
    }
  }

  public function updateGroupID($data, $old_groupid) {

    $new_groupid = $data["groupid"];
    $id = $data["id"];
    $adminUsername = "Cooper";
    $updateClient_command = 'UpdateClient';


    if($new_groupid != $old_groupid) {

      $postData_clientUpdate = array(
          'clientid' => $id,
          'groupid' => $new_groupid,
      );
      $u = localAPI($updateClient_command, $postData_clientUpdate, $adminUsername);
      if($u["result"] == "success") {
        return "Group Update Succes: " . Date();
      } else {
        return "Group Update Failed: " . Date();
      }
    }
    return "Up to date";

  }



  public function sortByPoints($a, $b) {
    return $a['points'] - $b['points'];
  }
}
