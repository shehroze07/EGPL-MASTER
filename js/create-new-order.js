var productArray = [];
function create_order() {
  var data = new FormData();
  var AllDataArray = [];
  var AllNoteArray = [];
  var curdate = new Date();
  var today = new Date();
  var date =
    today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate();
  var time =
    today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
  currentime = date.toString() + " " + time.toString();
  jQuery(".text").each(function () {
    var fieldID = jQuery(this).attr("id");
    if (jQuery(this).val() != null) {
      if (fieldID == "payment_date") {
        var times = jQuery(this).val().toString() + " " + time.toString();
        data.append(fieldID, times);
      } else {
        data.append(fieldID, jQuery(this).val());
      }
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
  jQuery(".option2").each(function () {
    var dataArray = { note: jQuery("textarea#order_note").val() };
    AllNoteArray.push(dataArray);
  });
  data.append("noteArray", JSON.stringify(AllNoteArray));

  data.append("timezone", currentime);
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
        var Name = jQuery(this).find("td").eq(0).html();
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
          Name: Name,
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
  } else if (check == false) {
    Swal.fire({
      icon: "error",

      title: "No Product Added",
      text: "Please add at least one product.",
    });
  } else if (check == "status") {
    var status = jQuery("#order_status  option:selected").val();
    if (status == "wc-partial-payment") {
      Swal.fire({
        icon: "error",
        confirmButtonClass: " btn btn-primary",
        title: "Oops",
        text: "The Status cannot be Initial Deposit Paid if there are no partial payment items in your order!",
      });
    } else {
      Swal.fire({
        icon: "error",
        confirmButtonClass: " btn btn-primary",
        title: "Validation Error",
        text: "Kindly select the status correctly!",
      });
    }
  } else if (check == "no_customer") {
    Swal.fire({
      icon: "error",
      confirmButtonClass: " btn btn-primary",
      title: "Validation Error",
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
  let flag = true;
  jQuery("#selectPackages option").each(function () {
    let id = jQuery(this).attr("value");
    let partial = jQuery(this).attr("partial");
    if (
      packageSelected == id &&
      (partial == "optional" || partial == "forced")
    ) {
      flag = false;
      var partialDiv =
        '<div class="partial-div" id="partial-divP" style=" position: absolute;margin-top: 41px;justify-content: space-between"> <input type="radio" id="full" checked name="paymentP" value=full"><label for="full">Paid Full</label><input type="radio" id="partial" name="paymentP" value="partial"><label for="partial">Paid Partial</label><br> </div>';

      jQuery("#packages-div").append(partialDiv);
      //jQuery("#add-ons-div ").csss('display',flex);
      jQuery("#package-quantity").css("width", 63);
      jQuery("#add-ons-div").css("margin-top", 12);
    } else if (flag != false) {
      // jQuery("#package-quantity").css("width", 211);

      jQuery("#partial-divP").hide();
    }
  });
}
function AddChange() {
  var AddonsSelected = jQuery("#selectAddOns option:selected").val();
  let flag = true;
  jQuery("#selectAddOns option").each(function () {
    let id = jQuery(this).attr("value");
    let partial = jQuery(this).attr("partial");
    if (
      AddonsSelected == id &&
      (partial == "optional" || partial == "forced")
    ) {
      flag = false;
      var partialDiv =
        '<div class="partial-div" style=" position: absolute;margin-top: 41px;justify-content: space-between" id="partial-divA"><input type="radio" id="full" checked name="paymentA" value=full"><label for="full">Paid Full</label><input type="radio" id="partial" name="paymentA" value="partial"><label for="partial">Paid Partial</label><br> </div>';
      jQuery("#add-ons-div ").append(partialDiv);
      //jQuery("#add-ons-div ").csss('display',flex);
      jQuery("#add-ons-quantity").css("width", 63);
    } else if (flag != false) {
      // jQuery("#add-ons-quantity").css("width", 211);
      jQuery("#partial-divA").hide();
    }
  });
}
function boothChange() {
  var boothSelected = jQuery("#selectBooths option:selected").val();
  let flag = true;
  jQuery("#selectBooths option").each(function () {
    let id = jQuery(this).attr("value");
    let partial = jQuery(this).attr("partial");
    if (boothSelected == id && (partial == "optional" || partial == "forced")) {
      flag = false;
      var partialDiv =
        '<div class="partial-div" id="partial-divB" style=" margin-top: 11px;justify-content: space-between"><input type="radio" id="full" checked name="paymentB" value=full"><label for="full">Paid Full</label><input type="radio" id="partial" name="paymentB" value="partial"><label for="partial">Paid Partial</label><br> </div>';
      jQuery("#booth-div ").append(partialDiv);
    } else if (flag != false) {
      jQuery("#partial-divB").hide();
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
      title: "No User Selected",
      text: "Please add a User to this order.",
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

        productArray = dataArray;
        jQuery("body").css("cursor", "default");
        var dropdown_Packages =
          '<select  name="getallPackages" onchange="packgChange()" id="selectPackages"  aria-invalid="false" class="js-example-basic-single packa js-states form-control form-control" required><option style="color" value="" hidden >Select Package</option>';
        var dropdown_AddOns =
          '<select  name="getallAddOns" onchange="AddChange()" id="selectAddOns"   aria-invalid="false" class="js-example-basic-single adda js-states form-control form-control" required><option style="color" value="" hidden >Select AddOns</option>';
        var dropdown_Booths =
          '<select  name="getallBooths" id="selectBooths" onchange="boothChange()"   aria-invalid="false" class="js-example-basic-single botha  js-states form-control form-control" required><option style="color" value="" hidden >Select Booth</option>';

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
              '" stock="' +
              obj["stock"] +
              '">' +
              obj["title"] +
              "&nbsp" +
              "Stock-" +
              obj["stock"];
            ("</option>");
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
              '"stock="' +
              obj["stock"] +
              '" title="' +
              obj["title"] +
              '">' +
              obj["title"] +
              "&nbsp" +
              "Stock-" +
              obj["stock"];
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
              '"stock="' +
              obj["stock"] +
              '" title="' +
              obj["title"] +
              '">' +
              obj["title"] +
              "&nbsp" +
              "Stock-" +
              obj["stock"];
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
          cancelButtonClass: " btn btn-danger",
          showCancelButton: true,
          showConfirmButton: false,
          cancelButtonText: "Close",
          allowOutsideClick: false,
          html:
            '<div style = "overflow-x: hidden !important;">' +
            '<div class="row"> ' +
            '<div class="col-sm-8"><h5>Product</h5>' +
            '<div id="select_Package_div"><label style="text-align: left;" class="">Package</label>' +
            dropdown_Packages +
            "</div>" +
            '<div id="select_AddOns_div"><label   style="text-align: left;" class="">Add-Ons</label>' +
            dropdown_AddOns +
            "</div>" +
            '<div id="select_Booth_div"><label  style="text-align: left;" class="">Booths</label>' +
            dropdown_Booths +
            "</div>" +
            "</div>" +
            '<div class="col-sm-4"><h5>Quantity</h5>' +
            '<div id="packages-div" min="0" class="quantity quantity-ip" style="display: flex;"><label class=""></label>' +
            '<input type="number" min="1" id="package-quantity" class="form-control" value="1" style="display: flex;    width: 66px;">' +
            '<button type="button" style="margin-left: 5px;" onclick="packageadd()" class="btn btn-primary btn-sm">Add</button>' +
            "</div>" +
            '<div id="add-ons-div"  class="quantity quantity-ip" style="display: flex;    margin-top: 11px;"><label class="" ></label>' +
            '<input type="number" min="1" id="add-ons-quantity" class="form-control " value="1" style="display: flex;width: 66px;">' +
            '<button type="button" style="margin-left: 5px;" onclick="addOneadd()" class="btn btn-primary btn-sm">Add</button>' +
            "</div>" +
            '<div id="booth-div" style="margin-top: 15px;" min="1" class="quantity-ip quantity">' +
            '<button type="button" onclick="boothAdd()" style="margin-left: 71px;" class="btn btn-primary btn-sm">Add</button>' +
            "</div>" +
            "</div>",

          preConfirm: function () {},
        }).then((result) => {
          if (result.isConfirmed) {
          }
        });
      },
    });
  }
}
function packageadd() {
  var packageSelected = jQuery("#selectPackages option:selected").val();
  var packageQuantity = jQuery("#package-quantity").val();
  var partialPackge = jQuery("input[name='paymentP']:checked").val();

  for (let obj of productArray) {
    if (packageSelected && packageSelected == obj["id"]) {
      let stock_check = checkstockstatus(obj, packageQuantity);
      if (stock_check == true) {
        checks = checkQuantity(obj, packageQuantity);
      }
      if (stock_check == true) {
        if (checks == undefined) {
          jQuery("#partial-divP").remove();
          jQuery("#selectPackages").val("").select2();
          jQuery("#package-quantity").val(1);
          var div =
            '<div id="message_div_booth" style="padding: 11px;background: #9df09b;"><span>Product Added!</span></div>';
          jQuery("#swal2-html-container").append(div);
          setTimeout(() => {
            jQuery("#message_div_booth").remove();
          }, 1500);
        } else {
          // packageQuantity = arrayQuantityP[counterP];
          var itemName = obj["title"].replaceAll('"', "");
          var price = obj["price"];
          var price_partialP = 0;
          if (partialPackge == "partial") {
            if (obj["type"] == "fixed") {
              price_partialP = obj["price"] - obj["deposit_amount"];
              if (price_partialP % 1 != 0) {
                price_partialP = parseFloat(price_partialP.toFixed(1));
              }
            } else {
              price_partialP = (obj["price"] / 100) * obj["deposit_amount"];
              price_partialP = price - price_partialP;
              if (price_partialP % 1 != 0) {
                price_partialP = parseFloat(price_partialP.toFixed(1));
              }
            }
          }
          var name = obj["title"];
          name = name.replace(/\s/g, "");
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
            '"' +
            name +
            '"' +
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

          jQuery("#partial-divP").remove();
          jQuery("#selectPackages").val("").select2();
          jQuery("#package-quantity").val(1);
          var div =
            '<div id="message_div_booth" style="padding: 11px;background: #9df09b;"><span>Product Added!</span></div>';
          jQuery("#swal2-html-container").append(div);
          setTimeout(() => {
            jQuery("#message_div_booth").remove();
          }, 1500);
        }
      } else {
        var div =
          '<div id="message_div_booth" style="padding: 11px;background: #fa424a;    color: white !important;"><span>Product Not Added!</span></div>';
        jQuery("#swal2-html-container").append(div);
        setTimeout(() => {
          jQuery("#message_div_booth").remove();
        }, 1500);
      }
    }
  }
  updateTable();
}
function addOneadd() {
  var AddonsSelected = jQuery("#selectAddOns option:selected").val();
  var AddOnsQuantity = jQuery("#add-ons-quantity").val();
  var partialAddons = jQuery("input[name='paymentA']:checked").val();
  for (let obj of productArray) {
    if (AddonsSelected && AddonsSelected == obj["id"]) {
      let stock_check = checkstockstatus(obj, AddOnsQuantity);
      if (stock_check == true) {
        checks = checkQuantity(obj, AddOnsQuantity);
      }
      if (stock_check == true) {
        if (checks == undefined) {
          jQuery("#partial-divA").remove();
          jQuery("#selectAddOns").val("").select2();
          jQuery("#add-ons-quantity").val(1);
          var div =
            '<div id="message_div_addon" style="padding: 11px;background: #9df09b;"><span>Product Added!</span></div>';
          jQuery("#swal2-html-container").append(div);
          setTimeout(() => {
            jQuery("#message_div_addon").remove();
          }, 1500);
        } else {
          var price = obj["price"];
          var price_partialA = 0;
          if (partialAddons == "partial") {
            if (obj["type"] == "fixed") {
              price_partialA = obj["price"] - obj["deposit_amount"];
              if (price_partialA % 1 != 0) {
                price_partialA = parseFloat(price_partialA.toFixed(1));
              }
            } else {
              price_partialA = (obj["price"] / 100) * obj["deposit_amount"];
              price_partialA = price - price_partialA;
              if (price_partialA % 1 != 0) {
                price_partialA = parseFloat(price_partialA.toFixed(1));
              }
            }
          }
          var name = obj["title"];
          name = name.replace(/\s/g, "");
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
            name +
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
          jQuery("#partial-divA").remove();
          jQuery("#selectAddOns").val("").select2();
          jQuery("#add-ons-quantity").val(1);
          var div =
            '<div id="message_div_addon" style="padding: 11px;background: #9df09b;"><span>Product Added!</span></div>';
          jQuery("#swal2-html-container").append(div);
          setTimeout(() => {
            jQuery("#message_div_addon").remove();
          }, 1500);
        }
      } else {
        var div =
          '<div id="message_div_addon" style="padding: 11px;background: #fa424a;    color: white !important;"><span>Product Not Added!</span></div>';
        jQuery("#swal2-html-container").append(div);
        setTimeout(() => {
          jQuery("#message_div_addon").remove();
        }, 1500);
      }
    }
  }
  updateTable();
}
function boothAdd() {
  var boothSelected = jQuery("#selectBooths option:selected").val();
  var partialBooth = jQuery("input[name='paymentB']:checked").val();
  var floorplanstatuslockunlock = "";
  var url = currentsiteurl + "/";
  var urlnew =
    url +
    "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=getFloorplanstatus";
  jQuery.ajax({
    url: urlnew,
    cache: false,
    contentType: false,
    processData: false,
    type: "POST",
    success: function (data) {
      console.log(data);
      floorplanstatuslockunlock = data.trim();

      if (floorplanstatuslockunlock == "unlock") {
        for (let obj of productArray) {
          if (boothSelected && boothSelected == obj["id"]) {
            let both_check = bothOnceCheck(obj);
            if (both_check == undefined) {
              jQuery("#partial-divB").remove();
              jQuery("#selectBooths").val("").select2();
              var div =
                '<div id="message_div_booths" style="padding: 11px;background: #fa424a;    color: white !important;"><span>Product Not Added!</span></div>';
              jQuery("#swal2-html-container").append(div);
              setTimeout(() => {
                jQuery("#message_div_booths").remove();
              }, 1500);
            } else {
              var price = obj["price"];
              var price_partialB = 0;
              if (partialBooth == "partial") {
                if (obj["type"] == "fixed") {
                  price_partialB = obj["price"] - obj["deposit_amount"];
                  if (price_partialB % 1 != 0) {
                    price_partialB = parseFloat(price_partialB.toFixed(1));
                  }
                } else {
                  price_partialB = (obj["price"] / 100) * obj["deposit_amount"];
                  price_partialB = price - price_partialB;
                  if (price_partialB % 1 != 0) {
                    price_partialB = parseFloat(price_partialB.toFixed(1));
                  }
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
                '</td><td id="packageDiscount">' +
                0 +
                "</td><td >" +
                price +
                '</td><td>-</td><td><span ><i class=" fusion-li-icon fa fas fa-times-circle fa-2x" title="Remove"onclick="deleteProduct(' +
                obj["id"] +
                ')"></i></span></td></tr>';
              jQuery("#productTable tbody").append(appendProduct);
              jQuery("#partial-divB").remove();
              jQuery("#selectBooths").val("").select2();
              var div =
                '<div id="message_div_booths" style="padding: 11px;background: #9df09b;"><span>Product Added!</span></div>';
              jQuery("#swal2-html-container").append(div);
              setTimeout(() => {
                jQuery("#message_div_booths").remove();
              }, 1500);
            }
          }
        }
      } else {
        var div =
          '<div id="message_div_booths" style="padding: 11px;background: #fa424a;    color: white !important;"><span>Floorplan is currently being edited, please try again later.</span></div>';
        jQuery("#swal2-html-container").append(div);
        setTimeout(() => {
          jQuery("#message_div_booths").remove();
        }, 1500);
      }
      updateTable();
    },
  });
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
      '<div ><label  class="" id="pro_name">' +
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
  var length = jQuery("#productTable tbody tr").length;
  if (length == 0) {
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

      jQuery(this).remove();
    });
    jQuery("#productDiscount ").attr("disc", "");
    jQuery("#cartDiscount").attr("disc", "");
    jQuery("#cartDiscount").html("$" + 0);
    jQuery("#productDiscount").html("$" + 0);
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
    var url = currentsiteurl + "/";
    var urlnew =
      url +
      "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=getCoupons";
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
        var dropdown_PackagesD =
          '<select  name="getallDPackages"  id="selectDPackages"  aria-invalid="false" class="js-example-basic-single" js-states form-control form-control" required><option style="color" value="" hidden >Select Cart Discount</option>';
        var dropdown_AddOnsD =
          '<select  name="getallDAddOns"  id="selectDAddOns"   aria-invalid="false" class="js-example-basic-single" js-states form-control form-control" required><option style="color" value="" hidden >Select Percentage Discount</option>';
        var dropdown_BoothsD =
          '<select  name="getallDBooths" id="selectDBooths"   aria-invalid="false" class="js-example-basic-single" js-states form-control form-control" required><option style="color" value="" hidden >Select Product Discount</option>';

        for (let obj of productArray) {
          if (obj["discount_type"] == "fixed_cart") {
            dropdown_PackagesD +=
              '<option value="' +
              obj["code"] +
              '">' +
              obj["code"] +
              "</option>";
          } else if (obj["discount_type"] == "percent") {
            dropdown_AddOnsD +=
              '<option value="' +
              obj["code"] +
              '">' +
              obj["code"] +
              "</option>";
          } else if (obj["discount_type"] == "fixed_product") {
            dropdown_BoothsD +=
              '<option value="' +
              obj["code"] +
              '">' +
              obj["code"] +
              "</option>";
          }
        }
        dropdown_PackagesD += "</select>";
        dropdown_AddOnsD += "</select>";
        dropdown_BoothsD += "</select>";
        Swal.fire({
          didOpen: () => {
            jQuery(".js-example-basic-single").select2({});
          },

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
            '<div class="col-sm-12" >' +
            "<label style='text-align: left;' >Fixed Cart Discount</label>" +
            dropdown_PackagesD +
            "</div>" +
            '<div class="col-sm-12" >' +
            "<label  style='text-align: left;' >Percentage</label>" +
            dropdown_AddOnsD +
            "</div>" +
            '<div class="col-sm-12" >' +
            "<label  style='text-align: left;'>Fixed Product </label>" +
            dropdown_BoothsD +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>",

          preConfirm: function () {},
        }).then((result) => {
          if (result.isConfirmed) {
            var data = new FormData();
            var packageSelectedD = jQuery(
              "#selectDPackages option:selected"
            ).val();
            var addOnsSelectedD = jQuery(
              "#selectDAddOns option:selected"
            ).val();
            var boothSelectedD = jQuery("#selectDBooths option:selected").val();
            if (packageSelectedD != "") {
              data.append("code", packageSelectedD);
            } else if (addOnsSelectedD != "") {
              data.append("code", addOnsSelectedD);
            } else {
              data.append("code", boothSelectedD);
            }

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
                var disc_code = datas["code"];
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
                      var Partial_Price = jQuery(this)
                        .find("td")
                        .eq(1)
                        .attr("id");
                      if (Partial_Price == undefined) {
                        Partial_Price = "0";
                      }
                      if (Partial_Price !== "0") {
                        flag = false;

                        Swal.fire({
                          icon: "error",
                          title: "Oops...",
                          text: "Cannot apply discount on partial payments",
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
                      text: "You can only add one discount per order",
                      showConfirmButton: true,
                    });
                    flag = false;
                  }
                  if (
                    (packageSelectedD != "" && addOnsSelectedD != "") ||
                    (packageSelectedD != "" && boothSelectedD != "") ||
                    (boothSelectedD != "" && addOnsSelectedD != "")
                  ) {
                    Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "You can only add one discount per order",
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
                          jQuery.inArray(parseInt(id), datas["product_ids"]) !==
                          -1
                        ) {
                          var disc = jQuery(this).find("td").eq(3).html();
                          disc = disc.substr(1);
                          var Qty = jQuery(this).find("td").eq(2).html();
                          disc =
                            parseFloat(disc) +
                            parseFloat(datas["amount"]) * Qty;
                          var discp = datas["amount"] * Qty;
                          if (Price < disc) {
                            disc = Price;
                            discp = Price;
                          }
                          jQuery(this)
                            .find("td")
                            .eq(3)
                            .html("$" + disc);
                          jQuery(this)
                            .find("td")
                            .eq(3)
                            .attr("disp", datas["code"]);
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
                          jQuery("#productDiscount").html(
                            "$" + productDiscount
                          );
                          jQuery("#productDiscount ").attr(
                            "disc",
                            datas["code"]
                          );
                          jQuery("#productDiscount ").attr("prod", id);
                          jQuery("#disocuntLabels").append(discount);
                          updateTable();
                        } else {
                          Swal.fire({
                            icon: "error",
                            confirmButtonClass: " btn btn-primary",
                            title: "Validation Error",
                            text: "None of the products in this order match this discount code",
                          });
                        }
                      });
                  }
                  if (
                    datas["discount_type"] == "percent" &&
                    flag == true &&
                    datas["amount"] != 1
                  ) {
                    jQuery("#cartDiscount ").attr("disc", datas["code"]);

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
      },
    });
  }
}
function getval(val) {
  let status = jQuery("#order_status").val();
  if (status == "wc-pending-deposit") {
    jQuery("#payment_method").removeAttr("required");
  } else {
    jQuery("#payment_method").attr("required", true);
  }
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
    jQuery("#secondPayment").html("$" + 0);
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
  } else if (length != 0) {
    return true;
  } else if (length == 0) {
    return false;
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
}
function check_validationsForUpdate() {
  var date = jQuery("#date").val();
  let deposit = false;
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
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      let deposit_check = jQuery(this).find("td").eq(1).attr("deposit_check");
      if (deposit_check != "no" && deposit_check != "") {
        deposit = true;
      }
    });
  if (status == "") {
    return "status";
  } else if (status == "wc-partial-payment" && deposit == false) {
    return "status";
  } else if (customer_id == "") {
    return "no_customer";
  }
  if (length != 0) {
    return true;
  }
}
// function check_validations() {
//   var date = jQuery("#date").val();
//   if (date == "") {
//     return false;
//   }
//   var status = jQuery("#order_status  option:selected").val();
//   var balanceDue = jQuery("#balanceDue").html();
//   var balanceDue = balanceDue.replace("$", "");
//   if (status == "wc-partial-payment" && balanceDue == 0) {
//     return true;
//   } else {
//     return true;
//   }
// }

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
          jQuery("#first_name_label").attr("name") == value["field"].trim() ||
          jQuery("#first_name_label").attr("name") == value["field"].trim() ||
          value["field"].trim() == "first_name"
        ) {
          jQuery("#first_name").val(value["value"]);
        } else if (
          jQuery("#last_name_label").html() == value["field"].trim() ||
          jQuery("#last_name_label").attr("name") == value["field"].trim() ||
          jQuery("#last_name_label").attr("name") == value["field"].trim() ||
          value["field"].trim() == "last_name"
        ) {
          jQuery("#last_name").val(value["value"]);
        } else if (
          jQuery("#company_label").html() == value["field"].trim() ||
          jQuery("#company_label").attr("name") == value["field"].trim() ||
          jQuery("#company_label").attr("name") == value["field"].trim() ||
          value["field"].trim() == "company_name"
        ) {
          jQuery("#company").val(value["value"]);
        } else if (
          jQuery("#email_label").html() == value["field"].trim() ||
          jQuery("#email_label").attr("name") == value["field"].trim() ||
          jQuery("#email_label").attr("name") == value["field"].trim() ||
          value["field"].trim() == "Semail"
        ) {
          jQuery("#email").val(value["value"]);
        } else if (
          jQuery("#phone_label").html() == value["field"].trim() ||
          jQuery("#phone_label").attr("name") == value["field"].trim() ||
          jQuery("#phone_label").attr("name2") == value["field"].trim() ||
          jQuery("#phone_label").attr("name3") == value["field"].trim() ||
          jQuery("#phone_label").attr("name1") == value["field"].trim() ||
          value["field"].trim() == "user_phone_1"
        ) {
          jQuery("#phone").val(value["value"]);
        } else if (
          jQuery("#address_1_label").html() == value["field"].trim() ||
          jQuery("#address_1_label").attr("name") == value["field"].trim() ||
          jQuery("#address_1_label").attr("name") == value["field"].trim() ||
          value["field"].trim() == "customefield_address_line_1_j3n0v"
        ) {
          jQuery("#address_1").val(value["value"]);
        } else if (
          jQuery("#address_2_label").html() == value["field"].trim() ||
          jQuery("#address_2_label").attr("name") == value["field"].trim() ||
          jQuery("#address_2_label").attr("name") == value["field"].trim() ||
          value["field"].trim() == "customefield_address_line_2_fep16"
        ) {
          jQuery("#address_2").val(value["value"]);
        } else if (
          jQuery("#city_label").html() == value["field"].trim() ||
          jQuery("#city_label").attr("name") == value["field"].trim() ||
          value["field"].trim() == "customefield_city_dgvsl"
        ) {
          jQuery("#city").val(value["value"]);
        } else if (
          jQuery("#state_label").html() == value["field"].trim() ||
          jQuery("#state_label").attr("name") == value["field"].trim() ||
          jQuery("#state_label").attr("name1") == value["field"].trim() ||
          value["field"].trim() == "customefield_state_fdxjg"
        ) {
          jQuery("#state").val(value["value"]);
        } else if (
          jQuery("#postcode_label").html() == value["field"].trim() ||
          jQuery("#postcode_label").attr("name") == value["field"].trim() ||
          jQuery("#postcode_label").attr("name2") == value["field"].trim() ||
          jQuery("#postcode_label").attr("name3") == value["field"].trim() ||
          jQuery("#postcode_label").attr("name1") == value["field"].trim() ||
          value["field"].trim() == "customefield_zip_code_pfua8"
        ) {
          jQuery("#postcode").val(value["value"]);
        } else if (
          jQuery("#Country_label").html() == value["field"].trim() ||
          jQuery("#Country_label").attr("name") == value["field"].trim() ||
          jQuery("#Country_label").attr("name1") == value["field"].trim()
        ) {
          jQuery("#region").val(value["value"]).select2();
        }
      });
    },
  });
});

