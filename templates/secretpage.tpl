

<style>
  h3 {
    color: #058;
    font-size: 20px;
  }
  .rewards-container {
    margin-bottom: 20px;
  }
  .tier-heading {
    margin-bottom: 18px;
  }
  .tier {
    text-transform: capitalize;
    padding: 1rem 3rem;
    border: 1.5px solid black;
    border-radius: 8px;
    background-position: center;
    background-size: 200px;
    background-repeat: no-repeat;
    font-weight: 600;
    box-shadow: 0px 0px 6px #245;
  }
  .tier-unknown {
    background: black;
    color: white;
    box-shadow: 0px 0px 0px white;

  }
  .tier-silver {
    background-image: url('assets/img/rewards_program/silver-bg.jpg');
  }
  .tier-gold {
    background-image: url('assets/img/rewards_program/gold-bg.jpg');
  }
  .tier-platinum {
    background-image: url('assets/img/rewards_program/platinum-bg.jpg');
  }
  .tier-diamond {
    background-image: url('assets/img/rewards_program/diamond-bg.png');
  }
  .status-Inactive {
    background-color: #bfbfbf;
    color: white;
  }
  .status-Closed {
    background-color: #c43c35;
    color: white;
  }
  .table-tier {
    text-transform: capitalize;
  }
  thead {
    color: #058;
  }
  th {
    font-size: 16px;
    font-weight: 600;
  }
  #points {
    font-weight: bold;
    font-size: 25px;
  }
  #points span {
    font-weight: 300;
    font-size: 21px;
  }
  #years {
    font-size: 22px;
  }
  #years span {
    font-weight: 600;
  }
  #tier-points {
    font-weight: bold;
    font-size: 20px;
  }
  tr.active-row {
    border-bottom: 4px solid #7bc144;
    border-left: 4px solid #7bc144;
    border-right: 1px solid #ddd;
    border-top: 1px solid #ddd;
  }
  p.active-row {
    border-bottom: 4px solid #7bc144;
    /* box-shadow: 1px 1px 5px #444; */
  }
  .requirements .row {
    margin: 10px 0;
  }
  .requirements span {
    font-size: 22px;
    font-weight: bold;
    text-transform: capitalize;
  }
  .requirements p {
    font-size: 21px;
    padding: 3px;
  }
  #check {
    color: #7bc144;
  }
  .benefits li {
    line-height: 28px;
    font-size: 16px;
  }
</style>

<div class="row rewards-container">
  <div class="col-md-6 col-sm-12">
    <h1>Hello, {$firstname}!</h1>
    {if $status == 'Active'}
    <h5 id="years">Years as client: <span>{$years}</span></h5>
    {/if}

  </div>
  <div class="col-md-6 col-sm-12">
    {if $status == 'Active'}
      {if $clientData.tier == 'unknown'}
      <h2 class="tier-heading">Current Tier: <span class="tier tier-{$clientData.tier}">No Tier</span> </h2>
      {else}
      <h2 class="tier-heading">Current Tier: <span class="tier tier-{$clientData.tier}">{$clientData.tier}</span> </h2>
      {/if}
    <h4 id="points">{$points} points <span>({$avgYearlyPoints} average annual points)</span></h4>
    {else}
    <h2 class="tier-heading">No tier, Status: <span class="tier status-{$status}">{$status}</span></h2>
    <h4 id="points">{$points} points</h4>
    {/if}
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-6 col-sm-12 benefits">
    {if $clientData.tier != 'unknown' && $status == 'Active'}
    <h3>Benefits for your tier (<span style="text-transform: capitalize;font-weight:bold;">{$clientData.tier}</span>):</h3>
    <ul>
      {foreach $clientBenefits as $benefit}
      <li>{$benefit}</li>
      {/foreach}
    </ul>
    {/if}
  </div>
  <div class="col-md-6 col-sm-12">
    {if count($productPoints) > 0}
    <h3>Points Breakdown</h3>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Product Name</th>
          <th scope="col">Points</th>
        </tr>
      </thead>
      <tbody>
        {foreach $productPoints as $product}
        <tr>
          <th scope="row">{$product@key}</th>
          <td>{$product}</td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    {/if}
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-6 col-sm-12">
    <h3 style="margin-bottom: 35px">Requirements</h3>
    <div class="requirements">
      {foreach $points_required as $p}
        {if $clientData.tier === $p@key}
        <p class="active-row">
        {else}
        <p>
        {/if}
      <span>{$p@key}</span>: {$p.total.formatted} total points & {$p.yearly.formatted} annual points <span id="check">{if $clientData.tier === $p@key}✓{/if}</span></p>
      {/foreach}

    </div>

  </div>
  <div class="col-md-6 col-sm-12">
    <h3>Benefits</h3>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Tier</th>
          <th scope="col">Benefits</th>
        </tr>
      </thead>
      <tbody>
        {foreach $benefits as $tier}
          {if $tier@key == $clientData.tier && $status == 'Active'}
          <tr class="active-row">
          {else}
          <tr>
          {/if}
          <th class="table-tier" scope="row">{$tier@key}</th>
          <td>
            <ul>
              {foreach $tier as $benefit}
              <li>{$benefit}</li>
              {/foreach}
            </ul>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</div>



<div class="" style="display: none">
{$clientData}
{$clientBenefits}
{$productPoints}
{$benefits}
{$invoices}
{$example_invoice}
</div>

<hr>
