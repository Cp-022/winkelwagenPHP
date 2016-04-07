<?php include 'header.php'; ?>
<?php include 'database.php';?>
<!-- BODY BELOW -->
<?php

session_start();

if( isset ( $_GET['remove']) ) {
  echo 'Bwam';
  $_SESSION['Cart_content'] = preg_replace('/'.$_GET['pid'].'/','', $_SESSION['Cart_content'], 1);
  $_SESSION['Cart_content'] = preg_replace('/,,/',',', $_SESSION['Cart_content'], 1);
  $_SESSION['Cart_content'] = trim ($_SESSION['Cart_content'], ",");
  header('location: cart.php');
}

if (isset ( $_GET['pid']) && !isset ($_GET['remove'])){
  if (!isset( $_SESSION['Cart_content'])){
    $_SESSION['Cart_content'] = $_GET['pid'];
  }
  else{
    $_SESSION['Cart_content'] .= ',';
    $_SESSION['Cart_content'] .= $_GET['pid'];
  }
}

if (isset ($_GET['empty'])){
  session_destroy();
  header('location:cart.php');
}

if ($database_config['debug']){
  echo "<pre>";
  print_r ($_SESSION);
  echo "</pre>";
}
?>

<div class='container'>
  <div class='row'>
    <h2>Producten in Winkelwagen</h2>
    <?php
    if (isset ($_SESSION['Cart_content'])){
      $cart_array = explode(',', $_SESSION['Cart_content']);

      if ($database_config['debug']){
        echo "<pre>";
        print_r( $cart_array);
        echo "</pre>";
      }
      foreach ($cart_array as $item){
        $query = "SELECT * FROM Cart_producten WHERE id='" .$item . "' ";
        $query_result = $conn->query($query);
        $product = $query_result->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class='col-md-12 col-xs-12 productlisting'>
          <p class='col-md-4'><?php echo $product['Naam']; ?></p>
          <p class='col-md-4'><?php echo $product['Prijs']; ?></p>
          <a class='col-md-4' href='./cart.php?remove=true&pid=<?php echo $product['id'];?>'>
          Remove</a>
        </div>
        <?php
      }
    } ?>
<!-- BODY ABOVE -->
<?php include 'footer.php'; ?>
