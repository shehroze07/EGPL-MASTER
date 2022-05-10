function create_order() {
  var data = new FormData();
  var AllDataArray = [];
  var AllNoteArray = [];

  jQuery(".text").each(function () {
    var fieldID = jQuery(this).attr("id");
    if (jQuery(this).val() != null) {
      data.append(fieldID, jQuery(this).val());
    } else {
      data.append(fieldID, "");
    }
  });

  jQuery(".option2").each(function () {
    var fieldID = jQuery(this).attr("id");
    if (jQuery(this).val() != null) {
      data.append(fieldID, jQuery(this).val());
    } else {
      data.append(fieldID, "");
    }
  });
  jQuery(".text1").each(function () {
    var dataArray = { note: jQuery(this).html() };
    AllNoteArray.push(dataArray);
  });
  data.append("noteArray", JSON.stringify(AllNoteArray));

  var customer_id = jQuery("#order_user  option:selected").val();
  data.append("customer_id", customer_id);
  data.append("coupon_code_prdt", jQuery("#productDiscount ").attr("disc"));
  data.append("coupon_code_cart", jQuery("#cartDiscount").attr("disc"));
  var date = jQuery("#date").val();
  var hour = jQuery("#time-hour").val();
  var mint = jQuery("#time-mins").val();
  date = date.toString();
  hour = hour.toString();
  mint = mint.toString();
  date = date + " " + hour + ":" + mint + ":" + "02";
  console.log(date);
  data.append("orderDate", date);
  var check = check_validations();
  if (check == true) {
    jQuery("body").css("cursor", "progress");
    jQuery("#productTable tbody")
      .find("tr")
      .each(function (index) {
        var ID = jQuery(this).attr("id");
        var Qty = jQuery(this).find("td").eq(2).html();
        var Price = jQuery(this).find("td").eq(1).html();
        Price = Price.substr(1);
        var checks = Price <= 0 ? 0 : 1;
        var Partial_Price = jQuery(this).find("td").eq(1).attr("id");
        if (Partial_Price == "undefined") {
          Partial_Price = -200;
        }
        var dataArray = {
          id: ID,
          quantity: Qty,

          partial_check: Partial_Price,
          check: checks,
        };
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
      success: function (data) {
        console.log(data.trim());
        if (data.trim() == "success") {
          jQuery("body").css("cursor", "default");
          Swal.fire({
            icon: "success",
            title: "Your Order has been created",
            showConfirmButton: true,
          }).then((result) => {
            document.location.href = currentsiteurl + "/order-reporting";
          });
        }
      },
    });
  } else if (check == undefined) {
    Swal.fire({
      icon: "error",

      title: "Product not added!",
      text: "Please add one item!",
    });
  } else if (check == "status") {
    Swal.fire({
      icon: "error",
      confirmButtonClass: " btn btn-primary",
      title: "Validation Errors",
      text: "Kindly select the status correctly!",
    });
  } else if (check == "no_customer") {
    Swal.fire({
      icon: "error",
      confirmButtonClass: " btn btn-primary",
      title: "Validation Errors",
      text: "Kindly select the User!",
    });
  }
}
function paymentChange() {
  var payment_method = jQuery("#payment_method option:selected").val();
  if (payment_method !== "cheque") {
    updateTable();
    console.log(jQuery("#Transaction_ID"));
    jQuery("#Transaction_ID").attr("required", true);
  } else {
    updateTable();
    jQuery("#Transaction_ID").removeAttr("required");
  }
}

function packgChange() {
  var packageSelected = jQuery("#selectPackages option:selected").val();
  jQuery("#selectPackages option").each(function () {
    if (
      packageSelected == jQuery(this).attr("value") &&
      jQuery(this).attr("partial") == "optional"
    ) {
      var partialDiv =
        '<div class="partial-div" id="partial-divP" style="position: absolute;"> <input input style="width: 20px;" type="radio" id="full" checked name="paymentP" value=full"><label for="full"><small>Paid Full</small></label>&nbsp;<input style="width: 20px;" type="radio" id="partial" name="paymentP" value="partial"><label for="partial"><small>Paid Partial</small></label><br> </div>';

      jQuery("#packages-div").append(partialDiv);
      return false;
    } else {
      jQuery("#partial-divP").remove();
    }
  });
}
function AddChange() {
  var AddonsSelected = jQuery("#selectAddOns option:selected").val();
  jQuery("#selectAddOns option").each(function () {
    if (
      AddonsSelected == jQuery(this).attr("value") &&
      jQuery(this).attr("partial") == "optional"
    ) {
      console.log("ABCD");
      var partialDiv =
        '<div class="partial-div" id="partial-divA" style="position: absolute;"><input input style="width: 20px;" type="radio" id="full" checked name="paymentA" value=full"><label for="full"><small>Paid Full</small></label>&nbsp;<input  style="width: 20px;" type="radio" id="partial" name="paymentA" value="partial"><label for="partial"><small>Paid Partial</small></label><br> </div>';
      jQuery("#add-ons-div ").append(partialDiv);
      return false;
    } else {
      jQuery("#partial-divA").remove();
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
        '<div class="partial-div" id="partial-divB" style="position: absolute;"><input input style="width: 20px;" type="radio" id="full" checked name="paymentB" value=full"><label for="full"><small>Paid Full</small></label>&nbsp;<input  style="width: 20px;" type="radio" id="partial" name="paymentB" value="partial"><label for="partial"><small>Paid Partial</small></label><br> </div>';
      jQuery("#booth-div ").append(partialDiv);
      return false;
    } else {
      jQuery("#partial-divB").remove();
    }
  });
}

