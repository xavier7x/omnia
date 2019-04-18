<?php
include('cabecera.php');
try
{
	$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
	$opt = array('resource' => 'customers');
	if (isset($_GET['Create']))
		$xml = $webService->get(array('url' => PS_SHOP_PATH.'/api/customers?schema=blank'));
	else
		$xml = $webService->get($opt);
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

if (count($_POST) > 0)
{
// Here we have XML before update, lets update XML
	foreach ($resources as $nodeKey => $node)
	{
		$resources->$nodeKey = $_POST[$nodeKey];
	}
	try
	{
		$opt = array('resource' => 'customers');
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

if (!isset($_GET['Create']))
	echo '<input type="button" class="btn btn-default" onClick="document.location.href=\'?Create\'" value="Create"/> <br>';
else
	echo '<form method="POST" action="?Create=Creating">';
?>

<br>
<table
  class="table table-striped table-bordered table-hover"
  data-provide="datatable"
  data-info="true"
>

      <?php
          if(isset($resources)) {
            if(count($_GET) == 0) {
              ?>
              <thead>
                <tr>
                  <th data-filterable="true">Customer's Id</th>
                  <th data-filterable="true">Firstname</th>
                  <th data-filterable="true">Lastname</th>
                  <th data-filterable="true">Email</th>
                </tr>
              </thead>
              <tbody>
              <?php
              foreach ($resources as $resource ) {
                $opt['id'] = $resource->attributes();
                $xml = $webService->get($opt);
                $r = $xml->children()->children();
                //print_r($r);
                echo '<tr>';
                echo '<td>'.$r->id.'</td>';
                echo '<td>'.$r->firstname.'</td>';
                echo '<td>'.$r->lastname.'</td>';
                echo '<td>'.$r->email.'</td>';
                echo '</tr>';
              }
            } else {
              foreach ($resources as $key => $resource) {
                echo '<tr>';
                echo '<th>'.$key.'</th><td>';
                if(isset($_GET['Create']))
                  echo '<input type="text" id="name" name="'.$key.'" class="form-control" data-required="true" >';
                echo '</td></tr>';
              }
            }
          }
      ?>


    </tbody>
  </table>


<?php

if (isset($_GET['Create']))
	echo '<input type="submit" class="btn btn-success" value="Create"></form>';





?>

              </div> <!-- /.table-responsive -->


            </div> <!-- /.portlet-content -->

          </div> <!-- /.portlet -->



        </div> <!-- /.col -->

      </div> <!-- /.row -->


<?php include('pie.php'); ?>
