<?php

/** 
 * Klassen Products innehåller funktioner som:
 * hämtar och avkodar API i json, omvandlar till array och visar data
 * Även funktion för att visa antal produkter från validerad GET-request
 * */

class Products
{

  public static $url = "http://localhost/webshop/api";

  public static function main()
  {
    try {
      $array = self::getData(self::$url);
      self::viewData($array);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }


  public static function getData($url)
  {
    $json = @file_get_contents($url); // @ anger att eget felmeddelande ska visas
    if (!$json)
      throw new Exception("<div class='alert alert-danger'>Cannot access $url</div>");
    return json_decode($json, true); //True ger associativ array
  }

  public static function valid_num_input($input)
  {
    $min = 1; 
    $max = 10; 
    $valid = false;

    if ($input >= $min and $input <= $max) {
      $valid = true;
    }

    return $valid;
  }

  public static function viewData($array) {

    if (isset($_GET['show']) and self::valid_num_input($_GET['show'])) {
      $show = ($_GET['show']);
    } else {
      $show = 10;
    }
    
    $shown = 0;

    $product = "
    <div class='row'>";
    
    foreach ($array as $key => $value) {

      if ($shown < $show) {

        $image = $value['image'];
        $name = $value['name'];
        $price = $value['price'];
        $description = $value['description'];

        $inStock = rand(0, 50);

        $product .= "
            <div class='col-md-6 mb-4'>
              <div class='card h-100'>
                <div>
                  <div class='icon-float right'>
                    <i class='fas fa-asterisk text-white' style='font-size: 2rem;'></i>
                  </div><!-- ./ icon -->
                                        
                  <img src='$image' alt='$image' style='' class='card-img-top img-fluid'>
                </div><!-- ./ img -->

                <div class='card-body'>
                  <div class='row'>
                    <div class='col'>
                      <h2 class='card-title'><a href='#'>$name</a></h2>
                    </div>

                    <div class='col-lg-auto'>
                      <h4 class='text-lg-right'>$price SEK</h4>
                    </div>
                  </div>

                  <p class='card-text text-right'>Lediga platser: $inStock</p>
                  <p class='card-text mt-3'>$description</p>

                  ".self::$booking_form."

                </div> <!-- ./ card-body -->
              </div> <!-- ./ card -->
            </div> <!-- ./ col -->";

        $shown++;
      }
  }
  $product .= "</div>";

  echo $product;
}

/**
 * Variabel som skriver kod för bokningsformulär
 * Används i printProducts()
 **/

public static $booking_form = 
    "<div class='mt-5'>
      <form>
          <div class='form-row align-items-end'>
            <div class='col-lg-4'>
              <div class='input-group mb-2'>
                <div class='input-group-prepend'>
                  <div class='input-group-text'><i class='fas fa-user' style='font-size: 1.5rem;'></i></div>
                </div>
                  <select class='form-control form-control-lg'>
                    <option selected>Vuxna</option>
                    <option>1 vuxen</option>
                    <option>2 vuxna</option>
                    <option>3 vuxna</option>
                    <option>4 vuxna</option>
                  </select>
                </div>
              </div>

              <div class='col-lg-4'>
                <div class='input-group mb-2'>
                  <div class='input-group-prepend'>
                    <div class='input-group-text'><i class='fas fa-child' style='font-size: 1.5rem;'></i></div>
                  </div>
                  <select class='form-control form-control-lg'>
                    <option selected>Barn</option>
                    <option>1 barn</option>
                    <option>2 barn</option>
                    <option>3 barn</option>
                    <option>4 barn</option>
                  </select>
                </div>
              </div>

              <div class='col-lg-4'>
                <button type='submit' class='btn btn-lg btn-success mb-2 btn-block'>Boka</button>
              </div>"

              // Legal notice
                ."
                <div class='col-12'>
                  <p class='card-text small'>
                  Genom att klicka på \"Boka\" bekräftar jag att jag har läst och godkänt 
                  <a href='#' class='alert-link text-primary'>Allmänna villkor</a>, 
                  <a href='#' class='alert-link text-primary'>Dataskyddsinformation</a> och 
                  <a href='#' class='alert-link text-primary'>Cookiepolicy</a>
                </div>"
              // ./ Legal notice

              ."
          </div>
        </form>
      </div>
    "; 
}