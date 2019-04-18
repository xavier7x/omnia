<?php
include('cabecera.php');
try {
  $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  $opt1['resource'] = 'customers';
  $opt2['resource'] = 'products';
  $opt3['resource'] = 'manufacturers';
  $opt4['resource'] = 'suppliers';
  $opt5['resource'] = 'employees';
  $opt6['resource'] = 'stocks';
  $opt7['resource'] = 'groups';
  $opt8['resource'] = 'guests';
  $opt9['resource'] = 'orders';
  $opt10['resource'] = 'zones';
  $opt11['resource'] = 'stores';
  $opt12['resource'] = 'customer_messages';

  $xml1 = $webService->get($opt1);
  $xml2 = $webService->get($opt2);
  $xml3 = $webService->get($opt3);
  $xml4 = $webService->get($opt4);
  $xml5 = $webService->get($opt5);
  $xml6 = $webService->get($opt6);
  $xml7 = $webService->get($opt7);
  $xml8 = $webService->get($opt8);
  $xml9 = $webService->get($opt9);
  $xml10 = $webService->get($opt10);
  $xml11 = $webService->get($opt11);
  $xml12 = $webService->get($opt12);

  $r1 = $xml1->children()->children();
  $r2 = $xml2->children()->children();
  $r3 = $xml3->children()->children();
  $r4 = $xml4->children()->children();
  $r5 = $xml5->children()->children();
  $r6 = $xml6->children()->children();
  $r7 = $xml7->children()->children();
  $r8 = $xml8->children()->children();
  $r9 = $xml9->children()->children();
  $r10 = $xml10->children()->children();
  $r11 = $xml11->children()->children();
  $r12 = $xml12->children()->children();

} catch (PrestaShopWebserviceException $e) {
  $trace = $e->getTrace();
  if($trace[0]['args'][0] == 404) echo 'Bad ID';
  else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
  else echo 'Other error <br />'.$e->getMessage();
}

?>
      <div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Employees</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r5);  ?></strong> </h3>
            <span class="label label-success row-stat-badge">employees</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Customers</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r1);  ?></strong></h3>
            <span class="label label-success row-stat-badge">customers</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Guests</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r8);  ?></strong></h3>
            <span class="label label-success row-stat-badge">guests</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Groups</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r7);  ?></strong></h3>
            <span class="label label-success row-stat-badge">groups</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

      </div> <!-- /.row -->

      <div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Manufacturers</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r3);  ?></strong> </h3>
            <span class="label label-danger row-stat-badge">manufacturers</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Suppliers</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r4);  ?></strong></h3>
            <span class="label label-danger row-stat-badge">suppliers</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Stocks</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r6);  ?></strong></h3>
            <span class="label label-danger row-stat-badge">stocks</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Stores</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r11);  ?></strong></h3>
            <span class="label label-danger row-stat-badge">stores</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

      </div> <!-- /.row -->

      <div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Products</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r2);  ?></strong> </h3>
            <span class="label label-info row-stat-badge">products</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Orders</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r9);  ?></strong></h3>
            <span class="label label-info row-stat-badge">orders</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Zones</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r10);  ?></strong></h3>
            <span class="label label-info row-stat-badge">zones</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Customer's Messages</p>
            <h3 class="row-stat-value"><strong> <?php echo count($r12);  ?></strong></h3>
            <span class="label label-info row-stat-badge">messages</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

      </div> <!-- /.row -->

      <br>



      <div class="row">





      <h3 >  Hello and Welcome to ENSAT's Shop :D <br/> </h3>
      <p>
        <a href="./R-Products.php"><input type="button" class="btn btn-default"  value="Products"/></a>
        <a href="./R-Customers.php"><input type="button" class="btn btn-default"  value="Customers"/></a>
      </p>

      </div> <!-- /.row -->

   <?php include('pie.php'); ?>