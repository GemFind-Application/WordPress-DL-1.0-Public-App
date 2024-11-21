jQuery(document).ready(function () {
  jQuery("#search-diamonds-form #submit").trigger("click");
  jQuery(".loading-mask.gemfind-loading-mask").css("display", "none");
});

function gemfindDT_formSubmit(e, url, id) {
  if (id == "form-drop-hint") {
    var action = "gemfindDT_resultdrophint";
  } else if (id == "form-email-friend") {
    var action = "gemfindDT_resultemailfriend";
  } else if (id == "form-request-info") {
    var action = "gemfindDT_resultreqinfo1";
  } else if (id == "form-schedule-view") {
    var action = "gemfindDT_resultscheview";
  }
  var options = {
    type: "popup",
    responsive: true,
    innerScroll: true,
    modalClass: "custom-modal",
    buttons: [],
    opened: function ($Event) {
      jQuery(".modal-footer").hide();
    },
  };

  var dataFormHint = jQuery("#" + id);

  dataFormHint.validate({
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        emailcustom: true,
      },
      recipient_name: {
        required: true,
      },
      recipient_email: {
        required: true,
        emailcustom: true,
      },
      gift_reason: {
        required: true,
      },
      hint_message: {
        required: true,
      },
      friend_name: {
        required: true,
      },
      friend_email: {
        required: true,
        emailcustom: true,
      },
      message: {
        required: true,
      },
      gift_deadline: {
        required: true,
      },
      phone: {
        required: true,
        phoneno: true,
      },
      location: {
        required: true,
      },
      avail_date: {
        required: true,
      },
      appnt_time: {
        required: true,
      },
      contact_pref: {
        required: true,
      },
    },
    messages: {
      gift_deadline: "Select the Gift Deadline.",
      avail_date: "Select your availability.",
      contact_pref: "Please select one of the options.",
    },
    errorPlacement: function (error, element) {
      if (element.is(":radio")) {
        error.appendTo(element.parents(".pref_container"));
      } else {
        // This is the default behavior
        error.insertAfter(element);
      }
    },
    submitHandler: function (form) {
      jQuery.ajax({
        type: "POST",
        url: url,
        data: { action: action, form_data: jQuery("#" + id).serializeArray() },
        dataType: "json",
        beforeSend: function (settings) {
          jQuery(".loading-mask.gemfind-loading-mask").css("display", "block");
        },
        success: function (response) {
          console.log(response);
          if (response.output.status == 1) {
            console.log("email send");
            var parId = jQuery("#" + id)
              .parent()
              .attr("id");
            jQuery(".loading-mask.gemfind-loading-mask").css("display", "none");
            jQuery("#popup-modal .note").html(response.output.msg);
            jQuery("#popup-modal .note").css("display", "block");
            jQuery("#popup-modal .note").css("color", "green");
            jQuery("#popup-modal").modal("show");
            jQuery("#popup-modal").on("hidden.bs.modal", function () {
              console.log("close modal");
              jQuery(".cancel.preference-btn").click();
            });
            setTimeout(function () {
              jQuery("#" + parId + " .note").html("");
              jQuery("#" + parId + " .note").css("display", "none");
              jQuery("#" + parId + " .note").css("background", "#fff");
            }, 5000);
          } else {
            console.log("some error");
            var parId = jQuery("#" + id)
              .parent()
              .attr("id");
            //jQuery('#' + parId + ' .note').html(response.output.msg);
            jQuery("#popup-modal .note").html(response.output.msg);
            jQuery("#popup-modal .modal-title").html("Error");
            jQuery(".loading-mask.gemfind-loading-mask").css("display", "none");
            jQuery("#popup-modal .note").css("display", "block");
            jQuery("#popup-modal .note").css("color", "red");
            jQuery("#popup-modal .note").css("background", "#f7c6c6");
            jQuery("#popup-modal").modal("show");
            jQuery("#popup-modal").on("hidden.bs.modal", function () {
              console.log("close modal");
              jQuery(".cancel.preference-btn").click();
            });
            setTimeout(function () {
              jQuery("#" + parId + " .note").html("");
              jQuery("#" + parId + " .note").css("display", "none");
              jQuery("#" + parId + " .note").css("background", "#fff");
            }, 5000);
          }
          document.getElementById(id).reset();
          return true;
        },
      });
    },
  });
  jQuery.validator.addMethod(
    "emailcustom",
    function (value, element) {
      return (
        this.optional(element) ||
        /^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/i.test(value)
      );
    },
    "Please enter valid email address"
  );

  jQuery.validator.addMethod(
    "phoneno",
    function (phone_number, element) {
      phone_number = phone_number.replace(/\s+/g, "");
      return (
        this.optional(element) ||
        (phone_number.length > 9 &&
          phone_number.match(
            /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/
          ))
      );
    },
    "<br />Please specify a valid phone number"
  );
}
function gemfindDT_CallSpecification() {
  document.getElementById("diamond-data").style.display = "none";
  document.getElementById("diamond-specification").style.display = "block";
}

