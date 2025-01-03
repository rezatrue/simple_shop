
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
                  <?php echo htmlspecialchars($row['d_date']); ?>  
             </td>
             
             <td class="name">
                    <?php echo "<strong>id:</strong> " . htmlspecialchars($row['o_id']) . "<br> <strong>Date:</strong> " . htmlspecialchars($row['o_date']);?>
             </td>

             <td class="name">
                    <?php 
                         $itemCount = 1;
                         $totalCost = 0;
                         foreach($row['o_items'] as $singleItem){
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


           </tr>



