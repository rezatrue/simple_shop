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
             <td class="tool">
                <a href="order_details.php?o_id=<?php echo htmlspecialchars($row['o_id']); ?>">Open</a>
             </td>
                
             <td class="name">
                  <?php echo htmlspecialchars($row['o_date']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['u_ip']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['order_amount']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['o_name']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['o_phone']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['o_address']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['o_notes']); ?>
             </td>

           </tr>
       