function gemfindDT_CallDiamondDetail() {
  document.getElementById("diamond-data").style.display = "block";
  document.getElementById("diamond-content-data").style.display = "block";
  document.getElementById("diamond-specification").style.display = "none";
  var el1 = document.getElementById("drop-hint-main");
  if (el1) {
    el1.style.display = "none";
    document.getElementById("form-drop-hint").reset();
  }
  var el2 = document.getElementById("email-friend-main");
  if (el2) {
    el2.style.display = "none";
    document.getElementById("form-email-friend").reset();
  }
  var el3 = document.getElementById("req-info-main");
  if (el3) {
    el3.style.display = "none";
    document.getElementById("form-request-info").reset();
  }
  var el4 = document.getElementById("schedule-view-main");
  if (el4) {
    el4.style.display = "none";
    document.getElementById("form-schedule-view").reset();
  }
}

function gemfindDT_CallShowform(e) {
  console.log("CallShowform");

  document.getElementById("diamond-specification").style.display = "none";
  var el1 = document.getElementById("drop-hint-main");
  if (el1) {
    el1.style.display = "none";
    document.getElementById("form-drop-hint").reset();
  }
  var el2 = document.getElementById("email-friend-main");
  if (el2) {
    el2.style.display = "none";
    document.getElementById("form-email-friend").reset();
  }
  var el3 = document.getElementById("req-info-main");
  if (el3) {
    el3.style.display = "none";
    document.getElementById("form-request-info").reset();
  }
  var el4 = document.getElementById("schedule-view-main");
  if (el4) {
    el4.style.display = "none";
    document.getElementById("form-schedule-view").reset();
  }
  document.getElementById("diamond-content-data").style.display = "none";
  var x = e.target.getAttribute("data-target");
  document.getElementById(x).style.display = "block";

  jQuery("#gift_deadline").datepicker({ minDate: 0 });
  //jQuery('#avail_date').datepicker({minDate: 0});

  function gemfindDT_appoitmentTime(start, stop) {
    times = "";
    stopAMPM = stop;
    interval = 30;
    start = start.split(":");
    starth = parseInt(start[0]);
    startm = parseInt(start[1]) ? parseInt(start[1]) : "0";
    stop = stop.split(":");
    stopAMPM = stopAMPM.slice(-2);
    stoph =
      stopAMPM.trim() === "PM" && stop[0] != "12" && stop[0] != "12 PM"
        ? +parseInt(stop[0].replace(":", "")) + +12
        : parseInt(stop[0]);
    stopm = parseInt(stop[1]) ? parseInt(stop[1]) : "0";
    size = stoph > starth ? stoph - starth + 1 : starth - stoph + 1;
    hours = [...Array(size).keys()].map((i) => i + starth);
    option = "";
    for (hour in hours) {
      for (min = startm; min < 60; min += interval) {
        startm = 0;
        if (hours.slice(-1)[0] === hours[hour] && min > stopm) {
          break;
        }
        if (hours[hour] > 11 && hours[hour] !== 24) {
          times =
            ("0" + (hours[hour] % 12 === 0 ? "12" : hours[hour] % 12)).slice(
              -2
            ) +
            ":" +
            ("0" + min).slice(-2) +
            " " +
            "PM";
        } else {
          times =
            ("0" + (hours[hour] % 12 === 0 ? "12" : hours[hour] % 12)).slice(
              -2
            ) +
            ":" +
            ("0" + min).slice(-2) +
            " " +
            "AM";
        }
        option += "<option value='" + times + "'>" + times + "</option>";
      }
    }
    return option;
  }

  jQuery("#avail_date").datepicker({
    minDate: 0,
    onSelect: function (dateText) {
      var curDate = jQuery(this).datepicker("getDate");
      var dayName = jQuery.datepicker.formatDate("DD", curDate);
      if (jQuery(".timing_days").length) {
        var timingDays = jQuery.parseJSON(jQuery(".timing_days.active").html());
        var dayId;
        if (dayName == "Sunday") {
          dayId = 0;
        } else if (dayName == "Monday") {
          dayId = 1;
        } else if (dayName == "Tuesday") {
          dayId = 2;
        } else if (dayName == "Wednesday") {
          dayId = 3;
        } else if (dayName == "Thursday") {
          dayId = 4;
        } else if (dayName == "Friday") {
          dayId = 5;
        } else {
          dayId = 6;
        }
        jQuery.each(timingDays, function (index, value) {
          if (dayId == index) {
            var key = Object.keys(value);
            if (index == 0) {
              option = gemfindDT_appoitmentTime(
                value.sundayStart,
                value.sundayEnd
              );
            } else if (index == 1) {
              option = gemfindDT_appoitmentTime(
                value.mondayStart,
                value.mondayEnd
              );
            } else if (index == 2) {
              option = gemfindDT_appoitmentTime(
                value.tuesdayStart,
                value.tuesdayEnd
              );
            } else if (index == 3) {
              option = gemfindDT_appoitmentTime(
                value.wednesdayStart,
                value.wednesdayEnd
              );
            } else if (index == 4) {
              option = gemfindDT_appoitmentTime(
                value.thursdayStart,
                value.thursdayEnd
              );
            } else if (index == 5) {
              option = gemfindDT_appoitmentTime(
                value.fridayStart,
                value.fridayEnd
              );
            } else if (index == 6) {
              option = gemfindDT_appoitmentTime(
                value.saturdayStart,
                value.saturdayEnd
              );
            }
            jQuery("#appnt_time").html(option);
          }
        });
        jQuery("#appnt_time").show();
      } else {
        jQuery(".timing_not_avail").show();
        jQuery(".book-slots").prop("disabled", true);
      }
    },
    beforeShowDay: function (d) {
      var day = d.getDay();
      var closeDay = [];
      var myarray = [];
      var timingDays = jQuery.parseJSON(jQuery(".timing_days.active").html());
      jQuery.each(timingDays, function (index, value) {
        var key = Object.keys(value);
        if (index == 0) {
          if (value.sundayStart == "" || value.sundayEnd == "") {
            closeDay.push(index);
          }
        } else if (index == 1) {
          if (value.mondayStart == "" || value.mondayEnd == "") {
            closeDay.push(index);
          }
        } else if (index == 2) {
          if (value.tuesdayStart == "" || value.tuesdayEnd == "") {
            closeDay.push(index);
          }
        } else if (index == 3) {
          if (value.wednesdayStart == "" || value.wednesdayEnd == "") {
            closeDay.push(index);
          }
        } else if (index == 4) {
          if (value.thursdayStart == "" || value.thursdayEnd == "") {
            closeDay.push(index);
          }
        } else if (index == 5) {
          if (value.fridayStart == "" || value.fridayEnd == "") {
            closeDay.push(index);
          }
        } else if (index == 6) {
          if (value.saturdayStart == "" || value.saturdayEnd == "") {
            closeDay.push(index);
          }
        }
      });
      jQuery(".day_status_arr").each(function () {
        myarray.push(jQuery(this).html());
        closeDay.push(parseInt(jQuery(this).html()));
      });
      if (jQuery.inArray(day, closeDay) != -1) {
        return [false, "closed", "Closed on Monday"];
        //return [ true, "", "" ];
      } else {
        return [true, "", ""];
      }
    },
  });

  jQuery("#schview_loc").on("change", function (e) {
    $locationid = jQuery(this).find(":selected").attr("data-locationid");
    if (jQuery("#avail_date").val() != "") {
      jQuery("#avail_date").datepicker("setDate", null);
    }
    jQuery(".timing_days").removeClass("active");
    jQuery(".timing_days").each(function (index) {
      if (jQuery(this).attr("data-location") == $locationid) {
        jQuery(this).addClass("active");
        return false;
      }
    });
  });
}

