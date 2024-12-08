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
                  <?php echo htmlspecialchars($row['total_amount']); ?>
             </td>  
             <td class="name">
                  <a target="_blank" href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>">Product Details</a>
             </td>
                
             <td class="name">
                  <?php echo htmlspecialchars($row['p_name']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['p_price']); ?>
             </td>

             <td class="name">
                  <input type="text" class="form-control" id="unit" name="unit" placeholder="Product unit" <?php echo 'value="'. htmlspecialchars($row['o_unit']) . '"'; ?>>
             </td>

             <td class="name">
                  <input type="text" class="form-control" id="size" name="size" placeholder="Product size" <?php echo 'value="'. htmlspecialchars($row['p_size']) . '"'; ?>>
             </td>

             <td class="name">
                  <input type="text" class="form-control" id="notes" name="notes" placeholder="Customer notes" <?php echo 'value="'. htmlspecialchars($row['c_notes']) . '"'; ?>>
             </td>

             <td class="name">
               <button type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">
                    OFF
               </button>
             </td>

           </tr>
       

