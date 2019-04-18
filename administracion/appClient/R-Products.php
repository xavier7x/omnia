<?php
include('cabecera.php');
try
{
	$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
	$opt['resource'] = 'products';
	if (isset($_GET['id']) && empty($_GET['id'])) {
		$opt['id'] = (int)$_GET['id'];
		}

	$xml = $webService->get($opt);

	$resources = $xml->children()->children();
}
catch (PrestaShopWebserviceException $e)
{
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error<br />'.$e->getMessage();
}
var_dump($resources);
exit(0);
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
                                <th data-filterable="true">Product's Id</th>
                                <th data-filterable="true">Manufacturer</th>
                                <th data-filterable="true">Supplier</th>
                                <th data-filterable="true">Price</th>
                                <th data-filterable="false" class="">Details</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                            
                            foreach ($resources as $resource ) {
                              $opt['id'] = $resource->attributes();
                              $xml1 = $webService->get($opt);
                              
                            	$r = $xml1->children()->children();
                              
                              $opt1['resource'] = 'manufacturers';
                              $opt1['id'] = $r->id_manufacturer;
                              $xml2 = $webService->get($opt1);
                            	$r2 = $xml2->children()->children();

                              $opt2['resource'] = 'suppliers';
                              $campo = '';
                              if($r->id_supplier != 0){
                                $opt2['id'] = $r->id_supplier;
                                $xml3 = $webService->get($opt2);
                                $r3 = $xml3->children()->children();
                                $campo = $r3->name;
                              }else{
                                $campo = 'none';
                              }
                              
                             
                              echo '<tr>';
                              echo '<td>'.$r->id.'</td>';
                              echo '<td>'.$r2->name.'</td>';
                              echo '<td>'.$campo.'</td>';
                              echo '<td>'.$r->price.'</td>';
                              echo '<td><a href="?id='.$resource->attributes().'">Details</a></td>';
                              echo '</tr>';
                            }
                            
                          }else{
                            foreach ($resources as $key => $resource){
                              //var_dump($resource);
                              //var_dump($resource->attributes());
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