// function Videorun(e){
//     document.getElementById("diamondimg").style.display = "none";
//     document.getElementById("diamondvideo").style.display = "block";
//     document.getElementById('iframevideo').setAttribute('src', document.getElementById('iframevideo').getAttribute('src'));
// }
function gemfindDT_Videorun() {
  jQuery("#detail_iframevideodb").removeAttr("src");
  jQuery("#mp4video").removeAttr("src");
  jQuery("#mp4video").attr("src", "");
  jQuery("#detail_DbModal").modal("show");
  jQuery(".loader_rb").show();
  var divid = jQuery(event.currentTarget).data("id");
  console.log(divid);
  jQuery.ajax({
    type: "POST",
    url: myajax.ajaxurl,
    data: {
      action: "getdiamondvideos",
      product_id: divid,
    },
    cache: true,
    success: function (response) {
      console.log(response);
      response = JSON.parse(response);
      if (response.showVideo == true) {
        var fileExtension = response.videoURL.replace(/^.*\./, "");
        console.log(fileExtension);
        if (fileExtension == "mp4") {
          jQuery("#detail_iframevideodb").hide();
          setTimeout(function () {
            jQuery("#detail_mp4video").attr("src", response.videoURL);
            jQuery(".loader_rb").hide();
            jQuery("#detail_mp4video").get(0).play();
          }, 3000);
        } else {
          jQuery("#detail_mp4video").hide();
          setTimeout(function () {
            jQuery("#detail_iframevideodb").attr("src", response.videoURL);
            jQuery(".loader_rb").hide();
            jQuery("#detail_iframevideodb").show();
          }, 3000);
        }
      }
    },
  });

  jQuery(".Dblinkclose").click(function () {
    jQuery("#detail_DbModal").modal("hide");
  });
}

