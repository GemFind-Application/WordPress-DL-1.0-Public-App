//jQuery.noConflict();
//var $ = $.noConflict();
jQuery(document).ready(function () {
  gemfindDT_IntitialFilter();
  setTimeout(function () {
    gemfindDT_diamond_search();
  }, 3000);
});
jQuery(window).bind("load", function () {
  jQuery(".testSelAll.SumoUnder").insertAfter(
    ".sumo_diamond_certificates .CaptionCont.SelectBox"
  );
  jQuery(".SlectBox.SumoUnder").insertAfter(
    ".sumo_gemfind_diamond_origin .CaptionCont.SelectBox"
  );
});

function gemfindDT_getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
function gemfindDT_diamond_search($) {
  //alert("here now");
  jQuery.noConflict();

  var $searchModule = jQuery("#search-diamonds");

  var filtermode = jQuery("#search-diamonds-form #filtermode").val();
  var resetfilterapplied = gemfindDT_getCookie("resetfilterapplied");
  var backvaluecookie = gemfindDT_getCookie("wp_dl_savebackvalue");
  //Initial Filter

  if (filtermode == "navfancycolored") {
    var diamondcookiedata = gemfindDT_getCookie("wp_dl_savefiltercookiefancy");
  } else if (filtermode == "navlabgrown") {
    var diamondcookiedata = gemfindDT_getCookie(
      "wp_dl_savefiltercookielabgrown"
    );
  } else if (filtermode == "navstandard") {
    var diamondcookiedata = gemfindDT_getCookie("wp_dl_savefiltercookie");
  } else {
    var diamondcookiedata = "";
  }

  /*if(filtermode == 'navfancycolored'){
      var diamondcookieinitialdata = gemfindDT_getCookie('wp_dl_intitialfiltercookiefancy');  
    }else */
  if (filtermode == "navlabgrown") {
    var diamondcookieinitialdata = gemfindDT_getCookie(
      "wp_dl_intitialfiltercookielabgrown"
    );
  } else if (filtermode == "navstandard") {
    var diamondcookieinitialdata = gemfindDT_getCookie(
      "wp_dl_intitialfiltercookie"
    );
  } else {
    var diamondcookieinitialdata = "";
  }
  var searchdiamondform = jQuery("#search-diamonds-form").serialize();
  var baseurl = jQuery("#baseurl");
  var filter_type = jQuery("#filtermode").val();
  jQuery.ajax({
    url: myajax.ajaxurl,
    data: {
      action: "gemfindDT_getDiamondFilters",
      filter_type: filter_type,
      shop: jQuery("#baseurl").val(),
      default_shape_filter: jQuery("#defaultshapevalue").val(),
      saveinitialvalue: diamondcookieinitialdata,
    },
    type: "POST",
    dataType: "html",
    cache: true,
    beforeSend: function (settings) {
      if (jQuery(".placeholder-content").length == 0) {
        jQuery(".loading-mask.gemfind-loading-mask").css("display", "block");
      }
    },
    success: function (response) {
      jQuery("#filter-main-div").html(response);
      jQuery("#search-diamonds-form").serialize();
      jQuery("#search-diamonds-form #submit").trigger("click");
      jQuery(".placeholder-content_item.box1, .placeholder-filter").remove();
      jQuery(".diamonds-details, .copyright-text").show();
      jQuery("button.accordion").click(function (e) {
        e.preventDefault();
        jQuery("button.accordion").toggleClass("active");
        jQuery(".filter-advanced .panel").css("max-height", "383px");
        jQuery(".filter-advanced .panel").toggleClass("cls-for-hide");
      });

      /*if(jQuery('#filtermode').val() != 'navlabgrown'){*/

      jQuery(".certificate-div p.select-all").click(function () {
        if (
          jQuery(".certificate-div p.select-all").hasClass("partial") &&
          jQuery(".certificate-div p.select-all").hasClass("selected")
        ) {
          jQuery(".certificate-div .selall ul.options li.selected").each(
            function () {
              jQuery(this).trigger("click");
            }
          );
          jQuery(".certificate-div .selall ul.options li").each(function () {
            jQuery(this).trigger("click");
          });
        } else if (
          !jQuery(".certificate-div p.select-all").hasClass("partial") &&
          jQuery(".certificate-div p.select-all").hasClass("selected")
        ) {
          jQuery(".certificate-div .selall ul.options li").each(function () {
            jQuery(this).trigger("click");
          });
        } else if (
          jQuery(".certificate-div p.select-all").hasClass("partial") &&
          !jQuery(".certificate-div p.select-all").hasClass("selected")
        ) {
          jQuery(".certificate-div .selall ul.options li.selected").each(
            function () {
              jQuery(this).trigger("click");
            }
          );
          jQuery(".certificate-div .selall ul.options li").each(function () {
            jQuery(this).trigger("click");
          });
        } else {
          jQuery(".certificate-div .selall ul.options li").each(function () {
            jQuery(this).trigger("click");
          });
        }
      });

      var sliders = jQuery("#noui_price_slider")[0];
      if (typeof sliders !== "undefined" && typeof sliders !== "undefined") {
        var $min_input = jQuery(sliders).find("input[data-type='min']");
        var $max_input = jQuery(sliders).find("input[data-type='max']");

        var $price_min_val = parseFloat(jQuery(sliders).attr("data-min"));
        var $price_max_val = parseFloat(jQuery(sliders).attr("data-max"));

        var $start_price_min = parseFloat($min_input.val());
        var $start_price_max = parseFloat($max_input.val());

        var first_half_interval = 50;
        var last_half_interval = 2500;

        if ($price_max_val >= 0 && $price_max_val <= 10000) {
          var range = {
            min: [$price_min_val, first_half_interval],
            "20%": [500, 100],
            "50%": [5000, 250],
            "70%": [7000, 500],
            max: [$price_max_val],
          };
        } else if ($price_max_val >= 10001 && $price_max_val <= 100000) {
          var range = {
            min: [$price_min_val, first_half_interval],
            "10%": [500, 100],
            "30%": [5000, 250],
            "50%": [10000, 500],
            "70%": [15000, 1000],
            "80%": [50000, 2500],
            max: [$price_max_val],
          };
        } else {
          var range = {
            min: [$price_min_val, first_half_interval],
            "10%": [500, 100],
            "30%": [5000, 250],
            "40%": [10000, 500],
            "50%": [15000, 1000],
            "60%": [50000, 2500],
            "70%": [100000, 10000],
            "80%": [250000, 25000],
            max: [$price_max_val],
          };
        }

        var slider_object = noUiSlider.create(sliders, {
          start: [$start_price_min, $start_price_max],
          //tooltips: [true, wNumb({decimals: 1})],
          connect: true,
          range: range,
          format: wNumb({
            decimals: 0,
            prefix: "",
            thousand: "",
          }),
        });

        sliders.noUiSlider.on("update", function (values, handle) {
          //console.log( values );
          var value_show = values[handle];
          if (handle) {
            $max_input.val(value_show);
          } else {
            $min_input.val(value_show);
          }
        });

        sliders.noUiSlider.on("change", function (values, handle) {
          jQuery("#search-diamonds-form #submit").trigger("click");
        });
        var $price_input1 = jQuery(sliders).find("input.slider-left");
        var $price_input2 = jQuery(sliders).find("input.slider-right");
        var price_inputs = [$price_input1, $price_input2];
        gemfindDT_slider_update_textbox(price_inputs, sliders);

        // console.log($min_input);
        // console.log($price_min_val);
        // console.log($start_price_min);

        jQuery("#rb_min_price").val($price_min_val);
        jQuery("#rb_max_price").val($price_max_val);
      }

      // var carat_slider = jQuery("#noui_carat_slider")[0];
      // if (
      //   typeof carat_slider !== "undefined" &&
      //   typeof carat_slider !== "undefined"
      // ) {
      //   var $carat_min_input = jQuery(carat_slider).find(
      //     "input[data-type='min']"
      //   );
      //   var $carat_max_input = jQuery(carat_slider).find(
      //     "input[data-type='max']"
      //   );

      //   var $carat_min_val = parseFloat(jQuery(carat_slider).attr("data-min"));
      //   var $carat_max_val = parseFloat(jQuery(carat_slider).attr("data-max"));

      //   var $start_carat_min = parseFloat($carat_min_input.val());
      //   var $start_carat_max = parseFloat($carat_max_input.val());
      //   // if ($carat_min_val >= 1.5) {
      //   //   var range = {
      //   //     min: [$carat_min_val, 0.01],
      //   //     "70%": [2.0, 0.1],
      //   //     "80%": [3.5, 0.25],
      //   //     "90%": [4.0, 0.5],
      //   //     max: [$carat_max_val],
      //   //   };
      //   // } else if ($carat_min_val >= 6.01 && $carat_min_val <= 10) {
      //   //   var range = {
      //   //     min: [$carat_min_val, 0.01],
      //   //     "60%": [7.1, 0.05],
      //   //     "70%": [8.0, 0.1],
      //   //     "80%": [9.5, 0.25],
      //   //     "90%": [10.0, 0.5],
      //   //     max: [$carat_max_val],
      //   //   };
      //   // } else if ($carat_max_val >= 0 && $carat_max_val <= 6) {
      //   //   var range = {
      //   //     min: [$carat_min_val, 0.01],
      //   //     "60%": [1.1, 0.05],
      //   //     "70%": [2.0, 0.1],
      //   //     "80%": [3.5, 0.25],
      //   //     "90%": [4.0, 0.5],
      //   //     max: [$carat_max_val],
      //   //   };
      //   // } else if ($carat_max_val >= 6.01 && $carat_max_val <= 10) {
      //   //   var range = {
      //   //     min: [$carat_min_val, 0.01],
      //   //     "60%": [1.1, 0.05],
      //   //     "70%": [2.0, 0.1],
      //   //     "80%": [3.5, 0.25],
      //   //     "90%": [5.0, 0.5],
      //   //     max: [$carat_max_val],
      //   //   };
      //   // } else {
      //   //   var range = {
      //   //     min: [$carat_min_val, 0.01],
      //   //     "50%": [1.1, 0.05],
      //   //     "60%": [2.0, 0.1],
      //   //     "70%": [3.5, 0.25],
      //   //     "80%": [5.0, 0.5],
      //   //     "90%": [10.0, 1],
      //   //     max: [$carat_max_val],
      //   //   };
      //   // }

      //   var carat_slider_object = noUiSlider.create(carat_slider, {
      //     start: [$start_carat_min, $start_carat_max],
      //     //tooltips: [true, wNumb({decimals: 2})],
      //     connect: true,
      //     step: 0.01,
      //     range: {
      //         'min': $carat_min_val,
      //         'max': $carat_max_val
      //       },
      //     format: wNumb({
      //       decimals: 2,
      //       prefix: "",
      //       thousand: "",
      //     }),
      //   });

      //   carat_slider.noUiSlider.on("update", function (values, handle) {
      //     //console.log( values );
      //     var carat_value_show = values[handle];
      //     if (handle) {
      //       $carat_max_input.val(carat_value_show);
      //     } else {
      //       $carat_min_input.val(carat_value_show);
      //     }
      //   });

      //   carat_slider.noUiSlider.on("change", function (values, handle) {
      //     jQuery("#search-diamonds-form #submit").trigger("click");
      //   });

      //   var $carat_input1 = jQuery(carat_slider).find("input.slider-left");
      //   var $carat_input2 = jQuery(carat_slider).find("input.slider-right");
      //   var carat_inputs = [$carat_input1, $carat_input2];
      //   gemfindDT_slider_update_textbox(carat_inputs, carat_slider);
      //   jQuery(carat_slider).find("input.slider-left").val($carat_min_val);
      //  // jQuery(carat_slider).find("input.slider-right").val($carat_max_val);
      //    jQuery('#noui_carat_slider input.slider-right').val($carat_max_val);
      // }

      var carat_slider = jQuery("#noui_carat_slider")[0];
      if (
        typeof carat_slider !== "undefined" &&
        typeof carat_slider !== "undefined"
      ) {
        var $carat_min_input = jQuery(carat_slider).find(
          "input[data-type='min']"
        );
        var $carat_max_input = jQuery(carat_slider).find(
          "input[data-type='max']"
        );

        var $carat_min_val = parseFloat(jQuery(carat_slider).attr("data-min"));
        var $carat_max_val = parseFloat(jQuery(carat_slider).attr("data-max"));

        var $start_carat_min = parseFloat($carat_min_input.val());
        var $start_carat_max = parseFloat($carat_max_input.val());

        var carat_slider_object = noUiSlider.create(carat_slider, {
          start: [$start_carat_min, $start_carat_max],
          //tooltips: [true, wNumb({decimals: 2})],
          connect: true,
          step: 0.01,
          range: {
            min: $carat_min_val,
            max: $carat_max_val,
          },
          format: wNumb({
            decimals: 2,
            prefix: "",
            thousand: ",",
          }),
        });

        carat_slider.noUiSlider.on("update", function (values, handle) {
          //console.log( values );
          var carat_value_show = values[handle];
          if (handle) {
            $carat_max_input.val(carat_value_show);
          } else {
            $carat_min_input.val(carat_value_show);
          }
        });

        carat_slider.noUiSlider.on("change", function (values, handle) {
          jQuery("#search-diamonds-form #submit").trigger("click");
        });

        var $carat_input1 = jQuery(carat_slider).find("input.slider-left");
        var $carat_input2 = jQuery(carat_slider).find("input.slider-right");
        var carat_inputs = [$carat_input1, $carat_input2];
        gemfindDT_slider_update_textbox(carat_inputs, carat_slider);
      }

      //cutRange
      var cutRangeSliders = jQuery("#cutRange-slider")[0];
      if (
        typeof cutRangeName !== "undefined" &&
        typeof cutRangeSliders !== "undefined"
      ) {
        var cutRange_array = JSON.parse(cutRangeName);
        var cutRangeSliders = jQuery("#cutRange-slider")[0];
        var rangeMax_step = parseInt(
          jQuery(cutRangeSliders).attr("data-steps")
        );
        var cutRangeleft = jQuery("#cutRangeleft").val();
        var cutRangeright = jQuery("#cutRangeright").val();
        var cutRangeleft = cutRangeleft ? cutRangeleft : 0;
        var cutRangeright = cutRangeright ? cutRangeright : rangeMax_step;
        var gfcutRange = [cutRangeleft, cutRangeright];
        if (jQuery("#diamond_cut").val() != "") {
          if (jQuery("#diamond_cut").val() == 1) {
            cutRangend = 1;
          } else {
            diamond_cut = jQuery("#diamond_cut").val();
            cutRangarray = diamond_cut.split(",");
            cutRangstart = cutRangarray[0];
            cutRangend = +cutRangarray[cutRangarray.length - 1] + +1;
          }
        } else {
          cutRangstart = cutRangeleft;
          cutRangend = cutRangeright;
        }
        noUiSlider.create(cutRangeSliders, {
          start: [cutRangeleft, cutRangend],
          connect: true,
          range: {
            min: 1,
            max: rangeMax_step,
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });
        cutRangeSliders.noUiSlider.on("update", function (values, handle) {
          var value = values[handle];
          jQuery("#cutRange-slider .noUi-pips .noUi-value").each(function (
            key,
            value
          ) {
            jQuery(this).html(cutRange_array[key]);
            jQuery(this).attr("data-color-id", cutRange_array[key]);
            jQuery(this).attr("title", cutRange_array[key]);
            jQuery(this).attr("data-placement", "bottom");
          });
        });

        cutRangeSliders.noUiSlider.on("change", function (values, handle) {
          var Selvalues = values;
          var cutStart = parseFloat(Selvalues[0]);
          var cutend = parseFloat(Selvalues[1]);
          var selectedcut = "";
          jQuery("#cutRangeleft").val(cutStart);
          jQuery("#cutRangedataright").val(cutend);
          for (ci = cutStart; ci < cutend; ci++) {
            if (cutend - 1 != ci) {
              selectedcut += cutStart + ",";
            } else {
              selectedcut += cutStart;
            }
            cutStart++;
          }
          jQuery("#diamond_cut").val(selectedcut);
          jQuery("#search-diamonds-form #submit").trigger("click");
        });

        cutRangeSliders.noUiSlider.on("start", function (values, handle) {
          jQuery("#cutRange-slider .noUi-tooltip").css("opacity", "1");
          jQuery("#cutRange-slider .noUi-tooltip").css("display", "block");
        });
        cutRangeSliders.noUiSlider.on("end", function (values, handle) {
          jQuery("#cutRange-slider .noUi-tooltip").fadeOut(2000);
        });
      }

      //ColorRange
      var ColorRangeSliders = jQuery("#colorRange-slider")[0];

      if (
        typeof ColorRangeName !== "undefined" &&
        typeof ColorRangeSliders !== "undefined"
      ) {
        var ColorRange_array = JSON.parse(ColorRangeName);
        var rangeMax_step = parseInt(
          jQuery(ColorRangeSliders).attr("data-steps")
        );
        var ColorRangeleft = jQuery("#colorRangeleft").val();
        var ColorRangeright = jQuery("#colorRangeright").val();
        if (jQuery("#diamond_color").val() != "") {
          ColorRangarray = jQuery("#diamond_color").val().split(",");
          ColorRangstart = ColorRangarray[0];
          ColorRangend = +ColorRangarray[ColorRangarray.length - 1] + +1;
          if (ColorRangend == 1) {
            ColorRangend = +ColorRangarray[ColorRangarray.length - 2] + +1;
          }
        } else {
          ColorRangstart = ColorRangeleft;
          ColorRangend = ColorRangeright;
        }
        var gfColorRange = [ColorRangeleft, ColorRangeright];
        noUiSlider.create(ColorRangeSliders, {
          start: [ColorRangstart, ColorRangend],
          connect: true,
          range: {
            min: parseInt(ColorRangeleft),
            max: parseInt(ColorRangeright),
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });

        ColorRangeSliders.noUiSlider.on("update", function (values, handle) {
          var value = values[handle];
          jQuery("#colorRange-slider .noUi-pips .noUi-value").each(function (
            key,
            value
          ) {
            jQuery(this).html(ColorRange_array[key]);
            jQuery(this).attr("data-color-id", ColorRange_array[key]);
            jQuery(this).attr("title", ColorRange_array[key]);
            jQuery(this).attr("data-placement", "bottom");
          });
        });

        ColorRangeSliders.noUiSlider.on("change", function (values, handle) {
          var ColorStart = parseFloat(values[0]);
          var Colorend = parseFloat(values[1]);
          //alert(Colorend);
          jQuery("#colorRangeright").val(Colorend);
          var selectedColor = "";
          for (ci = ColorStart; ci < Colorend; ci++) {
            if (Colorend != ci) {
              selectedColor += ColorStart + ",";
            } else {
              selectedColor += ColorStart;
            }
            ColorStart++;
          }
          jQuery("#diamond_color").val(selectedColor);
          jQuery("#search-diamonds-form #submit").trigger("click");
        });

        ColorRangeSliders.noUiSlider.on("start", function (values, handle) {
          jQuery("#colorRange-slider .noUi-tooltip").css("opacity", "1");
          jQuery("#colorRange-slider .noUi-tooltip").css("display", "block");
        });
        ColorRangeSliders.noUiSlider.on("end", function (values, handle) {
          jQuery("#colorRange-slider .noUi-tooltip").fadeOut(2000);
        });
      }

      //clarityRange

      var clarityRangeSliders = jQuery("#clarityRange-slider")[0];
      if (typeof clarityRangeSliders !== "undefined") {
        var clarityRange_array = JSON.parse(clarityRangeName);
        var rangeMax_step = parseInt(
          jQuery(clarityRangeSliders).attr("data-steps")
        );
        var clarityRangeleft = jQuery("#clarityRangeleft").val();
        var clarityRangeright = jQuery("#clarityRangeright").val();
        var clarityRangeleft = clarityRangeleft ? clarityRangeleft : 0;
        var clarityRangeright = clarityRangeright
          ? clarityRangeright
          : rangeMax_step;
        var gfclarityRange = [clarityRangeleft, clarityRangeright];
        if (jQuery("#diamond_clarity").val() != "") {
          clarityRangarray = jQuery("#diamond_clarity").val().split(",");
          clarityRangstart = clarityRangarray[0];
          clarityRangend = +clarityRangarray[clarityRangarray.length - 1] + +1;
          if (clarityRangend == 1) {
            clarityRangend =
              +clarityRangarray[clarityRangarray.length - 2] + +1;
          }
        } else {
          clarityRangstart = clarityRangeleft;
          clarityRangend = clarityRangeright;
        }
        noUiSlider.create(clarityRangeSliders, {
          start: [clarityRangeleft, clarityRangend],
          connect: true,
          range: {
            min: 1,
            max: rangeMax_step,
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });

        clarityRangeSliders.noUiSlider.on("update", function (values, handle) {
          var value = values[handle];
          jQuery("#clarityRange-slider .noUi-pips .noUi-value").each(function (
            key,
            value
          ) {
            jQuery(this).html(clarityRange_array[key]);
            jQuery(this).attr("data-clarity-id", clarityRange_array[key]);
            jQuery(this).attr("title", clarityRange_array[key]);
            jQuery(this).attr("data-placement", "bottom");
          });
        });

        clarityRangeSliders.noUiSlider.on("change", function (values, handle) {
          var Selvalues = values;
          var clarityStart = parseFloat(Selvalues[0]);
          var clarityend = parseFloat(Selvalues[1]);
          var selectedclarity = "";
          for (ci = clarityStart; ci < clarityend; ci++) {
            if (clarityend != ci) {
              selectedclarity += clarityStart + ",";
            } else {
              selectedclarity += clarityStart;
            }
            clarityStart++;
          }
          jQuery("#diamond_clarity").val(selectedclarity);
          jQuery("#search-diamonds-form #submit").trigger("click");
        });

        clarityRangeSliders.noUiSlider.on("start", function (values, handle) {
          jQuery("#clarityRange-slider .noUi-tooltip").css("opacity", "1");
          jQuery("#clarityRange-slider .noUi-tooltip").css("display", "block");
        });
        clarityRangeSliders.noUiSlider.on("end", function (values, handle) {
          jQuery("#clarityRange-slider .noUi-tooltip").fadeOut(2000);
        });
      }

      //intensityRange
      var intensityRangeSliders = jQuery("#intensityRange-slider")[0];
      if (typeof intensityRangeSliders !== "undefined") {
        var intensityRange_array = JSON.parse(intensityRangeName);
        var rangeMax_step = parseInt(
          jQuery(intensityRangeSliders).attr("data-steps")
        );
        var intensityRangeleft = jQuery("#intensityRangeleft").val();
        var intensityRangeright = jQuery("#intensityRangeright").val();
        var intensityRangeleft = intensityRangeleft ? intensityRangeleft : 0;
        var intensityRangeright = intensityRangeright
          ? intensityRangeright
          : rangeMax_step;
        var gfintensityRange = [intensityRangeleft, intensityRangeright];
        noUiSlider.create(intensityRangeSliders, {
          start: [intensityRangeleft, intensityRangeright],
          connect: true,
          range: {
            min: 1,
            max: rangeMax_step,
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });

        intensityRangeSliders.noUiSlider.on(
          "update",
          function (values, handle) {
            var value = values[handle];
            jQuery("#intensityRange-slider .noUi-pips .noUi-value").each(
              function (key, value) {
                jQuery(this).html(intensityRange_array[key]);
                jQuery(this).attr("data-clarity-id", intensityRange_array[key]);
                jQuery(this).attr("title", intensityRange_array[key]);
                jQuery(this).attr("data-placement", "bottom");
              }
            );
          }
        );

        intensityRangeSliders.noUiSlider.on(
          "change",
          function (values, handle) {
            var Selvalues = values;
            var intensityStart = parseFloat(Selvalues[0]) - 1;
            var intensityend = parseFloat(Selvalues[1]) - 1;
            var selectedintensity = "";
            for (ci = intensityStart; ci < intensityend; ci++) {
              if (intensityend != ci) {
                selectedintensity += intensityRange_array[intensityStart] + ",";
              } else {
                selectedintensity += intensityRange_array[intensityStart];
              }
              intensityStart++;
            }
            jQuery("#diamond_intintensity").val(selectedintensity);
            jQuery("#search-diamonds-form #submit").trigger("click");
          }
        );

        intensityRangeSliders.noUiSlider.on("start", function (values, handle) {
          jQuery("#intensityRange-slider .noUi-tooltip").css("opacity", "1");
          jQuery("#intensityRange-slider .noUi-tooltip").css(
            "display",
            "block"
          );
        });
        intensityRangeSliders.noUiSlider.on("end", function (values, handle) {
          jQuery("#intensityRange-slider .noUi-tooltip").fadeOut(2000);
        });
      }

      //diamondColorRange
      var diamondColorRangeSliders = jQuery("#diamondColorRange-slider")[0];
      if (typeof diamondColorRangeSliders !== "undefined") {
        var diamondColorRange_array = JSON.parse(diamondColorRangeName);
        var rangeMax_step = parseInt(
          jQuery(diamondColorRangeSliders).attr("data-steps")
        );
        var diamondColorRangeleft = jQuery("#diamondColorRangeleft").val();
        var diamondColorRangeright = jQuery("#diamondColorRangeright").val();
        var diamondColorRangeleft = diamondColorRangeleft
          ? diamondColorRangeleft
          : 0;
        var diamondColorRangeright = diamondColorRangeright
          ? diamondColorRangeright
          : rangeMax_step;
        var gfdiamondColorRange = [
          diamondColorRangeleft,
          diamondColorRangeright,
        ];
        noUiSlider.create(diamondColorRangeSliders, {
          start: [diamondColorRangeleft, diamondColorRangeright],
          connect: true,
          range: {
            min: 1,
            max: rangeMax_step,
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });

        diamondColorRangeSliders.noUiSlider.on(
          "update",
          function (values, handle) {
            var value = values[handle];
            jQuery("#diamondColorRange-slider .noUi-pips .noUi-value").each(
              function (key, value) {
                jQuery(this).html(diamondColorRange_array[key]);
                jQuery(this).attr(
                  "data-diamondColor-id",
                  diamondColorRange_array[key]
                );
                jQuery(this).attr("title", diamondColorRange_array[key]);
                jQuery(this).attr("data-placement", "bottom");
              }
            );
          }
        );

        diamondColorRangeSliders.noUiSlider.on(
          "change",
          function (values, handle) {
            var Selvalues = values;
            var diamondColorStart = parseFloat(Selvalues[0]) - 1;
            var diamondColorend = parseFloat(Selvalues[1]) - 1;
            var selecteddiamondColor = "";
            for (ci = diamondColorStart; ci < diamondColorend; ci++) {
              if (diamondColorend != ci) {
                selecteddiamondColor +=
                  diamondColorRange_array[diamondColorStart] + ",";
              } else {
                selecteddiamondColor +=
                  diamondColorRange_array[diamondColorStart];
              }
              diamondColorStart++;
            }
            jQuery("#diamondColorRangeright").val(diamondColorend);
            jQuery("#diamond_fancycolor").val(selecteddiamondColor);
            jQuery("#search-diamonds-form #submit").trigger("click");
          }
        );

        diamondColorRangeSliders.noUiSlider.on(
          "start",
          function (values, handle) {
            jQuery("#diamondColorRange-slider .noUi-tooltip").css(
              "opacity",
              "1"
            );
            jQuery("#diamondColorRange-slider .noUi-tooltip").css(
              "display",
              "block"
            );
          }
        );
        diamondColorRangeSliders.noUiSlider.on(
          "end",
          function (values, handle) {
            jQuery("#diamondColorRange-slider .noUi-tooltip").fadeOut(2000);
          }
        );
      }
      // depth slider
      var depthRangeSliders = jQuery("#noui_depth_slider")[0];
      if (typeof depthRangeSliders !== "undefined") {
        var depth_slider = jQuery("#noui_depth_slider")[0];
        var $depth_min_input = jQuery(depth_slider).find(
          "input[data-type='min']"
        );
        var $depth_max_input = jQuery(depth_slider).find(
          "input[data-type='max']"
        );

        var $depth_min_val = parseFloat(jQuery(depth_slider).attr("data-min"));
        var $depth_max_val = parseFloat(jQuery(depth_slider).attr("data-max"));
        if ($depth_max_val == 0) {
          $depth_max_val = 100;
        }
        var $start_depth_min = parseFloat($depth_min_input.val());
        var $start_depth_max = parseFloat($depth_max_input.val());
        if ($start_depth_max == 0) {
          $start_depth_max = 100;
        }
        var depth_slider_object = noUiSlider.create(depth_slider, {
          start: [$start_depth_min, $start_depth_max],
          //tooltips: [true, wNumb({decimals: 2})],
          connect: true,
          step: 1,
          range: {
            min: $depth_min_val,
            max: $depth_max_val,
          },
          format: wNumb({
            decimals: 0,
            prefix: "",
            thousand: "",
          }),
        });

        depth_slider.noUiSlider.on("update", function (values, handle) {
          //console.log( values );
          var depth_value_show = values[handle];
          if (handle) {
            $depth_max_input.val(depth_value_show);
          } else {
            $depth_min_input.val(depth_value_show);
          }
        });

        depth_slider.noUiSlider.on("change", function (values, handle) {
          jQuery("#search-diamonds-form #submit").trigger("click");
        });
        var $depth_input1 = jQuery(depth_slider).find("input.slider-left");
        var $depth_input2 = jQuery(depth_slider).find("input.slider-right");
        var depth_inputs = [$depth_input1, $depth_input2];
        gemfindDT_slider_update_textbox(depth_inputs, depth_slider);
      }

      var noui_tableper_slider = jQuery("#noui_tableper_slider")[0];
      if (typeof noui_tableper_slider !== "undefined") {
        var table_slider = jQuery("#noui_tableper_slider")[0];
        var $table_min_input = jQuery(table_slider).find(
          "input[data-type='min']"
        );
        var $table_max_input = jQuery(table_slider).find(
          "input[data-type='max']"
        );

        var $table_min_val = parseFloat(jQuery(table_slider).attr("data-min"));
        var $table_max_val = parseFloat(jQuery(table_slider).attr("data-max"));
        if ($table_max_val == 0) {
          $table_max_val = 100;
        }
        var $start_table_min = parseFloat($table_min_input.val());
        var $start_table_max = parseFloat($table_max_input.val());
        if ($start_table_max == 0) {
          $start_table_max = 100;
        }
        var table_slider_object = noUiSlider.create(table_slider, {
          start: [$start_table_min, $start_table_max],
          //tooltips: [true, wNumb({decimals: 2})],
          connect: true,
          step: 0.01,
          range: {
            min: $table_min_val,
            max: $table_max_val,
          },
          format: wNumb({
            decimals: 0,
            prefix: "",
            thousand: "",
          }),
        });

        table_slider.noUiSlider.on("update", function (values, handle) {
          //console.log( values );
          var table_value_show = values[handle];
          if (handle) {
            $table_max_input.val(table_value_show);
          } else {
            $table_min_input.val(table_value_show);
          }
        });

        table_slider.noUiSlider.on("change", function (values, handle) {
          jQuery("#search-diamonds-form #submit").trigger("click");
        });
        var $table_input1 = jQuery(table_slider).find("input.slider-left");
        var $table_input2 = jQuery(table_slider).find("input.slider-right");
        var table_inputs = [$table_input1, $table_input2];
        gemfindDT_slider_update_textbox(table_inputs, table_slider);
      }
      //polish Range

      var polishRangeSliders = jQuery("#polishRange-slider")[0];
      if (typeof polishRangeSliders !== "undefined") {
        console.log('polishRangeName:', polishRangeName);
        var polishRange_array = polishRangeName;
        var rangeMax_step = parseInt(
          jQuery(polishRangeSliders).attr("data-steps")
        );
        var polishRangeleft = jQuery("#polishRangeleft").val();
        var polishRangeright = jQuery("#polishRangeright").val();
        var polishRangeleft = polishRangeleft ? polishRangeleft : 0;
        var polishRangeright = polishRangeright
          ? polishRangeright
          : rangeMax_step;
        var gfpolishRange = [polishRangeleft, polishRangeright];
        if (jQuery("#diamond_polish").val() != "") {
          polishRangearray = jQuery("#diamond_polish").val().split(",");
          polishRangestart = polishRangearray[0];
          polishRangeend = +polishRangearray[polishRangearray.length - 1] + +1;
          if (polishRangeend == 1) {
            polishRangeend =
              +polishRangearray[polishRangearray.length - 2] + +1;
          }
        } else {
          polishRangestart = polishRangeleft;
          polishRangeend = polishRangeright;
        }
        noUiSlider.create(polishRangeSliders, {
          start: [polishRangeleft, polishRangeend],
          connect: true,
          range: {
            min: 1,
            max: rangeMax_step,
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });

        polishRangeSliders.noUiSlider.on("update", function (values, handle) {
          var value = values[handle];
          jQuery("#polishRange-slider .noUi-pips .noUi-value").each(function (
            key,
            value
          ) {
            jQuery(this).html(polishRange_array[key]);
            jQuery(this).attr("data-polish-id", polishRange_array[key]);
            jQuery(this).attr("title", polishRange_array[key]);
            jQuery(this).attr("data-placement", "bottom");
          });
        });

        polishRangeSliders.noUiSlider.on("change", function (values, handle) {
          var Selvalues = values;
          var polishStart = parseFloat(Selvalues[0]);
          var polishend = parseFloat(Selvalues[1]);
          var selectedpolish = "";
          for (ci = polishStart; ci < polishend; ci++) {
            if (polishend != ci) {
              selectedpolish += polishStart + ",";
            } else {
              selectedpolish += polishStart;
            }
            polishStart++;
          }
          jQuery("#diamond_polish").val(selectedpolish);
          jQuery("#search-diamonds-form #submit").trigger("click");
        });

        polishRangeSliders.noUiSlider.on("start", function (values, handle) {
          jQuery("#polishRange-slider .noUi-tooltip").css("opacity", "1");
          jQuery("#polishRange-slider .noUi-tooltip").css("display", "block");
        });
        polishRangeSliders.noUiSlider.on("end", function (values, handle) {
          jQuery("#polishRange-slider .noUi-tooltip").fadeOut(2000);
        });
      }

      //symmetry Range

      var symmetryRangeSliders = jQuery("#symmetryRange-slider")[0];
      if (typeof symmetryRangeSliders !== "undefined") {
        var symmetryRange_array = JSON.parse(symmetryRangeName);
        var rangeMax_step = parseInt(
          jQuery(symmetryRangeSliders).attr("data-steps")
        );
        var symmetryRangeleft = jQuery("#symmetryRangeleft").val();
        var symmetryRangeright = jQuery("#symmetryRangeright").val();
        var symmetryRangeleft = symmetryRangeleft ? symmetryRangeleft : 0;
        var symmetryRangeright = symmetryRangeright
          ? symmetryRangeright
          : rangeMax_step;
        var gfsymmetryRange = [symmetryRangeleft, symmetryRangeright];
        if (jQuery("#diamond_symmetry").val() != "") {
          symmetryarray = jQuery("#diamond_symmetry").val().split(",");
          symmetrystart = symmetryarray[0];
          symmetryend = +symmetryarray[symmetryarray.length - 1] + +1;
          if (symmetryend == 1) {
            symmetryend = +symmetryarray[symmetryarray.length - 2] + +1;
          }
        } else {
          symmetrystart = symmetryRangeleft;
          symmetryend = symmetryRangeright;
        }
        noUiSlider.create(symmetryRangeSliders, {
          start: [symmetryRangeleft, symmetryend],
          connect: true,
          range: {
            min: 1,
            max: rangeMax_step,
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });

        symmetryRangeSliders.noUiSlider.on("update", function (values, handle) {
          var value = values[handle];
          jQuery("#symmetryRange-slider .noUi-pips .noUi-value").each(function (
            key,
            value
          ) {
            jQuery(this).html(symmetryRange_array[key]);
            jQuery(this).attr("data-symmetry-id", symmetryRange_array[key]);
            jQuery(this).attr("title", symmetryRange_array[key]);
            jQuery(this).attr("data-placement", "bottom");
          });
        });

        symmetryRangeSliders.noUiSlider.on("change", function (values, handle) {
          var Selvalues = values;
          var symmetryStart = parseFloat(Selvalues[0]);
          var symmetryend = parseFloat(Selvalues[1]);
          var selectedsymmetry = "";
          for (ci = symmetryStart; ci < symmetryend; ci++) {
            if (symmetryend != ci) {
              selectedsymmetry += symmetryStart + ",";
            } else {
              selectedsymmetry += symmetryStart;
            }
            symmetryStart++;
          }
          jQuery("#diamond_symmetry").val(selectedsymmetry);
          jQuery("#search-diamonds-form #submit").trigger("click");
        });

        symmetryRangeSliders.noUiSlider.on("start", function (values, handle) {
          jQuery("#symmetryRange-slider .noUi-tooltip").css("opacity", "1");
          jQuery("#symmetryRange-slider .noUi-tooltip").css("display", "block");
        });
        symmetryRangeSliders.noUiSlider.on("end", function (values, handle) {
          jQuery("#symmetryRange-slider .noUi-tooltip").fadeOut(2000);
        });
      }

      //fluor Range

      var fluorRangeSliders = jQuery("#fluorRange-slider")[0];
      if (typeof fluorRangeSliders !== "undefined") {
        var fluorRange_array = JSON.parse(fluorRangeName);
        if (fluorRange_array[0]) {
          fluorRange_array[0] = "None";
        }
        if (fluorRange_array[1]) {
          fluorRange_array[1] = "Faint";
        }
        if (fluorRange_array[2]) {
          fluorRange_array[2] = "Medium";
        }
        if (fluorRange_array[3]) {
          fluorRange_array[3] = "Stong";
        }
        if (fluorRange_array[4]) {
          fluorRange_array[4] = "Very Strong";
        }
        var rangeMax_step = parseInt(
          jQuery(fluorRangeSliders).attr("data-steps")
        );
        var fluorRangeleft = jQuery("#fluorRangeleft").val();
        var fluorRangeright = jQuery("#fluorRangeright").val();
        var fluorRangeleft = fluorRangeleft ? fluorRangeleft : 0;
        var fluorRangeright = fluorRangeright ? fluorRangeright : rangeMax_step;
        var gffluorRange = [fluorRangeleft, fluorRangeright];
        if (jQuery("#diamond_fluorescence").val() != "") {
          fluorescencearray = jQuery("#diamond_fluorescence").val().split(",");
          fluorescencestart = fluorescencearray[0];
          fluorescenceend =
            +fluorescencearray[fluorescencearray.length - 1] + +1;
          if (fluorescenceend == 1) {
            fluorescenceend =
              +fluorescencearray[fluorescencearray.length - 2] + +1;
          }
        } else {
          fluorescencestart = fluorRangeleft;
          fluorescenceend = fluorRangeright;
        }
        noUiSlider.create(fluorRangeSliders, {
          start: [fluorRangeleft, fluorescenceend],
          connect: true,
          range: {
            min: 1,
            max: rangeMax_step,
          },
          step: 1,
          margin: 1,
          pips: {
            mode: "steps",
            density: rangeMax_step,
            filter: function () {
              return 1;
            },
          },
        });

        fluorRangeSliders.noUiSlider.on("update", function (values, handle) {
          var value = values[handle];
          jQuery("#fluorRange-slider .noUi-pips .noUi-value").each(function (
            key,
            value
          ) {
            jQuery(this).html(fluorRange_array[key]);
            jQuery(this).attr("data-fluorescence-id", fluorRange_array[key]);
            jQuery(this).attr("title", fluorRange_array[key]);
            jQuery(this).attr("data-placement", "bottom");
          });
        });

        fluorRangeSliders.noUiSlider.on("change", function (values, handle) {
          var Selvalues = values;
          var fluorStart = parseFloat(Selvalues[0]);
          var fluorend = parseFloat(Selvalues[1]);
          var selectedfluor = "";
          for (ci = fluorStart; ci < fluorend; ci++) {
            if (fluorend != ci) {
              selectedfluor += fluorStart + ",";
            } else {
              selectedfluor += fluorStart;
            }
            fluorStart++;
          }
          jQuery("#diamond_fluorescence").val(selectedfluor);
          jQuery("#search-diamonds-form #submit").trigger("click");
        });

        fluorRangeSliders.noUiSlider.on("start", function (values, handle) {
          jQuery("#fluorRange-slider .noUi-tooltip").css("opacity", "1");
          jQuery("#fluorRange-slider .noUi-tooltip").css("display", "block");
        });
        fluorRangeSliders.noUiSlider.on("end", function (values, handle) {
          jQuery("#fluorRange-slider .noUi-tooltip").fadeOut(2000);
        });
      }

      jQuery("#cutRange-slider .noUi-pips.noUi-pips-horizontal").insertAfter(
        "#cutRange-slider .noUi-base .noUi-connect"
      );
      jQuery("#colorRange-slider .noUi-pips.noUi-pips-horizontal").insertAfter(
        "#colorRange-slider .noUi-base .noUi-connect"
      );
      jQuery(
        "#clarityRange-slider .noUi-pips.noUi-pips-horizontal"
      ).insertAfter("#clarityRange-slider .noUi-base .noUi-connect");
      jQuery("#polishRange-slider .noUi-pips.noUi-pips-horizontal").insertAfter(
        "#polishRange-slider .noUi-base .noUi-connect"
      );
      jQuery(
        "#symmetryRange-slider .noUi-pips.noUi-pips-horizontal"
      ).insertAfter("#symmetryRange-slider .noUi-base .noUi-connect");
      jQuery("#fluorRange-slider .noUi-pips.noUi-pips-horizontal").insertAfter(
        "#fluorRange-slider .noUi-base .noUi-connect"
      );
      jQuery(
        "#diamondColorRange-slider .noUi-pips.noUi-pips-horizontal"
      ).insertAfter("#diamondColorRange-slider .noUi-base .noUi-connect");
      jQuery(
        "#intensityRange-slider .noUi-pips.noUi-pips-horizontal"
      ).insertAfter("#intensityRange-slider .noUi-base .noUi-connect");

      jQuery(window).keydown(function (event) {
        if (event.keyCode == 13) return false;
      });

      //If search module container exists hook slider to DOM
      if ($searchModule.length) {
        if (jQuery("#carat_slider").length) new numberSlider("carat", true);
        if (jQuery("#price_slider").length) new numberSlider("price", true);
        if (jQuery("#tableper_slider").length)
          new numberSlider("tableper", true);
        if (jQuery("#depth_slider").length) new numberSlider("depth", true);
        $searchModule.find(".ui-slider-handle:even").addClass("left-handle");
        $searchModule.find(".ui-slider-handle:odd").addClass("right-handle");
      }
      jQuery("input:checkbox").change(function () {
        //alert("something");
        if (jQuery(this).attr("name") == "diamond_fancycolor[]") {
          jQuery.ajax({
            url:
              jQuery("#search-diamonds-form #baseurl").val() +
              "diamondlink/loadshape",
            data: jQuery("#search-diamonds-form").serialize(),
            type: "POST",
            dataType: "json",
            //cache: true,
            beforeSend: function (settings) {
              jQuery(".loading-mask.gemfind-loading-mask").css(
                "display",
                "block"
              );
            },
            success: function (response) {
              jQuery("ul#shapeul li").css("display", "none");
              jQuery.each(response.shapes, function (key, value) {
                jQuery("li." + value).css("display", "block");
              });
            },
          });
        }
        if (jQuery(this).is(":checked")) {
          jQuery(this).parent().addClass("selected active");
          jQuery("#search-diamonds-form #submit").trigger("click");
        } else {
          jQuery(this).parent().removeClass("selected active");
          jQuery("#search-diamonds-form #submit").trigger("click");
        }
      });

      if (jQuery("#filtermode").val() == "navfancycolored") {
        var element = document.getElementById("navfancycolored");
        if (typeof element != "undefined" && element != null) {
          document.getElementById("navfancycolored").className = "active";
        }
        if (
          typeof document.getElementById("navstandard") != "undefined" &&
          document.getElementById("navstandard") != null
        ) {
          document.getElementById("navstandard").className = "";
        }
        if (
          typeof document.getElementById("navlabgrown") != "undefined" &&
          document.getElementById("navlabgrown") != null
        ) {
          document.getElementById("navlabgrown").className = "";
        }
      } else if (jQuery("#filtermode").val() == "navlabgrown") {
        var element = document.getElementById("navlabgrown");
        if (typeof element != "undefined" && element != null) {
          document.getElementById("navlabgrown").className = "active";
        }
        if (
          typeof document.getElementById("navstandard") != "undefined" &&
          document.getElementById("navstandard") != null
        ) {
          document.getElementById("navstandard").className = "";
        }
        if (
          typeof document.getElementById("navfancycolored") != "undefined" &&
          document.getElementById("navfancycolored") != null
        ) {
          document.getElementById("navfancycolored").className = "";
        }
      } else {
        var element = document.getElementById("navstandard");
        if (typeof element != "undefined" && element != null) {
          document.getElementById("navstandard").className = "active";
        }
        if (
          typeof document.getElementById("navfancycolored") != "undefined" &&
          document.getElementById("navfancycolored") != null
        ) {
          document.getElementById("navfancycolored").className = "";
        }
        if (
          typeof document.getElementById("navlabgrown") != "undefined" &&
          document.getElementById("navlabgrown") != null
        ) {
          document.getElementById("navlabgrown").className = "";
        }
      }
      // var mode = jQuery("input#viewmode").val();
      // console.log(mode);
      // if (mode == 'grid') {
      //           jQuery('li.grid-view a').addClass('active');
      //           jQuery('li.list-view a').removeClass('active');
      //           jQuery('#list-mode').addClass('cls-for-hide');
      //           jQuery('#grid-mode, #gridview-orderby, div.grid-view-sort').removeClass('cls-for-hide');
      // }
    },
    error: function (xhr, status, errorThrown) {
      console.log("Error happens. Try again.");
      console.log(errorThrown);
    },
  });
}

function gemfindDT_IntitialFilter() {
  //jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
  var shapeCheckboxes = jQuery("input[name='diamond_shape[]']");
  var shapeList = [];
  shapeCheckboxes.each(function () {
    if (this.checked === true) {
      shapeList.push(jQuery(this).val());
    }
  });
  var cutCheckboxes = jQuery("input[name='diamond_cut[]']");
  var CutGradeList = [];
  cutCheckboxes.each(function () {
    //if (this.checked === true) {
    CutGradeList.push(jQuery(this).val());
    //}
  });
  var colorCheckboxes = jQuery("input[name='diamond_color[]']");
  var ColorList = [];
  colorCheckboxes.each(function () {
    //if (this.checked === true) {
    ColorList.push(jQuery(this).val());
    //}
  });
  var clarityCheckboxes = jQuery("input[name='diamond_clarity[]']");
  var ClarityList = [];
  clarityCheckboxes.each(function () {
    //if (this.checked === true) {
    ClarityList.push(jQuery(this).val());
    // }
  });
  var polishCheckboxes = jQuery("input[name='diamond_polish[]']");
  var polishList = [];
  polishCheckboxes.each(function () {
    // if (this.checked === true) {
    polishList.push(jQuery(this).val());
    // }
  });
  var symmetryCheckboxes = jQuery("input[name='diamond_symmetry[]']");
  var SymmetryList = [];
  symmetryCheckboxes.each(function () {
    // if (this.checked === true) {
    SymmetryList.push(jQuery(this).val());
    //  }
  });
  var fluorescenceCheckboxes = jQuery("input[name='diamond_fluorescence[]']");
  var FluorescenceList = [];
  fluorescenceCheckboxes.each(function () {
    // if (this.checked === true) {
    FluorescenceList.push(jQuery(this).val());
    // }
  });

  var fancycolorCheckboxes = jQuery("input[name='diamond_fancycolor[]']");
  var FancycolorList = [];
  fancycolorCheckboxes.each(function () {
    //if (this.checked === true) {
    FancycolorList.push(jQuery(this).val());
    //}
  });

  var intintensityCheckboxes = jQuery("input[name='diamond_intintensity[]']");
  var intintensityList = [];
  intintensityCheckboxes.each(function () {
    //if (this.checked === true) {
    intintensityList.push(jQuery(this).val());
    //}
  });

  var certiCheckboxes = jQuery("select#certi-dropdown");
  var certificatelist = [];
  certificatelist.push(jQuery(certiCheckboxes).val());
  var caratMin = jQuery("div#noui_carat_slider input.slider-left").val();
  var caratMax = jQuery("div#noui_carat_slider input.slider-right").val();
  var PriceMin = jQuery("div#noui_price_slider input.slider-left").val();
  var PriceMax = jQuery("div#noui_price_slider input.slider-right").val();
  var depthMin = jQuery("div#noui_depth_slider input.slider-left").val();
  var depthMax = jQuery("div#noui_depth_slider input.slider-right").val();
  var tableMin = jQuery("div#noui_tableper_slider input.slider-left").val();
  var tableMax = jQuery("div#noui_tableper_slider input.slider-right").val();
  var orderBy = jQuery("input#orderby").val();
  var direction = jQuery("input#direction").val();
  var currentPage = jQuery("input#currentpage").val();
  var itemperpage = jQuery("input#itemperpage").val();
  var viewMode = jQuery("input#viewmode").val();
  var filtermode = jQuery("input#filtermode").val();
  var did = jQuery("input#did").val();
  var formdata = {
    shapeList: shapeList.toString(),
    caratMin: caratMin,
    caratMax: caratMax,
    PriceMin: PriceMin,
    PriceMax: PriceMax,
    certificate: certificatelist.toString(),
    SymmetryList: SymmetryList.toString(),
    polishList: polishList.toString(),
    depthMin: depthMin,
    depthMax: depthMax,
    tableMin: tableMin,
    tableMax: tableMax,
    FluorescenceList: FluorescenceList.toString(),
    CutGradeList: CutGradeList.toString(),
    ColorList: ColorList.toString(),
    ClarityList: ClarityList.toString(),
    FancycolorList: FancycolorList.toString(),
    IntintensityList: intintensityList.toString(),
    Filtermode: filtermode,
    currentPage: currentPage,
    orderBy: orderBy,
    direction: direction,
    viewmode: viewMode,
    itemperpage: itemperpage,
    did: did,
    backdiamondid: did,
  };

  var expire = new Date();
  expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
  var resetfilterapplied = gemfindDT_getCookie("resetfilterapplied");
  if (resetfilterapplied == "") {
    if (filtermode == "navfancycolored") {
      jQuery.cookie(
        "wp_dl_intitialfiltercookiefancy",
        JSON.stringify(formdata),
        {
          path: "/",
          expires: expire,
        }
      );
    } else if (filtermode == "navstandard") {
      jQuery.cookie("wp_dl_intitialfiltercookie", JSON.stringify(formdata), {
        path: "/",
        expires: expire,
      });
    } else if (filtermode == "navlabgrown") {
      jQuery.cookie(
        "wp_dl_intitialfiltercookielabgrown",
        JSON.stringify(formdata),
        {
          path: "/",
          expires: expire,
        }
      );
    }
  }
}

function gemfindDT_SaveFilter() {
  jQuery(".loading-mask.gemfind-loading-mask").css("display", "block");
  var shapeCheckboxes = jQuery("input[name='diamond_shape[]']");
  var shapeList = [];
  shapeCheckboxes.each(function () {
    if (this.checked === true) {
      shapeList.push(jQuery(this).val());
    }
    //shapeList.push(jQuery(this).val());
  });
  var cutCheckboxes = jQuery("input[name='diamond_cut[]']");
  var CutGradeList = [];
  cutCheckboxes.each(function () {
    CutGradeList.push(jQuery(this).val());
  });
  var colorCheckboxes = jQuery("input[name='diamond_color[]']");
  var ColorList = [];
  colorCheckboxes.each(function () {
    ColorList.push(jQuery(this).val());
  });
  var clarityCheckboxes = jQuery("input[name='diamond_clarity[]']");
  var ClarityList = [];
  clarityCheckboxes.each(function () {
    ClarityList.push(jQuery(this).val());
  });
  var polishCheckboxes = jQuery("input[name='diamond_polish[]']");
  var polishList = [];
  polishCheckboxes.each(function () {
    polishList.push(jQuery(this).val());
  });
  var symmetryCheckboxes = jQuery("input[name='diamond_symmetry[]']");
  var SymmetryList = [];
  symmetryCheckboxes.each(function () {
    SymmetryList.push(jQuery(this).val());
  });
  var fluorescenceCheckboxes = jQuery("input[name='diamond_fluorescence[]']");
  var FluorescenceList = [];
  fluorescenceCheckboxes.each(function () {
    FluorescenceList.push(jQuery(this).val());
  });
  var fancycolorCheckboxes = jQuery("input[name='diamond_fancycolor[]']");
  var FancycolorList = [];
  fancycolorCheckboxes.each(function () {
    FancycolorList.push(jQuery(this).val());
  });
  var intintensityCheckboxes = jQuery("input[name='diamond_intintensity[]']");
  var intintensityList = [];
  intintensityCheckboxes.each(function () {
    intintensityList.push(jQuery(this).val());
  });
  var certiCheckboxes = jQuery("select#certi-dropdown");
  var certificatelist = [];
  certificatelist.push(jQuery(certiCheckboxes).val());
  var caratMin = jQuery("div#noui_carat_slider input.slider-left").val();
  var caratMax = jQuery("div#noui_carat_slider input.slider-right").val();
  var PriceMin = jQuery("div#noui_price_slider input.slider-left").val();
  var PriceMax = jQuery("div#noui_price_slider input.slider-right").val();
  var depthMin = jQuery("div#noui_depth_slider input.slider-left").val();
  var depthMax = jQuery("div#noui_depth_slider input.slider-right").val();
  var tableMin = jQuery("div#noui_tableper_slider input.slider-left").val();
  var tableMax = jQuery("div#noui_tableper_slider input.slider-right").val();
  var SOrigin = jQuery("select#gemfind_diamond_origin").val();
  var orderBy = jQuery("input#orderby").val();
  var direction = jQuery("input#direction").val();
  var currentPage = jQuery("input#currentpage").val();
  var itemperpage = jQuery("input#itemperpage").val();
  var viewMode = jQuery("input#viewmode").val();
  var filtermode = jQuery("input#filtermode").val();
  var did = jQuery("input#did").val();
  var formdata = {
    shapeList: shapeList.toString(),
    caratMin: caratMin,
    caratMax: caratMax,
    PriceMin: PriceMin,
    PriceMax: PriceMax,
    certificate: certificatelist.toString(),
    SymmetryList: SymmetryList.toString(),
    polishList: polishList.toString(),
    depthMin: depthMin,
    depthMax: depthMax,
    tableMin: tableMin,
    tableMax: tableMax,
    FluorescenceList: FluorescenceList.toString(),
    CutGradeList: CutGradeList.toString(),
    ColorList: ColorList.toString(),
    ClarityList: ClarityList.toString(),
    FancycolorList: FancycolorList.toString(),
    IntintensityList: intintensityList.toString(),
    Filtermode: filtermode,
    SOrigin: SOrigin,
    currentPage: currentPage,
    orderBy: orderBy,
    direction: direction,
    viewmode: viewMode,
    itemperpage: itemperpage,
    did: did,
    backdiamondid: did,
  };
  // console.log(caratMin);
  // console.log(caratMax);
  // console.log(PriceMin);
  // console.log(PriceMax);
  // console.log(formdata);
  var expire = new Date();
  expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
  if (filtermode == "navfancycolored") {
    jQuery.cookie("wp_dl_savefiltercookiefancy", JSON.stringify(formdata), {
      path: "/",
      expires: expire,
    });
  } else if (filtermode == "navstandard") {
    jQuery.cookie("wp_dl_savefiltercookie", JSON.stringify(formdata), {
      path: "/",
      expires: expire,
    });
  } else {
    jQuery.cookie("wp_dl_savefiltercookielabgrown", JSON.stringify(formdata), {
      path: "/",
      expires: expire,
    });
  }
  setTimeout(function () {
    jQuery(".loading-mask.gemfind-loading-mask").css("display", "none");
  }, 400);
}

function gemfindDT_ResetFilter() {
  if (confirm("Are you sure you want to reset data?")) {
    localStorage.removeItem("compareItems");
    localStorage.removeItem("compareClick");

    var expire = new Date();
    expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
    jQuery.cookie("resetfilterapplied", 1, {
      path: "/",
      expires: expire,
    });

    jQuery.cookie("wp_dl_savefiltercookie", "", {
      path: "/",
      expires: -1,
    });

    jQuery.cookie("comparediamondProduct", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_savefiltercookiefancy", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_savefiltercookielabgrown", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_savebackvaluefancy", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_savebackvalue", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_savebackvaluelabgrown", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_intitialfiltercookiefancy", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_intitialfiltercookie", "", {
      path: "/",
      expires: -1,
    });
    jQuery.cookie("wp_dl_intitialfiltercookielabgrown", "", {
      path: "/",
      expires: -1,
    });

    window.location.reload();
  }
}
function gemfindDT_ResetBackCookieFilter() {
  jQuery.cookie("wp_dl_savebackvaluefancy", "", {
    path: "/",
    expires: -1,
  });
  jQuery.cookie("wp_dl_savebackvalue", "", {
    path: "/",
    expires: -1,
  });
  jQuery.cookie("wp_dl_savebackvaluelabgrown", "", {
    path: "/",
    expires: -1,
  });
}
// function gemfindDT_slider_update_textbox(slider_inputs, slidername) {
//   // Listen to keydown events on the input field.
//   slider_inputs.forEach(function (input, handle) {
//     input.change(function () {
//       console.log("change");
//       var vals = parseFloat(this.value);
//       if (handle) {
//         slidername.noUiSlider.set([null, vals]);
//       } else {
//         slidername.noUiSlider.set([vals, null]);
//       }
//       jQuery("#search-diamonds-form #submit").trigger("click");
//     });
//   });
// }

function gemfindDT_slider_update_textbox(slider_inputs, slidername) {
  // Listen to keydown events on the input field.
  slider_inputs.forEach(function (input, handle) {
    input.change(function () {
      var vals = parseFloat(this.value);
      if (this.name == "price[to]" || this.name == "price[from]") {
        //console.log(this.name);
        var vals = parseFloat(this.value.replace(/,/g, ""));
      } else {
        var vals = parseFloat(this.value);
      }
      if (handle) {
        slidername.noUiSlider.set([null, vals]);
      } else {
        slidername.noUiSlider.set([vals, null]);
      }
      jQuery("#search-diamonds-form #submit").trigger("click");
      //console.log(vals);
    });
    input.keyup(function (e) {
      var values = slidername.noUiSlider.get();
      var value = parseFloat(values[handle]);
      // [[handle0_down, handle0_up], [handle1_down, handle1_up]]
      var steps = slidername.noUiSlider.steps();
      // [down, up]
      var step = steps[handle];
      var position;
      // 13 is enter,
      // 38 is key up,
      // 40 is key down.
      switch (e.which) {
        case 13:
          var vals = parseFloat(this.value);
          if (this.name == "price[to]" || this.name == "price[from]") {
            //console.log(this.name);
            var vals = parseFloat(this.value.replace(/,/g, ""));
          } else {
            var vals = parseFloat(this.value);
          }
          if (handle) {
            slidername.noUiSlider.set([null, vals]);
          } else {
            slidername.noUiSlider.set([vals, null]);
          }
          jQuery("#search-diamonds-form #submit").trigger("click");
          break;

        case 38:
          position = step[1];
          // false = no step is set
          if (position === false) {
            position = 1;
          }
          // null = edge of slider
          if (position !== null) {
            // console.log(value);
            // console.log(typeof value);
            // console.log(position);
            var vals = parseInt(value + position);
            if (handle) {
              slidername.noUiSlider.set([null, vals]);
            } else {
              slidername.noUiSlider.set([vals, null]);
            }
          }
          jQuery("#search-diamonds-form #submit").trigger("click");
          break;
        case 40:
          position = step[0];
          if (position === false) {
            position = 1;
          }

          if (position !== null) {
            var vals = parseFloat(value - position);
            if (handle) {
              slidername.noUiSlider.set([null, vals]);
            } else {
              slidername.noUiSlider.set([vals, null]);
            }
          }
          jQuery("#search-diamonds-form #submit").trigger("click");
          break;
      }
    });
  });
}
