<?php

namespace WHMCS\Module\Addon\Rewards;

class constants {

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
      "Free Access to Rooster Grin Domains (Tier 1 - Limit 1 plus additional domain of lower tier)",
      "Template Website - $100 (every 3 years)",
      "Custom Website - $500 (every 3 years)",
      "Free VOIP Phone Hardware",
      "50 Additional Keywords for SEO Free",
      "Unlimited AdWords budget, no additional charge"
    ),
    "platinum" => array(
      "Priority Email Address",
      "Free Access to Rooster Grin Domains (Tier 2 - Limit 1 plus additional domain of lower tier)",
      "Template Website - $200 (every 3 years)",
      "Custom Website - $1,250 (every 3 years)",
      "Free VOIP Phone Hardware",
      "25 Additional Keywords for SEO Free",
      "Additional $1,000 of AdWords budget, no additional charge"
    ),
    "gold" => array(
      "Priority Email Address",
      "Free Access to Rooster Grin Domains (Tier 3 - Limit 1)",
      "Template Website - $400 (every 3 years)",
      "Custom Website - $2,000 (every 3 years)",
      "Free VOIP Phone Hardware",
      "10 Additional Keywords for SEO Free"
    ),
    "silver" => array(
      "Priority Email Address",
      "Free Access to Rooster Grin Domains (Tier 4 - Limit 1)",
      "5 Additional Keywords for SEO Free"
    )
  );

  const POINTS_REQUIRED = array(
    "diamond" => array(
      "total" => array(
        "value" => 100000,
        "formatted" => "100,000"
      ),
      "yearly" => array(
        "value" => 10000,
        "formatted" => "10,000"
      ),
    ),
    "platinum" => array(
      "total" => array(
        "value" => 50000,
        "formatted" => "50,000"
      ),
      "yearly" => array(
        "value" => 7000,
        "formatted" => "7,000"
      ),
    ),
    "gold" => array(
      "total" => array(
        "value" => 30000,
        "formatted" => "30,000"
      ),
      "yearly" => array(
        "value" => 5500,
        "formatted" => "5,500"
      ),
    ),
    "silver" => array(
      "total" => array(
        "value" => 10000,
        "formatted" => "10,000"
      ),
      "yearly" => array(
        "value" => 3000,
        "formatted" => "3,000"
      ),
    ),
  );

}