function add_product() {
  jQuery("body").css("cursor", "progress");

  var url = currentsiteurl + "/";
  var urlnew =
    url +
    "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=getProducts";
  var customer_id = jQuery("#order_user  option:selected").val();
  var option = jQuery(" #order_user option:selected").attr("level");
  if (customer_id == "") {
    Swal.fire({
      icon: "error",
      confirmButtonClass: " btn btn-primary",
      title: "Validation Errors",
      text: "Kindly select the User!",
    });
  } else {
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
          '<select  name="getallPackages" onchange="packgChange()" id="selectPackages"  aria-invalid="false" class="js-example-basic-single js-states form-control form-control" required><option style="color" value="" hidden >Select Package</option>';
        var dropdown_AddOns =
          '<select  name="getallAddOns" onchange="AddChange()" id="selectAddOns"   aria-invalid="false" class="js-example-basic-single js-states form-control form-control" required><option style="color" value="" hidden >Select AddOns</option>';
        var dropdown_Booths =
          '<select  name="getallBooths" id="selectBooths" onchange="boothChange()"   aria-invalid="false" class="js-example-basic-single js-states form-control form-control" required><option style="color" value="" hidden >Select Booth</option>';

        console.log(option);
        console.log(customer_id);
        for (let obj of productArray) {
          if (
            obj["catagory"] == "Uncategorized" &&
            obj["status"] == "instock" &&
            obj["stock"] > 0 &&
            (jQuery.inArray(customer_id.toString(), obj["boothOwner"]) != -1 ||
              obj["boothOwner"] == "" ||
              jQuery.inArray(option, obj["boothLevel"]) != -1) &&
            (obj["boothLevel"] == "" ||
              obj["boothLevel"][0] == "" ||
              jQuery.inArray(option, obj["boothLevel"]) != -1)
          ) {
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
            obj["status"] == "instock" &&
            obj["stock"] > 0
          ) {
            dropdown_Packages +=
              '<option value="' +
              obj["id"] +
              '" partial="' +
              obj["deposit"] +
              '">' +
              obj["title"] +
              "</option>";
          } else if (
            obj["catagory"] == "Add-ons" &&
            obj["status"] == "instock" &&
            obj["stock"] > 0
          ) {
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
          didOpen: () => {
            jQuery(".js-example-basic-single").select2();
          },

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
            '<div><label style="text-align: left;" class="">Package</label>' +
            dropdown_Packages +
            "</div>" +
            '<div><label   style="text-align: left;" class="">Add-Ons</label>' +
            dropdown_AddOns +
            "</div>" +
            '<div><label  style="text-align: left;" class="">Booths</label>' +
            dropdown_Booths +
            "</div>" +
            "</div>" +
            '<div class="col-sm-6"><h5>Quantity</h5>' +
            '<div id="packages-div" min="0" class="quantity quantity-ip"><label class=""></label>' +
            '<input type="number" min="1" id="package-quantity" class="form-control quan " value="1">' +
            "</div>" +
            '<div id="add-ons-div"  class="quantity quantity-ip"><label class=""></label>' +
            '<input type="number" min="1" id="add-ons-quantity" class="form-control quan  " value="1">' +
            "</div>" +
            '<div id="booth-div" style="margin-top: 28px;" min="1" class="quantity-ip quantity">' +
            "</div>" +
            "</div>",

          preConfirm: function () {},
        }).then((result) => {
          if (result.isConfirmed) {
            var packageSelected = jQuery(
              "#selectPackages option:selected"
            ).val();
            var addOnsSelected = jQuery("#selectAddOns option:selected").val();
            var boothSelected = jQuery("#selectBooths option:selected").val();
            var partialPackge = jQuery("input[name='paymentP']:checked").val();
            var partialAddons = jQuery("input[name='paymentA']:checked").val();
            var partialBooth = jQuery("input[name='paymentB']:checked").val();
            console.log(partialAddons);
            console.log(partialBooth);
            var packageQuantity = jQuery("#package-quantity").val();
            var AddOnsQuantity = jQuery("#add-ons-quantity").val();

            for (let obj of productArray) {
              if (
                packageSelected &&
                packageSelected == obj["id"] &&
                obj["stock"] >= packageQuantity
              ) {
                var itemName = obj["title"].replaceAll('"', "");
                var price = obj["price"];
                var price_partialP = 0;
                if (partialPackge == "partial") {
                  if (obj["type"] == "fixed") {
                    price_partialP = obj["price"] - obj["deposit_amount"];
                  } else {
                    price_partialP =
                      (obj["price"] / 100) * obj["deposit_amount"];
                  }
                }
                var appendProduct =
                  "<tr id=" +
                  obj["id"] +
                  "><td>" +
                  itemName +
                  "</td><td id=" +
                  price_partialP +
                  ">" +
                  "$" +
                  price +
                  "</td><td id=" +
                  obj["stock"] +
                  ">" +
                  packageQuantity +
                  '</td><td id="packageDiscount" disc="" disp="" cartp="" prodp="">' +
                  "$" +
                  0 +
                  "</td><td >" +
                  price +
                  "</td><td listofBooths=" +
                  obj["boothList"] +
                  '><span ><i class="fusion-li-icon fa fa-pencil-square fas fa-2x" title="Edit"  onclick=editProduct(' +
                  obj["id"] +
                  "," +
                  "'" +
                  obj["title"] +
                  "'" +
                  "," +
                  packageQuantity +
                  "," +
                  obj["stock"] +
                  ')></i></span></td><td><span ><i class="fusion-li-icon fas fa fa-times-circle fa-2x" title="Remove"onclick="deleteProduct(' +
                  obj["id"] +
                  "," +
                  packageQuantity +
                  ')"></i></span></td></tr>';
                jQuery("#productTable tbody").append(appendProduct);
              } else if (
                addOnsSelected &&
                addOnsSelected == obj["id"] &&
                obj["stock"] >= AddOnsQuantity
              ) {
                var price = obj["price"];
                var price_partialA = 0;
                if (partialAddons == "partial") {
                  if (obj["type"] == "fixed") {
                    price_partialA = obj["price"] - obj["deposit_amount"];
                  } else {
                    price_partialA =
                      (obj["price"] / 100) * obj["deposit_amount"];
                  }
                }
                var appendProduct =
                  "<tr id=" +
                  obj["id"] +
                  "><td>" +
                  obj["title"] +
                  "</td><td  id=" +
                  price_partialA +
                  ">" +
                  "$" +
                  price +
                  "</td><td id=" +
                  obj["stock"] +
                  ">" +
                  AddOnsQuantity +
                  '</td><td id="packageDiscount" disc="" disp="" cartp="" prodp="">' +
                  "$" +
                  0 +
                  "</td><td>" +
                  price +
                  '</td><td><span ><i class="fusion-li-icon fa fas fa-pencil-square fa-2x" title="Edit"  onclick="editProduct(' +
                  obj["id"] +
                  "," +
                  "'" +
                  obj["title"] +
                  "'" +
                  "," +
                  AddOnsQuantity +
                  "," +
                  obj["stock"] +
                  ')"></i></span></td><td><span ><i class="fusion-li-icon fa fas  fa-times-circle fa-2x" title="Remove" onclick="deleteProduct(' +
                  obj["id"] +
                  "," +
                  AddOnsQuantity +
                  ')"></i></span></td></tr>';
                jQuery("#productTable tbody").append(appendProduct);
              } else if (boothSelected && boothSelected == obj["id"]) {
                var price = obj["price"];
                var listofbooth = [];
                jQuery("#productTable tbody")
                  .find("tr")
                  .each(function () {
                    id = jQuery(this).find("td").eq(5).attr("listofbooths");
                    if (id != undefined) {
                      listofbooth = id;
                    }
                  });
                listofbooth = listofbooth.toString();
                listofbooth = listofbooth.split(",");
                console.log(listofbooth);
                var check = jQuery("#productTable").attr("zero");
                if (
                  jQuery.inArray(obj["id"].toString(), listofbooth) !== -1 &&
                  (check != 0 || check == undefined)
                ) {
                  price = 0;
                  jQuery("#productTable").attr("zero", 0);
                }
                var price_partialB = 0;
                if (partialBooth == "partial") {
                  if (obj["type"] == "fixed") {
                    price_partialB = obj["price"] - obj["deposit_amount"];
                  } else {
                    price_partialB =
                      (obj["price"] / 100) * obj["deposit_amount"];
                  }
                }
                var appendProduct =
                  "<tr id=" +
                  obj["id"] +
                  "><td>" +
                  obj["title"] +
                  "</td><td id=" +
                  price_partialB +
                  ">" +
                  "$" +
                  price +
                  "</td><td>" +
                  1 +
                  '</td><td id="packageDiscount" disc="" disp="" cartp="" prodp="">' +
                  "$" +
                  0 +
                  "</td><td >" +
                  price +
                  '</td><td></td><td><span ><i class="fusion-li-icon fa fas fa-times-circle fa-2x" title="Remove"onclick="deleteProduct(' +
                  obj["id"] +
                  "," +
                  1 +
                  ')"></i></span></td></tr>';
                jQuery("#productTable tbody").append(appendProduct);
              }
            }
            jQuery(".discount-area").css("display", "block");
            jQuery(".discs").css("display", "block");
            jQuery(".total-amount").css("display", "block");
            updateTable();
          }
        });
      },
    });
  }
}
function editProduct(id, name, qty, stock) {
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
      '<div class="quantity-ip"><label class="" id="pro_name">' +
      name +
      "</label>" +
      "</div>" +
      "</div>" +
      '<div class="col-sm-6"><h5>Quantity</h5>' +
      '<div class="quantity-ip"><label class=""></label>' +
      '<input type="number" max=' +
      stock +
      ' id="obj-edit-quantity" min="1" class="form-control center quan"  value=' +
      qty +
      ">" +
      "</div>" +
      "</div>" +
      '<div class="col-sm-12" style="padding-top: 20px"></div>' +
      "</div>" +
      "</div>",

    preConfirm: function () {},
  }).then((result) => {
    if (result.isConfirmed) {
      var Qauntity = 0;
      var qtn_change = jQuery("#obj-edit-quantity").val();
      var pro_id = jQuery("#productDiscount").attr("prod");
      var cartdisct = jQuery("#cartDiscount").html();
      var check_per = jQuery("#cartDiscount").attr("Percent");
      cartdisct = cartdisct.trim();
      var cartdic = cartdisct.substr(1);

      var disc_total = 0;
      jQuery("#productTable tbody")
        .find("tr")
        .each(function () {
          if (id == jQuery(this).attr("id")) {
            if (pro_id == id) {
              var disc = jQuery(this).find("td").eq(3).html();
              var Price = jQuery(this).find("td").eq(1).html();
              disc = disc.substr(1);
              Price = Price.substr(1);
              var Qty = jQuery(this).find("td").eq(2).html();
              disc = disc / Qty;
              disc = disc * qtn_change;
              jQuery(this)
                .find("td")
                .eq(3)
                .html("$" + disc);
              disc_total += disc;
              jQuery("#productDiscount").html("$" + disc_total);
            }
            jQuery(this).find("td").eq(2).html(qtn_change);
            jQuery(this).find("td").eq(5).html("");
            jQuery(this).find("td").eq(6).html("");
            var tabelData =
              '<span ><i class="fusion-li-icon fa fa-pencil-square fas fa-2x" title="Edit"  onclick=editProduct(' +
              id +
              "," +
              "'" +
              name +
              "'" +
              "," +
              qtn_change +
              "," +
              stock +
              ")></i></span>";
            var deleteBtn =
              '<span><i class="fusion-li-icon fa fas  fa-times-circle fa-2x" title="Remove" onclick="deleteProduct(' +
              id +
              "," +
              qtn_change +
              ')"></i></span>';
            jQuery(this).find("td").eq(5).append(tabelData);
            jQuery(this).find("td").eq(6).append(deleteBtn);
          }
          var Qty = jQuery(this).find("td").eq(2).html();
          Qty = parseInt(Qty);
          var Price = jQuery(this).find("td").eq(1).html();
          Price = Price.substr(1);
          if (Price != 0) {
            Qauntity += Qty;
          }
          // Qauntity += Qty;
        });
      if (cartdic !== "0" && check_per == "") {
        var per_item_discount = cartdic / Qauntity;
        recalculateDiscount(per_item_discount.toFixed(2));
      } else if (check_per !== "") {
        var code = jQuery("#productDiscount").attr("disc");
        var total = apply_coupon_percent_cart(check_per, code, null);
        jQuery("#cartDiscount").html("$" + total);
      }

      updateTable();
    }
  });
}
function deleteProduct(id, quant = 0) {
  let ids;
  var Qauntity = 0;
  var cartdisct = jQuery("#cartDiscount").html();
  var productdisct = jQuery("#productDiscount").html();
  var check_per = jQuery("#cartDiscount").attr("Percent");
  cartdisct = cartdisct.trim();
  productdisct = productdisct.trim();
  var cartdic = cartdisct.substr(1);
  var prodic = productdisct.substr(1);
  var listofbooth = [];
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      ids = jQuery(this).find("td").eq(5).attr("listofbooths");
      if (ids != undefined) {
        listofbooth = ids;
      }
    });
  listofbooth = listofbooth.toString();
  listofbooth = listofbooth.split(",");
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      var Qty = jQuery(this).find("td").eq(2).html();
      Qty = parseInt(Qty);
      var Price = jQuery(this).find("td").eq(1).html();
      Price = Price.substr(1);
      if (Price != 0) {
        Qauntity += Qty;
      }
      // Qauntity += Qty;
      if (id == jQuery(this).attr("id")) {
        var pro_id = jQuery("#productDiscount").attr("prod");
        if (pro_id == id) {
          jQuery("#productDiscount").html("$" + 0);
        }
        jQuery(this).remove();
        if (jQuery.inArray(id, listofbooth) !== -1) {
          jQuery("#productTable").attr("zero", 1);
        }
      }
    });
  Qauntity -= quant;
  if (cartdic !== "0" && check_per == "") {
    var per_item_discount = cartdic / Qauntity;
    recalculateDiscount(per_item_discount.toFixed(2));
  } else if (check_per !== "") {
    var code = jQuery("#productDiscount").attr("disc");
    var total = apply_coupon_percent_cart(check_per, code, null);
    jQuery("#cartDiscount").html("$" + total);
  }
  updateTable();
}