function gemfindDT_Imageswitch1(e) {
  document.getElementById("diamondimg").style.display = "block";
  // document.getElementById("diamondvideo").style.display = "none";
  document
    .getElementById("diamondmainimage")
    .setAttribute(
      "src",
      document.getElementById("thumbimg1").getAttribute("src")
    );
}

function gemfindDT_Imageswitch2(e) {
  document.getElementById("diamondimg").style.display = "block";
  // document.getElementById("diamondvideo").style.display = "none";
  document
    .getElementById("diamondmainimage")
    .setAttribute(
      "src",
      document.getElementById("thumbimg2").getAttribute("src")
    );
}

function gemfindDT_Closeform(e) {
  var x = e.target.getAttribute("data-target");
  var el1 = document.getElementById("form-drop-hint");
  if (el1) {
    el1.reset();
    jQuery("#form-drop-hint .form-field label label.error").html("");
  }
  var el2 = document.getElementById("form-email-friend");
  if (el2) {
    el2.reset();
    jQuery("#form-email-friend .form-field label label.error").html("");
  }
  var el3 = document.getElementById("form-request-info");
  if (el3) {
    el3.reset();
    jQuery("#form-request-info .form-field label label.error").html("");
    jQuery(
      "#form-request-info .form-field .prefrence-area ul.pref_container label.error"
    ).html("");
  }
  var el4 = document.getElementById("form-schedule-view");
  if (el4) {
    el4.reset();
    jQuery("#form-schedule-view .form-field label label.error").html("");
  }
  document.getElementById(x).style.display = "none";
  document.getElementById("diamond-content-data").style.display = "block";
}

function gemfindDT_focusFunction(e) {
  if (!e.value) {
    jQuery(e).parent().addClass("moveUp");
    jQuery(e).nextAll("span:first").addClass("moveUp");
  }
}

function gemfindDT_focusoutFunction(e) {
  if (!e.value) {
    jQuery(e).parent().removeClass("moveUp");
    jQuery(e).nextAll("span:first").removeClass("moveUp");
  }
}
