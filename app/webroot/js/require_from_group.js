$.validator.addMethod("require_from_group",function(e,d,b){var a=$(b[1],d.form),g=a.eq(0),c=g.data("valid_req_grp")?g.data("valid_req_grp"):$.extend({},this),f=a.filter(function(){return c.elementValue(this)}).length>=b[0];g.data("valid_req_grp",c);if(!$(d).data("being_validated")){a.data("being_validated",true);a.each(function(){c.element(this)});a.data("being_validated",false)}return f},$.validator.format("*Seleccione al menos 1"));