function apply_discount() {
  var length = jQuery("#productTable tbody tr").length;
  if (length == 0) {
    Swal.fire({
      icon: "error",

      title: "Product not added!",
      text: "Please add one item!",
    });
  } else {
    Swal.fire({
      icon: "info",
      title: "Apply Discount",
      scrollbarPadding: false,
      confirmButtonText: "Apply",
      confirmButtonClass: "btn-primary",
      cancelButtonClass: "btn-danger",
      showCancelButton: true,
      cancelButtonText: "Cancel",
      allowOutsideClick: false,

      html:
        '<div style = "overflow-x: hidden !important">' +
        '<div class="row"> ' +
        '<div class="col-sm-6" style="margin-left:123px;">' +
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
            // console.log(data);
            // console.log(JSON.parse(data));
            var datas = JSON.parse(data);
            if (datas["amount"] == 0) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Code did not match!",
              });
            } else {
              var flag = true;
              jQuery("#productTable tbody")
                .find("tr")
                .each(function () {
                  var Partial_Price = jQuery(this).find("td").eq(1).attr("id");
                  if (Partial_Price == undefined) {
                    Partial_Price = "0";
                  }
                  if (Partial_Price !== "0") {
                    flag = false;

                    Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "Cannot apply discount to partial payments",
                      showConfirmButton: true,
                    });
                  }
                });
              var cartdisct = jQuery("#cartDiscount").html();
              var productdisct = jQuery("#productDiscount").html();
              var totalAmt = jQuery("#totalPrice").html();
              cartdisct = cartdisct.trim();
              productdisct = productdisct.trim();
              var cartdic = cartdisct.substr(1);
              var prodic = productdisct.substr(1);
              if (cartdic !== "0" || prodic !== "0") {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Cannot apply discount one than one time",
                  showConfirmButton: true,
                });
                flag = false;
              }
              if (datas["discount_type"] == "fixed_cart" && flag == true) {
                var total = apply_coupon_fixed_cart(
                  datas["amount"],
                  datas["code"],
                  datas["discount_type"],
                  null
                );
                prodic = parseFloat(prodic);
                var totalAmt$ = totalAmt.substr(1);
                cartdic = parseFloat(cartdic);
                cartdic = cartdic + parseFloat(datas["amount"]);
                jQuery("#cartDiscount").html("$" + cartdic);
                jQuery("#cartDiscount ").attr("disc", datas["code"]);
                totalAmt$ =
                  totalAmt$ - (cartdic.toFixed(2) + prodic.toFixed(2));
                if (totalAmt$ < 0) {
                  totalAmt$ = 0;
                }
                jQuery("#totalAmount").html("$" + totalAmt$);
                var discount =
                  " <li id=" +
                  disc_code +
                  " class='code editable'><span > " +
                  disc_code +
                  '</span><i id="data-code" class="fusion-li-icon fa cross  fa-times-circle" code=' +
                  disc_code +
                  ' title="Remove" onclick="deleteDisc(' +
                  "'" +
                  disc_code +
                  "'" +
                  "," +
                  "'" +
                  "Cart" +
                  "'" +
                  ')"></i></li>';
                Swal.fire({
                  icon: "success",
                  title: "Discount Added",
                  showConfirmButton: true,
                });
                jQuery("#disocuntLabels").append(discount);
                updateTable();
              } else if (
                datas["discount_type"] == "fixed_product" &&
                flag == true
              ) {
                jQuery("#productTable tbody")
                  .find("tr")
                  .each(function () {
                    var Price = jQuery(this).find("td").eq(4).html();
                    Price = Price.substr(1);
                    var id = jQuery(this).attr("id");
                    if (
                      jQuery.inArray(parseInt(id), datas["product_ids"]) !== -1
                    ) {
                      var disc = jQuery(this).find("td").eq(3).html();
                      disc = disc.substr(1);
                      var Qty = jQuery(this).find("td").eq(2).html();
                      disc =
                        parseFloat(disc) + parseFloat(datas["amount"]) * Qty;
                      var discp = datas["amount"] * Qty;
                      if (Price < disc) {
                        disc = Price;
                        discp = Price;
                      }
                      jQuery(this)
                        .find("td")
                        .eq(3)
                        .html("$" + disc.toFixed(2));
                      jQuery(this).find("td").eq(3).attr("disp", datas["code"]);
                      jQuery(this).find("td").eq(3).attr("prodp", discp);
                      Swal.fire({
                        icon: "success",
                        title: "Discount Added",
                        showConfirmButton: true,
                      });
                      var discount =
                        " <li id=" +
                        disc_code +
                        " class='code editable'><span > " +
                        disc_code +
                        '</span><i id="data-code" class="fusion-li-icon fa cross  fa-times-circle" code=' +
                        disc_code +
                        ' title="Remove" onclick="deleteDisc(' +
                        "'" +
                        disc_code +
                        "'" +
                        "," +
                        "'" +
                        "Cart" +
                        "'" +
                        ')"></i></li>';
                      let productDiscount = datas["amount"] * Qty;
                      jQuery("#productDiscount").html("$" + productDiscount);
                      jQuery("#productDiscount ").attr("disc", datas["code"]);
                      jQuery("#productDiscount ").attr("prod", id);
                      jQuery("#disocuntLabels").append(discount);
                      updateTable();
                    }
                  });
              }
              if (
                datas["discount_type"] == "percent" &&
                flag == true &&
                datas["amount"] != 1
              ) {
                jQuery("#cartDiscount ").attr("discP", datas["code"]);

                var cartdisct = jQuery("#cartDiscount").html();
                var productdisct = jQuery("#productDiscount").html();
                var totalAmt = jQuery("#totalPrice").html();
                cartdisct = cartdisct.trim();
                productdisct = productdisct.trim();
                var cartdic = cartdisct.substr(1);
                var prodic = productdisct.substr(1);
                var totalAmt$ = totalAmt.substr(1);
                var percentTotal = (totalAmt$ / 100) * datas["amount"];
                percentTotal = Math.round(percentTotal * 100) / 100;
                var total = apply_coupon_percent_cart(
                  datas["amount"],
                  datas["code"],
                  datas["discount_type"],
                  null
                );

                var discount =
                  " <li id=" +
                  disc_code +
                  " class='code editable'><span > " +
                  disc_code +
                  '</span><i id="data-code" class="fusion-li-icon fa cross  fa-times-circle" code=' +
                  disc_code +
                  ' title="Remove" onclick="deleteDisc(' +
                  "'" +
                  disc_code +
                  "'" +
                  "," +
                  "'" +
                  "Cart" +
                  "'" +
                  ')"></i></li>';
                cartdic = parseFloat(cartdic);
                percentTotal += cartdic;
                jQuery("#cartDiscount").html("$" + percentTotal);
                jQuery("#cartDiscount").attr("Percent", datas["amount"]);
                totalAmt$ = totalAmt$ - (percentTotal.toFixed(2) + prodic);
                if (totalAmt$ < 0) {
                  totalAmt$ = 0;
                }
                jQuery("#totalAmount").html("$" + totalAmt$);
                Swal.fire({
                  icon: "success",
                  title: "Discount Added",
                  showConfirmButton: true,
                });
                jQuery("#disocuntLabels").append(discount);

                updateTable();
              }
            }
          },
        });
      }
    });
  }
}
function getval(val) {
  this.updateTable();
}
function updateTable() {
  var totalPriceSum = 0;
  var totalAmount = 0;
  var totalAmountPartial = 0;
  var cartdisct = jQuery("#cartDiscount").html();
  var productdisct = jQuery("#productDiscount").html();
  var firstPayment = jQuery("#firstPayment").html();
  var secondPayment = jQuery("#secondPayment").html();
  var balanceDue = jQuery("#balanceDue").html();
  cartdisct = cartdisct.trim();
  productdisct = productdisct.trim();
  var cartdic = cartdisct.substr(1);
  var prodic = productdisct.substr(1);
  var firstPayment = firstPayment.substr(1);
  var secondPayment = secondPayment.substr(1);
  var balanceDue = balanceDue.substr(1);
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      var Qty = jQuery(this).find("td").eq(2).html();
      var Price = jQuery(this).find("td").eq(1).html();
      var Partial_Price = jQuery(this).find("td").eq(1).attr("id");
      var Discount = jQuery(this).find("td").eq(3).html();
      var price = Price.substr(1);
      Discount = Discount.substr(1);
      var SalesPrice = Qty * price - Discount;
      var totalPrice = Qty * price;
      if (Partial_Price == undefined) {
        Partial_Price = 0;
      }

      jQuery(this)
        .find("td")
        .eq(4)
        .html("$" + SalesPrice.toFixed(2));

      var totalAmount_partial = Qty * Partial_Price;
      totalPriceSum += totalPrice;
      totalAmountPartial += totalAmount_partial;
    });
  console.log(totalPriceSum);
  console.log(totalPrice);
  jQuery("#totalPrice").html("$" + totalPriceSum);

  totalAmount = totalPriceSum - (parseFloat(cartdic) + parseFloat(prodic));
  console.log(totalAmount);
  firstPayment = totalAmount - totalAmountPartial;
  if (totalAmount < 0) {
    totalAmount = 0;
    firstPayment = 0;
  }
  jQuery("#totalAmount").html("$" + totalAmount);
  jQuery("#firstPayment").html("$" + firstPayment);
  jQuery("#secondPayment").html("$" + totalAmountPartial);
  var status = jQuery("#order_status  option:selected").val();
  var payment_method = jQuery("#payment_method option:selected").val();
  if (status == "wc-pending") {
    jQuery("#balanceDue").html("$" + totalAmount);
    jQuery("#firstPayment").html("$" + 0);
  } else {
    jQuery("#balanceDue").html("$" + 0);
    jQuery("#balanceDue").html("$" + totalAmountPartial);
  }
}

