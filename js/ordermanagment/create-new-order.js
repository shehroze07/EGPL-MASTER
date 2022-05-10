function create_order() {
  var data = new FormData();

  data.append("orderDate", jQuery("#date").val());
  var AllDataArray = [];
  jQuery(".text").each(function () {
    var fieldID = jQuery(this).attr("id");
    if (jQuery(this).val() != null) {
      console.log(fieldID + "=" + jQuery(this).val());
      // console.log(jQuery(this).val());
      data.append(fieldID, jQuery(this).val());
    } else {
      data.append(fieldID, "");
    }
  });
  console.log("--------------------------------");
  jQuery(".option2").each(function () {
    var fieldID = jQuery(this).attr("id");
    if (jQuery(this).val() != null) {
      console.log(fieldID + "=" + jQuery(this).val());
      //console.log(jQuery(this).val());
      data.append(fieldID, jQuery(this).val());
    } else {
      data.append(fieldID, "");
    }
  });
  var customer_id = jQuery("#order_user  option:selected").val();
  data.append("customer_id", customer_id);
  data.append("coupon_code_prdt", jQuery("#productDiscount ").attr("disc"));
  data.append("coupon_code_cart", jQuery("#cartDiscount").attr("disc"));
  jQuery("#productTable tbody")
    .find("tr")
    .each(function (index) {
      var ID = jQuery(this).attr("id");
      var Qty = jQuery(this).find("td").eq(2).html();
      var dataArray = { id: ID, quantity: Qty };
      AllDataArray.push(dataArray);
    });
  data.append("productArray", JSON.stringify(AllDataArray));
  console.log("HELLO");
  var url = currentsiteurl + "/";
  var urlnew =
    url +
    "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=createOrder";
  jQuery.ajax({
    url: urlnew,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: "POST",
  });
}

function packgChange() {
  var packageSelected = jQuery("#selectPackages option:selected").val();
  jQuery("#selectPackages option").each(function () {
    if (
      packageSelected == jQuery(this).attr("value") &&
      jQuery(this).attr("partial") == "optional"
    ) {
      var partialDiv =
        '<div class="partial-div"><input type="radio" id="full" checked name="paymentP" value=full"><label for="full">Paid Full</label><input type="radio" id="partial" name="paymentP" value="partial"><label for="partial">Paid Partial</label><br> </div>';

      jQuery("#packages-div").append(partialDiv);
      //jQuery("#add-ons-div ").csss('display',flex);
      jQuery("#package-quantity").css("width", 63);
    } else {
      jQuery("#package-quantity").css("width", 211);
      jQuery(".partial-div").hide();
    }
  });

  console.log(productArray);
}
function AddChange() {
  var AddonsSelected = jQuery("#selectAddOns option:selected").val();
  jQuery("#selectAddOns option").each(function () {
    if (
      AddonsSelected == jQuery(this).attr("value") &&
      jQuery(this).attr("partial") == "optional"
    ) {
      var partialDiv =
        '<div class="partial-div"><input type="radio" id="full" checked name="paymentA" value=full"><label for="full">Paid Full</label><input type="radio" id="partial" name="paymentA" value="partial"><label for="partial">Paid Partial</label><br> </div>';
      jQuery("#add-ons-div ").append(partialDiv);
      //jQuery("#add-ons-div ").csss('display',flex);
      jQuery("#add-ons-quantity").css("width", 63);
    } else {
      jQuery("#add-ons-quantity").css("width", 211);
      jQuery(".partial-div").hide();
    }
  });
}
function boothChange() {
  var boothSelected = jQuery("#selectBooths option:selected").val();
  jQuery("#selectBooths option").each(function () {
    if (
      boothSelected == jQuery(this).attr("value") &&
      jQuery(this).attr("partial") == "optional"
    ) {
      var partialDiv =
        '<div class="partial-div"><input type="radio" id="full" checked name="paymentB" value=full"><label for="full">Paid Full</label><input type="radio" id="partial" name="paymentB" value="partial"><label for="partial">Paid Partial</label><br> </div>';
      jQuery("#booth-div ").append(partialDiv);
    } else {
      jQuery(".partial-div").hide();
    }
  });
}

