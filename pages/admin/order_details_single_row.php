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
               <strong id="total-<?php echo $row['p_id']; ?>"><?php echo htmlspecialchars($row['total_amount']); ?></strong>
          </td>  
          <td class="name">
               <input type="hidden" id="id-<?php echo $row['p_id']; ?>" name="id-<?php echo $row['p_id']; ?>" value="<?php echo $row['p_id']; ?>">
               <a target="_blank" href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>">Details</a>
          </td>
               
          <td class="name">
               <?php echo htmlspecialchars($row['p_name']); ?>
          </td>

          <td class="name">
               <div class="input-group">
                    <input type="number" class="form-control" id="unit-<?php echo $row['p_id']; ?>" name="unit-<?php echo $row['p_id']; ?>" placeholder="Product unit" <?php echo 'value="'. htmlspecialchars($row['o_unit']) . '"'; ?>>
                    <div id="message-<?php echo $row['p_id']; ?>"></div>
               </div> 
          </td>

          <td class="name">
                    <div id="price-<?php echo $row['p_id']; ?>"><?php echo htmlspecialchars($row['p_price']); ?></div>
          </td>

          <td class="name">
               <input type="text" class="form-control" id="size-<?php echo $row['p_id']; ?>" name="size-<?php echo $row['p_id']; ?>" placeholder="Product size" <?php echo 'value="'. htmlspecialchars($row['p_size']) . '"'; ?>>
          </td>

          <td class="name">
               <input type="text" class="form-control" id="notes-<?php echo $row['p_id']; ?>" name="notes-<?php echo $row['p_id']; ?>" placeholder="Customer notes" <?php echo 'value="'. htmlspecialchars($row['c_notes']) . '"'; ?>>
          </td>

          <td class="name">
               <input type="hidden" id="o_id" name="o_id" value="<?php echo $o_id; ?>">
               <input type="hidden" id="p_id-<?php echo $row['p_id']; ?>" name="p_id-<?php echo $row['p_id']; ?>" value="<?php echo $row['p_id']; ?>">
               <input id="toggle-event-<?php echo $row['p_id']; ?>" type="checkbox" data-toggle="toggle">
          </td>
     </tr>

<script>
$(document).ready(function() {
     $('#toggle-event-<?php echo $row['p_id']; ?>').off('change');
     $('#toggle-event-<?php echo $row['p_id']; ?>').bootstrapToggle('on');
     $('#toggle-event-<?php echo $row['p_id']; ?>').change(function() {
          let oId = '<?php echo $o_id; ?>';
          let pId =  $('#p_id-<?php echo $row['p_id']; ?>').val();
          let isOn = $(this).prop('checked');
          var notes = $('#notes-<?php echo $row['p_id']; ?>').val(); 
          var size = $('#size-<?php echo $row['p_id']; ?>').val(); 
          var unit = parseFloat($('#unit-<?php echo $row['p_id']; ?>').val()) || 0; // Default to 0 if NaN   
          alert(oId + ' ' + pId + ' ' + isOn + ' ' + notes + ' ' + size + ' ' + unit);        
          $.ajax({
               url: './data/order_item_update.php',
               method: 'GET',
               data: { status: isOn, pid: pId, oid: oId, ounit: unit, psize: size, cnotes: notes },
                    success: function(data) {
                         console.log('Server response:', data); // Log server response for debugging
                              // Handle success logic here
                         },
                    error: function(xhr, status, error) {
                         console.error('AJAX Error:', status, error); // Log any AJAX errors
                    }
          });
          if (isOn) {
               console.log('Toggle is OFF');

          }else {
               console.log('Toggle is ON');
          }   
     });
});

// changing previous products status
$(document).ready(function() {
        $('#unit-<?php echo $row['p_id']; ?>').on('input', function() {
            let units = $(this).val();
            let pid = '<?php echo $row['p_id']; ?>';
            let oid = '<?php echo $_GET['o_id']; ?>';
               $.ajax({
               url: './data/order_units_update.php',
               method: 'GET',
               data: { units: units, pid: pid, oid: oid },
               success: function(data) {
                    $('#message-<?php echo $row['p_id']; ?>').html(data).show();
                    // Get the current row's price and unit IDs
                    var priceId = 'price-<?php echo $row['p_id']; ?>';
                    var unitId = 'unit-<?php echo $row['p_id']; ?>';
                    var totalId = 'total-<?php echo $row['p_id']; ?>';

                    // Retrieve the price and unit values
                    var price = parseFloat($('#' + priceId).text()) || 0; // Default to 0 if NaN
                    var unit = parseFloat($('#' + unitId).val()) || 0; // Default to 0 if NaN

                    // Calculate the total for this item
                    var total = price * unit;

                    // Update the total element for this item
                    $('#' + totalId).text(total.toFixed(2)); // Display total with 2 decimal places

                    // Now, calculate the grand total
                    var grandTotal = 0;

                    // Iterate through all elements with IDs starting with 'total-'
                    $('[id^="total-"]').each(function() {
                         var itemTotal = parseFloat($(this).text()) || 0; // Get each item's total
                         grandTotal += itemTotal; // Sum up the totals
                    });

                    // Update the grand total display and data-price attribute
                    $('#grand-total').text('Total: ' + grandTotal.toFixed(2)); // Update displayed total
                    $('#grand-total').attr('data-price', grandTotal.toFixed(2)); // Update data-price attribute
               }
               });
        });

        // nessage removed from dispaly
        $(document).on('blur', '#unit-<?php echo $row['p_id']; ?>', function() {
            $('#message-<?php echo $row['p_id']; ?>').hide();
        });
    });
</script> 
