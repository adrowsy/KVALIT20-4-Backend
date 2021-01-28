<?php

/** 
 * Klassen Products innehåller funktioner som:
 * hämtar och avkodar API i json, omvandlar till array och visar data
 * - Visa antal produkter från validerad GET-request
 * - Visa felmeddelande om:
 * -- endpoint-url saknas
 * -- ogiltigt värde i $_GET['show']
 * 
 * Annika Rengfelt
 * https://github.com/adrowsy
 * KVALIT20 - Backend - Uppgift 3 - del 2 och 3
 * 2021-01-27
 * */

class Products
{

  public static $url = "http://localhost/webshop/api/v2";
  public static $min = 1;
  public static $max = 10;

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
    $json = @file_get_contents($url);
    if (!$json)
      throw new Exception("<div class='alert alert-warning'>Cannot access $url</div>");

    if (isset($_GET['show']) and !self::valid_input($_GET['show']))
      throw new Exception("<div class='alert alert-warning'><h4 class='alert-heading'>Ogiltigt värde</h4><p class='mb-0'>Ett ogiltigt värde har skrivits in i url efter <b>?show=</b>. Giltiga värden är tal mellan " . self::$min . " och " . self::$max . ". 
      <button type='button' class='btn btn-primary' href='.'>Ladda om sidan</button> </div>");

    return json_decode($json, true);
  }

  public static function valid_input($input)
  {
    $valid = false;

    if ($input >= self::$min and $input <= self::$max) {
      $valid = true;
    }

    return $valid;
  }

  public static function viewData($array)
  {
    $show = isset($_GET['show']) ? $_GET['show'] : self::$max;

    $shown = 0;
    $product = "
    <div class='row'>";

    foreach ($array as $key => $value) {

      if ($shown < $show) {

        $img_dir = "http://localhost/webshop/img";
        $image = $value['image'];
        $name = $value['name'];
        $price = $value['price'];
        $description = $value['description'];
        $inStock = $value['in stock'];

        $product .= "
            <div class='col-md-6 mb-4'>
              <div class='card h-100'>
                <div>
                  <div class='icon-float right'>
                    <i class='fas fa-asterisk text-white' style='font-size: 2rem;'></i>
                  </div><!-- ./ icon -->
                                        
                  <img src='$img_dir/$image' alt='$image' style='' class='card-img-top img-fluid'>
                </div><!-- ./ img -->

                <div class='card-body'>
                  <div class='row'>
                    <div class='col'>
                      <h2 class='card-title'><a href='#'>$name</a></h2>
                    </div>

                    <div class='col-lg-auto'>
                      <h4 class='text-lg-right'>" . number_format($price, 0, ',', ' ') . " SEK</h4>
                    </div>
                  </div>

                  <p class='card-text text-right'>Lediga platser: $inStock</p>
                  <p class='card-text mt-3'>$description</p>

                  " . self::$booking_form . "

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
    . "
                <div class='col-12'>
                  <p class='card-text small'>
                  Genom att klicka på \"Boka\" bekräftar jag att jag har läst och godkänt 
                  <a href='#' class='alert-link text-primary'>Allmänna villkor</a>, 
                  <a href='#' class='alert-link text-primary'>Dataskyddsinformation</a> och 
                  <a href='#' class='alert-link text-primary'>Cookiepolicy</a>
                </div>"
    // ./ Legal notice

    . "
          </div>
        </form>
      </div>
    ";
}
