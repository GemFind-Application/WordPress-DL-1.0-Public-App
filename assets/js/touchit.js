jQuery.fn.touchit = function (method) {
  if (methods[method]) {
    return methods[method].apply(
      this,
      Array.prototype.slice.call(arguments, 1)
    );
  } else if (typeof method === "object" || !method) {
    return methods.init.apply(this, arguments);
  } else {
    $.error("Method " + method + " does not exist on touchit");
  }
};

var methods = {
  init: function (options) {
    var $this = jQuery(this);

    $this.data(
      "touchit",
      jQuery.extend(
        {
          doubleTapTimeout: 500,
          doubleTapWaiting: false,
          preTouchStart: false,
          onTouchStart: function (x, y) {},
          onTouchMove: function (x, y) {},
          onTouchEnd: function (x, y) {},
          onDoubleTap: function (x, y) {},
          onPinch: function (scale) {},
        },
        options
      )
    );

    return this.each(function () {
      gemfindDT_setupMobileEvents();

      function gemfindDT_setupMobileEvents() {
        var el = $this[0];

        //test if Gestures are supported:
        var gestureIsSupported = "ongesturestart" in el;

        //So I guess we can support gestures
        if (gestureIsSupported) {
          el.addEventListener("gesturechange", gemfindDT_gestureChange, false);
          el.addEventListener("gestureend", gemfindDT_gestureEnd, false);
        }

        var touchIsSupported = "ontouchstart" in el;

        //So I guess we can support touch
        if (touchIsSupported) {
          el.addEventListener("touchstart", gemfindDT_touchHandler, false);
          el.addEventListener("touchmove", gemfindDT_touchHandler, true);
          el.addEventListener("touchend", gemfindDT_touchHandler, true);
          el.addEventListener("touchcancel", gemfindDT_touchHandler, true);
        }
      }

      var gestureChanged = false;

      function gemfindDT_gestureChange(event) {
        gestureChanged = true;

        $this.data("touchit").onPinch.call(this, event.scale);
        $this.data("touchit").preTouchStart = true;
        event.preventDefault();
      }

      function gemfindDT_gestureEnd(event) {
        gestureChanged = false;

        event.preventDefault();
      }

      function gemfindDT_touchHandler(event) {
        if (gestureChanged == true) {
          return;
        } //  don't interfere with the gesture
        var touches = event.changedTouches,
          first = touches[0],
          type = "";

        switch (event.type) {
          case "touchstart":
            type = "mousedown";
            $this
              .data("touchit")
              .onTouchStart.call(this, first.screenX, first.screenY);
            $this.data("touchit").preTouchStart = true;
            break;
          case "touchmove":
            type = "mousemove";
            $this
              .data("touchit")
              .onTouchMove.call(this, first.screenX, first.screenY);
            $this.data("touchit").preTouchStart = false;
            break;
          case "touchend":
            type = "mouseup";
            $this
              .data("touchit")
              .onTouchEnd.call(this, first.screenX, first.screenY);

            //as we are lifting our fingers after a pinch if they lift within 100ms then consider a release from a pinch
            if (
              $this.data("touchit").doubleTapWaiting == true &&
              $this.data("touchit").preTouchStart == true
            ) {
              $this
                .data("touchit")
                .onDoubleTap.call(this, first.screenX, first.screenY);
            } else {
              $this.data("touchit").doubleTapWaiting = true;
              $this
                .data("touchit")
                .onTouchEnd.call(this, first.screenX, first.screenY);

              window.setTimeout(function () {
                $this.data("touchit").doubleTapWaiting = false;
              }, $this.data("touchit").doubleTapTimeout);
            }
            $this.data("touchit").preTouchStart = false;
            break;
          default:
            return;
        }

        var simulatedEvent = document.createEvent("MouseEvent");
        simulatedEvent.initMouseEvent(
          type,
          true,
          true,
          window,
          1,
          first.screenX,
          first.screenY,
          first.clientX,
          first.clientY,
          false,
          false,
          false,
          false,
          0,
          null
        );

        first.target.dispatchEvent(simulatedEvent);

        event.preventDefault();
      }
    });
  },
  destroy: function () {
    return this.each(function () {
      var $this = $(this),
        data = $this.data("touchit");

      if (data) {
        $this.removeData("touchit");
      }
    });
  },
};