function check_validations() {
  var date = jQuery("#date").val();
  if (date == "") {
    return false;
  }
  var status = jQuery("#order_status  option:selected").val();
  var customer_id = jQuery("#order_user  option:selected").val();
  var firstPayment = jQuery("#firstPayment").html();
  var secondPayment = jQuery("#secondPayment").html();
  var length = jQuery("#productTable tbody tr").length;
  var balanceDue = jQuery("#balanceDue").html();
  var firstPayment = firstPayment.replace("$", "");
  var secondPayment = secondPayment.replace("$", "");
  var balanceDue = balanceDue.replace("$", "");
  if (status == "") {
    return "status";
  } else if (
    (status == "wc-partial-payment" && balanceDue == 0) ||
    (status == "wc-pending" && balanceDue == 0) ||
    status == "" ||
    (status == "wc-completed" && balanceDue != 0)
  ) {
    return "status";
  } else if (customer_id == "") {
    return "no_customer";
  }
  if (length != 0) {
    return true;
  }
}
function check_validations_update() {
  var date = jQuery("#date").val();
  if (date == "") {
    return false;
  }
  var status = jQuery("#order_status  option:selected").val();
  var balanceDue = jQuery("#balanceDue").html();
  var balanceDue = balanceDue.replace("$", "");
  if (status == "wc-partial-payment" && balanceDue == 0) {
    return "status";
  } else {
    return true;
  }
}

