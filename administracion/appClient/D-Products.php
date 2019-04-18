<?php
include('cabecera.php');
if (isset($_GET['DeleteID']))
{
  try
  {
    $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
    // Call for a deletion, we specify the resource name and the id of the resource in order to delete the item
    $webService->delete(array('resource' => 'products', 'id' => intval($_GET['DeleteID'])));
    // If there's an error we throw an exception

    echo 'Successfully deleted !<meta http-equiv="refresh" content="5"/>';
  }
  catch (PrestaShopWebserviceException $e)
  {
    // Here we are dealing with errors
    $trace = $e->getTrace();
    if ($trace[0]['args'][0] == 404) echo 'Bad ID';
    else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
    else echo 'Other error<br />'.$e->getMessage();
  }
  }
  else
  {
    try
  	{
  		$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  		$opt = array('resource' => 'products');
  		$xml = $webService->get($opt);
  		$resources = $xml->children()->children();
  	}
  	catch (PrestaShopWebserviceException $e)
  	{
  		// Here we are dealing with errors
  		$trace = $e->getTrace();
  		if ($trace[0]['args'][0] == 404) echo 'Bad ID';
  		else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
  		else echo 'Other error';
  	}


?>
              <table
                class="table table-striped table-bordered table-hover"
                data-provide="datatable"
                data-info="true"
              >

                    <?php
                        if(isset($resources)) {
                          if(!isset($DeletionID)) {
                            ?>
                            <thead>
                              <tr>
                                <th data-filterable="true">Product's Id</th>
                                <th data-filterable="true">Manufacturer</th>
                                <th data-filterable="true">Supplier</th>
                                <th data-filterable="true">Price</th>
                                <th data-filterable="false" class="hidden-xs hidden-sm">Delete</th>
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
                              echo '<td><a href="?DeleteID='.$resource->attributes().'">Delete</a></td>';
                              echo '</tr>';
                            }
                          }
                          }
                        }
                    ?>


                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->


            </div> <!-- /.portlet-content -->

          </div> <!-- /.portlet -->



        </div> <!-- /.col -->

      </div> <!-- /.row -->

<?php include('pie.php'); ?>
