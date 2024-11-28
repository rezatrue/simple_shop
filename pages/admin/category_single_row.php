
          <tr class="db-row" data-filter-row="001_SHOP" style="">
             <td class="name">
                  <?php echo htmlspecialchars($row['cat_id']); ?>
             </td>
             <td class="tool">
                <a href="add_category.php?id=<?php echo htmlspecialchars($row['sub_cat_id']); ?>">Edit</a>
             </td>
                
             <td class="name">
                  <?php echo htmlspecialchars($row['cat_name']); ?>
             </td>

             <td class="name">
                  <?php echo "(" . htmlspecialchars($row['sub_cat_id']) . ") " . htmlspecialchars($row['sub_cat_name']); ?>
             </td>

             <td class="name">
                  <?php if (htmlspecialchars($row['cat_is_catofmonth'])==1) echo "Yes"; else echo "No"; ?>
             </td>
             <td class="name">
                  <img height="20px" weight="20px" src="<?php echo $row['cat_image']; ?>" >
             </td>
             <td class="name">
                  <form method="post" action="category_list.php">
			<input type="submit" value="Delete"/>
		  </form>
             </td>

           </tr>
       

