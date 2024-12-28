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
                
             <td class="name">
                  <?php echo htmlspecialchars($row['o_date']); ?>
             </td>

             <td class="name">
                  <?php foreach($row['c_items'] as $item){
                    echo '<strong class="bg-secondary bg-gradient">'.htmlspecialchars($item).'</strong> </br>'; 
                  }
                  ?>
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
                  <?php echo htmlspecialchars($row['c_notes']); ?>
             </td>

           </tr>
       

