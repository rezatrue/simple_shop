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
                  <?php echo htmlspecialchars($row['p_id']); ?>
             </td>
             <td class="tool">
                <a href="add_product.php?id=<?php echo htmlspecialchars($row['p_id']); ?>">Edit</a>
             </td>
                
             <td class="name">
                  <?php echo htmlspecialchars($row['p_name']); ?>
             </td>

             <td class="name">
                  <img height="40px" weight="40px" src="<?php echo $row['p_image']; ?>" >
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['p_price']); ?>
             </td>

             <td class="name">
                  <?php echo htmlspecialchars($row['p_sizes']); ?>
             </td>

             <td class="name">
                  <?php  if($row['p_is_show']==1) echo "Show"; else echo "Hide"; ?>
             </td>

             <td class="name">
                  <?php if (htmlspecialchars($row['p_is_featured'])==1) echo "Yes"; else echo "No"; ?>
             </td>


             <td class="name">
               <div class="scrollable-cell">
                  <?php echo htmlspecialchars($row['p_short_description']); ?>
               </div>
             </td>

             <td class="name">
               <div class="scrollable-cell">
               <?php echo htmlspecialchars($row['p_specification']); ?>
               </div>
             </td>  

             <td class="name">
                  <form method="post" action="category_list.php">
			<input type="submit" value="Delete"/>
		  </form>
             </td>

           </tr>
       

