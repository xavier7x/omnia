<?php
include('cabecera.php');
try
{
	$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
	$opt = array('resource' => 'products');
	if (isset($_GET['id']))
		$opt['id'] = $_GET['id'];
	$xml = $webService->get($opt);

	// Here we get the elements from children of customer markup which is children of prestashop root markup
	$resources = $xml->children()->children();
}
catch (PrestaShopWebserviceException $e)
{
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error<br />'.$e->getMessage();
}

if (isset($_GET['id']) && isset($_POST['id']))
{
// Here we have XML before update, lets update XML
	foreach ($resources as $nodeKey => $node)
	{
		$resources->$nodeKey = $_POST[$nodeKey];
	}
	try
	{
		$opt = array('resource' => 'products');
		if ($_GET['Create'] == 'Creating')
		{
			$opt['postXml'] = $xml->asXML();
			$xml = $webService->add($opt);
			echo "Successfully added.";
		}
	}
	catch (PrestaShopWebserviceException $ex)
	{
		// Here we are dealing with errors
		$trace = $ex->getTrace();
		if ($trace[0]['args'][0] == 404) echo 'Bad ID';
		else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
		else echo 'Other error<br />'.$ex->getMessage();
	}
}

if (isset($_GET['id']))
	echo '<form method="POST" action="?id='.$_GET['id'].'">';
?>

<br>
<table
  class="table table-striped table-bordered table-hover"
  data-provide="datatable"
  data-info="true"
>

      <?php
          if(isset($resources)) {
            if(!isset($_GET['id']) && $_GET['id'] != '') {
              ?>
              <thead>
                <tr>
                  <th data-filterable="true">Product's Id</th>
                  <th data-filterable="true">Manufacturer</th>
                  <th data-filterable="true">Supplier</th>
                  <th data-filterable="true">Price</th>
                  <th data-filterable="false" class="hidden-xs hidden-sm">Update</th>
                </tr>
              </thead>
              <tbody>
              <?php
              foreach ($resources as $resource ) {
                $opt['id'] = $resource->attributes();
                $xml = $webService->get($opt);
                $r = $xml->children()->children();

                $opt1['resource'] = 'manufacturers';
                $opt1['id'] = $r->id_manufacturer;
                $xml = $webService->get($opt1);
                $r2 = $xml->children()->children();

                $opt2['resource'] = 'suppliers';
                $opt2['id'] = $r->id_supplier;
                $xml = $webService->get($opt2);
                $r3 = $xml->children()->children();
                //print_r($r2);
                //print_r($r);
                echo '<tr>';
                echo '<td>'.$r->id.'</td>';
                echo '<td>'.$r2->name.'</td>';
                echo '<td>'.$r3->name.'</td>';
                echo '<td>'.$r->price.'</td>';
                echo '<td><a href="?id='.$resource->attributes().'">Update</a></td>';
                echo '</tr>';
              }
            } else {
              foreach ($resources as $key => $resource) {
                echo '<tr>';
                echo '<th>'.$key.'</th><td>';
                echo '<input type="text" id="name" name="'.$key.'" value="'.$resource.'" class="form-control" data-required="true" >';
                echo '</td></tr>';
              }
            }
          }
      ?>


    </tbody>
  </table>


<?php

if (isset($_GET['id']))
	echo '<input type="submit" class="btn btn-success" value="Update"></form>';





?>

              </div> <!-- /.table-responsive -->


            </div> <!-- /.portlet-content -->

          </div> <!-- /.portlet -->



        </div> <!-- /.col -->

      </div> <!-- /.row -->

<?php include('pie.php'); ?>
