(function() {
  var Popovers,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Popovers = (function() {
    function Popovers(selector) {
      this.selector = selector;
      this.inject = __bind(this.inject, this);
    }

    Popovers.prototype.inject = function() {
      console.log("Injected Popover on elements: " + this.selector);
      return $(this.selector).popover();
    };

    return Popovers;

  })();

  $(document).ready(function() {
    var popovers;

    popovers = new Popovers('.setPopover');
    return popovers.inject();
  });

}).call(this);
