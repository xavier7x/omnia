<?php
include('cabecera.php');
try
{
	$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  $opt['resource'] = 'customers';
  
	if (isset($_GET['id'])) {
		$opt['id'] = (int)$_GET['id'];
		}

	$xml = $webService->get($opt);

	$resources = $xml->children()->children();
  //print_r($resources);
}
catch (PrestaShopWebserviceException $e)
{
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error<br />'.$e->getMessage();
}


?>
              <table
                class="table table-striped table-bordered table-hover"
                data-provide="datatable"
                data-info="true"
              >

                    <?php
                        if(isset($resources)) {
                          if(!isset($_GET['id'])) {
                            ?>
                            <thead>
                              <tr>
                                <th data-filterable="true">Customer's Id</th>
                                <th data-filterable="true">Firstname</th>
                                <th data-filterable="true">Lastname</th>
                                <th data-filterable="true">Email</th>
                                <th data-filterable="false" class="hidden-xs hidden-sm">Details</th>
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
                              echo '<td><a href="?id='.$resource->attributes().'">Details</a></td>';
                              echo '</tr>';
                            }
                          } else {
                            foreach ($resources as $key => $resource) {
                              echo '<tr>';
                              echo '<th>'.$key.'</th>';
                              echo '<td>'.$resource.'</td>';
                              echo '</tr>';
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