jQuery("#Load").on("click", function () {
  var data = new FormData();
  var customer_id = jQuery("#order_user  option:selected").val();
  data.append("customer_id", customer_id);
  var url = currentsiteurl + "/";
  var urlnew =
    url +
    "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=getbilingDetials";
  jQuery.ajax({
    url: urlnew,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: "POST",
    success: function (data) {
      console.log(JSON.parse(data));
      datas = JSON.parse(data.trim());
      console.log(datas);
      jQuery(datas).each(function (index, value) {
        console.log(value["field"]);
        if (
          jQuery("#first_name_label").html() == value["field"].trim() ||
          jQuery("#first_name_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#first_name").val(value["value"]);
        } else if (
          jQuery("#last_name_label").html() == value["field"].trim() ||
          jQuery("#last_name_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#last_name").val(value["value"]);
        } else if (
          jQuery("#company_label").html() == value["field"].trim() ||
          jQuery("#company_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#company").val(value["value"]);
        } else if (
          jQuery("#email_label").html() == value["field"].trim() ||
          jQuery("#email_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#email").val(value["value"]);
        } else if (
          jQuery("#phone_label").html() == value["field"].trim() ||
          jQuery("#phone_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#phone").val(value["value"]);
        } else if (
          jQuery("#address_1_label").html() == value["field"].trim() ||
          jQuery("#address_1_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#address_1").val(value["value"]);
        } else if (
          jQuery("#address_2_label").html() == value["field"].trim() ||
          jQuery("#address_2_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#address_2").val(value["value"]);
        } else if (
          jQuery("#city_label").html() == value["field"].trim() ||
          jQuery("#city_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#city").val(value["value"]);
        } else if (
          jQuery("#state_label").html() == value["field"].trim() ||
          jQuery("#state_label").attr("name") == value["field"].trim()
        ) {
          jQuery("#state").val(value["value"]);
        }
      });
    },
  });
});