function buttonLoad() {
  jQuery("#Load").prop("disabled", false);
}
jQuery(".orders_note").bind("input propertychange", function () {
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
  var div = "<p class='text1' style='display:none;'>" + note + "</p>";
  // var div_new = '<div class="text1">' + note + "</div>";
  jQuery(".order-hist").append(div);
  jQuery("textarea#order_note").val(note);
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

function statusChange(status, value) {
  let refunded = "Refunded";
  let balance = "Balance Due";
  console.log(jQuery("#order_status").val());
  let val = jQuery("#order_status").val();
  if (val == "wc-partial-payment") {
    val = "Initial Deposit Paid";
  } else if (val == "wc-completed") {
    val = "Paid in Full";
  } else if (val == "wc-pending-deposit") {
    val = "Balance Due";
  } else if (val == "wc-cancelled") {
    val = "Cancelled";
  } else if (val == "wc-refunded") {
    val = "Refunded";
  } else if (val == "wc-failed") {
    val = "Failed";
  }
  console.log("assda");
  if (status == "wc-refunded") {
    Swal.fire({
      icon: "error",
      confirmButtonClass: "btn btn-primary",
      title: "Validation Error",
      text:
        "You cannot update this order from " +
        refunded +
        " to " +
        val +
        ". You must create a new order",
    });
    jQuery("#order_status").val("wc-refunded").select2();
  } else if (status == "wc-pending-deposit" && val == "Refunded") {
    Swal.fire({
      icon: "error",
      confirmButtonClass: "btn btn-primary",
      title: "Validation Error",
      text: "You cannot update this order from " + balance + " to " + val,
    });
    jQuery("#order_status").val("wc-pending-deposit").select2();
  }
  if (status == "wc-pending-deposit") {
    jQuery("#payment_method").removeAttr("required");
  }
}
function emailchange() {
  var status = jQuery("#emailinvoice  option:selected").val();
  if (status != 0) {
    jQuery("#sendEmail").prop("disabled", false);
  } else {
    jQuery("#sendEmail").prop("disabled", true);
  }
}

jQuery("#sendEmail").click(function () {
  Swal.fire({
    title: "Are you sure?",
    icon: "warning",
    scrollbarPadding: false,

    cancelButtonClass: " btn btn-danger",
    confirmButtonText: "Send",
    confirmButtonClass: " btn btn-primary",
    showCancelButton: true,
    cancelButtonText: "Cancel",
    allowOutsideClick: false,

    html:
      '<div style = "overflow-x: hidden !important">' +
      "<p>By clicking Send you will immediately send this user an email of the order details (if paid in full) or invoice (if a balance due) with a link to make the remaining payment</p>" +
      "</div>",

    didOpen: () => {},
  }).then((result) => {
    if (result.isConfirmed) {
      var data = new FormData();
      var order_id = jQuery("#date").attr("order_id");
      data.append("order_id", order_id);
      var url = currentsiteurl + "/";
      var urlnew =
        url +
        "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=sendEmail";
      jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        success: function (data) {
          if ((data = "success")) {
            Swal.fire({
              icon: "success",
              title: "Email has been sent",
              showConfirmButton: true,
              confirmButtonText: "OK",
              confirmButtonClass: " btn btn-primary",
            });
          }
        },
      });
    }
  });
});

