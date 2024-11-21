var $searchModule = jQuery("#search-diamonds");
jQuery(document).ready(function ($) {
  $("#search-diamonds-form #submitby").val("ajax");
  $("form#search-diamonds-form").submit(function (e) {
    //alert("its her enow");
    $(".loading-mask.gemfind-loading-mask").css("display", "block");
    //console.log('form submitted');
    e.preventDefault();
    var filter_type = jQuery(".filter-left").children(".active").attr("id");
    var data = jQuery("#search-diamonds-form").serializeArray();
    // console.log(jQuery("#search-diamonds-form").serializeArray());
    // testArray = jQuery.inArray("diamond_shape[]", jQuery("#search-diamonds-form").serializeArray());
    // console.log(testArray);

    var hasDiamondShape = false;

  // Loop through each object in the array
    jQuery.each(data, function(index, obj) {
        // Check if the current object has the "name" property equal to "diamond_shape[]"
        if (obj.name === "diamond_shape[]") {
            hasDiamondShape = true;
            // Exit the loop early if the key is found
            return false;
        }
    });

  // Check the result
  if (hasDiamondShape) {
      console.log("The 'diamond_shape[]' key exists in the array.");
  } else {
      console.log("The 'diamond_shape[]' key does not exist in the array.");
      // Create an array to store the selected values
          var selectedValues = [];
          // Find all checkboxes with the class "selected"
          $('#shapeul li div.selected input[type="checkbox"]').each(function() {
              var title = 'diamond_shape[]';
              var value = $(this).val();
              var obj = { "name": title, "value": value };
              data.push(obj);
             $(this).prop('checked',true);
          });
          
        //  data = data.selectedValues;
          // Log the selected values
          console.log(data);
  }

    //var filter_type = jQuery("#filtermode").val();
    $.ajax({
      url: myajax.ajaxurl,
      data: {
        action: "gemfindDT_getDiamonds",
        filter_type: filter_type,
        shop: jQuery("#baseurl").val(),
        filter_data: data,
      },
      type: "POST",
      //dataType: 'json',
      cache: true,
      beforeSend: function (settings) {
        $(".loading-mask.gemfind-loading-mask").css("display", "block");
      },
      success: function (response) {
        $(".result").html(response);
        // console.log(response);
        $(".placeholder-content").remove();
        var totalDia = $("div.number-of-search strong").html();
        var total_diamonds = parseInt(totalDia.replace(/,/, ""));

        var itemppage = parseInt($("#itemperpage").val());
        var totalpage = Math.ceil(total_diamonds / itemppage);
        // console.log(total_diamonds);
        // console.log(totalDia);

        if (
          $("div.number-of-search strong").html() < 20 &&
          $("#currentpage").val() > 1
        ) {
          $("#currentpage").val(1);
          $("#search-diamonds-form #submit").trigger("click");
        }
        if (
          $("div.number-of-search strong").html() > 20 &&
          $("#currentpage").val() > totalpage
        ) {
          $("#currentpage").val(1);
          $("#search-diamonds-form #submit").trigger("click");
          $(".loading-mask.gemfind-loading-mask").css("display", "none");
        }
        //$('.loading-mask.gemfind-loading-mask').css('display', 'none');
        var mode = $("input#viewmode").val();
        if (mode == "grid") {
          $("li.grid-view a").addClass("active");
          $("li.list-view a").removeClass("active");
          $("#list-mode").addClass("cls-for-hide");
          $("#grid-mode, #gridview-orderby, div.grid-view-sort").removeClass(
            "cls-for-hide"
          );
        }
        $(".change-view-result li a").click(function () {
          $(this).addClass("active");
          $(".table-responsive input:checkbox[name=compare]").prop(
            "checked",
            false
          );
          if ($(this).parent("li").attr("class") == "list-view") {
            $("li.grid-view a").removeClass("active");
            $("#list-mode").removeClass("cls-for-hide");
            $("#grid-mode, div.grid-view-sort").addClass("cls-for-hide");
            $("input#viewmode").val("list");
          } else {
            $("li.list-view a").removeClass("active");
            $("#list-mode").addClass("cls-for-hide");
            $("#grid-mode, div.grid-view-sort").removeClass("cls-for-hide");
            $("input#viewmode").val("grid");
          }
        });
        $(".search-product-grid .trigger-info").click(function (e) {
          $(this).parent().next().toggleClass("active");
          e.stopPropagation();
        });
        $(".search-product-grid .product-inner-info").click(function (e) {
          e.stopPropagation();
        });
        $(document).click(function (e) {
          $(".search-product-grid .product-inner-info").removeClass("active");
        });
        $("#gridview-orderby option").each(function () {
          if ($(this).val() == $("input#orderby").val()) {
            $(this).attr("selected", "selected");
            return;
          }
        });
        if ($("input#direction").val() == "ASC") {
          $("#ASC").addClass("active");
          $("#DESC").removeClass("active");
        } else {
          $("#DESC").addClass("active");
          $("#ASC").removeClass("active");
        }
        $("#pagesize option").each(function () {
          if ($(this).val() == $("input#itemperpage").val()) {
            $(this).attr("selected", "selected");
            return;
          }
        });

        $("#diamondfilter option").each(function () {
          // console.log($(this).val());
          // console.log($("input#diamondfilter").val());
          if ($(this).val() == $("input#diamondfilter").val()) {
            $(this).attr("selected", "selected");

            return;
          }
        });
        $("th#" + $("input#orderby").val()).addClass(
          $("input#direction").val()
        );
        $("#gridview-orderby").SumoSelect({
          forceCustomRendering: true,
          triggerChangeCombined: false,
        });
        $("#pagesize").SumoSelect({
          forceCustomRendering: true,
          triggerChangeCombined: false,
        });
        $("#diamondfilter").SumoSelect({
          forceCustomRendering: true,
          triggerChangeCombined: false,
        });
        $(".pagesize.SumoUnder").insertAfter(
          ".sumo_pagesize .CaptionCont.SelectBox"
        );
        $(".diamondfilter.SumoUnder").insertAfter(
          ".sumo_pagesize .CaptionCont.SelectBox"
        );
        $(".gridview-orderby.SumoUnder").insertAfter(
          ".sumo_gridview-orderby .CaptionCont.SelectBox"
        );
        // $("input[name='compare']").change(function() {
        //     var maxAllowed = 6;
        //     var cnt = compareItemsarray.length;
        //     // var arrayFromStroage = localStorage.compareItems;
        //     // console.log(arrayFromStroage);

        //     if(JSON.parse(localStorage.getItem("compareItems"))){
        //        var localStoragedata = JSON.parse(localStorage.getItem("compareItems")).length;
        //        console.log(localStoragedata);
        //     }
        //     else{
        //         var localStoragedata = 0;
        //     }

        //     var totalcnt = cnt + localStoragedata;
        //     if (totalcnt > maxAllowed) {
        //         $(this).prop("checked", "");
        //          alert('You can select a maximum of ' + maxAllowed + ' diamonds to compare! Please check your compare item page you have some items in your compare list.');
        //          return false;
        //     }
        // });
        $("#searchdidfield").keydown(function (e) {
          if (e.keyCode == 13) {
            $("#searchdid").trigger("click");
          }
        });
        $("#searchdid").click(function () {
          if ($("#searchdidfield").val() != "") {
            $("input#did").val($("#searchdidfield").val().trim());
            $("#search-diamonds-form #submit").trigger("click");
          } else {
            $("input#searchdidfield").effect(
              "highlight",
              { color: "#f56666" },
              2000
            );
            return false;
          }
        });
        if ($("input#did").val()) {
          $("#searchintable").addClass("executed");
        }
        $("#searchdidfield").val($("input#did").val());
        $("input#did").val("");
        $("#resetsearchdata").click(function () {
          $("#searchdidfield").val();
          $("input#did").val("");
          $("#search-diamonds-form #submit").trigger("click");
        });
        jQuery.each(compareItemsarray, function (key, value) {
          if (value) {
            if (mode == "grid") {
              jQuery(
                "#grid-mode  #" + value + " input:checkbox[name=compare]"
              ).prop("checked", true);
              jQuery(
                "#grid-mode  #" + value + " input:checkbox[name=compare]"
              ).val();
            } else {
              jQuery("#" + value + " input:checkbox[name=compare]").prop(
                "checked",
                true
              );
              jQuery(
                "#" + parseInt(value) + " input:checkbox[name=compare]"
              ).val();
            }
          }
        });
        var total_diamonds = compareItemsarray.length;
        if (total_diamonds <= 6) {
          jQuery("#totaldiamonds").text(total_diamonds);
        }

        $(".loading-mask.gemfind-loading-mask").css("display", "none");
      },
      error: function (xhr, status, errorThrown) {
        $(".loading-mask.gemfind-loading-mask").css("display", "none");
        console.log("Error happens. Try again.");
        console.log(errorThrown);
      },
    });
  });
  // Add a custom validation function for the pattern
  $('input[data-type="min"]').on("input", function () {
    var pattern = /^[-+]?\d*\.?\d+$/;
    var value = $(this).val();

    if (!pattern.test(value)) {
      // Handle invalid input (e.g., display a message or prevent form submission)
      alert("Invalid input. Please enter a valid number.");
    }
  });
});
function gemfindDT_fnSort(strSort) {
  //alert("something");
  var orderBy = document.getElementById("orderby").value;
  var direction = document.getElementById("direction").value;
  // console.log(orderBy);
  // console.log(direction);
  if (strSort == orderBy) {
    if (direction == "ASC") direction = "DESC";
    else direction = "ASC";
  } else {
    direction = "ASC";
  }
  orderBy = strSort;
  direction = direction;
  document.getElementById("orderby").value = strSort;
  document.getElementById("direction").value = direction;
  document.getElementById("currentpage").value = 1;
  document.getElementById("submit").click();
}
function gemfindDT_gridSort(selectObject) {
  var orderBy = document.getElementById("orderby").value;
  var direction = document.getElementById("direction").value;
  var selectedvalue = selectObject.value;
  orderBy = selectedvalue;
  direction = direction;
  document.getElementById("orderby").value = selectedvalue;
  document.getElementById("direction").value = direction;
  document.getElementById("currentpage").value = 1;
  document.getElementById("submit").click();
}
function gemfindDT_gridDire(selectedvalue) {
  var direction = document.getElementById("direction").value;
  var selectedvalue = selectedvalue;
  if (direction != selectedvalue) {
    direction = selectedvalue;
  }
  if (direction == "ASC") {
    document.getElementById("DESC").className = "";
    document.getElementById("ASC").className = "active";
  } else {
    document.getElementById("DESC").className = "active";
    document.getElementById("ASC").className = "";
  }
  document.getElementById("direction").value = direction;
  document.getElementById("currentpage").value = 1;
  document.getElementById("submit").click();
}
function gemfindDT_ItemPerPage(selectObject) {
  var resultperpage = document.getElementById("itemperpage").value;
  var selectedvalue = selectObject.value;
  resultperpage = selectedvalue;
  document.getElementById("itemperpage").value = selectedvalue;
  document.getElementById("currentpage").value = 1;
  document.getElementById("submit").click();
}

