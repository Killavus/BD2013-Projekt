class Popovers
  constructor: (@selector) ->
    
  inject: =>
    console.log("Injected Popover on elements: #{@selector}")
    $(@selector).popover()

$(document).ready ->
  popovers = new Popovers('.setPopover')
  popovers.inject()
