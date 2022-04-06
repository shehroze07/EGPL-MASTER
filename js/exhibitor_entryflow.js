function exhibitor_entry_flow_settings_update() {
  var packge = jQuery("#packages-status :checked").val();
  console.log(packge);
  var packge1 = jQuery("#floor-plan-status :checked").val();
  console.log(packge1);
  var packge2 = jQuery("#add-ons-status :checked").val();
  console.log(packge2);
  var exhibitor_check = jQuery("#exhibitorentryflow:checked").val();
  console.log(exhibitor_check);
  //code by AD//
  if (exhibitor_check != undefined) {
    if (
      jQuery("#packages-status").prop("checked") ||
      jQuery("#floor-plan-status").prop("checked") ||
      jQuery("#add-ons-status").prop("checked")
    ) {
      swal(
        {
          title: "Are you sure?",
          text: "Updating this area may affect your users engaged in this process in real time. It is recommended to only make changes before your portal is live, or if it is live, to notify your users ahead of time.",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-success",
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          closeOnConfirm: true,
          closeOnCancel: true,
        },
        function (isConfirm) {
          if (isConfirm) {
            var Sname = exhibitor_entry_flow_settings_update_run();
          } else {
          }
        }
      );
    } else {
      //   if (jQuery("#exhibitorentryflow").prop("checked")) {
      swal({
        title: "Warning",
        text: "You must enable at least a Package, Booth, or Add-On in the entry wizard, or your users will get stuck. If you do not wish to have your users guided through a purchasing process, you can simply disable the wizard entirely.",
        type: "warning",
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Ok",
      });
      //   } else {
      //     swal({
      //       title: "Warning",
      //       text: "You must enable at least a Package, Booth, or Add-On in the entry wizard, or your users will get stuck. If you do not wish to have your users guided through a purchasing process, you can simply disable the wizard entirely.",
      //       type: "warning",
      //       confirmButtonClass: "btn-warning",
      //       confirmButtonText: "Ok",
      //     });
      //   }
    }
  }else if (exhibitor_check == undefined){
    swal(
        {
          title: "Are you sure?",
          text: "Updating this area may affect your users engaged in this process in real time. It is recommended to only make changes before your portal is live, or if it is live, to notify your users ahead of time.",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-success",
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          closeOnConfirm: true,
          closeOnCancel: true,
        },
        function (isConfirm) {
          if (isConfirm) {
            var Sname = exhibitor_entry_flow_settings_update_run();
          } else {
          }
        }
      );
  }
}

function exhibitor_entry_flow_settings_update_run() {
  jQuery("body").css({ cursor: "wait" });
  var taskdataupdate = {};
  var data = new FormData();
  var flowshowstatus = "";

  if (jQuery("#applicationmoderationstatus").is(":checked")) {
    data.append("applicationmoderationstatus", "checked");
  } else {
    data.append("applicationmoderationstatus", "");
  }

  if (jQuery("#exhibitorentryflow").prop("checked")) {
    jQuery(".saveeverything").each(function (index) {
      var taskid = jQuery(this).attr("id");
     console.log(taskid);
      var singletaskarray = {};
      singletaskarray["name"] = jQuery("#title-" + taskid).val();
      singletaskarray["url"] = jQuery("#url-" + taskid).val();
      singletaskarray["slug"] = jQuery("#slug-" + taskid).val();
      singletaskarray["status"] = jQuery("#status-" + taskid).val();
      singletaskarray["description"] = jQuery("#description-" + taskid).val();
      singletaskarray["icon"] = jQuery("#icon-" + taskid).val();
      if (jQuery("#" + taskid + "-status").is(":checked")) {
        console.log(taskid);
        singletaskarray["statusactive"] = true;
        taskdataupdate[taskid] = singletaskarray;
      } else {
        singletaskarray["statusactive"] = false;
      }

      flowshowstatus = "checked";
    });
  } else {
    flowshowstatus = "";
  }

  var url = currentsiteurl + "/";
  var urlnew =
    url +
    "wp-content/plugins/EGPL/exhibitorentryflow.php?exhibitorflowrequest=saveallflowsettings";

  data.append("exhibitorsavedata", JSON.stringify(taskdataupdate));
  data.append("flowshowstatus", flowshowstatus);

  jQuery.ajax({
    url: urlnew,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: "POST",
    success: function (data) {
      jQuery("body").css("cursor", "default");

      swal(
        {
          title: "Updated!",
          text: "All changes saved successfully",
          type: "success",
          confirmButtonClass: "btn-success",
        },
        function (isConfirm) {
          jQuery("body").css({ cursor: "wait" });
          localStorage.setItem('activeTab', "#tabs-1-tab-2");
          location.reload();
          //document.location.href = currentsiteurl+'/dashboard'
        }
      );
    },
    error: function (xhr, ajaxOptions, thrownError) {
      swal({
        title: "Error",
        text: "There was an error during the requested operation. Please try again.",
        type: "error",
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Ok",
      });
    },
  });
}