function gemfindDT_DimondFilter(selectObject) {
  var resultperpage = document.getElementById("diamondfilter").value;

  console.log(resultperpage);

  var selectedvalue = selectObject.value;

  console.log(selectedvalue);

  resultperpage = selectedvalue;

  var selectedvalue1 = selectedvalue.toLowerCase();

  document.getElementById("diamondfilter").value = selectedvalue1;

  document.getElementById("submit").click();
}
function gemfindDT_PagerClick(intpageNo) {
  document.getElementById("currentpage").value = intpageNo;

  document.getElementById("submit").click();
}
function gemfindDT_mode(targetid) {
  var id = targetid;
  console.log(targetid);
  var items = document.getElementById("navbar").getElementsByTagName("li");
  for (var i = 0; i < items.length; ++i) {
    items[i].className = "";
  }
  document.getElementById(id).className = "active";
  if (id != "navcompare") document.getElementById("filtermode").value = id;
}
function gemfindDT_SetBackValue(diamondid) {
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
    // if (this.checked === true) {
    ClarityList.push(jQuery(this).val());
    //}
  });
  var polishCheckboxes = jQuery("input[name='diamond_polish[]']");
  var polishList = [];
  polishCheckboxes.each(function () {
    //if (this.checked === true) {
    polishList.push(jQuery(this).val());
    //}
  });
  var symmetryCheckboxes = jQuery("input[name='diamond_symmetry[]']");
  var SymmetryList = [];
  symmetryCheckboxes.each(function () {
    //if (this.checked === true) {
    SymmetryList.push(jQuery(this).val());
    //}
  });
  var fluorescenceCheckboxes = jQuery("input[name='diamond_fluorescence[]']");
  var FluorescenceList = [];
  fluorescenceCheckboxes.each(function () {
    // if (this.checked === true) {
    FluorescenceList.push(jQuery(this).val());
    //}
  });
  var fancycolorCheckboxes = jQuery("input[name='diamond_fancycolor[]']");
  var FancycolorList = [];
  fancycolorCheckboxes.each(function () {
    if (this.checked === true) {
      FancycolorList.push(jQuery(this).val());
    }
  });
  var intintensityCheckboxes = jQuery("input[name='diamond_intintensity[]']");
  var intintensityList = [];
  intintensityCheckboxes.each(function () {
    if (this.checked === true) {
      intintensityList.push(jQuery(this).val());
    }
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
  var viewMode = jQuery("input#viewmode").val();
  var did = diamondid;
  var filtermode = jQuery("input#filtermode").val();
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
    FancycolorList: FancycolorList.toString(),
    IntintensityList: intintensityList.toString(),
    CutGradeList: CutGradeList.toString(),
    ColorList: ColorList.toString(),
    ClarityList: ClarityList.toString(),
    currentPage: currentPage,
    orderBy: orderBy,
    direction: direction,
    viewmode: viewMode,
    filtermode: filtermode,
    did: did,
    backdiamondid: did,
  };
  var expire = new Date();
  expire.setTime(expire.getTime() + 0.5 * 3600 * 1000);
  if (filtermode == "navfancycolored") {
    jQuery.cookie("wp_dl_savebackvaluefancy", JSON.stringify(formdata), {
      path: "/",
      expires: expire,
    });
  } else if (filtermode == "navstandard") {
    jQuery.cookie("wp_dl_savebackvalue", JSON.stringify(formdata), {
      path: "/",
      expires: expire,
    });
  } else {
    jQuery.cookie("wp_dl_savebackvaluelabgrown", JSON.stringify(formdata), {
      path: "/",
      expires: expire,
    });
  }
  // $('.list-view a').click(function() {
  // });
}
