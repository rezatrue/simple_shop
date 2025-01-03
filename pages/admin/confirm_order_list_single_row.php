
<style>
.scrollable-cell {
     max-height: 100px; /* Set the desired height */
     overflow-y: auto; /* Enable vertical scrolling */
     overflow-x: hidden; /* Hide horizontal scrolling */
     display: block; /* Make the td behave like a block */
}
</style>

          <tr class="db-row" data-filter-row="001_SHOP" style="">
             <td class="name">
                  <?php echo htmlspecialchars($row['o_id']); ?>
             </td>
             
             <td class="name" id="detail_link-<?php echo $row['o_id']; ?>">
                   <?php 
                         if($row['o_is_delivered']  == 0){
                              echo '<a href="order_details.php?o_id= 10"' . htmlspecialchars($row['o_id']) . '">open</a>'; 
                         }else{
                              echo '<p>closed</p>';
                         }
                    ?>
                   <div ><?php  ?></div>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['o_date']); ?>
             </td>

             <td class="name">

                  <?php 
                  $itemCount = 1;
                  $totalCost = 0;
                  foreach($row['c_items']['item'] as $singleItem){
                    echo "<strong>(". $itemCount . ")".
                    " Name: </strong>" . htmlspecialchars($singleItem['p_name']) .
                    "- Unit: " . htmlspecialchars($singleItem['o_unit']) .
                    "- Size: " . htmlspecialchars($singleItem['p_size']) .
                    "- Notes: " . htmlspecialchars($singleItem['c_notes']) .
                    "- <i>price: " . htmlspecialchars($singleItem['total_amount']) ."<i><br>";
                    $itemCount++;
                    $totalCost = $totalCost + $singleItem['total_amount'];
                  }
                  echo "<strong>Total : " . $totalCost . "</strong>"; 
                  ?>
                  
             </td>

             <td class="name">
                  <?php 
                  echo "Name: " . htmlspecialchars($row['o_name']) . "</br>
                        Phone: " . htmlspecialchars($row['o_phone']) . "</br>
                        Address: " . htmlspecialchars($row['o_address']) . "</br>"; 
                  ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['o_notes']); ?>
             </td>

             <td class="name">
             
               <input type="hidden" id="o_id-<?php echo $row['o_id']; ?>" name="o_id-<?php echo $row['o_id']; ?>" value="<?php echo $row['o_id']; ?>">
               <input id="delivery-<?php echo $row['o_id']; ?>" type="checkbox" data-toggle="toggle" data-on="Completed" data-off="Not Completed" >                  
             </td>
           </tr>

<script>
$(document).ready(function() {
    $('#delivery-<?php echo $row['o_id']; ?>').off('change');

    if(<?php echo $row['o_is_delivered']; ?> == 1){
          $('#delivery-<?php echo $row['o_id']; ?>').bootstrapToggle('on');
          $('#delivery-<?php echo $row['o_id']; ?>').bootstrapToggle('disable');
     }     
     if(<?php echo $row['o_is_delivered']; ?> == 0)
          $('#delivery-<?php echo $row['o_id']; ?>').bootstrapToggle('off'); 

    $('#delivery-<?php echo $row['o_id']; ?>').change(function() {
        let oId = $('#o_id-<?php echo $row['o_id']; ?>').val();
        let isOn = $(this).prop('checked');
        let row = <?php echo json_encode($row); ?>;
        $.ajax({
               url: './data/delivery_details_status_update.php',
               method: 'GET',
               data: { status: isOn, oid: oId, row: row},
                    success: function(data) {
                         console.log('Server response:', data); // Log server response for debugging
                              // Handle success logic here
                              $('#delivery-<?php echo $row['o_id']; ?>').bootstrapToggle('disable');
                              $('#detail_link-<?php echo $row['o_id']; ?>').text('Closed');    
                         },
                    error: function(xhr, status, error) {
                         console.error('AJAX Error:', status, error); // Log any AJAX errors
                    }
          });
        if (isOn) {
               console.log('Toggle is ON');
          }else {
               console.log('Toggle is OFF');
          } 
    });
});     

</script>