function buttonLoad() {
  jQuery("#Load").prop("disabled", false);
}
jQuery("#order_note").bind("input propertychange", function () {
  if (this.value.length) {
    jQuery("#addNote").prop("disabled", false);
  } else {
    jQuery("#addNote").prop("disabled", true);
  }
});
jQuery("#cancel1").click(function () {
  document.location.href = currentsiteurl + "/order-reporting";
});
jQuery("#addNote").click(function () {
  var note = jQuery("textarea#order_note").val();
  var div = '<div class="text1">' + note + "</div>";
  jQuery(".order-hist").append(div);
  jQuery("textarea#order_note").val("");
});

function recalculateDiscount(per_item_discount) {
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      var Qty = jQuery(this).find("td").eq(2).html();
      Qty = parseInt(Qty);
      var disc = jQuery(this).find("td").eq(3).html();
      var Price = jQuery(this).find("td").eq(1).html();
      Price = Price.substr(1);
      disc = parseFloat(disc.substr(1));
      var totalPrice = Price * Qty;
      var discount = per_item_discount * Qty;
      if (discount > totalPrice) {
        var remainder = Math.ceil(discount - totalPrice);
        discount = discount - remainder;
        per_item_discount += remainder;
      }

      jQuery(this)
        .find("td")
        .eq(3)
        .html("$" + discount.toFixed(2));
      console.log(discount);
      jQuery(this).find("td").eq(3).attr("cartp", discount);
    });
}

function deleteDisc(id, type = null) {
  id = id.trim();
  console.log(type);
  console.log(id);
  jQuery("#productDiscount").html("$" + 0);
  jQuery("#cartDiscount").html("$" + 0);
  jQuery("#cartDiscount").attr("disc", "");
  jQuery("#productDiscount").attr("disc", "");
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      jQuery(this)
        .find("td")
        .eq(3)
        .html("$" + 0);
    });
  jQuery("#disocuntLabels li").each(function () {
    console.log(jQuery(this).attr("id"));
    if (id == jQuery(this).attr("id")) {
      jQuery(this).remove();
    }
  });
  updateTable();
}

function apply_coupon_fixed_cart(amount, code, type = "fixed_cart", price) {
  if ((price = null)) {
    price = amount;
  }
  var Qauntity = 0;
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      var Qty = jQuery(this).find("td").eq(2).html();
      Qty = parseInt(Qty);
      var Price = jQuery(this).find("td").eq(1).html();
      Price = Price.substr(1);
      if (Price != 0) {
        Qauntity += Qty;
      }
    });
  var per_item_discount = amount / Qauntity;
  console.log(per_item_discount);
  var totalDiscount = 0;
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      var Qty = jQuery(this).find("td").eq(2).html();
      Qty = parseInt(Qty);
      var disc = jQuery(this).find("td").eq(3).html();
      var Price = jQuery(this).find("td").eq(1).html();
      Price = Price.substr(1);
      disc = disc.substr(1);
      var totalPrice = Price * Qty;
      var discount = per_item_discount * Qty;
      if (discount > totalPrice) {
        var remainder = Math.ceil(discount - totalPrice);
        discount = discount - remainder;
        per_item_discount += remainder;
      }
      jQuery(this)
        .find("td")
        .eq(3)
        .html("$" + discount.toFixed(2));
      console.log(discount);
      if (type == "fixed_cart" || type == "percent") {
        jQuery(this).find("td").eq(3).attr("disc", code);
        jQuery(this).find("td").eq(3).attr("cartp", discount.toFixed(2));
      } else {
        jQuery(this).find("td").eq(3).attr("disp", code);
        jQuery(this).find("td").eq(3).attr("prodp", discount.toFixed(2));
      }
    });
  return totalDiscount;
}
function apply_coupon_percent_cart(amount, code = null, per_item_discount) {
  var totalDiscount = 0;
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      var Qty = jQuery(this).find("td").eq(2).html();
      Qty = parseFloat(Qty);
      var disc = jQuery(this).find("td").eq(3).html();
      var Price = jQuery(this).find("td").eq(1).html();
      Price = Price.substr(1);
      disc = disc.substr(1);
      var totalPrice = Price * Qty;
      var discount = (totalPrice * amount) / 100;
      if (discount > totalPrice) {
        var remainder = Math.ceil(discount - totalPrice);
        discount = discount - remainder;
        per_item_discount += remainder;
      }
      jQuery(this)
        .find("td")
        .eq(3)
        .html("$" + discount.toFixed(2));
      console.log(discount);
      totalDiscount += discount;
      jQuery(this).find("td").eq(3).attr("cartp", discount);
      jQuery(this).find("td").eq(3).attr("disc", code);
    });
  return totalDiscount;
}