function update_order(id, status_preset, initial_id) {
  var status = jQuery("#order_status  option:selected").val();
  var prev_status = jQuery("#prev_status").attr("value");
  var flag = true;
  if (
    status_preset != "cancelled" &&
    (status == "wc-refunded" || status == "wc-cancelled") &&
    initial_id == undefined
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
        var curdate = new Date();
        var today = new Date();
        var date =
          today.getFullYear() +
          "-" +
          (today.getMonth() + 1) +
          "-" +
          today.getDate();
        var time =
          today.getHours() +
          ":" +
          today.getMinutes() +
          ":" +
          today.getSeconds();
        // data.append("orderDate", jQuery("#date").val());
        var AllDataArray = [];
        jQuery(".text").each(function () {
          var fieldID = jQuery(this).attr("id");
          if (jQuery(this).val() != null) {
            console.log(fieldID + "=" + jQuery(this).val());
            // console.log(jQuery(this).val());
            if (fieldID == "payment_date") {
              var times = jQuery(this).val().toString() + " " + time.toString();
              data.append(fieldID, times);
            } else {
              data.append(fieldID, jQuery(this).val());
            }
          } else {
            data.append(fieldID, "");
          }
        });
        jQuery(".option2").each(function () {
          var dataArray = { note: jQuery("textarea#order_note").val() };
          AllNoteArray.push(dataArray);
        });
        data.append("noteArray", JSON.stringify(AllNoteArray));
        data.append("orderStatus", prev_status);

        var usertimezone = curdate.getTimezoneOffset() / 60;
        currentime = date.toString() + " " + time.toString();
        data.append("usertimezone", usertimezone);
        data.append("timezone", currentime);
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
            var Name = jQuery(this).find("td").eq(0).html();
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
              Name: Name,
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
              Swal.fire("Oops something is wrong!!!.");
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
    // data.append("orderDate", jQuery("#date").val());
    var AllDataArray = [];
    var curdate = new Date();
    var today = new Date();
    var date =
      today.getFullYear() +
      "-" +
      (today.getMonth() + 1) +
      "-" +
      today.getDate();
    var time =
      today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    jQuery(".text").each(function () {
      var fieldID = jQuery(this).attr("id");
      if (jQuery(this).val() != null) {
        console.log(fieldID + "=" + jQuery(this).val());
        // console.log(jQuery(this).val());
        if (fieldID == "payment_date") {
          var times = jQuery(this).val().toString() + " " + time.toString();
          data.append(fieldID, times);
        } else {
          data.append(fieldID, jQuery(this).val());
        }
      } else {
        data.append(fieldID, "");
      }
    });
    jQuery(".option2").each(function () {
      var dataArray = { note: jQuery("textarea#order_note").val() };
      AllNoteArray.push(dataArray);
    });
    data.append("noteArray", JSON.stringify(AllNoteArray));
    data.append("orderStatus", prev_status);

    var usertimezone = curdate.getTimezoneOffset() / 60;
    currentime = date.toString() + " " + time.toString();
    data.append("usertimezone", usertimezone);
    data.append("timezone", currentime);
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
    var date = jQuery("#date").val();
    var hour = jQuery("#time-hour").val();
    var mint = jQuery("#time-mins").val();
    date = date.toString();
    hour = hour.toString();
    mint = mint.toString();
    date = date + " " + hour + ":" + mint + ":" + "02";
    console.log(date);
    data.append("orderDate", date);
    var check = check_validationsForUpdate();
    if (check == true) {
      jQuery("body").css("cursor", "progress");
      jQuery("#productTable tbody")
        .find("tr")
        .each(function (index) {
          var ID = jQuery(this).attr("id");
          var Qty = jQuery(this).find("td").eq(2).html();
          var Price = jQuery(this).find("td").eq(1).html();
          var Name = jQuery(this).find("td").eq(0).html();
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
            Name: Name,
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
            Swal.fire("Oops something is wrong!!!.");
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
      var status = jQuery("#order_status  option:selected").val();
      if (status == "wc-partial-payment") {
        Swal.fire({
          icon: "error",
          confirmButtonClass: " btn btn-primary",
          title: "Oops",
          text: "The Status cannot be Initial Deposit Paid if there are no partial payment items in your order!",
        });
      } else {
        Swal.fire({
          icon: "error",
          confirmButtonClass: " btn btn-primary",
          title: "Validation Error",
          text: "Kindly select the status correctly!",
        });
      }
    } else if (check == "no_customer") {
      Swal.fire({
        icon: "error",
        confirmButtonClass: " btn btn-primary",
        title: "Validation Error",
        text: "Kindly select the User!",
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
  //     title: "Validation Error",
  //     text: "Kindly select the status correctly!",
  //   });
  // } else if (check == "no_customer") {
  //   Swal.fire({
  //     icon: "error",
  //     confirmButtonClass: " btn btn-primary",
  //     title: "Validation Error",
  //     text: "Kindly select the User!",
  //   });
  // }
}
function delete_order(order_id, custome_id) {
  var data = new FormData();

  // var order_id = jQuery(e).attr("order_id");
  if (custome_id == undefined) {
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
        "<p>You cannot undo this action. You will be asked in the next screen what to do about the stock quantity for all  products in this order upon delete</p>" +
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
                  // Swal.fire("Oops something is wrong!!!.");
                }
              },
            });
          }
        });
      },
    });
  } else {
    Swal.fire({
      title: "Are you sure?",
      icon: "warning",
      scrollbarPadding: false,

      cancelButtonClass: " btn btn-danger",
      confirmButtonText: "Delete",
      confirmButtonClass: " btn btn-primary",
      showCancelButton: true,
      cancelButtonText: "Cancel",
      allowOutsideClick: false,

      didOpen: () => {},
    }).then((result) => {
      if (result.isConfirmed) {
        // var restore_stock = jQuery("input[name='stock']:checked").val();

        // var leave_stock = jQuery("input[name='stock']:checked").val();
        // console.log("Restore Stock: " + restore_stock);

        // console.log("Leave Stock: " + leave_stock);
        data.append("order_id", order_id);
        data.append("stock", "leave");

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
                  document.location.href = currentsiteurl + "/order-reporting/";
                }
              });
            } else {
              // Swal.fire("Oops something is wrong!!!.");
            }
          },
        });
      }
    });
  }
}
function refund_order(id, total, refunded_Amt) {
  Swal.fire({
    didOpen: () => {
      if (refunded_Amt > 0) {
        jQuery("#restock-div").empty();
      }
    },

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
      '<div id="restock-div" style="display: flex;    justify-content: space-between;">' +
      "<p>Restore Stock Quantity</p>" +
      "<span>" +
      '<input type="checkbox" id="restore" checked value="0"></input>' +
      "</span>" +
      "</div>" +
      '<div style="display: flex;    justify-content: space-between;">' +
      "<p>Amount already refunded</p>" +
      "<span>" +
      "$" +
      refunded_Amt +
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
      "<p>Reason for refund(optional)</p>" +
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
      var restore_check = 1;
      if (refunded_Amt > 0) {
        restore_check = 1;
      } else {
        restore_check = jQuery("#restore:checked").val();
      }
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
function checkQuantity(obj, newQty) {
  var flag = true;
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      if (obj["id"] == jQuery(this).attr("id")) {
        flag = false;
        var Qty = jQuery(this).find("td").eq(2).html();
        Qty = parseInt(Qty) + parseInt(newQty);
        jQuery(this).find("td").eq(2).html(Qty);
        jQuery(this).find("td").eq(5).html("");
        jQuery(this).find("td").eq(6).html("");
        var tabelData =
          '<span ><i class="fusion-li-icon fa fa-pencil-square fas fa-2x" title="Edit"  onclick=editProduct(' +
          obj["id"] +
          "," +
          "'" +
          obj["title"] +
          "'" +
          "," +
          Qty +
          "," +
          obj["stock"] +
          ")></i></span>";
        var deleteBtn =
          '<span><i class="fusion-li-icon fa fas  fa-times-circle fa-2x" title="Remove" onclick="deleteProduct(' +
          obj["id"] +
          "," +
          Qty +
          ')"></i></span>';
        jQuery(this).find("td").eq(5).append(tabelData);
        jQuery(this).find("td").eq(6).append(deleteBtn);
        return "Not Added";
      }
    });
  if (flag) {
    return false;
  }
}
function checkstockstatus(obj, newQty) {
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      if (obj["id"] == jQuery(this).attr("id")) {
        var Qty = jQuery(this).find("td").eq(2).html();
        newQty = parseInt(Qty) + parseInt(newQty);
      }
    });
  if (parseInt(obj["stock"]) >= parseInt(newQty)) {
    return true;
  } else {
    return false;
  }
}
function bothOnceCheck(obj) {
  var flag = true;
  jQuery("#productTable tbody")
    .find("tr")
    .each(function () {
      if (obj["id"] == jQuery(this).attr("id")) {
        flag = false;
        return "Yes";
      }
    });
  if (flag) {
    return false;
  }
}

