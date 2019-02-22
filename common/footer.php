<!DOCTYPE html>
<html>
  <div id="footer"><br>
    <?php if (ENVIRONMENT <> 'production') { ?>
      <div id="footer_dev">
          <b>SESSIE:</b>  <?php echo ENVIRONMENT;  ?> <br/>
          <b> POST: </b><br/>
          <?php print_r($_POST); ?><br/><br/>
          <b> VARIABELEN: </b><br/>
          <?php echo '<pre>'.print_r(get_defined_vars(),true).'</pre>'; ?>
      </div>
    <?php } ?>
   </div>
    
</html>
