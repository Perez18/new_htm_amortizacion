<?php
  
   include("../inc/duplicado.function.php ");
    require "../inc/db.php";
    
    /* -------------------------------------CONSULTA A MYSQL -------------------------------   */
     $tabla ="";
     $query="SELECT DISTINCT i.id,i.itemtype,c.name,i.bill ,i.buy_date,i.value ,i.sink_coeff,i.sink_time,i.amortizacion,l.name ubicacion,u.name user
     from glpi_infocoms i 
     inner join glpi_computers c /*   users_id */
     on i.items_id = c.id
     inner join glpi_locations l 
     on c.locations_id = l.id 
     LEFT JOIN glpi_users u /*  usuario_id  */
     on c.users_id = u.id
      where itemtype='computer' 
     union all
SELECT DISTINCT i.id,i.itemtype,s.name ,i.bill,i.buy_date ,i.value,
i.sink_coeff,i.sink_time,i.amortizacion,l.name ubicacion,u.name  user
     from glpi_infocoms i 
     inner join glpi_softwares s 
     on i.items_id = s.id    
     inner join glpi_locations l 
     on s.locations_id = l.id  
     LEFT join glpi_users u
     on s.users_id = u.id 
     where itemtype='software'
     ";

     if(isset($_POST['consulta'])){

      $q=mysqli_real_escape_string($conexion, $_POST['consulta']);
      $query=" SELECT  DISTINCT i.id,i.itemtype,c.name,i.bill ,i.buy_date,i.value ,i.sink_coeff,i.sink_time,i.amortizacion,l.name ubicacion,u.name user
      from glpi_infocoms i 
      left join glpi_computers c /*   users_id */
      on i.items_id = c.id
      inner join glpi_locations l 
      on c.locations_id = l.id 
      LEFT JOIN glpi_users u /* usuario_id  */
      on c.users_id = u.id
       where c.name LIKE '%".$q."%' OR u.name LIKE '%".$q."%' and itemtype='computer'
       union all
SELECT  DISTINCT i.id,i.itemtype,s.name ,i.bill,i.buy_date ,i.value,
i.sink_coeff,i.sink_time,i.amortizacion,l.name ubicacion,u.name  user
     from glpi_infocoms i 
     LEFT join glpi_softwares s 
     on i.items_id = s.id    
     inner join glpi_locations l 
     on s.locations_id = l.id  
     LEFT join glpi_users u
     on s.users_id = u.id 
     where  s.name LIKE '%".$q."%'  OR  u.name LIKE '%".$q."%'
    ";

     }

     $resultado = mysqli_query($conexion,$query);
     $cantidad =  mysqli_num_rows($resultado);

    //echo $cantidad;
                       

   //---------------------------- Tabla  de activos --------------------------                        
    if($cantidad>0){
      
      $tabla.=' <table class=" table table-hover table-sm  table-responsive ">
       
      <thead>
              <tr class="btn-dark">
                  <td>Tipo</td>
                  <td>Nombre</td>
                  <td>Factura</td>
                  <td>Fecha de compra </td>
                  <td>Coeficiente </td>
                  <td>Vida Util</td>
                  <td>Valor</td>
                  <td>Valor Neto</td>
                  <td>Ubicacion</td>
                  <td>Usuario</td>
                  <td>Opciones</td>
                  <!-- factura -->
                  <!-- Añadir a excel -->
              </tr>
          </thead> ';
               while($row = mysqli_fetch_assoc($resultado)){
                   $tabla.='
               <tr>
                   <td>'.$row['itemtype'].'</td>
                   <td>'.$row['name'].'</td>
                   <td>'.$row['bill'].'</td>
                   <td>'.$row['buy_date'].'</td>
                   <td>'.$row['sink_coeff'].'</td>
                   <td>'.$row['sink_time'].'</td>
                   <td>'.$row['value'].'</td>
                   <td>'.$row['amortizacion'].'</td>
                   <td>'.$row['ubicacion'].'</td>
                   <td>'.$row['user'].'</td>
                   <td>
                       <a href="../inc/factura.class.php?id='.$row['id'].'" class="btn btn-danger">
                             <i class="fas fa-file-invoice-dollar"></i> 
                         </a>

                      <a href="" class="btn btn-danger">

                              <i class="fas fa-chart-line"></i>
                         </a>
                  
                    </td>
                       
              </tr>';   }

            $tabla.='</table>';

    }

      else{
            $tabla.='No se encontro resultado';
          
           }

  echo $tabla;
  mysqli_close($conexion);
      
     
                               
?>