function add_product() {
  var url = currentsiteurl + "/";
  var urlnew =
    url +
    "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=getProducts";

  jQuery.ajax({
    url: urlnew,
    cache: false,
    contentType: false,
    processData: false,
    type: "POST",
    success: function (data) {
      // success callback function
      console.log(data);
      var dataArray = JSON.parse(data);
      console.log(dataArray);
      var productArray = [];
      productArray = dataArray;
      jQuery("body").css("cursor", "default");
      var dropdown_Packages =
        '<select  name="getallPackages" onchange="packgChange()" id="selectPackages" style=" text-align: center;" class="js-example-placeholder-multiple js-states form-control form-control" required><option style="color" value="" hidden >Select Pacakge</option>';
      var dropdown_AddOns =
        '<select  name="getallAddOns" onchange="AddChange()" id="selectAddOns" style=" text-align: center;" class="js-example-placeholder-multiple js-states form-control form-control" required><option style="color" value="" hidden >Select AddOns</option>';
      var dropdown_Booths =
        '<select  name="getallBooths" id="selectBooths" onchange="boothChange()" style="text-align: center;" class="js-example-placeholder-multiple js-states form-control form-control" required><option style="color" value="" hidden >Select Booth</option>';

      for (let obj of productArray) {
        if (obj["catagory"] == "Uncategorized" && obj["status"] == "instock") {
          dropdown_Booths +=
            '<option value="' +
            obj["id"] +
            '" partial="' +
            obj["deposit"] +
            '">' +
            obj["title"] +
            "</option>";
        } else if (
          obj["catagory"] == "Packages" &&
          obj["status"] == "instock"
        ) {
          dropdown_Packages +=
            '<option value="' +
            obj["id"] +
            '" partial="' +
            obj["deposit"] +
            '">' +
            obj["title"] +
            "</option>";
        } else if (obj["status"] == "instock") {
          dropdown_AddOns +=
            '<option value="' +
            obj["id"] +
            '" partial="' +
            obj["deposit"] +
            '">' +
            obj["title"] +
            "</option>";
        }
      }
      dropdown_Packages += "</select>";
      dropdown_Booths += "</select>";
      dropdown_AddOns += "</select>";
      Swal.fire({
        didOpen: () => {},

        title: "Add Products",
        scrollbarPadding: false,
        confirmButtonText: "Add",
        confirmButtonClass: " btn btn-primary",
        cancelButtonClass: " btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        allowOutsideClick: false,
        html:
          '<div style = "overflow-x: hidden !important">' +
          '<div class="row"> ' +
          '<div class="col-sm-6"><h5>Product</h5>' +
          '<hr style="border-top: 1px solid #000 !important; margin:0px !important; width: 250px;">' +
          '<div><label class="">Package</label>' +
          dropdown_Packages +
          "</div>" +
          '<div><label class="">Add-Ons</label>' +
          dropdown_AddOns +
          "</div>" +
          '<div><label class="">Booths</label>' +
          dropdown_Booths +
          "</div>" +
          "</div>" +
          '<div class="col-sm-6"><h5>Quantity</h5>' +
          '<hr style="border-top: 1px solid #000 !important; margin:0px !important; width: 250px;">' +
          '<div id="packages-div" class="quantity"><label class=""></label>' +
          '<input type="number"  id="package-quantity" class="form-control center" value="1">' +
          "</div>" +
          '<div id="add-ons-div" class="quantity"><label class=""></label>' +
          '<input type="number" id="add-ons-quantity" class="form-control center " value="1">' +
          "</div>" +
          "</div>" +
          '<div id="booth-div"></div>' +
          '<div class="col-sm-12" style="padding-top: 20px"><hr style="border-top: 1px solid #000 !important; margin:0px !important"></div>' +
          "</div>" +
          "</div>",

        preConfirm: function () {},
      }).then((result) => {
        if (result.isConfirmed) {
          var packageSelected = jQuery("#selectPackages option:selected").val();
          var addOnsSelected = jQuery("#selectAddOns option:selected").val();
          var boothSelected = jQuery("#selectBooths option:selected").val();
          var partialPackge = jQuery("input[name='paymentP']:checked").val();
          var partialAddons = jQuery("input[name='paymentA']:checked").val();
          var partialBooth = jQuery("input[name='paymentB']:checked").val();
          console.log(partialAddons);
          var packageQuantity = jQuery("#package-quantity").val();
          var AddOnsQuantity = jQuery("#add-ons-quantity").val();

          for (let obj of productArray) {
            console.log(obj);
            if (packageSelected && packageSelected == obj["id"]) {
              var itemName = obj["title"].replaceAll('"', "");
              var price = obj["price"];
              if (partialPackge == "partial") {
                price = obj["deposit_amount"];
              }
              var appendProduct =
                "<tr id=" +
                obj["id"] +
                "><td>" +
                itemName +
                "</td><td>" +
                "$" +
                price +
                "</td><td>" +
                packageQuantity +
                '</td><td id="packageDiscount">' +
                0 +
                "</td><td>" +
                price +
                '</td><td><span ><i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit"  onclick=editProduct(' +
                obj["id"] +
                "," +
                "'" +
                obj["title"] +
                "'" +
                "," +
                packageQuantity +
                ')></i></span></td><td><span ><i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" title="Remove"onclick="deleteProduct(' +
                obj["id"] +
                ')"></i></span></td></tr>';
              jQuery("#productTable tbody").append(appendProduct);
            } else if (addOnsSelected && addOnsSelected == obj["id"]) {
              var price = obj["price"];
              if (partialAddons == "partial") {
                price = obj["deposit_amount"];
              }
              var appendProduct =
                "<tr id=" +
                obj["id"] +
                "><td>" +
                obj["title"] +
                "</td><td>" +
                "$" +
                price +
                "</td><td>" +
                AddOnsQuantity +
                '</td><td id="packageDiscount">' +
                0 +
                "</td><td>" +
                price +
                '</td><td><span ><i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit"  onclick="editProduct(' +
                obj["id"] +
                "," +
                "'" +
                obj["title"] +
                "'" +
                "," +
                AddOnsQuantity +
                ')"></i></span></td><td><span ><i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" title="Remove"onclick="deleteProduct(' +
                obj["id"] +
                ')"></i></span></td></tr>';
              jQuery("#productTable tbody").append(appendProduct);
            } else if (boothSelected && boothSelected == obj["id"]) {
              var price = obj["price"];
              if (partialBooth == "partial") {
                price = obj["deposit_amount"];
              }
              var appendProduct =
                "<tr id=" +
                obj["id"] +
                "><td>" +
                obj["title"] +
                "</td><td>" +
                "$" +
                price +
                "</td><td>" +
                1 +
                '</td><td id="packageDiscount">' +
                0 +
                "</td><td>" +
                price +
                '</td><td>-</td><td><span ><i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" title="Remove"onclick="deleteProduct(' +
                obj["id"] +
                ')"></i></span></td></tr>';
              jQuery("#productTable tbody").append(appendProduct);
            }
          }
          updateTable();
        }
      });
    },
  });
}
function editProduct(id, name, qty) {
  Swal.fire({
    didOpen: () => {},

    title: "Edit Product",
    scrollbarPadding: false,
    confirmButtonText: "Update",
    confirmButtonClass: " btn btn-primary",
    cancelButtonClass: " btn btn-danger",
    showCancelButton: true,
    cancelButtonText: "Cancel",
    allowOutsideClick: false,
    html:
      '<div style = "overflow-x: hidden !important">' +
      '<div class="row"> ' +
      '<div class="col-sm-6"><h5>Product</h5>' +
      '<hr style="border-top: 1px solid #000 !important; margin:0px !important; width: 250px;">' +
      '<div><label class="" id="pro_name">' +
      name +
      "</label>" +
      "</div>" +
      "</div>" +
      '<div class="col-sm-6"><h5>Quantity</h5>' +
      '<hr style="border-top: 1px solid #000 !important; margin:0px !important; width: 250px;">' +
      '<div class="quantity"><label class=""></label>' +
      '<input type="number" id="obj-edit-quantity" class="form-control" value=' +
      qty +
      ">" +
      "</div>" +
      "</div>" +
      '<div class="col-sm-12" style="padding-top: 20px"><hr style="border-top: 1px solid #000 !important; margin:0px !important"></div>' +
      "</div>" +
      "</div>",

    preConfirm: function () {},
  }).then((result) => {
    if (result.isConfirmed) {
      var qtn_change = jQuery("#obj-edit-quantity").val();
      var totalPriceSum = 0;
      var totalAmount = 0;
      jQuery("#productTable tbody")
        .find("tr")
        .each(function () {
          if (id == jQuery(this).attr("id")) {
            var productId = jQuery(this).find("td").eq(2).html(qtn_change);
          }
          var Qty = jQuery(this).find("td").eq(2).html();
          var Price = jQuery(this).find("td").eq(1).html();
          var Discount = jQuery(this).find("td").eq(3).html();
          var price = Price.replace("$", "");
          var SalesPrice = Qty * (price - Discount);
          var totalPrice = Qty * price;
          totalPriceSum += totalPrice;
          console.log(totalPriceSum);
          console.log(totalPrice);
          jQuery(this).find("td").eq(4).html(SalesPrice);
          jQuery("#totalPrice").html("$" + totalPriceSum);
          console.log(productId);
        });
      var cartdisct = jQuery("#cartDiscount").html();
      var productdisct = jQuery("#productDiscount").html();
      var cartdic = cartdisct.replace("$", "");
      var prodic = productdisct.replace("$", "");
      console.log(prodic);
      console.log(cartdic);
      totalAmount = totalPriceSum - (parseInt(cartdic) + parseInt(prodic));

      jQuery("#totalAmount").html("$" + totalAmount);
    }
  });
}
function deleteProduct(id) {
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      if (id == jQuery(this).attr("id")) {
        console.log("T");
        jQuery(this).remove();
      }
    });
}