function OrderHistory($id, $history_id, order_id) {
  jQuery("body").css("cursor", "progress");
  var data = new FormData();
  var url = currentsiteurl + "/";
  var urlnew =
    url +
    "wp-content/plugins/EGPL/order-manage-egpl.php?orderManagerRequest=order_hisotry_id";
  data.append("order_hisotry_id", $id);
  data.append("history_log_id", $history_id);
  data.append("order_id", order_id);
  jQuery.ajax({
    url: urlnew,
    cache: false,
    data: data,
    contentType: false,
    processData: false,
    type: "POST",
    success: function (data) {
      datas = JSON.parse(data);
      console.log(data);
      if (datas) {
        var tablehtml = "";

        tablehtml =
          '<table class="table table-striped table-bordered table-condensed" width="100%"><tbody>';
        if (datas.length == 1) {
          datas = datas[0];
          jQuery.each(datas, function (i, l) {
            if (
              i !== "noteArray" &&
              i !== "orderDate" &&
              i !== "orderStatus" &&
              i !== "order_id" &&
              i !== "date" &&
              i !== "timezone" &&
              i !== "usertimezone"
            ) {
              if (i == "first_name") {
                i = "First Name";
              } else if (i == "last_name") {
                i = "Last Name";
              } else if (i == "payment_date") {
                i = "Payment Date";
              } else if (i == "order_status") {
                i = "Order Status";
                if (l == "wc-completed") {
                  l = "Paid in Full";
                } else if (l == "wc-partial-payment") {
                  l = "Initial Deposit Paid";
                } else if (l == "wc-pending-deposit") {
                  l = "Balance Due";
                } else if (l == "wc-cancelled") {
                  l = "Cancelled";
                } else if (l == "wc-refunded") {
                  l = "Refunded";
                } else if (l == "wc-failed") {
                  l = "Failed";
                }
              } else if (i == "payment_method") {
                i = "Payment Method";
                if (l == "cheque") {
                  l = "Check";
                } else if (l == "stripe") {
                  l = "Credit Card";
                }
              } else if (i == "customer_id") {
                i = "Customer ID";
              } else if (i == "coupon_code_prdt") {
                i = "Product Discount Applied";
              } else if (i == "date") {
                i = "Order Date";
              } else if (i == "state") {
                i = "State";
              } else if (i == "postcode") {
                i = "Post Code";
              } else if (i == "productArray") {
                i = "Line Items";
                // l = JSON.parse(l);
                var div = "<div id='productDiv'><label> Name</label>";
                var divQ =
                  "<div id='productDiv' style='margin-left: 13px;'><label> Quantity</label>";
                l.forEach((element) => {
                  div +=
                    "<p style='    margin: 0px !important;'> " +
                    element.Name +
                    " </p>";
                  divQ +=
                    "<p style='    margin: 0px !important;'> " +
                    element.quantity +
                    " </p>";
                });
                div += "</div>";
                divQ += "</div>";
              } else if (i == "coupon_code_cart") {
                i = "Cart Discount Applied";
              } else if (i == "company") {
                i = "Company";
              } else if (i == "region") {
                i = "Region";
              } else if (i == "city") {
                i = "City";
              } else if (i == "address_1") {
                i = "Address 1";
              } else if (i == "address_2") {
                i = "Address 2";
              } else if (i == "phone") {
                i = "Phone";
              } else if (i == "email") {
                i = "Email";
              } else if (i == "Transaction_ID") {
                i = "Transaction ID";
              } else if (i == "emailinvoice") {
                i = "Order details sent to customer";
                if (l == "0") {
                  l = "Order Details not sent";
                } else {
                  l = "Order Details  sent";
                }
              } else if (i == "stock") {
                i = "Restock Stock";
                if (l == "restore") {
                  l = "Yes";
                } else {
                  l = "No";
                }
              }
              if (i == "Line Items") {
                tablehtml +=
                  '<tr><td style="text-align:right;width:50%;"><b>' +
                  i +
                  '</b></td><td style="width:50%;"><div style="display: flex;">' +
                  div +
                  divQ;
                ("</div></td></tr>");
              } else {
                tablehtml +=
                  '<tr><td style="text-align:right;width:50%;"><b>' +
                  i +
                  '</b></td><td style="width:50%;">' +
                  l +
                  "</td></tr>";
              }
            }
          });
        } else {
          let result;
          currentHistory = datas[0];
          previousHistory = datas[1];
          jQuery.each(currentHistory, function (i, l) {
            if (
              i !== "noteArray" &&
              i !== "orderDate" &&
              i !== "orderStatus" &&
              i !== "order_id" &&
              i !== "date" &&
              i !== "timezone" &&
              i !== "usertimezone"
            ) {
              if (i == "first_name") {
                result = l.localeCompare(previousHistory[i]);
                i = "First Name";
              } else if (i == "last_name") {
                result = l.localeCompare(previousHistory[i]);
                i = "Last Name";
              } else if (i == "payment_date") {
                result = l.localeCompare(previousHistory[i]);
                i = "Payment Date";
              } else if (i == "order_status") {
                result = l.localeCompare(previousHistory[i]);
                i = "Order Status";
                if (l == "wc-completed") {
                  l = "Paid in Full";
                } else if (l == "wc-partial-payment") {
                  l = "Initial Deposit Paid";
                } else if (l == "wc-pending-deposit") {
                  l = "Balance Due";
                } else if (l == "wc-cancelled") {
                  l = "Cancelled";
                } else if (l == "wc-refunded") {
                  l = "Refunded";
                } else if (l == "wc-failed") {
                  l = "Failed";
                }
              } else if (i == "payment_method") {
                result = l.localeCompare(previousHistory[i]);
                i = "Payment Method";
                if (l == "cheque") {
                  l = "Check";
                } else if (l == "stripe") {
                  l = "Credit Card";
                }
              } else if (i == "customer_id") {
                result = l.localeCompare(previousHistory[i]);
                i = "Customer ID";
              } else if (i == "coupon_code_prdt") {
                result = l.localeCompare(previousHistory[i]);
                i = "Product Discount Applied";
              } else if (i == "date") {
                result = l.localeCompare(previousHistory[i]);
                i = "Order Date";
              } else if (i == "state") {
                result = l.localeCompare(previousHistory[i]);
                i = "State";
              } else if (i == "postcode") {
                result = l.localeCompare(previousHistory[i]);
                i = "Post Code";
              } else if (i == "productArray") {
                // result = l.localeCompare(previousHistory[i]);
                l1 = previousHistory[i];
                i = "Line Items";
                // l = JSON.parse(l);
                var div = "<div id='productDiv'><label> Product Name</label>";
                var divQ =
                  "<div id='productDiv' style='margin-left: 13px;'><label> Qty</label>";
                l.forEach((element) => {
                  l1.forEach((element1) => {
                    if (element.id == element1.id) {
                      result = 0;
                    }
                  });
                  if (result != 0) {
                    div +=
                      "<p style='    margin: 0px !important;color: red;'> " +
                      element.Name +
                      " </p>";
                    divQ +=
                      "<p style='    margin: 0px !important;color: red;'> " +
                      element.quantity +
                      " </p>";
                  } else {
                    div +=
                      "<p style='    margin: 0px !important;'> " +
                      element.Name +
                      " </p>";
                    divQ +=
                      "<p style='    margin: 0px !important;'> " +
                      element.quantity +
                      " </p>";
                  }

                  result = 1;
                });
                div += "</div>";
                divQ += "</div>";
              } else if (i == "coupon_code_cart") {
                result = l.localeCompare(previousHistory[i]);
                i = "Cart Discount Applied";
              } else if (i == "company") {
                result = l.localeCompare(previousHistory[i]);
                i = "Company";
              } else if (i == "region") {
                result = l.localeCompare(previousHistory[i]);
                i = "Region";
              } else if (i == "city") {
                result = l.localeCompare(previousHistory[i]);
                i = "City";
              } else if (i == "address_1") {
                result = l.localeCompare(previousHistory[i]);
                i = "Address 1";
              } else if (i == "address_2") {
                result = l.localeCompare(previousHistory[i]);
                i = "Address 2";
              } else if (i == "phone") {
                result = l.localeCompare(previousHistory[i]);
                i = "Phone";
              } else if (i == "email") {
                result = l.localeCompare(previousHistory[i]);
                i = "Email";
              } else if (i == "Transaction_ID") {
                result = l.localeCompare(previousHistory[i]);
                i = "Transaction ID";
              } else if (i == "emailinvoice") {
                result = l.localeCompare(previousHistory[i]);
                i = "Order details sent to customer";
                if (l == "0") {
                  l = "Order Details not sent";
                } else {
                  l = "Order Details  sent";
                }
              } else if (i == "stock") {
                result = l.localeCompare(previousHistory[i]);
                i = "Restock Stock";
                if (l == "restore") {
                  result = l.localeCompare(previousHistory[i]);
                  l = "Yes";
                } else {
                  result = l.localeCompare(previousHistory[i]);
                  l = "No";
                }
              }
              if (i == "Line Items") {
                tablehtml +=
                  '<tr><td style="text-align:right;width:50%;"><b>' +
                  i +
                  '</b></td><td style="width:50%;"><div style="display: flex;">' +
                  div +
                  divQ;
                ("</div></td></tr>");
              } else {
                if (result != 0) {
                  tablehtml +=
                    '<tr><td style="text-align:right;width:50%;"><b>' +
                    i +
                    '</b></td><td style="width:50%;color: red;">' +
                    l +
                    "</td></tr>";
                } else {
                  tablehtml +=
                    '<tr><td style="text-align:right;width:50%;"><b>' +
                    i +
                    '</b></td><td style="width:50%;">' +
                    l +
                    "</td></tr>";
                }
              }
              result = "";
            }
          });
        }

        tablehtml += "</tbody></table>";
        //console.log(tablehtml);

        jQuery.confirm({
          title: '<p style="text-align:center;">Order History</p>',
          content: tablehtml,
          confirmButtonClass: "mycustomwidth",
          cancelButtonClass: "customeclasshide",
          animation: "rotateY",
          closeIcon: true,
          columnClass: "jconfirm-box-container-special",
        });
      }
      jQuery("body").css("cursor", "default");
    },
  });
}
// jQuery("#order_status").on("change", function () {
//   var selected = jQuery(this).val();

//   if (selected == "wc-refunded") {
//     Swal.fire({
//       icon: "error",
//       confirmButtonClass: "btn btn-primary",
//       title: "Validation Error",
//       text: "You must create a new order!",
//     });
//     jQuery(this).val("wc-refunded").select2();
//   }
// });