function statusChange(status) {
  console.log("assda");
  if (status == "wc-refunded") {
    Swal.fire({
      icon: "error",
      confirmButtonClass: "btn btn-primary",
      title: "Validation Errors",
      text: "You must create a new order!",
    });
    jQuery("#order_status").val("wc-refunded").select2();
  }
}

function update_order(id, status_preset) {
  var status = jQuery("#order_status  option:selected").val();
  var flag = true;
  if (
    status_preset != "cancelled" &&
    (status == "wc-refunded" || status == "wc-cancelled")
  ) {
    flag = false;
    Swal.fire({
      scrollbarPadding: false,

      cancelButtonClass: " btn btn-danger",
      confirmButtonText: "Update",
      confirmButtonClass: " btn btn-primary",
      showCancelButton: true,
      cancelButtonText: "Cancel",
      allowOutsideClick: false,

      html:
        '<div style = "overflow-x: hidden !important">' +
        "<p>Choose what to do with the quantity of all items in this order  </p>" +
        "<br>" +
        '<div style="display: flex;">' +
        '<input type="radio" id="restore-stock-quantity" checked name="stock" value="restore"></input>' +
        "&nbsp" +
        "<p>Restore Stock Quantity</p><br>" +
        "</div>" +
        '<div style="display: flex;">' +
        '<input type="radio" id="leave-stock-quantity" name="stock" value="leave"></input>' +
        "&nbsp" +
        "<p>Leave Stock Quantity the Same</p><br>" +
        "</div>" +
        "</div>",

      didOpen: () => {},
    }).then((result) => {
      if (result.isConfirmed) {
        jQuery("body").css("cursor", "progress");
        var restore_stock = jQuery("input[name='stock']:checked").val();
        var data = new FormData();
        data.append("stock", restore_stock);
        console.log("HELLO");
        var AllNoteArray = [];
        // data.append("orderDate", jQuery("#date").val());
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
        jQuery(".text1").each(function () {
          var dataArray = { note: jQuery(this).html() };
          AllNoteArray.push(dataArray);
        });
        data.append("noteArray", JSON.stringify(AllNoteArray));

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
        data.append(
          "coupon_code_prdt",
          jQuery("#productDiscount ").attr("disc")
        );
        data.append("coupon_code_cart", jQuery("#cartDiscount").attr("disc"));
        data.append("order_id", id);
        var date = jQuery("#date").val();
        var hour = jQuery("#time-hour").val();
        var mint = jQuery("#time-mins").val();
        date = date.toString();
        hour = hour.toString();
        mint = mint.toString();
        date = date + " " + hour + ":" + mint + ":" + "02";
        console.log(date);
        data.append("orderDate", date);

        jQuery("#productTable tbody")
          .find("tr")
          .each(function (index) {
            var ID = jQuery(this).attr("id");
            var Qty = jQuery(this).find("td").eq(2).html();
            var Price = jQuery(this).find("td").eq(1).html();
            Price = Price.substr(1);
            var Partial_Price = jQuery(this).find("td").eq(1).attr("id");
            if (Partial_Price == undefined || Partial_Price == "0") {
              Partial_Price = -200;
            }
            var dataArray = {
              id: ID,
              quantity: Qty,
              price: Price,
              partial_check: Partial_Price,
            };
            AllDataArray.push(dataArray);
          });

        data.append("productArray", JSON.stringify(AllDataArray));
        var url = currentsiteurl + "/";
        var urlnew =
          url +
          "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=updateOrder";
        jQuery.ajax({
          url: urlnew,
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          type: "POST",
          success: function (data) {
            console.log(data);
            if (data.trim() == "success") {
              flag = false;
              jQuery("body").css("cursor", "default");
              Swal.fire({
                icon: "success",
                title: "Your Order has been updated",
                showConfirmButton: true,
                confirmButtonText: "OK",
                confirmButtonClass: " btn btn-primary",
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
            } else {
              Swal.fire("Oops somthing is wrong!!!.");
            }
          },
        });
      }
    });
  }

  if (flag == true) {
    console.log("HELLO");
    var data = new FormData();
    var AllNoteArray = [];
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
    jQuery(".text1").each(function () {
      var dataArray = { note: jQuery(this).html() };
      AllNoteArray.push(dataArray);
    });
    data.append("noteArray", JSON.stringify(AllNoteArray));

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
    data.append("order_id", id);
    var check = check_validations_update();
    if (check == true) {
      jQuery("body").css("cursor", "progress");
      jQuery("#productTable tbody")
        .find("tr")
        .each(function (index) {
          var ID = jQuery(this).attr("id");
          var Qty = jQuery(this).find("td").eq(2).html();
          var Price = jQuery(this).find("td").eq(1).html();
          Price = Price.substr(1);
          var Partial_Price = jQuery(this).find("td").eq(1).attr("id");
          if (Partial_Price == undefined || Partial_Price == "0") {
            Partial_Price = -200;
          }
          var dataArray = {
            id: ID,
            quantity: Qty,
            price: Price,
            partial_check: Partial_Price,
          };
          AllDataArray.push(dataArray);
        });

      data.append("productArray", JSON.stringify(AllDataArray));
      var url = currentsiteurl + "/";
      var urlnew =
        url +
        "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=updateOrder";
      jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        success: function (data) {
          console.log(data);
          if (data.trim() == "success") {
            jQuery("body").css("cursor", "default");
            Swal.fire({
              icon: "success",
              title: "Your Order has been updated",
              showConfirmButton: true,
              confirmButtonText: "OK",
              confirmButtonClass: " btn btn-primary",
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
          } else {
            Swal.fire("Oops somthing is wrong!!!.");
          }
        },
      });
    } else if (check == "status") {
      Swal.fire({
        icon: "error",
        confirmButtonClass: " btn btn-primary",
        title: "Validation Errors",
        text: "Kindly select the status correctly!",
      });
    } else {
      Swal.fire({
        icon: "error",
        confirmButtonClass: " btn btn-primary",
        title: "Validation Errors",
        text: "Kindly select the date correctly!",
      });
    }
  }
  // } else if (check == undefined) {
  //   Swal.fire({
  //     icon: "error",

  //     title: "Product not added!",
  //     text: "Please add one item!",
  //   });
  // } else if (check == "status") {
  //   Swal.fire({
  //     icon: "error",
  //     confirmButtonClass: " btn btn-primary",
  //     title: "Validation Errors",
  //     text: "Kindly select the status correctly!",
  //   });
  // } else if (check == "no_customer") {
  //   Swal.fire({
  //     icon: "error",
  //     confirmButtonClass: " btn btn-primary",
  //     title: "Validation Errors",
  //     text: "Kindly select the User!",
  //   });
  // }
}
function delete_order(e) {
  var data = new FormData();

  var order_id = jQuery(e).attr("order_id");

  Swal.fire({
    title: "Are you sure?",
    icon: "warning",
    scrollbarPadding: false,

    cancelButtonClass: " btn btn-danger",
    confirmButtonText: "Next",
    confirmButtonClass: " btn btn-primary",
    showCancelButton: true,
    cancelButtonText: "Cancel",
    allowOutsideClick: false,

    html:
      '<div style = "overflow-x: hidden !important">' +
      "<p>You cannot undo this action. You will be asked the next screen what to do about the stock quantity for all the products in this order upon delete</p>" +
      "</div>",

    didOpen: () => {},

    preConfirm: function () {
      Swal.fire({
        scrollbarPadding: false,

        cancelButtonClass: " btn btn-danger",
        confirmButtonText: "Delete",
        confirmButtonClass: " btn btn-primary",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        allowOutsideClick: false,

        html:
          '<div style = "overflow-x: hidden !important">' +
          "<p>Choose what to do with the quantity of all items in this order  </p>" +
          "<br>" +
          '<div style="display: flex;">' +
          '<input type="radio" id="restore-stock-quantity" checked name="stock" value="restore"></input>' +
          "&nbsp" +
          "<p>Restore Stock Quantity</p><br>" +
          "</div>" +
          '<div style="display: flex;">' +
          '<input type="radio" id="leave-stock-quantity" name="stock" value="leave"></input>' +
          "&nbsp" +
          "<p>Leave Stock Quantity the Same</p><br>" +
          "</div>" +
          "</div>",

        didOpen: () => {},
      }).then((result) => {
        if (result.isConfirmed) {
          var restore_stock = jQuery("input[name='stock']:checked").val();

          // var leave_stock = jQuery("input[name='stock']:checked").val();
          console.log("Restore Stock: " + restore_stock);

          // console.log("Leave Stock: " + leave_stock);
          data.append("order_id", order_id);
          data.append("stock", restore_stock);

          var url = currentsiteurl + "/";
          var urlnew =
            url +
            "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=deleteOrder";
          jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            success: function (data) {
              console.log(data);
              if (data.trim() == "success") {
                Swal.fire({
                  icon: "success",
                  title: "Your Order has been deleted",
                  showConfirmButton: true,
                  confirmButtonText: "OK",
                  confirmButtonClass: " btn btn-primary",
                }).then((result) => {
                  if (result.isConfirmed) {
                    document.location.href =
                      currentsiteurl + "/order-reporting/";
                  }
                });
              } else {
                // Swal.fire("Oops somthing is wrong!!!.");
              }
            },
          });
        }
      });
    },
  });
}
function refund_order(id, total) {
  Swal.fire({
    didOpen: () => {},

    title: "Refund Order",
    scrollbarPadding: false,

    cancelButtonClass: " btn btn-danger",
    confirmButtonText: "Refund",
    confirmButtonClass: " btn btn-primary",
    showCancelButton: true,
    cancelButtonText: "Cancel",
    allowOutsideClick: false,
    html:
      '<div style = "overflow-x: hidden !important">' +
      "<p>Choose what to do with the quantity of all items in this order  </p>" +
      "<br>" +
      '<div style="display: flex;    justify-content: space-between;">' +
      "<p>Restore Stock Quantity</p>" +
      "<span>" +
      '<input type="checkbox" id="restore" checked value="0"></input>' +
      "</span>" +
      "</div>" +
      '<div style="display: flex;    justify-content: space-between;">' +
      "<p>Amount already refunded</p>" +
      "<span>" +
      "$" +
      0 +
      "</span>" +
      "</div>" +
      '<div  style="display: flex;    justify-content: space-between;">' +
      "<p>Total available to refund</p>" +
      "<span>" +
      "$" +
      total +
      "</span>" +
      "</div>" +
      '<div id="amount_div" style="display: flex;    justify-content: space-between;">' +
      "<p>Refund Amount</p>" +
      "<span>" +
      '<input type="text" id="refunded-amount" class="form-control"></input>' +
      "</span>" +
      "</div>" +
      '<div style="display: flex;    justify-content: space-between;">' +
      "<p>Reason for refund(optionol)</p>" +
      "<span>" +
      '<input class="form-control" type="text" id="refunded-reason"></input>' +
      "</span>" +
      "</div>" +
      "</div>",

    preConfirm: function () {
      var amount = jQuery("#refunded-amount").val();
      if (amount > total) {
        Swal.showValidationMessage("Invalid Amount");
      }
    },
  }).then((result) => {
    if (result.isConfirmed) {
      var data = new FormData();
      var amount = jQuery("#refunded-amount").val();
      var reason = jQuery("#refunded-reason").val();
      var restore_check = jQuery("#restore:checked").val();

      data.append("ID", id);
      data.append("amount", amount);
      data.append("reason", reason);
      data.append("check", restore_check);

      var url = currentsiteurl + "/";
      var urlnew =
        url +
        "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=refundOrder";
      jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        success: function (data) {
          console.log(data);
          if (data.trim() == "success") {
            Swal.fire({
              icon: "success",
              title: "Order has been refunded",
              showConfirmButton: true,
            }).then(() => {
              location.reload();
            });
          } else if ("Order has been already refunded" == data.trim()) {
            Swal.fire({
              icon: "error",
              title: "Opps..",
              text: "Order has been already refunded.",
              showConfirmButton: true,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Opps..",
              text: "Invalid refund amount.",
              showConfirmButton: true,
            });
          }
        },
      });
    }
  });
}

// jQuery("#order_status").on("change", function () {
//   var selected = jQuery(this).val();

//   if (selected == "wc-refunded") {
//     Swal.fire({
//       icon: "error",
//       confirmButtonClass: "btn btn-primary",
//       title: "Validation Errors",
//       text: "You must create a new order!",
//     });
//     jQuery(this).val("wc-refunded").select2();
//   }
// });