function apply_discount() {
  Swal.fire({
    title: "Apply Discount",
    scrollbarPadding: false,
    customClass: "custom_dis_width",
    confirmButtonText: "Appply",
    confirmButtonClass: " btn btn-primary",
    cancelButtonClass: " btn btn-danger",
    showCancelButton: true,
    cancelButtonText: "Cancel",
    allowOutsideClick: false,

    html:
      '<div style = "overflow-x: hidden !important">' +
      '<div class="row"> ' +
      '<div class="col-sm-6" style="margin-left:85px;">' +
      "<label >Code</label>" +
      '<input type="text" id="dsct_input" class="form-control">' +
      "</div>" +
      "</div>" +
      "</div>" +
      "</div>",

    didOpen: () => {},

    preConfirm: function () {},
  }).then((result) => {
    if (result.isConfirmed) {
      var data = new FormData();
      var disc_code = jQuery("#dsct_input").val();
      data.append("code", disc_code);
      var url = currentsiteurl + "/";
      var urlnew =
        url +
        "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=applyDiscount";
      jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        success: function (data) {
          console.log(data);
          console.log(JSON.parse(data));
          var datas = JSON.parse(data);
          if (datas["amount"] == 0) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Code did not match!",
            });
          } else {
            if (datas["discount_type"] == "fixed_cart") {
              // jQuery("#packageDiscount").html(data);
              jQuery("#cartDiscount").html("$" + datas["amount"]);
              jQuery("#cartDiscount ").attr("disc", datas["code"]);

              var cartdisct = jQuery("#cartDiscount").html();
              var productdisct = jQuery("#productDiscount").html();
              var totalAmt = jQuery("#totalPrice").html();
              var cartdic = cartdisct.replace("$", "");
              var prodic = productdisct.replace("$", "");
              var totalAmt$ = totalAmt.replace("$", "");
              totalAmt$ = totalAmt$ - (parseInt(cartdic) + parseInt(prodic));
              jQuery("#totalAmount").html("$" + totalAmt$);
              Swal.fire("Discount Added!", "", "success");
            } else if (datas["discount_type"] == "fixed_product") {
              Swal.fire("Discount Added!", "", "success");
              var productDiscount = 0;
              var productDiscountTotal = 0;
              var totalPriceSum = 0;
              var totalAmount = 0;

              jQuery("#productTable tbody")
                .find("tr")
                .each(function () {
                  // console.log(jQuery(this).attr("id"));
                  var id = jQuery(this).attr("id");
                  if (
                    jQuery.inArray(parseInt(id), datas["product_ids"]) != -1
                  ) {
                    jQuery(this).find("td").eq(3).html(datas["amount"]);
                  }
                  var Qty = jQuery(this).find("td").eq(2).html();
                  var Price = jQuery(this).find("td").eq(1).html();
                  var Discount = jQuery(this).find("td").eq(3).html();
                  var price = Price.replace("$", "");
                  console.log(price);
                  console.log(Discount);
                  console.log(Qty);

                  var SalesPrice = Qty * (price - Discount);
                  productDiscount = Discount * Qty;
                  productDiscountTotal += productDiscount;
                  var totalPrice = Qty * price;
                  totalPriceSum += totalPrice;
                  jQuery(this)
                    .find("td")
                    .eq(4)
                    .html("$" + SalesPrice);
                });
              console.log(totalPriceSum);
              console.log(totalPrice);
              console.log(productDiscountTotal);
              jQuery("#productDiscount").html("$" + productDiscountTotal);
              jQuery("#productDiscount ").attr("disc", datas["code"]);
              console.log(jQuery("#productDiscount ").attr("disc"));
              jQuery("#totalPrice").html("$" + totalPriceSum);
              var cartdisct = jQuery("#cartDiscount").html();
              var productdisct = jQuery("#productDiscount").html();
              var cartdic = cartdisct.replace("$", "");
              var prodic = productdisct.replace("$", "");
              console.log(prodic);
              console.log(cartdic);
              totalAmount =
                totalPriceSum - (parseInt(cartdic) + parseInt(prodic));

              jQuery("#totalAmount").html("$" + totalAmount);
            }
            if (datas["discount_type"] == "percent") {
              jQuery("#cartDiscount ").attr("disc", datas["code"]);
              var cartdisct = jQuery("#cartDiscount").html();
              var productdisct = jQuery("#productDiscount").html();
              var totalAmt = jQuery("#totalPrice").html();
              var cartdic = cartdisct.replace("$", "");
              var prodic = productdisct.replace("$", "");
              var totalAmt$ = totalAmt.replace("$", "");
              var percentTotal = (totalAmt$ / 100) * datas["amount"];
              jQuery("#cartDiscount").html("$" + percentTotal);
              totalAmt$ = totalAmt$ - (percentTotal + parseInt(prodic));
              jQuery("#totalAmount").html("$" + totalAmt$);
              Swal.fire("Discount Added!", "", "success");
              //updateTable();
            }
          }
        },
      });
    }
  });
}
function updateTable() {
  var productDiscount = 0;
  var productDiscountTotal = 0;
  var totalPriceSum = 0;
  var totalAmount = 0;

  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      // console.log(jQuery(this).attr("id"));

      var Qty = jQuery(this).find("td").eq(2).html();
      var Price = jQuery(this).find("td").eq(1).html();
      var Discount = jQuery(this).find("td").eq(3).html();
      var price = Price.replace("$", "");
      console.log(price);
      console.log(Discount);
      console.log(Qty);

      var SalesPrice = Qty * (price - Discount);
      productDiscount = Discount * Qty;
      productDiscountTotal += productDiscount;
      var totalPrice = Qty * price;
      totalPriceSum += totalPrice;
      jQuery(this)
        .find("td")
        .eq(4)
        .html("$" + SalesPrice);
    });
  console.log(totalPriceSum);
  console.log(totalPrice);
  console.log(productDiscountTotal);
  jQuery("#productDiscount").html("$" + productDiscountTotal);
  jQuery("#totalPrice").html("$" + totalPriceSum);
  var cartdisct = jQuery("#cartDiscount").html();
  var productdisct = jQuery("#productDiscount").html();
  var firstPayment = jQuery("#firstPayment").html();
  var secondPayment = jQuery("#secondPayment").html();
  var balanceDue = jQuery("#balanceDue").html();
  var cartdic = cartdisct.replace("$", "");
  var prodic = productdisct.replace("$", "");
  var firstPayment = firstPayment.replace("$", "");
  var secondPayment = secondPayment.replace("$", "");
  var balanceDue = balanceDue.replace("$", "");
  console.log(prodic);
  console.log(cartdic);
  totalAmount = totalPriceSum - (parseInt(cartdic) + parseInt(prodic));
  console.log(totalAmount);
  jQuery("#totalAmount").html("$" + totalAmount);
}
